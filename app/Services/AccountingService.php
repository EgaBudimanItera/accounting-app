<?php

namespace App\Services;

use App\Models\Journal;
use App\Models\JournalDetail;
use Illuminate\Support\Facades\DB;

class AccountingService
{
    public function createJournal($data)
    {
        // dd("ASU");
        // dd($data);
        return DB::transaction(function () use ($data) {

            $journal = Journal::create([
                'date' => $data['date'],
                'description' => $data['description'],
                'created_by' => auth()->id() ?? 1 // FIX DI SINI
            ]);
            
            $totalDebit = 0;
            $totalCredit = 0;

            foreach ($data['details'] as $item) {

                // skip kalau kosong semua
                if (($item['debit'] == 0 || $item['debit'] == null) &&
                    ($item['credit'] == 0 || $item['credit'] == null)) {
                    continue;
                }

                JournalDetail::create([
                    'journal_id' => $journal->id,
                    'account_id' => $item['account_id'],
                    'debit' => $item['debit'] ?? 0,
                    'credit' => $item['credit'] ?? 0,
                ]);

                $totalDebit += $item['debit'] ?? 0;
                $totalCredit += $item['credit'] ?? 0;
            }

            if ($totalDebit != $totalCredit) {
                throw new \Exception('Debit dan Credit tidak balance!');
            }

            return $journal;
        });
    }

    public function closingYear($year)
    {
        $startDate = $year . '-01-01';
        $endDate   = $year . '-12-31';

        $accounts = \App\Models\Account::doesntHave('children')->get();

        $journal = \App\Models\Journal::create([
            'date' => $endDate,
            'description' => 'Tutup Buku Tahun ' . $year,
            'created_by' => auth()->id() ?? 1
        ]);

        $totalRevenue = 0;
        $totalExpense = 0;

        foreach ($accounts as $acc) {

            if (!$acc->isIncomeStatement()) {
                continue;
            }

            $balance = app(\App\Services\ReportService::class)
                ->getBalance($acc->id, $startDate, $endDate);

            if ($balance == 0) continue;

            /*
            |--------------------------------------------------------------------------
            | REVENUE → DI DEBIT (NOLKAN)
            |--------------------------------------------------------------------------
            */
            if ($acc->type == 'revenue') {

                \App\Models\JournalDetail::create([
                    'journal_id' => $journal->id,
                    'account_id' => $acc->id,
                    'debit' => $balance,
                    'credit' => 0,
                ]);

                $totalRevenue += $balance;
            }

            /*
            |--------------------------------------------------------------------------
            | EXPENSE → DI CREDIT (NOLKAN)
            |--------------------------------------------------------------------------
            */
            if ($acc->type == 'expense') {

                \App\Models\JournalDetail::create([
                    'journal_id' => $journal->id,
                    'account_id' => $acc->id,
                    'debit' => 0,
                    'credit' => $balance,
                ]);

                $totalExpense += $balance;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | LABA DITAHAN
        |--------------------------------------------------------------------------
        */
        $profit = $totalRevenue - $totalExpense;

        $retained = \App\Models\Account::where('name', 'Laba Ditahan')->first();

        if (!$retained) {
            throw new \Exception('Akun Laba Ditahan belum ada!');
        }

        // kalau laba
        if ($profit > 0) {
            \App\Models\JournalDetail::create([
                'journal_id' => $journal->id,
                'account_id' => $retained->id,
                'debit' => 0,
                'credit' => $profit,
            ]);
        } else {
            \App\Models\JournalDetail::create([
                'journal_id' => $journal->id,
                'account_id' => $retained->id,
                'debit' => abs($profit),
                'credit' => 0,
            ]);
        }
    }
}