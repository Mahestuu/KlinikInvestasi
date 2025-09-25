<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriKbliSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $kategoriKbli = [
            ['nama' => 'A - Pertanian, Kehutanan dan Perikanan'],
            ['nama' => 'B - Pertambangan dan Penggalian'],
            ['nama' => 'C - Industri Pengolahan'],
            ['nama' => 'D - Pengadaan Listrik, Gas, Uap/Air Panas Dan Udara Dingin'],
            ['nama' => 'E - Treatment Air, Treatment Air Limbah, Treatment dan Pemulihan Material Sampah, dan Aktivitas Remediasi'],
            ['nama' => 'F - Konstruksi'],
            ['nama' => 'G - Perdagangan Besar Dan Eceran'],
            ['nama' => 'H - Pengangkutan dan Pergudangan'],
            ['nama' => 'I - Penyediaan Akomodasi Dan Penyediaan Makan Minum'],
            ['nama' => 'J - Informasi Dan Komunikasi'],
            ['nama' => 'K - Aktivitas Keuangan dan Asuransi'],
            ['nama' => 'L - Real Estat'],
            ['nama' => 'M - Aktivitas Profesional, Ilmiah Dan Teknis'],
            ['nama' => 'N - Aktivitas Penyewaan dan Sewa Guna Usaha Tanpa Hak Opsi, Ketenagakerjaan, Agen Perjalanan dan Penunjang Usaha Lainnya'],
            ['nama' => 'O - Administrasi Pemerintahan, Pertahanan Dan Jaminan Sosial Wajib'],
            ['nama' => 'P - Pendidikan'],
            ['nama' => 'Q - Aktivitas Kesehatan Manusia Dan Aktivitas Sosial'],
            ['nama' => 'R - Kesenian, Hiburan Dan Rekreasi'],
            ['nama' => 'S - Aktivitas Jasa Lainnya'],
            ['nama' => 'T - Aktivitas Rumah Tangga Sebagai Pemberi Kerja'],
            ['nama' => 'U - Aktivitas Badan Internasional Dan Badan Ekstra Internasional Lainnya'],
        ];

        DB::table('kategori_kbli')->insert($kategoriKbli);
    }
}
