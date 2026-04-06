<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Account;
use App\Services\ReportService;
class ReportController extends Controller
{
    protected $service;
    public function __construct(ReportService $service)
    {
        $this->service = $service;
    }
    
    public function ledger(Request $request)
    {
        $accounts = \App\Models\Account::leaf()->get();

        $accountId = $request->account_id ?? $accounts->first()->id;

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $account = \App\Models\Account::find($accountId);

        // 🔥 HITUNG SALDO AWAL (SEBELUM START DATE)
        $openingBalance = 0;

        if ($startDate) {

            $before = \App\Models\JournalDetail::with('journal')
                ->where('account_id', $accountId)
                ->whereHas('journal', function ($q) use ($startDate) {
                    $q->where('date', '<', $startDate);
                })
                ->get();

            foreach ($before as $b) {
                if ($account->normal_balance == 'debit') {
                    $openingBalance += ($b->debit - $b->credit);
                } else {
                    $openingBalance += ($b->credit - $b->debit);
                }
            }
        }

        // 🔥 QUERY TRANSAKSI DALAM RANGE
        $query = \App\Models\JournalDetail::with('journal')
            ->where('account_id', $accountId);

        if ($startDate && $endDate) {
            $query->whereHas('journal', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('date', [$startDate, $endDate]);
            });
        }

        $details = $query
            ->join('journals', 'journal_details.journal_id', '=', 'journals.id')
            ->orderBy('journals.date', 'asc')
            ->select('journal_details.*')
            ->get();

        // 🔥 RUNNING BALANCE DARI SALDO AWAL
        $balance = $openingBalance;

        foreach ($details as $d) {

            if ($account->normal_balance == 'debit') {
                $balance += ($d->debit - $d->credit);
            } else {
                $balance += ($d->credit - $d->debit);
            }

            $d->balance = $balance;
        }
        $endingBalance = $balance;
        return view('report.ledger', compact(
            'accounts',
            'account',
            'details',
            'startDate',
            'endDate',
            'openingBalance',
            'endingBalance'
        ));
    }

    public function hutangPiutang()
    {
        $accounts = \App\Models\Account::whereIn('type', ['asset','liability'])->get();

        $data = [];

        foreach ($accounts as $acc) {
            $total = \App\Models\JournalDetail::where('account_id', $acc->id)
                ->selectRaw('SUM(debit - credit) as saldo')
                ->value('saldo');

            $data[] = [
                'account' => $acc->name,
                'saldo' => $total
            ];
        }

        return view('report.hutang', compact('data'));
    }

    public function trialBalance(Request $request)
    {
        $data = $this->service->trialBalance(
            $request->start_date,
            $request->end_date
        );

        return view('report.trial-balance', compact('data'));
    }

    public function incomeStatement(Request $request)
    {
        $data = $this->service->incomeStatement(
            $request->start_date,
            $request->end_date
        );

        return view('report.income', compact('data'));
    }
    
    public function balanceSheet()
    {
        $data = $this->service->balanceSheet();

        return view('report.balance-sheet', compact('data'));
    }

    
}

