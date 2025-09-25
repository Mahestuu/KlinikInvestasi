<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriKbli extends Model
{
    protected $table = 'kategori_kbli';
    protected $primaryKey = 'kategorikbli_id';
    protected $fillable = ['nama'];

    public function kbli()
    {
        return $this->hasMany(Kbli::class, 'kategorikbli_id', 'kategorikbli_id');
    }
}
