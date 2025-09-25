<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

class Pbumku extends Model
{
    use Searchable;

    protected $table = 'pbumku';
    protected $primaryKey = 'pbumku_id';
    protected $fillable = ['dinas_id', 'nama'];

    public function toSearchableArray()
    {
        return [
            'nama' => $this->nama,
        ];
    }

    public function kbli(): BelongsToMany
    {
        return $this->belongsToMany(Kbli::class, 'kbli_pbumku', 'pbumku_id', 'kbli_id');
    }

    public function persyaratanPbumku(): HasMany
    {
        return $this->hasMany(PersyaratanPbumku::class, 'pbumku_id', 'pbumku_id');
    }
    public function dinas()
    {
        return $this->belongsTo(Dinas::class, 'dinas_id', 'dinas_id');
    }
}
