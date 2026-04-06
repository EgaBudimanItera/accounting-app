<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Journal;
use App\Models\JournalDetail;
use App\Models\Account;
use App\Models\User;
use Carbon\Carbon;

class JournalSeeder extends Seeder
{
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        JournalDetail::truncate();
        Journal::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $user = User::first();

        $leafAccounts = Account::doesntHave('children')->get();
        $accounts = $leafAccounts->keyBy('name');

        $patterns = [
            ['debit' => 'Kas', 'credit' => 'Pendapatan Usaha'],
            ['debit' => 'Bank', 'credit' => 'Pendapatan Usaha'],
            ['debit' => 'Piutang Usaha', 'credit' => 'Pendapatan Usaha'],
            ['debit' => 'Kas', 'credit' => 'Piutang Usaha'],
            ['debit' => 'Beban Operasional', 'credit' => 'Kas'],
            ['debit' => 'Beban Gaji', 'credit' => 'Kas'],
            ['debit' => 'Kas', 'credit' => 'Modal'],
            ['debit' => 'Kas', 'credit' => 'Hutang Usaha'],
            ['debit' => 'Hutang Usaha', 'credit' => 'Kas'],
        ];

        for ($i = 1; $i <= 100; $i++) {

            $date = Carbon::create(2026, 3, rand(1, 28));

            $pattern = $patterns[array_rand($patterns)];

            $debitAccount = $accounts[$pattern['debit']] ?? null;
            $creditAccount = $accounts[$pattern['credit']] ?? null;

            if (!$debitAccount || !$creditAccount) {
                $debitAccount = $leafAccounts->random();
                $creditAccount = $leafAccounts
                    ->where('id', '!=', $debitAccount->id)
                    ->random();
            }

            /*
            |--------------------------------------------------------------------------
            | 🔥 FIX: SATU NILAI SAJA (JANGAN PISAH)
            |--------------------------------------------------------------------------
            */
            $baseAmount = rand(50000, 2000000);
            $variance = rand(0, 20000);

            // 👉 SATU NILAI FINAL
            $finalAmount = $baseAmount + $variance;

            $journal = Journal::create([
                'date' => $date,
                'description' => 'Transaksi #' . $i,
                'created_by' => $user->id
            ]);

            /*
            |--------------------------------------------------------------------------
            | DETAIL (PASTI BALANCE)
            |--------------------------------------------------------------------------
            */

            JournalDetail::create([
                'journal_id' => $journal->id,
                'account_id' => $debitAccount->id,
                'debit' => $finalAmount,
                'credit' => 0,
            ]);

            JournalDetail::create([
                'journal_id' => $journal->id,
                'account_id' => $creditAccount->id,
                'debit' => 0,
                'credit' => $finalAmount,
            ]);
        }
    }
}