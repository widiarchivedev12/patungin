<?php

namespace App\Services;

use App\Models\Group;
use App\Models\GroupMember;

class BalanceService
{
    /**
     * Hitung saldo bersih setiap anggota dalam grup.
     * Nilai positif = orang lain berhutang ke dia.
     * Nilai negatif = dia berhutang ke orang lain.
     *
     * @return array<int, int>  member_id => saldo (dalam Rupiah integer)
     */
    public function netBalances(Group $group): array
    {
        $balances = [];

        // Inisialisasi semua anggota dengan 0
        foreach ($group->members as $member) {
            $balances[$member->id] = 0;
        }

        // Pengeluaran: yang bayar dapat kredit, yang dapat bagian kena debet
        $expenses = $group->expenses()
            ->with(['shares', 'paidBy'])
            ->where('is_draft', false)
            ->get();

        foreach ($expenses as $expense) {
            $payerId = $expense->paid_by_member_id;
            if (! isset($balances[$payerId])) {
                $balances[$payerId] = 0;
            }
            $balances[$payerId] += $expense->amount;

            foreach ($expense->shares as $share) {
                $memberId = $share->member_id;
                if (! isset($balances[$memberId])) {
                    $balances[$memberId] = 0;
                }
                $balances[$memberId] -= $share->amount;
            }
        }

        // Settlement yang sudah dikonfirmasi mengurangi utang
        $settlements = $group->settlements()
            ->where('status', 'confirmed')
            ->get();

        foreach ($settlements as $settlement) {
            $balances[$settlement->from_member_id] = ($balances[$settlement->from_member_id] ?? 0) + $settlement->amount;
            $balances[$settlement->to_member_id] = ($balances[$settlement->to_member_id] ?? 0) - $settlement->amount;
        }

        return $balances;
    }

    /**
     * Sederhanakan utang menggunakan greedy algorithm.
     * Kembalikan list transfer minimum yang diperlukan.
     *
     * @param  array<int, int>  $balances  member_id => saldo
     * @return array<int, array{from: int, to: int, amount: int}>
     */
    public function simplifyDebts(array $balances): array
    {
        $creditors = [];
        $debtors = [];

        foreach ($balances as $memberId => $balance) {
            if ($balance > 0) {
                $creditors[] = ['id' => $memberId, 'amount' => $balance];
            } elseif ($balance < 0) {
                $debtors[] = ['id' => $memberId, 'amount' => abs($balance)];
            }
        }

        usort($creditors, fn ($a, $b) => $b['amount'] - $a['amount']);
        usort($debtors, fn ($a, $b) => $b['amount'] - $a['amount']);

        $transactions = [];
        $i = 0;
        $j = 0;

        while ($i < count($creditors) && $j < count($debtors)) {
            $settle = min($creditors[$i]['amount'], $debtors[$j]['amount']);

            if ($settle > 0) {
                $transactions[] = [
                    'from' => $debtors[$j]['id'],
                    'to' => $creditors[$i]['id'],
                    'amount' => $settle,
                ];
            }

            $creditors[$i]['amount'] -= $settle;
            $debtors[$j]['amount'] -= $settle;

            if ($creditors[$i]['amount'] === 0) {
                $i++;
            }
            if ($debtors[$j]['amount'] === 0) {
                $j++;
            }
        }

        return $transactions;
    }

    /**
     * Hitung saldo anggota tertentu terhadap anggota lain dalam grup.
     *
     * @return array<int, int>  member_id => jumlah (positif = mereka berhutang ke kita)
     */
    public function memberBalanceAgainst(GroupMember $member): array
    {
        $group = $member->group;
        $allBalances = $this->netBalances($group);
        $simplifiedDebts = $this->simplifyDebts($allBalances);

        $result = [];
        foreach ($simplifiedDebts as $txn) {
            if ($txn['from'] === $member->id) {
                // Member ini berhutang ke 'to'
                $result[$txn['to']] = ($result[$txn['to']] ?? 0) - $txn['amount'];
            } elseif ($txn['to'] === $member->id) {
                // 'from' berhutang ke member ini
                $result[$txn['from']] = ($result[$txn['from']] ?? 0) + $txn['amount'];
            }
        }

        return $result;
    }
}
