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
        Schema::table('kbli', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('nama');
        });
        
        // Generate slugs for existing data
        $this->generateSlugsForExistingData();
        
        // Now make slug unique and not null
        Schema::table('kbli', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable(false)->change();
        });
    }
    
    private function generateSlugsForExistingData(): void
    {
        $data = DB::table('kbli')->whereNull('slug')->orWhere('slug', '')->get();
        
        foreach($data as $item) {
            $slug = Str::slug($item->nama);
            if (empty($slug)) {
                $slug = 'kbli-' . $item->kbli_id;
            }
            
            // Check if slug already exists
            $existing = DB::table('kbli')->where('slug', $slug)->where('kbli_id', '!=', $item->kbli_id)->first();
            if ($existing) {
                $slug = $slug . '-' . $item->kbli_id;
            }
            
            DB::table('kbli')->where('kbli_id', $item->kbli_id)->update(['slug' => $slug]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kbli', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
