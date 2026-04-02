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
}