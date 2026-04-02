<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;

class AccountSeeder extends Seeder
{
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Account::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        /*
        |--------------------------------------------------------------------------
        | LEVEL 1
        |--------------------------------------------------------------------------
        */

        $asset = Account::create([
            'code'=>'1',
            'name'=>'ASET',
            'type'=>'asset',
            'normal_balance'=>'debit',
        ]);

        $liability = Account::create([
            'code'=>'2',
            'name'=>'LIABILITAS',
            'type'=>'liability',
            'normal_balance'=>'credit',
        ]);

        $equity = Account::create([
            'code'=>'3',
            'name'=>'EKUITAS',
            'type'=>'equity',
            'normal_balance'=>'credit',
        ]);

        $revenue = Account::create([
            'code'=>'4',
            'name'=>'PENDAPATAN',
            'type'=>'revenue',
            'normal_balance'=>'credit',
        ]);

        $expense = Account::create([
            'code'=>'5',
            'name'=>'BEBAN',
            'type'=>'expense',
            'normal_balance'=>'debit',
        ]);

        /*
        |--------------------------------------------------------------------------
        | LEVEL 2
        |--------------------------------------------------------------------------
        */

        $asetLancar = Account::create([
            'code'=>'1.1',
            'name'=>'Aset Lancar',
            'type'=>'asset',
            'parent_id'=>$asset->id,
            'normal_balance'=>'debit',
        ]);

        $hutangLancar = Account::create([
            'code'=>'2.1',
            'name'=>'Hutang Lancar',
            'type'=>'liability',
            'parent_id'=>$liability->id,
            'normal_balance'=>'credit',
        ]);

        /*
        |--------------------------------------------------------------------------
        | LEVEL 3 (DETAIL)
        |--------------------------------------------------------------------------
        */

        // ASET
        Account::create([
            'code'=>'1.1.1',
            'name'=>'Kas',
            'type'=>'asset',
            'parent_id'=>$asetLancar->id,
            'normal_balance'=>'debit',
        ]);

        Account::create([
            'code'=>'1.1.2',
            'name'=>'Bank',
            'type'=>'asset',
            'parent_id'=>$asetLancar->id,
            'normal_balance'=>'debit',
        ]);

        Account::create([
            'code'=>'1.1.3',
            'name'=>'Piutang Usaha',
            'type'=>'asset',
            'parent_id'=>$asetLancar->id,
            'normal_balance'=>'debit',
            'is_receivable'=>true
        ]);

        // LIABILITAS
        Account::create([
            'code'=>'2.1.1',
            'name'=>'Hutang Usaha',
            'type'=>'liability',
            'parent_id'=>$hutangLancar->id,
            'normal_balance'=>'credit',
            'is_payable'=>true
        ]);

        // EKUITAS
        Account::create([
            'code'=>'3.1',
            'name'=>'Modal',
            'type'=>'equity',
            'parent_id'=>$equity->id,
            'normal_balance'=>'credit',
        ]);

        // PENDAPATAN
        Account::create([
            'code'=>'4.1',
            'name'=>'Pendapatan Usaha',
            'type'=>'revenue',
            'parent_id'=>$revenue->id,
            'normal_balance'=>'credit',
        ]);

        // BEBAN
        Account::create([
            'code'=>'5.1',
            'name'=>'Beban Operasional',
            'type'=>'expense',
            'parent_id'=>$expense->id,
            'normal_balance'=>'debit',
        ]);

        Account::create([
            'code'=>'5.2',
            'name'=>'Beban Gaji',
            'type'=>'expense',
            'parent_id'=>$expense->id,
            'normal_balance'=>'debit',
        ]);
    }
}