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
        Schema::create('kbli', function (Blueprint $table) {
            $table->id('kbli_id');
            $table->string('nama');
            $table->string('kode');
            $table->text('ruang_lingkup');
            $table->foreignId('dinas_id')->constrained('dinas', 'dinas_id')->onDelete('cascade'); // Spesifikasikan 'dinas_id' sebagai PK referensi
            $table->foreignId('kategorikbli_id')->constrained('kategori_kbli', 'kategorikbli_id')->onDelete('cascade'); // Spesifikasikan 'kategorikbli_id'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kbli');
    }
};
