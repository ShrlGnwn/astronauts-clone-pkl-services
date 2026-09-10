# Astronauts Clone PKL (Backend API + Dashboard)

Boilerplate **skeleton** backend untuk peserta PKL di Bafageh — pendamping FE
[astronauts-clone-pkl](https://github.com/ShrlGnwn/astronauts-clone-pkl)
(clone UI [astronauts.id](https://www.astronauts.id/) — belanja kebutuhan harian 15 menit).

Endpoint masih placeholder (return text) — tugas PKL mengisi logic-nya.
Project ini **bukan cuma API**: ada juga dashboard admin (Blade) di `/dashboard/*`.

## Stack
Laravel 12 + SQLite (bawaan PHP, tanpa server DB) + Blade (dashboard) + Sanctum (tugas PKL)

## Jalankan
```bash
cd astronauts-clone-pkl-api
composer install
cp .env.example .env            # Windows (CMD): copy .env.example .env
touch database/database.sqlite  # Windows (CMD): type nul > database\database.sqlite
php artisan key:generate
php artisan migrate --seed      # bikin tabel + seed 12 kategori (contoh CRUD)
php artisan serve               # API + dashboard di http://127.0.0.1:8000
```

| | Requirement |
|---|---|
| PHP | **8.2+** (disarankan 8.3) |
| Composer | 2.x |
| DB | **SQLite** — bawaan PHP, gak perlu install server DB |
| Tes API | **Postman** (bukan curl) |

> Laptop Windows & PHP/Composer belum ada? Baca **[SETUP-WINDOWS.md](SETUP-WINDOWS.md)** —
> panduan install dari nol (Laragon/XAMPP) + troubleshooting.

## Tes API pakai Postman
1. Import **`astronauts-clone-pkl-api.postman_collection.json`** (folder project)
2. Buat Environment: variable `base_url` = `http://127.0.0.1:8000`
3. Send request **Ping** → `200 {"message":"pong",...}` = API hidup ✅

Tips: jalankan bersamaan FE (`npm run dev`, port 5173) — di FE panggil URL lengkap
`http://127.0.0.1:8000/api/...`.

## Yang wajib dipegang (jangan dilanggar)
- **Contoh standar = CRUD Kategori** (`CategoryController`) — tiru polanya buat resource lain,
  **jangan dihapus**. Bentuk JSON harus sama dengan data dummy FE (snake_case DB → camelCase JSON).
- **Jangan ubah kontrak endpoint** yang sudah ditulis (path & method) tanpa konfirmasi —
  FE atau tugas PKL lain memanggilnya.
- **Validasi & hitung uang selalu di server** (jangan percaya angka dari FE).
- Dashboard: **asset di `public/css` & `public/js`** (bukan inline di blade), tabel pakai
  **DataTables server-side**, form tambah/edit pakai modal + `fetch` JSON.

## Struktur folder
```
routes/
├── api.php                     # semua endpoint API (prefix /api)
└── web.php                     # login session + halaman /dashboard/*
app/
├── Http/Controllers/
│   ├── CategoryController.php          # CONTOH CRUD standar ← tiru
│   └── Dashboard/CategoryController.php # contoh CRUD dashboard (AJAX + DataTables)
├── Http/Controllers/Auth/LoginController.php   # login lokal dashboard
├── Http/Middleware/EnsureAccessAdmin.php       # cek users.access = 'admin'
├── Http/Resources/             # CategoryResource (snake_case → camelCase)
└── Models/                     # Category
database/
├── migrations/                 # create_categories_table, add_access_to_users_table, ...
└── seeders/                    # CategorySeeder (12 kategori), AdminSeeder (user dashboard)
resources/views/
├── auth/login.blade.php
├── dashboard/                  # index, orders (blank/TODO), categories (contoh CRUD AJAX)
└── layouts/dashboard.blade.php # layout sidebar-kiri + @stack('styles'/'scripts')
public/css/ & public/js/        # asset dashboard (dashboard.css, categories.css/js)
```

## Data & kontrak (acuan dari FE)
| Data | Sumber di FE | Endpoint target |
|---|---|---|
| Produk (37 item) | `src/features/catalog/data/products.js` | `GET /api/products`, `/api/products/{slug}` |
| Kategori (12 item) | `src/features/catalog/data/categories.js` | `/api/categories` ✅ |
| Koleksi `/c/:key` | `src/features/catalog/data/collectionProducts.js` | `GET /api/collections/{key}/products` |
| Promo `/promo/:slug` | `src/features/home/data/promoProducts.js` | `GET /api/promos/{slug}/products` |
| User demo | `src/features/auth/data/users.js` | `/api/auth/*`, `/api/me` |
| Order (checkout) | `src/features/checkout/services/checkoutApi.js` | `POST/GET /api/orders` |

## Endpoint API
| Method | Path | Status |
|---|---|---|
| GET | `/api/ping` | ✅ jadi |
| GET/POST | `/api/categories` | ✅ jadi (contoh CRUD) |
| GET/PUT/DELETE | `/api/categories/{id}` | ✅ jadi (contoh CRUD) |
| GET | `/api/products` (filter `?category=`, `?popular=1`, `?search=`) | ⬜ TODO |
| GET | `/api/products/{slug}` | ⬜ TODO |
| GET | `/api/collections/{key}/products` | ⬜ TODO |
| GET | `/api/promos/{slug}/products` | ⬜ TODO |
| POST | `/api/auth/register` · `/api/auth/login` · `/api/auth/logout` | ⬜ TODO |
| GET | `/api/me` | ⬜ TODO |
| POST | `/api/orders` | ⬜ TODO |
| GET | `/api/orders` | ⬜ TODO |

## Dashboard Web (Blade)
| Route | Status | Akses |
|---|---|---|
| `/login` · `/logout` | ✅ jadi | tamu / login |
| `/dashboard` → redirect `/dashboard/index` | ✅ jadi | login + admin |
| `/dashboard/index` | ✅ jadi (statistik dummy, tinggal diisi) | login + admin |
| `/dashboard/orders` | ⬜ blank — tugas PKL (BF5) | login + admin |
| `/dashboard/categories` (+ `/data`, POST/PUT/DELETE) | ✅ jadi (contoh CRUD AJAX) | login + admin |

## Akun demo
| Akun | Password | Untuk |
|---|---|---|
| `admin@demo.com` | `password` | login **dashboard** admin (kolom `access = 'admin'`) |
| `customer@demo.com` | `password` | contoh user belanja — **ditolak** di dashboard |
| `demo@demo.com` | `password` | user demo FE (dibuat di tugas BF3) |

Dashboard detail tugas & fase: **`PLAN.md`** (Fase **BF1–BF5**, sinkron dengan card
Trello board *PKL - Bafageh Daily Store* label **Services**).
