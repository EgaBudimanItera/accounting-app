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
        // disable FK biar aman
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        JournalDetail::truncate();
        Journal::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $user = User::first();

        // 🔥 AMBIL HANYA AKUN LEAF
        $leafAccounts = Account::doesntHave('children')->get();

        // mapping akun berdasarkan nama (leaf only)
        $accounts = $leafAccounts->keyBy('name');

        /*
        |--------------------------------------------------------------------------
        | POLA TRANSAKSI REALISTIS
        |--------------------------------------------------------------------------
        */
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

            // pilih pola
            $pattern = $patterns[array_rand($patterns)];

            $debitAccount = $accounts[$pattern['debit']] ?? null;
            $creditAccount = $accounts[$pattern['credit']] ?? null;

            // fallback (kalau nama tidak ketemu)
            if (!$debitAccount || !$creditAccount) {
                $debitAccount = $leafAccounts->random();
                $creditAccount = $leafAccounts
                    ->where('id', '!=', $debitAccount->id)
                    ->random();
            }

            // nominal
            $amount = rand(50000, 2000000);

            // variasi kecil biar natural
            $variance = rand(0, 1) ? rand(0, 20000) : 0;

            // buat jurnal
            $journal = Journal::create([
                'date' => $date,
                'description' => 'Transaksi #' . $i,
                'created_by' => $user->id
            ]);

            /*
            |--------------------------------------------------------------------------
            | DETAIL JURNAL (DOUBLE ENTRY)
            |--------------------------------------------------------------------------
            */

            // DEBIT
            JournalDetail::create([
                'journal_id' => $journal->id,
                'account_id' => $debitAccount->id,
                'debit' => $amount + ($debitAccount->normal_balance == 'debit' ? $variance : 0),
                'credit' => 0,
            ]);

            // CREDIT
            JournalDetail::create([
                'journal_id' => $journal->id,
                'account_id' => $creditAccount->id,
                'debit' => 0,
                'credit' => $amount + ($creditAccount->normal_balance == 'credit' ? $variance : 0),
            ]);
        }
    }
}