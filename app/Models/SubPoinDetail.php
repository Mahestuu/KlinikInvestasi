<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubPoinDetail extends Model
{
    protected $table = 'subpoin_details';
    protected $primaryKey = 'sub_poin_detail_id';
    protected $fillable = ['subpoin_id', 'text'];

    public function subpoin()
    {
        return $this->belongsTo(Subpoin::class, 'subpoin_id');
    }
}
