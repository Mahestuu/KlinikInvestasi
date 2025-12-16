<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubPoinPbumku extends Model
{
    protected $table = 'subpoin_pbumku';
    protected $primaryKey = 'subpoin_pbumku_id';
    protected $fillable = ['persyaratan_pbumku_id', 'item'];

    public function persyaratanPbumku(): BelongsTo
    {
        return $this->belongsTo(PersyaratanPbumku::class, 'persyaratan_pbumku_id', 'persyaratan_pbumku_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(SubPoinDetailPbumku::class, 'subpoin_pbumku_id', 'subpoin_pbumku_id');
    }
}
