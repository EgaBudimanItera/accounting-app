<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Services\AccountingService;

class JournalController extends Controller
{
    public function create()
    {
        $accounts = Account::all();
        return view('journal.create', compact('accounts'));
    }

    public function store(Request $request, AccountingService $service)
    {
        
        // VALIDASI
        $request->validate([
            'date' => 'required',
            'description' => 'required',
            'details' => 'required|array|min:2'
        ]);

        // VALIDASI BALANCE
        $totalDebit = collect($request->details)->sum('debit');
        $totalCredit = collect($request->details)->sum('credit');

        if ($totalDebit != $totalCredit) {
            return back()->with('error', 'Debit dan Credit tidak balance!');
        }

        try {

            // 🔥 FIX DI SINI
            $service = app(\App\Services\AccountingService::class);

            $service->createJournal($request->all());

            return back()->with('success', 'Jurnal berhasil disimpan');

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }
}