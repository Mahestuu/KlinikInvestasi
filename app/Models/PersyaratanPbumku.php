<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PersyaratanPbumku extends Model
{
    protected $table = 'persyaratan_pbumku';
    protected $primaryKey = 'persyaratan_pbumku_id';
    protected $fillable = ['pbumku_id', 'nama'];

    public function pbumku(): BelongsTo
    {
        return $this->belongsTo(Pbumku::class, 'pbumku_id', 'pbumku_id');
    }

    public function subpoinPbumku()
    {
        return $this->hasMany(SubpoinPbumku::class, 'persyaratan_pbumku_id', 'persyaratan_pbumku_id');
    }
}
