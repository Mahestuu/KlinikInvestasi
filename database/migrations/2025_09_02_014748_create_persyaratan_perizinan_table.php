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
        Schema::create('persyaratan_perizinan', function (Blueprint $table) {
            $table->id('persyaratan_id');
            $table->string('nama');
            $table->foreignId('kbli_id')->constrained('kbli', 'kbli_id')->onDelete('cascade'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persyaratan_perizinan');
    }
};
