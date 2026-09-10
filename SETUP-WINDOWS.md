# SETUP WINDOWS — Instalasi & Menjalankan Project (untuk anak PKL)

> Dibaca pelan-pelan, ikuti urut. Kalau ada langkah yang error, cek dulu bagian
> **Troubleshooting** di bawah sebelum tanya pembimbing.

---

## 1. Requirement

| Kebutuhan | Minimal |
|---|---|
| OS | Windows 10 / 11 (64-bit) |
| RAM | 4 GB (2 GB juga jalan, agak pelan) |
| Koneksi internet | untuk download installer & `composer install` |
| Akun [GitHub](https://github.com) | untuk clone repo |
| [Postman](https://www.postman.com/downloads/) | untuk tes API (**ganti curl**) |

Software yang **belum ada** dan harus diinstall: **PHP 8.2+** dan **Composer**.
Pilih salah satu cara di bawah (disarankan **Cara A**).

---

## 2. Cara A — Laragon (PALING GAMPANG, disarankan)

Laragon = satu paket: PHP + Composer + MySQL + Apache + otomatis masuk PATH.
Khusus project ini kita cuma butuh **PHP + Composer**-nya, tapi gak masalah.

1. Download Laragon: <https://laragon.org/download/>
   Pilih **Laragon Full** (sudah termasuk PHP 8.x & Composer).
2. Install, lalu buka **Laragon**.
3. Klik menu **Tools → PHP → Version Manager** → pastikan versi **8.2 atau 8.3**.
   (Kalau belum ada versi 8.2+, klik *Add another version* → pilih 8.3.)
4. Pastikan ekstensi SQLite aktif:
   - Menu **Tools → PHP → php.ini**
   - Cari baris `;extension=sqlite3` dan `;extension=pdo_sqlite`
   - **Hapus titik-koma (`;`)** di depannya → simpan
   - Menu **Tools → Restart** (atau restart Laragon)
5. Buka **terminal Laragon** (tombol *Terminal* di kanan atas).
   Laragon otomatis menyediakan `php` & `composer` di terminal itu.
6. Selesai — lompat ke **Bagian 4**.

> **Alternatif**: kalau mau Composer yang "asli" Windows (bisa dipakai di CMD
> mana pun), tetap install **Composer-Setup.exe** di Cara B langkah 4 — setelah
> itu Composer jalan di semua terminal. PHP-nya tetap dari Laragon.

---

## 3. Cara B — Manual / XAMPP (kalau Laragon bermasalah)

### 3a. Install PHP

1. Download PHP **8.2 atau 8.3 (x64, thread safe)**:
   <https://windows.php.net/download/>
   File zip-nya yang namanya berakhiran `-nts-Windows-x64.zip` **jangan**,
   pilih yang **`-ts-Windows-x64.zip`** (thread-safe).
2. Ekstrak zip ke folder `C:\php`.
3. Tambahkan ke PATH:
   - Klik Start → ketik **"environment"** → *Edit environment variables*
   - **Environment Variables…** → di *User variables* pilih **Path** → **Edit**
   - **New** → ketik `C:\php` → OK semua
4. Aktifkan ekstensi SQLite:
   - Buka `C:\php\php.ini-development`, simpan sebagai `C:\php\php.ini`
   - Cari `;extension=sqlite3` & `;extension=pdo_sqlite` → hapus `;` → simpan
5. Tes: buka **CMD** (`Win+R` → ketik `cmd` → Enter) → ketik `php -v`
   → harus muncul versi PHP.

> **Pakai XAMPP?** Install XAMPP, lalu pakai `php.ini` di folder XAMPP
> (`C:\xampp\php\php.ini`) dan tambahkan `C:\xampp\php` ke PATH.
> Aktifkan juga `extension=pdo_sqlite` & `extension=sqlite3` di php.ini itu
> (hapus `;` di depannya).

### 3b. Install Composer

1. Download **Composer-Setup.exe**: <https://getcomposer.org/download/>
2. Jalankan installer-nya.
   - Saat ditanya lokasi PHP, pilih otomatis (installer mendeteksi `php.exe`)
   - Pilihan lain biarkan default
3. Tes: buka **CMD baru** → ketik `composer -V` → harus muncul versi Composer.

---

## 4. Jalankan Project

Buka **CMD / terminal Laragon / PowerShell**, lalu:

```bash
# 1. Ambil project (ganti URL sesuai repo yang dikasih pembimbing)
git clone https://github.com/ShrlGnwn/astronauts-clone-pkl-api.git
cd astronauts-clone-pkl-api

# 2. Install dependency PHP
composer install

# 3. Buat file konfigurasi .env
#    (CMD)     :  copy .env.example .env
#    (PowerShell):  Copy-Item .env.example .env

# 4. Buat database SQLite (file kosong — isinya dibuat otomatis oleh migrate)
#    (CMD)     :  type nul > database\database.sqlite
#    (PowerShell):  New-Item database\database.sqlite

# 5. Kunci enkripsi aplikasi
php artisan key:generate

# 6. Bikin tabel + isi data contoh (12 kategori)
php artisan migrate --seed

# 7. Jalankan server
php artisan serve
```

Kalau sukses, terminal menampilkan:
```
INFO  Server running on [http://127.0.0.1:8000]
```
**Biarkan terminal ini terbuka** (selagi server jalan, terminal tidak bisa dipakai
mengetik perintah lain — buka CMD baru kalau mau jalanin perintah lain).

> Port 8000 sedang dipakai? Jalankan dengan port lain:
> `php artisan serve --port=8001` → buka `http://127.0.0.1:8001`

---

## 5. Tes API pakai POSTMAN (bukan curl!)

1. Buka Postman → tombol **Import** → pilih file
   `astronauts-clone-pkl-api.postman_collection.json` (ada di folder project)
   → collection "Astronauts Clone PKL API" masuk.
2. **Buat Environment** (opsional, biar gak ketik URL terus):
   - Klik ikon *Environment* (kanan atas) → **+**
   - Nama: `local` → variable `base_url` = `http://127.0.0.1:8000`
3. Pilih environment `local` di kanan atas, lalu buka collection → klik request
   **Ping** → tombol **Send**.
   → Response `200 OK` dengan `{"message":"pong",...}` = **API hidup**. 🎉
4. Coba request lain:
   - **List Produk** → `GET {{base_url}}/api/products`
   - **Filter** → ubah *Params*: `category` = `buah-segar`, atau `popular` = `1`,
     atau `search` = `pisang`
   - **Detail Produk** → ganti *Path variable* `slug` = `pisang-cavendish`
   - Request bertanda **TODO** → balikannya masih text petunjuk, artinya itu
     **tugas kalian** untuk mengisinya (lihat `PLAN.md`).

**Cara baca response:**
- `200` = sukses
- `404` = data tidak ditemukan (contoh: slug salah)
- `500` = error di code — baca pesannya, biasanya karena query/model belum dibuat

---

## 6. Troubleshooting

| Gejala | Penyebab & Solusi |
|---|---|
| `'php' is not recognized as internal or external command` | PHP belum masuk PATH. Cek Cara A/3a langkah PATH. Kalau pakai Laragon, jalankan lewat **Terminal Laragon**. |
| `'composer' is not recognized...` | Composer belum di PATH → jalankan installer Composer sekali lagi (dia otomatis fix PATH). |
| `SQLSTATE[HY000]: database file does not exist` | File `database\database.sqlite` belum dibuat → ulangi langkah 4 bagian 4 (CMD: `type nul > database\database.sqlite`). |
| `could not find driver (SQLite)` / `pdo_sqlite` | Ekstensi belum aktif → buka php.ini, pastikan `extension=pdo_sqlite` & `extension=sqlite3` **tanpa** `;` di depan, restart terminal. |
| `Address already in use` saat `php artisan serve` | Port 8000 kepake → `php artisan serve --port=8001`. |
| Error `Target class [X] does not exist` | Dependency/autoload belum ke-refresh → `composer dump-autoload`. |
| `composer install` error soal versi PHP | PHP lo < 8.2 → install PHP 8.2/8.3 (Bagian 2/3). |
| Laragon: `composer` tidak ada di menu | Tools → **Composer** → *Install/Update Composer*, atau pakai Composer-Setup.exe (Cara B). |

---

## 7. Ceklis selesai setup

- [ ] `php -v` → versi 8.2+
- [ ] `composer -V` → muncul versi
- [ ] `php artisan serve` jalan → `http://127.0.0.1:8000`
- [ ] Postman: request **Ping** → `200 {"message":"pong"}`
- [ ] Postman: request **Kategori: List** → 12 kategori muncul
- [ ] Postman: request **Ping** → `200 {"message":"pong"}`
- [ ] Postman: request **List Produk** → balikannya text TODO (itu tugas PKL #2)

Kalau semua centang ✅ → lanjut ke **`PLAN.md`** 🚀
