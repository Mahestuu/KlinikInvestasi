<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dinas extends Model
{
    protected $table = 'dinas';
    protected $primaryKey = 'dinas_id';
    protected $fillable = ['nama'];

    public function kbli()
    {
        return $this->hasMany(Kbli::class, 'dinas_id', 'dinas_id');
    }
}
