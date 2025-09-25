<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersyaratanPerizinan extends Model
{
    protected $table = 'persyaratan_perizinan';
    protected $primaryKey = 'persyaratan_id';
    protected $fillable = ['nama', 'kbli_id'];

    public function kbli()
    {
        return $this->belongsTo(Kbli::class, 'kbli_id', 'kbli_id');
    }

    public function subpoin()
    {
        return $this->hasMany(SubPoin::class, 'persyaratan_id', 'persyaratan_id');
    }
}
