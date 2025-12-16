<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubPoinDetailPbumku extends Model
{
    protected $table = 'subpoin_details_pbumku';
    protected $primaryKey = 'sub_poin_detail_pbumku_id';
    protected $fillable = ['subpoin_pbumku_id', 'text'];

    public function subpoinPbumku(): BelongsTo
    {
        return $this->belongsTo(SubPoinPbumku::class, 'subpoin_pbumku_id', 'subpoin_pbumku_id');
    }
}
