<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pbumku', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('nama');
        });
        
        // Generate slugs for existing data
        $this->generateSlugsForExistingData();
        
        // Now make slug unique and not null
        Schema::table('pbumku', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable(false)->change();
        });
    }
    
    private function generateSlugsForExistingData(): void
    {
        $data = DB::table('pbumku')->whereNull('slug')->orWhere('slug', '')->get();
        
        foreach($data as $item) {
            $slug = Str::slug($item->nama);
            if (empty($slug)) {
                $slug = 'pbumku-' . $item->pbumku_id;
            }
            
            // Check if slug already exists
            $existing = DB::table('pbumku')->where('slug', $slug)->where('pbumku_id', '!=', $item->pbumku_id)->first();
            if ($existing) {
                $slug = $slug . '-' . $item->pbumku_id;
            }
            
            DB::table('pbumku')->where('pbumku_id', $item->pbumku_id)->update(['slug' => $slug]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pbumku', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
