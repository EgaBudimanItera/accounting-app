<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasFactory;
    protected $fillable = ['date','description','reference','created_by'];

    public function details()
    {
        return $this->hasMany(JournalDetail::class);
    }
}
