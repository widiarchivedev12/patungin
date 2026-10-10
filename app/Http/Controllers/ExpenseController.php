<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Group;
use App\Services\SplitService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function __construct(private SplitService $splitService) {}

    public function create(Group $group): View
    {
        $this->authorizeMember($group);

        $members = $group->members;

        return view('expenses.create', compact('group', 'members'));
    }

    public function store(Request $request, Group $group): RedirectResponse
    {
        $this->authorizeMember($group);

        $validated = $request->validate([
            'paid_by_member_id' => 'required|exists:group_members,id',
            'amount' => 'required|integer|min:1',
            'description' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'split_type' => 'required|in:equal,percentage,exact,by_item',
            'spent_at' => 'required|date',
            'member_ids' => 'required|array|min:1',
            'member_ids.*' => 'exists:group_members,id',
            'exact_amounts' => 'required_if:split_type,exact|array',
            'exact_amounts.*' => 'integer|min:0',
            'percentages' => 'required_if:split_type,percentage|array',
            'percentages.*' => 'numeric|min:0|max:100',
        ]);

        $expense = Expense::create([
            'group_id' => $group->id,
            'paid_by_member_id' => $validated['paid_by_member_id'],
            'amount' => $validated['amount'],
            'description' => $validated['description'],
            'category' => $validated['category'] ?? null,
            'split_type' => $validated['split_type'],
            'spent_at' => $validated['spent_at'],
            'source' => 'manual',
        ]);

        $members = $group->members()->whereIn('id', $validated['member_ids'])->get();

        $shares = match ($validated['split_type']) {
            'equal' => $this->splitService->splitEqual($validated['amount'], $members),
            'percentage' => $this->splitService->splitByPercentage(
                $validated['amount'],
                array_combine($validated['member_ids'], $validated['percentages'] ?? [])
            ),
            'exact' => $this->splitService->splitByExact(
                $validated['amount'],
                array_combine($validated['member_ids'], $validated['exact_amounts'] ?? [])
            ),
            default => $this->splitService->splitEqual($validated['amount'], $members),
        };

        $this->splitService->saveShares($expense, $shares);

        return redirect()->route('groups.show', $group)
            ->with('success', 'Pengeluaran berhasil dicatat!');
    }

    public function show(Group $group, Expense $expense): View
    {
        $this->authorizeMember($group);
        abort_if($expense->group_id !== $group->id, 404);

        $expense->load(['paidBy', 'shares.member', 'receiptItems']);

        return view('expenses.show', compact('group', 'expense'));
    }

    public function edit(Group $group, Expense $expense): View
    {
        $this->authorizeMember($group);
        abort_if($expense->group_id !== $group->id, 404);

        $members = $group->members;

        return view('expenses.edit', compact('group', 'expense', 'members'));
    }

    public function update(Request $request, Group $group, Expense $expense): RedirectResponse
    {
        $this->authorizeMember($group);
        abort_if($expense->group_id !== $group->id, 404);

        $validated = $request->validate([
            'paid_by_member_id' => 'required|exists:group_members,id',
            'amount' => 'required|integer|min:1',
            'description' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'split_type' => 'required|in:equal,percentage,exact,by_item',
            'spent_at' => 'required|date',
            'member_ids' => 'required|array|min:1',
            'member_ids.*' => 'exists:group_members,id',
            'exact_amounts' => 'required_if:split_type,exact|array',
            'exact_amounts.*' => 'integer|min:0',
            'percentages' => 'required_if:split_type,percentage|array',
            'percentages.*' => 'numeric|min:0|max:100',
        ]);

        $expense->update([
            'paid_by_member_id' => $validated['paid_by_member_id'],
            'amount' => $validated['amount'],
            'description' => $validated['description'],
            'category' => $validated['category'] ?? null,
            'split_type' => $validated['split_type'],
            'spent_at' => $validated['spent_at'],
        ]);

        $members = $group->members()->whereIn('id', $validated['member_ids'])->get();

        $shares = match ($validated['split_type']) {
            'equal' => $this->splitService->splitEqual($validated['amount'], $members),
            'percentage' => $this->splitService->splitByPercentage(
                $validated['amount'],
                array_combine($validated['member_ids'], $validated['percentages'] ?? [])
            ),
            'exact' => $this->splitService->splitByExact(
                $validated['amount'],
                array_combine($validated['member_ids'], $validated['exact_amounts'] ?? [])
            ),
            default => $this->splitService->splitEqual($validated['amount'], $members),
        };

        $this->splitService->saveShares($expense, $shares);

        return redirect()->route('groups.expenses.show', [$group, $expense])
            ->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    public function destroy(Group $group, Expense $expense): RedirectResponse
    {
        $this->authorizeMember($group);
        abort_if($expense->group_id !== $group->id, 404);

        $expense->delete();

        return redirect()->route('groups.show', $group)
            ->with('success', 'Pengeluaran berhasil dihapus.');
    }

    private function authorizeMember(Group $group): void
    {
        $member = $group->memberForUser(auth()->user());
        abort_if(! $member, 403);
    }
}
