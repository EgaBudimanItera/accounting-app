<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use HasFactory;
    protected $fillable = ['code','name','type','parent_id','normal_balance'];
    protected $table = 'accounts';

    public function parent()
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Account::class, 'parent_id');
    }

    public function scopeLeaf($query)
    {
        return $query->doesntHave('children');
    }

    public function journalDetails(): HasMany
    {
        return $this->hasMany(JournalDetail::class, 'account_id');
    }

    public function isUsed()
    {
        return $this->journalDetails()->exists();
    }

    public function hasChildren()
    {
        return $this->children()->exists();
    }

    public function isBalanceSheet()
    {
        return in_array($this->type, ['asset','liability','equity']);
    }

    public function isIncomeStatement()
    {
        return in_array($this->type, ['revenue','expense']);
    }
}
