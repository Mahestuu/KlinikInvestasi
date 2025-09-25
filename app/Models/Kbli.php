<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

class Kbli extends Model
{
    use Searchable;
    protected $table = 'kbli';
    protected $primaryKey = 'kbli_id';
    protected $fillable = ['nama', 'kode', 'ruang_lingkup', 'dinas_id', 'kategorikbli_id'];

    public function toSearchableArray()
    {
        return [
            'kode' => $this->kode,
            'nama' => $this->nama,
            'ruang_lingkup' => $this->ruang_lingkup,
        ];
    }

    public function dinas()
    {
        return $this->belongsTo(Dinas::class, 'dinas_id', 'dinas_id');
    }

    public function kategoriKbli()
    {
        return $this->belongsTo(KategoriKbli::class, 'kategorikbli_id', 'kategorikbli_id');
    }

    public function persyaratanPerizinan()
    {
        return $this->hasMany(PersyaratanPerizinan::class, 'kbli_id', 'kbli_id');
    }
}
