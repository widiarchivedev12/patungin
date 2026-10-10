<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Settlement;
use App\Services\BalanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettlementController extends Controller
{
    public function __construct(private BalanceService $balanceService) {}

    public function index(Group $group): View
    {
        $this->authorizeMember($group);

        $settlements = $group->settlements()
            ->with(['fromMember', 'toMember', 'proof'])
            ->latest()
            ->get();

        $balances = $this->balanceService->netBalances($group);
        $simplifiedDebts = $this->balanceService->simplifyDebts($balances);
        $members = $group->members()->with('user')->get()->keyBy('id');

        return view('settlements.index', compact('group', 'settlements', 'simplifiedDebts', 'members'));
    }

    public function store(Request $request, Group $group): RedirectResponse
    {
        $this->authorizeMember($group);

        $validated = $request->validate([
            'from_member_id' => 'required|exists:group_members,id',
            'to_member_id' => 'required|exists:group_members,id|different:from_member_id',
            'amount' => 'required|integer|min:1',
            'note' => 'nullable|string|max:500',
            'proof' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:2048',
        ]);

        $proofId = null;
        if ($request->hasFile('proof')) {
            $path = $request->file('proof')->store('proofs', 'private');
            $attachment = \App\Models\Attachment::create([
                'path' => $path,
                'original_name' => $request->file('proof')->getClientOriginalName(),
                'mime_type' => $request->file('proof')->getMimeType(),
                'size' => $request->file('proof')->getSize(),
                'type' => 'proof_of_transfer',
            ]);
            $proofId = $attachment->id;
        }

        Settlement::create([
            'group_id' => $group->id,
            'from_member_id' => $validated['from_member_id'],
            'to_member_id' => $validated['to_member_id'],
            'amount' => $validated['amount'],
            'note' => $validated['note'] ?? null,
            'proof_id' => $proofId,
            'status' => 'pending',
        ]);

        return redirect()->route('groups.settlements.index', $group)
            ->with('success', 'Pelunasan berhasil dicatat, menunggu konfirmasi.');
    }

    public function confirm(Group $group, Settlement $settlement): RedirectResponse
    {
        $this->authorizeAdmin($group);
        abort_if($settlement->group_id !== $group->id, 404);

        $settlement->confirm();

        return back()->with('success', 'Pelunasan berhasil dikonfirmasi.');
    }

    public function destroy(Group $group, Settlement $settlement): RedirectResponse
    {
        $this->authorizeMember($group);
        abort_if($settlement->group_id !== $group->id, 404);
        abort_if($settlement->status === 'confirmed', 403, 'Pelunasan yang sudah dikonfirmasi tidak bisa dihapus.');

        $settlement->delete();

        return redirect()->route('groups.settlements.index', $group)
            ->with('success', 'Pelunasan berhasil dihapus.');
    }

    private function authorizeMember(Group $group): void
    {
        abort_if(! $group->memberForUser(auth()->user()), 403);
    }

    private function authorizeAdmin(Group $group): void
    {
        $member = $group->memberForUser(auth()->user());
        abort_if(! $member || ! $member->isAdmin(), 403);
    }
}
