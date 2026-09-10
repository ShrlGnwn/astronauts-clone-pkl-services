<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

/**
 * Seeder 12 kategori — data asli dari dummy FE
 * src/features/catalog/data/categories.js
 */
class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'slug' => 'buah-segar',
                'name' => 'Buah Segar',
                'description' => 'Buah pilihan yang dipetik dan dikirim hari ini.',
                'icon' => 'https://picsum.photos/seed/cat-buah/200/200',
            ],
            [
                'slug' => 'sayur-segar',
                'name' => 'Sayur Segar',
                'description' => 'Sayuran harian untuk masakan rumah.',
                'icon' => 'https://picsum.photos/seed/cat-sayur/200/200',
            ],
            [
                'slug' => 'daging-ayam-seafood',
                'name' => 'Daging, Ayam & Seafood',
                'description' => 'Protein segar dan beku siap olah.',
                'icon' => 'https://picsum.photos/seed/cat-protein/200/200',
            ],
            [
                'slug' => 'telur-tahu-tempe',
                'name' => 'Telur, Tahu & Tempe',
                'description' => 'Lauk sederhana yang selalu tersedia.',
                'icon' => 'https://picsum.photos/seed/cat-telur/200/200',
            ],
            [
                'slug' => 'susu-olahan',
                'name' => 'Susu & Olahan',
                'description' => 'Susu UHT, yogurt, keju, dan butter.',
                'icon' => 'https://picsum.photos/seed/cat-susu/200/200',
            ],
            [
                'slug' => 'snack',
                'name' => 'Snack',
                'description' => 'Camilan untuk menemani aktivitasmu.',
                'icon' => 'https://picsum.photos/seed/cat-snack/200/200',
            ],
            [
                'slug' => 'minuman',
                'name' => 'Minuman',
                'description' => 'Minuman siap saji dan serbuk.',
                'icon' => 'https://picsum.photos/seed/cat-minuman/200/200',
            ],
            [
                'slug' => 'makanan-beku',
                'name' => 'Makanan Beku',
                'description' => 'Praktis tinggal panaskan.',
                'icon' => 'https://picsum.photos/seed/cat-beku/200/200',
            ],
            [
                'slug' => 'bumbu-masak',
                'name' => 'Bumbu Masak',
                'description' => 'Bumbu jadi dan rempah pilihan.',
                'icon' => 'https://picsum.photos/seed/cat-bumbu/200/200',
            ],
            [
                'slug' => 'kebutuhan-rumah',
                'name' => 'Kebutuhan Rumah',
                'description' => 'Perlengkapan rumah tangga harian.',
                'icon' => 'https://picsum.photos/seed/cat-rumah/200/200',
            ],
            [
                'slug' => 'perawatan-diri',
                'name' => 'Perawatan Diri',
                'description' => 'Sabun, pasta gigi, dan kebutuhan mandi.',
                'icon' => 'https://picsum.photos/seed/cat-care/200/200',
            ],
            [
                'slug' => 'astro-goods',
                'name' => 'Astro Goods',
                'description' => 'Merchandise eksklusif Astro.',
                'icon' => 'https://picsum.photos/seed/cat-astro/200/200',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
