<?php

namespace App\Http\Controllers;

use App\Models\Due;
use App\Models\DuePayment;
use App\Models\Group;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DueController extends Controller
{
    public function index(Group $group): View
    {
        $this->authorizeMember($group);

        $dues = $group->dues()->with(['payments.member'])->latest()->get();

        return view('dues.index', compact('group', 'dues'));
    }

    public function create(Group $group): View
    {
        $this->authorizeAdmin($group);

        return view('dues.create', compact('group'));
    }

    public function store(Request $request, Group $group): RedirectResponse
    {
        $this->authorizeAdmin($group);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|integer|min:1',
            'recurrence' => 'required|in:once,weekly,monthly,yearly',
            'due_date' => 'required|date',
            'ends_at' => 'nullable|date|after:due_date',
            'member_ids' => 'nullable|array',
            'member_ids.*' => 'exists:group_members,id',
        ]);

        $due = Due::create([
            'group_id' => $group->id,
            'title' => $validated['title'],
            'amount' => $validated['amount'],
            'recurrence' => $validated['recurrence'],
            'due_date' => $validated['due_date'],
            'ends_at' => $validated['ends_at'] ?? null,
        ]);

        // Buat due_payments untuk setiap anggota yang dipilih (atau semua)
        $memberIds = $validated['member_ids'] ?? $group->members()->pluck('id')->toArray();
        foreach ($memberIds as $memberId) {
            DuePayment::create([
                'due_id' => $due->id,
                'member_id' => $memberId,
                'status' => 'unpaid',
            ]);
        }

        return redirect()->route('groups.dues.index', $group)
            ->with('success', 'Iuran berhasil dibuat!');
    }

    public function show(Group $group, Due $due): View
    {
        $this->authorizeMember($group);
        abort_if($due->group_id !== $group->id, 404);

        $due->load(['payments.member', 'payments.proof']);

        $paidCount = $due->payments->where('status', 'paid')->count();
        $totalCount = $due->payments->count();

        return view('dues.show', compact('group', 'due', 'paidCount', 'totalCount'));
    }

    public function destroy(Group $group, Due $due): RedirectResponse
    {
        $this->authorizeAdmin($group);
        abort_if($due->group_id !== $group->id, 404);

        $due->delete();

        return redirect()->route('groups.dues.index', $group)
            ->with('success', 'Iuran berhasil dihapus.');
    }

    public function confirmPayment(Request $request, Group $group, DuePayment $payment): RedirectResponse
    {
        $this->authorizeAdmin($group);
        abort_if($payment->due->group_id !== $group->id, 404);

        $payment->markAsPaid();

        return back()->with('success', 'Pembayaran '.$payment->member->display_name.' berhasil dikonfirmasi.');
    }

    public function submitProof(Request $request, Group $group, DuePayment $payment): RedirectResponse
    {
        $this->authorizeMember($group);
        abort_if($payment->due->group_id !== $group->id, 404);

        $request->validate([
            'proof' => 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:2048',
        ]);

        $path = $request->file('proof')->store('proofs', 'private');

        $attachment = \App\Models\Attachment::create([
            'path' => $path,
            'original_name' => $request->file('proof')->getClientOriginalName(),
            'mime_type' => $request->file('proof')->getMimeType(),
            'size' => $request->file('proof')->getSize(),
            'type' => 'proof_of_transfer',
        ]);

        $payment->update([
            'proof_id' => $attachment->id,
            'status' => 'pending_verification',
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diunggah, menunggu konfirmasi.');
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
