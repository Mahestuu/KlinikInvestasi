<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Dinas;
use Illuminate\Support\Facades\DB;

class DinasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $dinas = [
            ['nama' => 'Dinas Pendidikan'],
            ['nama' => 'Dinas Kesehatan'],
            ['nama' => 'Dinas Sumber Daya Air dan Bina Marga'],
            ['nama' => 'Dinas Perumahan Rakyat dan Kawasan Permukiman Serta Pertanahan'],
            ['nama' => 'Dinas Pemadam Kebakaran dan Penyelamatan'],
            ['nama' => 'Dinas Sosial'],
            ['nama' => 'Dinas Perindustrian dan Tenaga Kerja'],
            ['nama' => 'Dinas Pemberdayaan Perempuan dan Perlindungan Anak serta Pengendalian Penduduk dan Keluarga Berencana'],
            ['nama' => 'Dinas Ketahanan Pangan dan Pertanian'],
            ['nama' => 'Dinas Lingkungan Hidup'],
            ['nama' => 'Dinas Kependudukan dan Pencatatan Sipil'],
            ['nama' => 'Dinas Perhubungan'],
            ['nama' => 'Dinas Komunikasi dan Informatika'],
            ['nama' => 'Dinas Koperasi Usaha Kecil dan Menengah dan Perdagangan'],
            ['nama' => 'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu'],
            ['nama' => 'Dinas Kebudayaan, Kepemudaan dan Olah Raga serta Pariwisata'],
            ['nama' => 'Dinas Perpustakaan dan Kearsipan'],
        ];

        DB::table('dinas')->insert($dinas);
    }
}
