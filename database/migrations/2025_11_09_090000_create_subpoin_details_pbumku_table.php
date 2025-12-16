<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subpoin_details_pbumku', function (Blueprint $table) {
            $table->id('sub_poin_detail_pbumku_id');
            $table->foreignId('subpoin_pbumku_id')->constrained('subpoin_pbumku', 'subpoin_pbumku_id')->onDelete('cascade');
            $table->text('text');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subpoin_details_pbumku');
    }
};
