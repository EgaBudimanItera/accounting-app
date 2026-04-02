<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Account;
use App\Services\AccountService;

class AccountController extends Controller
{
    protected $service;
    public function __construct(AccountService $service)
    {
        $this->service = $service;
    }
    public function index()
    {
        $accounts = Account::whereNull('parent_id')
                    ->with([
                        'children.children',
                        'journalDetails'
                    ])
                    ->orderBy('code')
                    ->get();

        return view('account.index', compact('accounts'));
    }

    public function create()
    {
        $parents = Account::orderBy('code')->get();
        return view('account.create', compact('parents'));
    }

    
    public function store(Request $request)
    {
        
        $this->service->create($request->all());

        return redirect('/accounts')->with('success','Berhasil tambah akun');
    }

    public function update(Request $request, $id)
    {
        $account = \App\Models\Account::findOrFail($id);

        // ❌ kalau sudah dipakai jurnal → tidak boleh edit
        if ($account->journalDetails()->count()) {
            return back()->with('error', 'Akun sudah dipakai transaksi, tidak bisa diubah');
        }

        $this->service->update($id, $request->all());

        return redirect('/accounts')->with('success', 'Akun berhasil diupdate');
    }

    public function destroy($id)
    {
        $account = \App\Models\Account::findOrFail($id);

        // ❌ tidak boleh delete kalau punya child
        if ($account->children()->count()) {
            return back()->with('error', 'Akun punya turunan, tidak bisa dihapus');
        }

        // ❌ tidak boleh delete kalau sudah dipakai jurnal
        if ($account->journalDetails()->count()) {
            return back()->with('error', 'Akun sudah dipakai transaksi');
        }

        $account->delete();

        return back()->with('success', 'Akun berhasil dihapus');
    }
}   