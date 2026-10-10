<?php

namespace Database\Seeders;

use App\Models\Due;
use App\Models\DuePayment;
use App\Models\Expense;
use App\Models\ExpenseShare;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // Buat user demo
        $admin = User::firstOrCreate(
            ['email' => 'admin@demo.com'],
            ['name' => 'Budi', 'password' => Hash::make('password')]
        );

        $user2 = User::firstOrCreate(
            ['email' => 'ani@demo.com'],
            ['name' => 'Ani', 'password' => Hash::make('password')]
        );

        $user3 = User::firstOrCreate(
            ['email' => 'cici@demo.com'],
            ['name' => 'Cici', 'password' => Hash::make('password')]
        );

        // Buat grup patungan
        $group = Group::create([
            'name' => 'Liburan Bali 2026',
            'type' => 'patungan',
            'owner_id' => $admin->id,
            'description' => 'Liburan bareng ke Bali Oktober 2026',
        ]);

        $memberBudi = GroupMember::create([
            'group_id' => $group->id, 'user_id' => $admin->id, 'display_name' => 'Budi', 'role' => 'admin',
        ]);
        $memberAni = GroupMember::create([
            'group_id' => $group->id, 'user_id' => $user2->id, 'display_name' => 'Ani', 'role' => 'member',
        ]);
        $memberCici = GroupMember::create([
            'group_id' => $group->id, 'user_id' => $user3->id, 'display_name' => 'Cici', 'role' => 'member',
        ]);
        // Anggota tamu
        $memberDodi = GroupMember::create([
            'group_id' => $group->id, 'user_id' => null, 'display_name' => 'Dodi', 'role' => 'member', 'is_guest' => true,
        ]);

        // Catat pengeluaran
        $expense1 = Expense::create([
            'group_id' => $group->id,
            'paid_by_member_id' => $memberBudi->id,
            'amount' => 800000,
            'description' => 'Hotel 2 malam',
            'category' => 'Akomodasi',
            'split_type' => 'equal',
            'spent_at' => '2026-10-05',
            'source' => 'manual',
        ]);

        // Bagi rata ke 4 orang = 200000 each
        foreach ([$memberBudi, $memberAni, $memberCici, $memberDodi] as $m) {
            ExpenseShare::create(['expense_id' => $expense1->id, 'member_id' => $m->id, 'amount' => 200000]);
        }

        $expense2 = Expense::create([
            'group_id' => $group->id,
            'paid_by_member_id' => $memberAni->id,
            'amount' => 240000,
            'description' => 'Makan malam hari pertama',
            'category' => 'Makan',
            'split_type' => 'equal',
            'spent_at' => '2026-10-05',
            'source' => 'manual',
        ]);

        foreach ([$memberBudi, $memberAni, $memberCici, $memberDodi] as $m) {
            ExpenseShare::create(['expense_id' => $expense2->id, 'member_id' => $m->id, 'amount' => 60000]);
        }

        // Buat grup iuran
        $groupIuran = Group::create([
            'name' => 'Iuran RT 05',
            'type' => 'iuran',
            'owner_id' => $admin->id,
            'description' => 'Iuran bulanan RT 05',
        ]);

        $rtBudi = GroupMember::create([
            'group_id' => $groupIuran->id, 'user_id' => $admin->id, 'display_name' => 'Budi (Bendahara)', 'role' => 'admin',
        ]);
        $rtAni = GroupMember::create([
            'group_id' => $groupIuran->id, 'user_id' => $user2->id, 'display_name' => 'Ani', 'role' => 'member',
        ]);
        $rtCici = GroupMember::create([
            'group_id' => $groupIuran->id, 'user_id' => $user3->id, 'display_name' => 'Cici', 'role' => 'member',
        ]);

        $due = Due::create([
            'group_id' => $groupIuran->id,
            'title' => 'Iuran Kas Oktober 2026',
            'amount' => 50000,
            'recurrence' => 'monthly',
            'due_date' => '2026-10-20',
        ]);

        DuePayment::create(['due_id' => $due->id, 'member_id' => $rtBudi->id, 'status' => 'paid', 'paid_at' => now()]);
        DuePayment::create(['due_id' => $due->id, 'member_id' => $rtAni->id, 'status' => 'unpaid']);
        DuePayment::create(['due_id' => $due->id, 'member_id' => $rtCici->id, 'status' => 'unpaid']);

        $this->command->info('Demo seeder selesai! Login: admin@demo.com / password');
    }
}
