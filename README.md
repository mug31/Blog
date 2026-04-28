# Sistem Manajemen Blog (CMS) - UTS Web Teori

Aplikasi web Sistem Manajemen Blog (CMS) ini dikembangkan menggunakan PHP, MySQL, dan JavaScript murni. Proyek ini dibangun sepenuhnya menggunakan pendekatan *asynchronous* melalui **Fetch API**, sehingga seluruh operasi manipulasi data (CRUD) berlangsung dinamis tanpa *reload* halaman (menyerupai *Single Page Application*).

## Pemenuhan Rubrik Penilaian

Pengembangan proyek ini telah diusahakan sebaik mungkin agar sejalan dan memenuhi berbagai indikator yang terdapat pada rubrik penilaian Ujian Tengah Semester:

- [x] **1. Struktur Database dan Perintah SQL (10%)**
  Menggunakan database relasional `db_blog` dengan 3 tabel utama (`kategori_artikel`, `penulis`, `artikel`). Relasi *Foreign Key* diterapkan secara ketat (`ON DELETE RESTRICT`, `ON UPDATE CASCADE`).
- [x] **2. Koneksi PHP dan Database (5%)**
  Koneksi diimplementasikan secara terpusat pada `koneksi.php` menggunakan ekstensi `mysqli` berorientasi objek dengan *charset* `utf8mb4`.
- [x] **3. Tampilan / GUI (10%)**
  Antarmuka didesain *clean* dan modern menggunakan *Flexbox* CSS murni. Interaksi pengguna dikelola secara halus menggunakan form *modal pop-up* yang tertata rapi.
- [x] **4. CRUD Kategori Artikel (10%)**
  Operasi Create, Read, Update, Delete berjalan *asynchronous*. Dilengkapi proteksi penghapusan; kategori tidak dapat dihapus jika masih digunakan oleh sebuah artikel.
- [x] **5. CRUD Penulis (25%)**
  Termasuk fitur unggah foto profil (dengan *fallback* otomatis ke `default.png` jika dikosongkan). Kata sandi penulis dienkripsi secara aman menggunakan fungsi `password_hash()` dengan algoritma `PASSWORD_BCRYPT`.
- [x] **6. CRUD Artikel (30%)**
  Mengintegrasikan *dropdown* dinamis untuk relasi Penulis dan Kategori. Termasuk fitur unggah gambar utama dan *auto-generate* string `hari_tanggal` berdasarkan zona waktu `Asia/Jakarta` langsung dari *server-side*.
- [x] **7. Validasi dan Keamanan (10%)**
  - **SQL Injection Prevention:** Seluruh eksekusi *database* menggunakan *Prepared Statements* (`bind_param`).
  - **XSS Prevention:** Sanitasi *output* menggunakan `htmlspecialchars()` saat pengiriman data *response*.
  - **Validasi File Ekstra Ketat:** Pemeriksaan tipe MIME file secara nyata menggunakan `finfo_file()`, menghindari celah manipulasi `$_FILES['type']`. Terdapat juga limitasi ukuran file maksimal 2 MB.
  - **Proteksi Direktori:** Folder unggahan (`uploads_penulis/` dan `uploads_artikel/`) dilindungi dari eksekusi *script* PHP menggunakan konfigurasi `Require all denied` pada file `.htaccess`.

## 📂 Struktur Direktori

```text
blog/
├── uploads_artikel/       # Direktori penyimpanan gambar artikel (dilindungi .htaccess)
├── uploads_penulis/       # Direktori penyimpanan foto profil (dilindungi .htaccess)
│   └── default.png        # Gambar bawaan untuk profil kosong
├── index.php              # Kerangka utama antarmuka (GUI) & Modal
├── koneksi.php            # Konfigurasi koneksi database MySQLi
├── script.js              # Logika Fetch API, navigasi, dan interaksi DOM
├── style.css              # Tata letak visual dan styling komponen
├── ambil_*.php            # Endpoint untuk operasi Read (Kategori, Penulis, Artikel)
├── simpan_*.php           # Endpoint untuk operasi Create & validasi file
├── update_*.php           # Endpoint untuk operasi Update & pembaruan file
├── hapus_*.php            # Endpoint untuk operasi Delete & penghapusan relasi fisik
└── db_blog.sql            # Skema database dan dummy data untuk import
