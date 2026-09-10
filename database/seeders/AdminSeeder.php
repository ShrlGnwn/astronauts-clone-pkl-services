<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * User contoh untuk login DASHBOARD (web).
 *
 *   admin@demo.com   / password   → access 'admin'    (boleh masuk dashboard)
 *   customer@demo.com / password  → access 'customer' (user belanja biasa;
 *                                   login dashboard DITOLAK)
 *
 * Dipakai sama dengan user API (tabel users). Pembeda cuma kolom `access`.
 * Pola updateOrCreate → seeder aman dijalankan berulang (idempotent).
 */
class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@demo.com'],
            [
                'name' => 'Admin ASTRO',
                'password' => 'password', // cast 'hashed' otomatis di-hash
                'access' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'customer@demo.com'],
            [
                'name' => 'Customer Demo',
                'password' => 'password',
                'access' => 'customer',
            ]
        );
    }
}
