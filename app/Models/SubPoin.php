<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubPoin extends Model
{
    protected $table = 'subpoin';
    protected $primaryKey = 'subpoin_id';
    protected $fillable = ['item', 'persyaratan_id'];

    public function persyaratanPerizinan()
    {
        return $this->belongsTo(PersyaratanPerizinan::class, 'persyaratan_id', 'persyaratan_id');
    }
}