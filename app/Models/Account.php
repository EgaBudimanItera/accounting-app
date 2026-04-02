<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    protected $fillable = ['code','name','type','parent_id','normal_balance'];

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

    public function isUsed()
    {
        return $this->journalDetails()->exists();
    }

    public function hasChildren()
    {
        return $this->children()->exists();
    }
}
