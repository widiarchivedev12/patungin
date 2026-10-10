<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupMember;
use App\Services\BalanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GroupController extends Controller
{
    public function __construct(private BalanceService $balanceService) {}

    public function index(): View
    {
        $memberships = auth()->user()->groupMemberships()
            ->with('group')
            ->get();

        $groups = $memberships->map(fn ($m) => $m->group)->filter();

        return view('groups.index', compact('groups'));
    }

    public function create(): View
    {
        return view('groups.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:patungan,iuran',
            'description' => 'nullable|string|max:1000',
        ]);

        $group = Group::create([
            ...$validated,
            'owner_id' => auth()->id(),
        ]);

        // Otomatis tambah pembuat sebagai admin member
        GroupMember::create([
            'group_id' => $group->id,
            'user_id' => auth()->id(),
            'display_name' => auth()->user()->name,
            'role' => 'admin',
        ]);

        return redirect()->route('groups.show', $group)
            ->with('success', 'Grup berhasil dibuat!');
    }

    public function show(Group $group): View
    {
        $this->authorizeGroupAccess($group);

        $group->load(['members.user', 'expenses' => fn ($q) => $q->where('is_draft', false)->latest('spent_at')->limit(10), 'dues']);

        $balances = $this->balanceService->netBalances($group);
        $simplifiedDebts = $this->balanceService->simplifyDebts($balances);
        $currentMember = $group->memberForUser(auth()->user());

        return view('groups.show', compact('group', 'balances', 'simplifiedDebts', 'currentMember'));
    }

    public function edit(Group $group): View
    {
        $this->authorizeAdminAccess($group);

        return view('groups.edit', compact('group'));
    }

    public function update(Request $request, Group $group): RedirectResponse
    {
        $this->authorizeAdminAccess($group);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $group->update($validated);

        return redirect()->route('groups.show', $group)
            ->with('success', 'Grup berhasil diperbarui.');
    }

    public function destroy(Group $group): RedirectResponse
    {
        $this->authorizeOwnerAccess($group);

        $group->delete();

        return redirect()->route('groups.index')
            ->with('success', 'Grup berhasil dihapus.');
    }

    public function join(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'invite_code' => 'required|string|size:8',
        ]);

        $group = Group::where('invite_code', strtoupper($validated['invite_code']))->firstOrFail();

        $existingMember = $group->memberForUser(auth()->user());
        if ($existingMember) {
            return redirect()->route('groups.show', $group)
                ->with('info', 'Kamu sudah menjadi anggota grup ini.');
        }

        GroupMember::create([
            'group_id' => $group->id,
            'user_id' => auth()->id(),
            'display_name' => auth()->user()->name,
            'role' => 'member',
        ]);

        return redirect()->route('groups.show', $group)
            ->with('success', 'Berhasil bergabung ke grup '.$group->name.'!');
    }

    public function inviteLink(Group $group): View
    {
        $this->authorizeGroupAccess($group);

        return view('groups.invite', compact('group'));
    }

    public function regenerateInvite(Group $group): RedirectResponse
    {
        $this->authorizeAdminAccess($group);

        $group->regenerateInviteCode();

        return redirect()->route('groups.invite', $group)
            ->with('success', 'Kode undangan baru telah dibuat.');
    }

    public function addGuest(Request $request, Group $group): RedirectResponse
    {
        $this->authorizeAdminAccess($group);

        $validated = $request->validate([
            'display_name' => 'required|string|max:255',
        ]);

        GroupMember::create([
            'group_id' => $group->id,
            'user_id' => null,
            'display_name' => $validated['display_name'],
            'role' => 'member',
            'is_guest' => true,
        ]);

        return redirect()->route('groups.show', $group)
            ->with('success', 'Anggota tamu berhasil ditambahkan.');
    }

    public function removeMember(Group $group, GroupMember $member): RedirectResponse
    {
        $this->authorizeAdminAccess($group);

        abort_if($member->group_id !== $group->id, 403);
        abort_if($member->user_id === $group->owner_id, 403, 'Tidak bisa menghapus pemilik grup.');

        $member->delete();

        return redirect()->route('groups.show', $group)
            ->with('success', 'Anggota berhasil dihapus.');
    }

    private function authorizeGroupAccess(Group $group): void
    {
        $member = $group->memberForUser(auth()->user());
        abort_if(! $member, 403);
    }

    private function authorizeAdminAccess(Group $group): void
    {
        $member = $group->memberForUser(auth()->user());
        abort_if(! $member || ! $member->isAdmin(), 403);
    }

    private function authorizeOwnerAccess(Group $group): void
    {
        abort_if($group->owner_id !== auth()->id(), 403);
    }
}
