<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Dinas;
// use App\Models\KategoriKbli;
use App\Models\Kbli;
use App\Models\Persyaratan;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->call([
            DinasSeeder::class,
            KategoriKbliSeeder::class,
            KbliSeeder::class,
        ]);
    }
}
