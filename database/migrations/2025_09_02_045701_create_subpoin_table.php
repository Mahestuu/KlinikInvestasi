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
        Schema::create('subpoin', function (Blueprint $table) {
            $table->id('subpoin_id');
            $table->text('item');
            $table->foreignId('persyaratan_id')->constrained('persyaratan_perizinan', 'persyaratan_id')->onDelete('cascade'); // Spesifikasikan 'persyaratan_id'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subpoin');
    }
};
