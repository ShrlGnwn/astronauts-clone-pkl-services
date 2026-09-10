<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Catatan PKL: daftarkan seeder lain di sini supaya ikut jalan
     * saat `php artisan db:seed`. Contoh di bawah sudah terdaftar.
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,   // contoh CRUD lengkap: 12 kategori (/api/categories)
            AdminSeeder::class,      // user dashboard: admin@demo.com & customer@demo.com (kolom access)
            // ProductSeeder::class, // TODO (PKL): seed produk dari FE dummy (TUGAS #2)
            // UserSeeder::class,    // TODO (PKL): user demo demo@demo.com / password (TUGAS #5)
        ]);
    }
}
