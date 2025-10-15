<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;
use Illuminate\Support\Str;

class Kbli extends Model
{
    use Searchable;
    protected $table = 'kbli';
    protected $primaryKey = 'kbli_id';
    protected $fillable = ['nama', 'kode', 'ruang_lingkup', 'dinas_id', 'kategorikbli_id', 'slug'];

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

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($kbli) {
            if (empty($kbli->slug)) {
                $kbli->slug = $kbli->generateSlug();
            }
        });

        static::updating(function ($kbli) {
            if ($kbli->isDirty('nama') && empty($kbli->slug)) {
                $kbli->slug = $kbli->generateSlug();
            }
        });
    }

    /**
     * Generate a unique slug from the nama field.
     */
    public function generateSlug()
    {
        $slug = Str::slug($this->nama);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->where('kbli_id', '!=', $this->kbli_id ?? 0)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
