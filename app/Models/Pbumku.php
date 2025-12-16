<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;
use Illuminate\Support\Str;

class Pbumku extends Model
{
    use Searchable;

    protected $table = 'pbumku';
    protected $primaryKey = 'pbumku_id';
    protected $fillable = ['dinas_id', 'nama', 'slug'];

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

        static::creating(function ($pbumku) {
            if (empty($pbumku->slug)) {
                $pbumku->slug = $pbumku->generateSlug();
            }
        });

        static::updating(function ($pbumku) {
            // Jika nama berubah, selalu generate slug baru
            if ($pbumku->isDirty('nama')) {
                $pbumku->slug = $pbumku->generateSlug();
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

        while (static::where('slug', $slug)->where('pbumku_id', '!=', $this->pbumku_id ?? 0)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
