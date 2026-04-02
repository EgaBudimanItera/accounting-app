<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalDetail extends Model
{
    use HasFactory;
    protected $fillable = ['journal_id','account_id','debit','credit'];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function journal()
    {
        return $this->belongsTo(\App\Models\Journal::class);
    }

    public function isUsed()
    {
        return $this->journalDetails()->exists();
    }
}
