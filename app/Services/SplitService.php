<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\GroupMember;
use Illuminate\Support\Collection;

class SplitService
{
    /**
     * Hitung pembagian untuk split equal.
     *
     * @param  Collection<GroupMember>  $members
     * @return array<int, int>  member_id => jumlah
     */
    public function splitEqual(int $totalAmount, Collection $members): array
    {
        $count = $members->count();
        if ($count === 0) {
            return [];
        }

        $base = intdiv($totalAmount, $count);
        $remainder = $totalAmount % $count;

        $shares = [];
        foreach ($members as $i => $member) {
            $shares[$member->id] = $base + ($i < $remainder ? 1 : 0);
        }

        return $shares;
    }

    /**
     * Hitung pembagian berdasarkan persentase.
     *
     * @param  array<int, float>  $percentages  member_id => persen (0-100)
     * @return array<int, int>
     */
    public function splitByPercentage(int $totalAmount, array $percentages): array
    {
        $shares = [];
        $assigned = 0;
        $lastMemberId = array_key_last($percentages);

        foreach ($percentages as $memberId => $pct) {
            if ($memberId === $lastMemberId) {
                $shares[$memberId] = $totalAmount - $assigned;
            } else {
                $share = (int) round($totalAmount * $pct / 100);
                $shares[$memberId] = $share;
                $assigned += $share;
            }
        }

        return $shares;
    }

    /**
     * Hitung pembagian berdasarkan nominal eksak.
     * Sisa (selisih pembulatan) diberikan ke anggota pertama.
     *
     * @param  array<int, int>  $amounts  member_id => nominal
     * @return array<int, int>
     */
    public function splitByExact(int $totalAmount, array $amounts): array
    {
        $sum = array_sum($amounts);
        if ($sum !== $totalAmount && ! empty($amounts)) {
            $firstKey = array_key_first($amounts);
            $amounts[$firstKey] += ($totalAmount - $sum);
        }

        return $amounts;
    }

    /**
     * Simpan pembagian pengeluaran ke database.
     *
     * @param  array<int, int>  $shares  member_id => jumlah
     */
    public function saveShares(Expense $expense, array $shares): void
    {
        $expense->shares()->delete();

        $rows = [];
        foreach ($shares as $memberId => $amount) {
            if ($amount > 0) {
                $rows[] = [
                    'expense_id' => $expense->id,
                    'member_id' => $memberId,
                    'amount' => $amount,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (! empty($rows)) {
            $expense->shares()->insert($rows);
        }
    }
}
