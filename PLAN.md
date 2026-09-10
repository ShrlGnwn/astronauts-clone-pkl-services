# PLAN (Roadmap PKL) - Astronauts Clone API

Ini daftar tugas untuk kamu, peserta PKL. Kerjakan urut dari **Fase BF1** ke bawah.
Centang (`[x]`) setiap kali satu poin selesai, supaya pembimbing bisa memantau progres.

Frontend pendamping: **[astronauts-clone-pkl](https://github.com/ShrlGnwn/astronauts-clone-pkl)**
(lihat `PLAN.md` di sana untuk sisi UI).

> Tiap poin di file ini = satu **card Trello** di board *PKL - Bafageh Daily Store*
> (label **Services**, format nama `[BFx] …`, checklist-nya ada di card).

---

## Pembagian feature (penting)

| Feature | Isi | Contoh file |
|---|---|---|
| `api/` | Endpoint REST untuk FE belanja (prefix `/api`) | `routes/api.php`, `CategoryController.php` |
| `dashboard/` | Halaman admin (Blade + AJAX + DataTables) | `routes/web.php`, `Dashboard/CategoryController.php` |
| `auth/` | Login dashboard (session) & API (Sanctum) | `Auth/LoginController.php`, `AuthController` (tugas) |

Dashboard **bukan bagian** dari `api/`: dia pakai session + Blade, bukan token.
Tapi keduanya memakai tabel & `Resource` yang sama (satu sumber data).

---

## Sebelum mulai: kenali dulu kontraknya

Backend ini melayani clone [astronauts.id](https://www.astronauts.id/) (ASTRO) —
belanja kebutuhan harian **15 menit**, buka **24 jam**. Semua bentuk data harus
**sama dengan dummy FE**, biar FE tinggal ganti sumber data.

| Hal | Nilai |
|---|---|
| Kategori | 12 item (`categories.js`) |
| Produk | 37 item (`products.js`) |
| List per halaman | `/c/:key` & `/promo/:slug` (map dari FE) |
| User demo | `demo@demo.com` / `password` |
| Minimum belanja | Rp20.000 (validasi di FE; server tetap hitung ulang) |
| Pembayaran | E-wallet, VA, Saldo Astro (**tidak ada COD**) |
| Loyalty | Astro Coin (1 coin = Rp5) |

---

## Blueprint endpoint (WAJIB jadi acuan)

Bentuk response **camelCase** (kolom DB snake_case → diubah di `Resource`).
Baca baik-baik sebelum ngoding — **jangan ubah path/method** tanpa konfirmasi.

### Produk
- `GET /api/products` — list 37 produk; filter `?category=`, `?popular=1`, `?search=`, `?limit=`
- `GET /api/products/{slug}` — detail 1 produk; **404** kalau tidak ada

### Koleksi & Promo (halaman daftar)
- `GET /api/collections/{key}/products` — produk untuk `/c/:key`
- `GET /api/promos/{slug}/products` — produk untuk `/promo/:slug`

### Auth (pakai Sanctum — token)
- `POST /api/auth/register` — `{name, email, password}` → user baru + token
- `POST /api/auth/login` — `{email, password}` → `{token, user}`
- `POST /api/auth/logout` — hapus token (header `Authorization: Bearer <token>`)
- `GET /api/me` — user yang login (middleware `auth:sanctum`)

### Order
- `POST /api/orders` — `{items:[{id, qty}], address, paymentMethod, subtotal, shippingFee, totalPrice}`
  → **subtotal dihitung ULANG di server** dari tabel produk (jangan percaya angka FE)
- `GET /api/orders` — order milik user login, terbaru di atas

### Dashboard (session, bukan token)
| Route | Fungsi |
|---|---|
| `/login` · `/logout` | login/logout admin (kolom `users.access = 'admin'`) |
| `/dashboard/index` | halaman awal (statistik) |
| `/dashboard/orders` | list order (tugas) |
| `/dashboard/categories` | contoh CRUD AJAX (DataTables + modal) |

**Sudah disiapkan:**
- `astronauts-clone-pkl-api.postman_collection.json` — collection Postman siap import.
- Route placeholder di `routes/api.php` (return text) untuk endpoint yang belum jadi.

> Tes pakai **Postman**, bukan curl. Environment: `base_url` = `http://127.0.0.1:8000`.

---

## Sudah disiapkan pembimbing — jangan diubah

- [x] Project Laravel 12 + SQLite (tanpa server DB)
- [x] Routing API (`routes/api.php`) + routing web (`routes/web.php`)
- [x] `GET /api/ping` — tes koneksi FE ↔ API
- [x] Contoh CRUD **Kategori** lengkap: migrasi + model + seeder (12 kategori) +
      `CategoryController` + `CategoryResource` ← **contoh standar, pelajari**
- [x] Postman collection + route placeholder untuk endpoint TODO
- [x] Kolom `users.access` (`'admin'` / `'customer'`) + `AdminSeeder`
- [x] Login dashboard (session): `/login`, `/logout` + middleware `EnsureAccessAdmin`
- [x] Layout dashboard sidebar-kiri + halaman `/dashboard/index` & `/dashboard/orders`
- [x] **Contoh CRUD AJAX dashboard**: `/dashboard/categories` (modal + DataTables server-side)
- [x] Standar dashboard: asset di `public/css` & `public/js` (bukan inline blade)
- [x] `SETUP-WINDOWS.md` (install PHP/Composer dari nol) + `README.md`

**Kalau butuh field/tabel baru, bilang pembimbing dulu — jangan edit sendiri.**

Field penting `Product` (kolom DB → JSON):

| Kolom DB | JSON | Arti |
|---|---|---|
| `slug` | `slug` | Key di URL → `/p/:slug` |
| `price` | `price` | Harga sekarang (integer rupiah) |
| `original_price` | `originalPrice` | Harga coret (`null` = tidak diskon) |
| `unit` | `unit` | Satuan jual (mis. `500 g`) |
| `stock` | `stock` | `0` = habis, tidak bisa dibeli |
| `is_popular` | `isPopular` | Untuk section "Paling Laris" |
| `category_slug` | `categorySlug` | Kategori produk |
| `images` | `images` | Array URL gambar (json) |
| `rating` | `rating` | Rating produk |
| `description` | `description` | Deskripsi |

---

## Fase BF1 — Fondasi & Produk

- [ ] **Kenali bentuk data FE**: baca `products.js` (37), `categories.js` (12),
      `collectionProducts.js`, `promoProducts.js`, `users.js` — catat semua field-nya
- [ ] Jalankan perintah: `php artisan make:model Product -m`
- [ ] Edit file migration: tambah kolom `slug` (unique), `name`, `unit`, `price`,
      `original_price` (nullable), `category_slug`, `images` (json), `rating`,
      `stock`, `is_popular`, `description` (nullable)
- [ ] Jalankan perintah: `php artisan make:seeder ProductSeeder`
- [ ] Isi seeder: 37 produk dari `products.js`, lalu daftarkan di `DatabaseSeeder`
- [ ] Jalankan perintah: `php artisan make:controller ProductController`
- [ ] Isi `index()` — list + filter `?category=`, `?popular=1`, `?search=`
- [ ] Isi `show($slug)` — 404 kalau produk tidak ada (tiru pola `CategoryController`)
- [ ] Jalankan perintah: `php artisan make:resource ProductResource`
- [ ] Isi resource: `originalPrice`, `categorySlug`, `isPopular` (camelCase)
- [ ] Ganti route placeholder di `routes/api.php` → `[ProductController::class, ...]`
- [ ] Tes di Postman: list, filter, detail, slug salah → 404
- [ ] Jalankan perintah: `php artisan migrate --seed`, lalu pastikan 37 produk masuk database
- [ ] Bonus: relasi `Category` → `Product` (sekarang masih string `category_slug`)
- [ ] Bonus: filter produk by kategori lewat relasi
- [ ] Bonus: endpoint list kategori + jumlah produk per kategori

## Fase BF2 — Koleksi & Promo

- [ ] Bikin tabel pivot `product_collections` (collection_key, product_id) + seed map dari FE
- [ ] Bikin tabel pivot `product_promos` (promo_slug, product_id) + seed map dari FE
- [ ] Bikin endpoint `GET /api/collections/{key}/products` — list produk satu koleksi (`/c/:key`)
- [ ] Bikin endpoint `GET /api/promos/{slug}/products` — list produk satu promo (`/promo/:slug`)

## Fase BF3 — Auth (Sanctum)

- [ ] Jalankan perintah: `composer require laravel/sanctum`
- [ ] Publish + jalankan migration `personal_access_tokens`
- [ ] Bikin endpoint `POST /api/auth/register` — terima `{name, email, password}`, balikin user + token
- [ ] Bikin endpoint `POST /api/auth/login` — terima `{email, password}`, balikin `{token, user}`
- [ ] Bikin endpoint `POST /api/auth/logout` — hapus token (header `Authorization: Bearer <token>`)
- [ ] Bikin endpoint `GET /api/me` — pakai middleware `auth:sanctum`
- [ ] Bikin seeder user demo: `demo@demo.com` / `password` (biar FE demo tetap bisa login)
- [ ] Tambah kolom `saldo` & `astro_coin` di tabel users — nilai contoh: Rp250.000 & 1.500 Astro Coin

## Fase BF4 — Order (transaksi)

- [ ] Bikin migration `orders`: `user_id`, `address`, `payment_method`, `subtotal`,
      `shipping_fee`, `total_price`, `status`
- [ ] Bikin migration `order_items`: `order_id`, `product_id`, `qty`, `price`
- [ ] Tambahkan relasi: `Order` → `User`, `Order` → `OrderItem`
- [ ] Bikin endpoint `POST /api/orders` (auth) — terima
      `{items:[{id, qty}], address, paymentMethod, subtotal, shippingFee, totalPrice}`
- [ ] **Hitung ulang subtotal di server** dari tabel produk — jangan percaya angka dari FE
- [ ] Simpan order + order_items dalam satu transaction
- [ ] Tes Postman: sukses 201, `qty` > stok → tolak
- [ ] Bikin endpoint `GET /api/orders` (auth) — order milik user login, terbaru di atas
- [ ] Bonus: bikin endpoint `GET /api/orders/{id}` + status progress order
- [ ] Bonus: kalau `paymentMethod` = Saldo Astro, potong kolom `saldo` user

## Fase BF5 — Dashboard Web & Integrasi FE

- [ ] Isi `/dashboard/orders`: bikin endpoint `data()` pola DataTables server-side
      (`draw/recordsTotal/recordsFiltered`) + tabel di `dashboard/orders.blade.php`
- [ ] Isi statistik `/dashboard/index` pakai query beneran (total order, customer, pendapatan)
- [ ] Tambah halaman dashboard baru — copy 3 file: view + `public/css/<page>.css` +
      `public/js/<page>.js` (tirukan halaman Kategori, jangan inline CSS/JS)
- [ ] CORS: izinkan origin FE (`http://localhost:5173`) di `config/cors.php`
- [ ] Ganti `catalogApi.js`: dari dummy lokal → `fetch` API (`GET /api/products`, dll)
- [ ] Ganti `AuthContext.jsx`: dari `demoUsers` → `POST /api/auth/login` (token Sanctum)
- [ ] Ganti `checkoutApi.js`: dari `localStorage` → `POST /api/orders` (kirim token)

---

## Aturan main

- Kerjakan **satu checklist per satu**, tes dulu (Postman atau langsung dari FE).
- Kalau nyangkut > 30 menit, tanya pembimbing — jangan diam.
- Boleh ubah struktur `routes/api.php` (pindah ke Controller), **tapi jangan
  ubah kontrak endpoint** (path & method) tanpa konfirmasi — FE memanggilnya.
- Jangan hapus/ubah **contoh standar** (Kategori CRUD & dashboard Kategori) — itu acuan pola.
- Validasi **selalu di server**; JS di dashboard cuma nampilin error dari server.

## Kredensial demo

| Akun | Password | Untuk |
|---|---|---|
| `admin@demo.com` | `password` | login dashboard (access = `admin`) |
| `customer@demo.com` | `password` | contoh user belanja — ditolak di dashboard |
| `demo@demo.com` | `password` | user demo FE (dibuat di Fase BF3) |

## Perintah penting

```bash
php artisan migrate --seed     # bikin tabel + seed contoh (12 kategori + user demo dashboard)
php artisan migrate:fresh --seed   # reset DB (hati-hati: data hilang)
php artisan serve              # API + dashboard → http://127.0.0.1:8000
php artisan route:list         # cek daftar route
```
