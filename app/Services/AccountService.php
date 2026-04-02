<?php

namespace App\Services;

use App\Models\Account;

class AccountService
{
    public function create($data)
    {
        return Account::create([
            'code' => $data['code'],
            'name' => $data['name'],
            'type' => $data['type'],
            'parent_id' => $data['parent_id'] ?? null,
            'normal_balance' => $data['normal_balance'],
            'is_receivable' => isset($data['is_receivable']),
            'is_payable' => isset($data['is_payable']),
        ]);
    }

    public function update($id, $data)
    {
        $account = Account::findOrFail($id);

        $account->update([
            'code' => $data['code'],
            'name' => $data['name'],
            'type' => $data['type'],
            'parent_id' => $data['parent_id'] ?? null,
            'normal_balance' => $data['normal_balance'],
            'is_receivable' => isset($data['is_receivable']),
            'is_payable' => isset($data['is_payable']),
        ]);

        return $account;
    }

    public function delete($id)
    {
        $account = Account::findOrFail($id);

        if ($account->children()->count()) {
            throw new \Exception('Akun punya turunan');
        }

        if ($account->journalDetails()->count()) {
            throw new \Exception('Akun sudah dipakai transaksi');
        }

        return $account->delete();
    }
}