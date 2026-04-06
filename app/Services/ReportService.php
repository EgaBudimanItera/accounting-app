<?php

namespace App\Services;

use App\Models\Account;
use App\Models\JournalDetail;

class ReportService
{
    /*
    |--------------------------------------------------------------------------
    | SALDO PER ACCOUNT
    |--------------------------------------------------------------------------
    */
    public function getBalance($accountId, $startDate = null, $endDate = null)
    {
        if (isset($this->balanceCache[$accountId])) {
            return $this->balanceCache[$accountId];
        }

        $account = \App\Models\Account::find($accountId);

        $query = \App\Models\JournalDetail::where('account_id', $accountId);

        if ($startDate && $endDate) {
            $query->whereHas('journal', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('date', [$startDate, $endDate]);
            });
        }

        $debit = $query->sum('debit');
        $credit = $query->sum('credit');

        $balance = $account->normal_balance == 'debit'
            ? $debit - $credit
            : $credit - $debit;

        // 🔥 simpan cache
        $this->balanceCache[$accountId] = $balance;

        return $balance;
    }

    /*
    |--------------------------------------------------------------------------
    | TRIAL BALANCE
    |--------------------------------------------------------------------------
    */
    public function trialBalance($startDate = null, $endDate = null)
    {
        $accounts = Account::doesntHave('children')->get();

        $data = [];

        foreach ($accounts as $acc) {
            $balance = $this->getBalance($acc->id, $startDate, $endDate);

            if ($acc->normal_balance == 'debit') {

                $data[] = [
                    'account' => $acc,
                    'debit' => $balance > 0 ? $balance : 0,
                    'credit' => $balance < 0 ? abs($balance) : 0,
                ];

            } else {

                $data[] = [
                    'account' => $acc,
                    'debit' => $balance < 0 ? abs($balance) : 0,
                    'credit' => $balance > 0 ? $balance : 0,
                ];
            }
        }

        return $data;
    }

    /*
    |--------------------------------------------------------------------------
    | LABA RUGI
    |--------------------------------------------------------------------------
    */
    public function incomeStatement($startDate, $endDate)
    {
        $accounts = Account::doesntHave('children')->get();

        $revenue = 0;
        $expense = 0;

        foreach ($accounts as $acc) {

            // 🔥 FILTER PAKAI HELPER
            if (!$acc->isIncomeStatement()) {
                continue;
            }

            $balance = $this->getBalance($acc->id, $startDate, $endDate);

            if ($acc->type == 'revenue') {
                $revenue += abs($balance);
            }

            if ($acc->type == 'expense') {
                $expense += abs($balance);
            }
        }

        return [
            'revenue' => $revenue,
            'expense' => $expense,
            'profit' => $revenue - $expense
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | NERACA (TREE AGGREGATION)
    |--------------------------------------------------------------------------
    */
    public function balanceSheet($startDate = null, $endDate = null)
    {
        $accounts = \App\Models\Account::whereNull('parent_id')
            ->with('children.children')
            ->get();

        $tree = $this->buildTree($accounts, $startDate, $endDate);

        // 🔥 pisahkan kiri & kanan
        $assets = collect($tree)->filter(fn($x) => $x['account']->type == 'asset');
        $liabilities = collect($tree)->filter(fn($x) => $x['account']->type == 'liability');
        $equities = collect($tree)->filter(fn($x) => $x['account']->type == 'equity');

        // 🔥 hitung laba
        $profit = $this->getProfit($startDate, $endDate);

        // 🔥 tambahkan laba ditahan ke equity
        $equities->push([
            'account' => (object)[
                'code' => '999',
                'name' => 'Laba Ditahan'
            ],
            'balance' => $profit,
            'children' => []
        ]);

        return [
            'assets' => $assets,
            'liabilities' => $liabilities,
            'equities' => $equities
        ];
    }
    public function getProfit($startDate = null, $endDate = null)
    {
        $accounts = \App\Models\Account::doesntHave('children')->get();

        $revenue = 0;
        $expense = 0;

        foreach ($accounts as $acc) {

            // 🔥 hanya akun laba rugi
            if (!$acc->isIncomeStatement()) {
                continue;
            }

            $balance = $this->getBalance($acc->id, $startDate, $endDate);

            /*
            |--------------------------------------------------------------------------
            | PENTING: getBalance sudah pakai NORMAL BALANCE
            |--------------------------------------------------------------------------
            | revenue  → hasilnya positif
            | expense  → hasilnya positif
            */

            if ($acc->type == 'revenue') {
                $revenue += $balance;
            }

            if ($acc->type == 'expense') {
                $expense += $balance;
            }
        }

        return $revenue - $expense;
    }
    private function buildTree($accounts, $startDate = null, $endDate = null)
    {
        $result = [];

        foreach ($accounts as $acc) {

            // skip kalau bukan neraca
            if (!$acc->isBalanceSheet()) {
                continue;
            }

            $children = $this->buildTree($acc->children, $startDate, $endDate);

            if ($acc->children->count()) {
                $balance = collect($children)->sum('balance');
            } else {
                $balance = $this->getBalance($acc->id, $startDate, $endDate);
            }

            $result[] = [
                'account' => $acc,
                'balance' => $balance,
                'children' => $children
            ];
        }

        return $result;
    }
}