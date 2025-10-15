<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pbumku;
use Illuminate\Support\Str;

class PbumkuSlugSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pbumkus = Pbumku::whereNull('slug')->orWhere('slug', '')->get();
        
        foreach ($pbumkus as $pbumku) {
            $slug = Str::slug($pbumku->nama);
            $originalSlug = $slug;
            $counter = 1;
            
            // Check if slug already exists
            while (Pbumku::where('slug', $slug)->where('pbumku_id', '!=', $pbumku->pbumku_id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            
            $pbumku->update(['slug' => $slug]);
        }
        
        $this->command->info('Generated slugs for ' . $pbumkus->count() . ' PBUMKU records.');
    }
}