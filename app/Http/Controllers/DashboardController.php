<?php

namespace App\Http\Controllers;

use App\Services\BalanceService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private BalanceService $balanceService) {}

    public function index(): View
    {
        $user = auth()->user();

        // Ambil semua grup tempat user menjadi anggota
        $memberships = $user->groupMemberships()
            ->with(['group.members', 'group.expenses', 'group.dues'])
            ->get();

        $groups = $memberships->map(fn ($m) => $m->group)->filter()->unique('id');

        // Hitung total utang/piutang user di semua grup
        $totalOwed = 0;   // Total yang orang lain berhutang ke user
        $totalDebt = 0;   // Total yang user berhutang ke orang lain

        $debtSummary = [];

        foreach ($groups as $group) {
            $member = $group->memberForUser($user);
            if (! $member) {
                continue;
            }

            $balances = $this->balanceService->netBalances($group);
            $balance = $balances[$member->id] ?? 0;

            if ($balance > 0) {
                $totalOwed += $balance;
            } else {
                $totalDebt += abs($balance);
            }

            // Cari siapa yang user harus bayar atau menerima dari
            $simplified = $this->balanceService->simplifyDebts($balances);
            foreach ($simplified as $txn) {
                if ($txn['from'] === $member->id || $txn['to'] === $member->id) {
                    $debtSummary[] = [
                        'group' => $group,
                        'from_member' => $group->members->find($txn['from']),
                        'to_member' => $group->members->find($txn['to']),
                        'amount' => $txn['amount'],
                        'i_owe' => $txn['from'] === $member->id,
                    ];
                }
            }
        }

        // Pengeluaran terbaru dari semua grup
        $recentExpenses = collect();
        foreach ($groups as $group) {
            foreach ($group->expenses->take(3) as $expense) {
                $recentExpenses->push(['group' => $group, 'expense' => $expense]);
            }
        }
        $recentExpenses = $recentExpenses->sortByDesc(fn ($item) => $item['expense']->spent_at)->take(5);

        // Iuran yang belum dibayar oleh user ini
        $unpaidDues = collect();
        foreach ($memberships as $membership) {
            $group = $membership->group;
            if (! $group) {
                continue;
            }
            $member = $membership;

            $unpaid = $member->duePayments()
                ->where('status', 'unpaid')
                ->with(['due.group'])
                ->whereHas('due', fn ($q) => $q->where('is_active', true))
                ->get();

            foreach ($unpaid as $payment) {
                $unpaidDues->push($payment);
            }
        }

        return view('dashboard', compact(
            'groups',
            'totalOwed',
            'totalDebt',
            'debtSummary',
            'recentExpenses',
            'unpaidDues',
        ));
    }
}
