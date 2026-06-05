<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tworzymy użytkowników za pomocą Twojego UserFactory
        $oliwia = User::factory()->create(['name' => 'Oliwia', 'email' => 'oliwia@example.com', 'role' => 'admin']);
        $adam = User::factory()->create(['name' => 'Adam Nowak', 'email' => 'adam@example.com']);
        $ewa = User::factory()->create(['name' => 'Ewa Kowalska', 'email' => 'ewa@example.com']);
        $haker = User::factory()->create(['name' => 'Jan Hacker', 'email' => 'hacker@example.com']);

        // 2. Tworzymy grupę
        $groupId = DB::table('groups')->insertGetId([
            'name' => 'Wycieczka w góry 2026',
            'owner_id' => $oliwia->id,
            'total_amount' => 0.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Przypisujemy ludzi do grupy (oprócz Hakera)
        DB::table('group_user')->insert([
            ['group_id' => $groupId, 'user_id' => $oliwia->id, 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => $groupId, 'user_id' => $adam->id, 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => $groupId, 'user_id' => $ewa->id, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 4. Oliwia placi za rachunek (600 zl)
        $billId = DB::table('bills')->insertGetId([
            'group_id' => $groupId,
            'payer_id' => $oliwia->id,
            'description' => 'Obiad i napoje',
            'amount' => 600.00,
            'date' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Symulacja podziału kosztów w bill_splits
        DB::table('bill_splits')->insert([
            ['bill_id' => $billId, 'user_id' => $oliwia->id, 'amount' => 200.00, 'is_paid' => true],
            ['bill_id' => $billId, 'user_id' => $adam->id, 'amount' => 200.00, 'is_paid' => false],
            ['bill_id' => $billId, 'user_id' => $ewa->id, 'amount' => 200.00, 'is_paid' => false],
        ]);

        // 5. Tworzymy pozycję z paragonu
        $itemId = DB::table('bill_items')->insertGetId([
            'bill_id' => $billId,
            'name' => 'Duża Pizza',
            'price' => 80.00,
            'quantity' => 1
        ]);

        if (DB::getDriverName() === 'mysql') {
            $group = \App\Models\Group::find($groupId);
            $group->update(['total_amount' => 600.00]);

            $this->command->info('=== TESTY LOGIKI BAZODANOWEJ (MySQL) ===');

            $balance = DB::select('SELECT get_user_net_balance(?, ?) AS balance', [$oliwia->id, $groupId])[0]->balance;
            $this->command->comment('Saldo Oliwii (funkcja SQL): ' . $balance . ' zl');

            try {
                DB::table('bill_item_user')->insert([
                    'bill_item_id' => $itemId,
                    'user_id' => $haker->id,
                ]);
            } catch (\Exception $e) {
                $this->command->error('TRIGGER zablokowal osobe spoza grupy: ' . $e->getMessage());
            }
        } else {
            DB::table('groups')->where('id', $groupId)->update(['total_amount' => 600.00]);
            $this->command->warn('SQLite: triggery i funkcja SQL dostepne po przejsciu na MySQL.');
        }

        $this->command->info('Konta demo (haslo: password):');
        $this->command->line('  admin: oliwia@example.com');
        $this->command->line('  user:  adam@example.com, ewa@example.com');
    }
}
