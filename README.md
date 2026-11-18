# KursusKu — Laravel Implementation (Pertemuan 7, 9, 10, 11 & 12)

Platform website edukasi dan pelatihan kursus digital yang dibangun dengan kerangka kerja modern **Laravel Framework**, mengimplementasikan arsitektur MVC, Blade templating, integrasi Vite, Bootstrap 5, jQuery Ajax, database MySQL, migrasi skema, model Eloquent ORM, operasi CRUD lengkap, serta **Basis Data Relasional Tiga Entitas** (*Participants, Courses, Enrollments*) dengan *Cascade On Delete*, *Composite Unique Index*, dan *Eager Loading*.

---

## 1. Deskripsi Proyek

**KursusKu** adalah platform penyedia program pelatihan keahlian digital (Teknologi, Desain Grafis, Bisnis, dan Perkantoran) dengan kurikulum praktis berbasis industri. Proyek ini mengusung tagline:

> *"Belajar, daftar, dan kelola kursus dalam satu tempat."*

- **Milestone Pertemuan 7**: Fondasi arsitektur Model-View-Controller (MVC), sistem routing terstruktur, controller in-memory, Blade template, dan desain visual ungu-putih responsif.
- **Milestone Pertemuan 9**: Pemasangan Bootstrap 5, Popper, dan jQuery via Vite npm bundle; implementasi Master Layout (`layouts/app.blade.php`) dan Reusable Navbar (`partials/navbar.blade.php`); formulir pendaftaran interaktif dengan proteksi `@csrf` dan validasi server-side; serta endpoint ketersediaan kursi realtime menggunakan **jQuery Ajax** tanpa reload halaman.
- **Milestone Pertemuan 10**: Koneksi database MySQL (`kursus_dea`), perancangan migrasi tabel `courses`, implementasi Model Eloquent `Course`, resource route kursus, manajemen controller `CourseController` (Tampil & Insert), formulir tambah kursus dengan validasi komprehensif, penyajian tabel kursus responsif, serta integrasi dinamis data kursus pada formulir pendaftaran dan pengecekan kuota kursi via Ajax.
- **Milestone Pertemuan 11**: Laravel CRUD Lanjutan — implementasi Route Model Binding, halaman rincian detail kursus (`courses.show`), pemisahan logika validasi ke Form Request (`StoreCourseRequest` dan `UpdateCourseRequest`), validasi keunikan dinamis dengan `Rule::unique()->ignore()`, formulir edit dengan pemuatan nilai awal (`old('field', $course->field)`), method spoofing `@method('PUT')` dan `@method('DELETE')`, operasi Update dan Delete dengan konfirmasi JavaScript, umpan balik error di bawah setiap field, serta pengujian terstruktur 15 skenario.
- **Milestone Pertemuan 12**: Relational Database — perancangan skema relasi tiga entitas (`courses`, `participants`, `enrollments`), penerapan *Foreign Key* dengan *Cascade On Delete*, indeks keunikan komposit (*Composite Unique*) pada pasangan `(participant_id, course_id)`, relasi Eloquent ORM (*One-to-Many*, *Many-to-One*, dan *Many-to-Many* dengan pivot `status`), penyemaian data peserta dummy, manajemen controller `EnrollmentController` (pendaftaran dua dropdown & laporan relasional dengan *Eager Loading* `with(['participant', 'course'])`), serta pengujian menyeluruh skenario pendaftaran dan *cascade delete*.

---

## 2. Kebutuhan Sistem (Requirements)

Untuk menjalankan proyek ini pada lingkungan pengembangan lokal:

- **PHP**: Versi 8.2 atau lebih baru (lingkungan pengembangan: PHP 8.3.33)
- **Database Server**: MySQL 8.0+ / MariaDB 10.4+ (Port 3306)
- **Composer**: Versi 2.x
- **Node.js**: Versi 18+ (lingkungan pengembangan: Node.js v22.18.0) & npm
- **Ekstensi PHP Aktif**: `pdo`, `pdo_mysql`, `pdo_sqlite`, `ctype`, `curl`, `dom`, `fileinfo`, `filter`, `hash`, `mbstring`, `openssl`, `pcre`, `session`, `tokenizer`, `xml`
- **Web Server / CLI**: Laragon (Apache/Nginx) atau built-in server PHP (`php artisan serve`)
- **Peramban Web (Browser)**: Google Chrome, Mozilla Firefox, Microsoft Edge, atau Safari modern

---

## 3. Teknologi yang Digunakan

- **Backend Framework**: Laravel 11/12 (PHP 8.3)
- **Database & ORM**: MySQL (InnoDB Engine) & Laravel Eloquent ORM
- **Asset Bundler**: Vite 8.x (`laravel-vite-plugin`)
- **Frontend Framework & Library**:
  - **Bootstrap 5.3.x**: Komponen navigasi responsif, form control, utilities, alert, tombol aksi, dan tabel responsif.
  - **@popperjs/core**: Dependensi tooltip dan popover Bootstrap.
  - **jQuery 4.x / 3.x**: Manipulasi DOM dan pemrosesan asinkron via `$.ajax()`.
- **Templating**: Laravel Blade Template Engine (Master layout, `@yield`, `@section`, `@include`, `@forelse`, `@method`).
- **Styling**: Native CSS3 Modern & Custom Purple-White Theme (`public/css/style.css`).
- **Testing**: PHPUnit 11 (Automated Feature & Unit Testing — 44 tests, 271 assertions).

---

## 4. Konfigurasi Database MySQL

Konfigurasi koneksi database diatur pada berkas `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kursus_dea
DB_USERNAME=root
DB_PASSWORD=
```

Pastikan database `kursus_dea` sudah dibuat di MySQL sebelum menjalankan migrasi:
```sql
CREATE DATABASE IF NOT EXISTS kursus_dea CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

---

## 5. Cara Menjalankan Migrasi, Seeder & Aplikasi

1. **Buka Terminal / Command Prompt**
   Arahkan direktori aktif ke folder proyek:
   ```bash
   cd D:\laragon\www\kursusku-laravel
   ```

2. **Pastikan Dependensi Terpasang**
   ```bash
   composer install
   npm install
   ```

3. **Pastikan Application Key Tersedia**
   ```bash
   php artisan key:generate
   ```

4. **Jalankan Migrasi Database**
   Eksekusi migrasi untuk membentuk tabel `courses`, `participants`, dan `enrollments`:
   ```bash
   php artisan migrate
   ```
   > *Catatan: Dilarang menggunakan `migrate:fresh` agar data yang telah ada tidak terhapus secara tidak sengaja.*

5. **Jalankan Seeder Peserta Dummy (Pertemuan 12)**
   ```bash
   php artisan db:seed --class=ParticipantSeeder
   ```

6. **Kompilasi Aset Frontend (Vite)**
   ```bash
   npm run build
   ```
   *Atau jalankan `npm run dev` untuk mode hot-reload selama pengembangan.*

7. **Jalankan Server Lokal**
   Gunakan perintah artisan serve:
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses melalui:
   - Beranda: `http://127.0.0.1:8000/`
   - Daftar Kursus: `http://127.0.0.1:8000/courses`
   - Tambah Kursus: `http://127.0.0.1:8000/courses/create`
   - Detail Kursus: `http://127.0.0.1:8000/courses/1`
   - Edit Kursus: `http://127.0.0.1:8000/courses/1/edit`
   - Laporan Enrollment Relasional: `http://127.0.0.1:8000/enrollments`
   - Form Pendaftaran Enrollment: `http://127.0.0.1:8000/enrollments/create`
   - Form Pendaftaran Publik (Ajax): `http://127.0.0.1:8000/pendaftaran`
   - Cek Kuota Kursus (JSON): `http://127.0.0.1:8000/courses/1/availability`

8. **Menjalankan Pengujian Otomatis (PHPUnit)**
   ```bash
   php artisan test
   ```

---

## 6. Struktur Berkas Utama

```text
kursusku-laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Controller.php
│   │   │   ├── HomeController.php         <-- Landing page (Pertemuan 7)
│   │   │   ├── CourseController.php       <-- CRUD Lengkap Kursus (Pertemuan 10 & 11)
│   │   │   └── EnrollmentController.php   <-- Manajemen Enrollment Relasional & Publik (Pertemuan 9 & 12)
│   │   └── Requests/
│   │       ├── StoreCourseRequest.php     <-- Form Request validasi insert kursus baru (Pertemuan 11)
│   │       └── UpdateCourseRequest.php    <-- Form Request validasi update kursus (Pertemuan 11)
│   └── Models/
│       ├── Course.php                     <-- Model Course dengan relasi enrollments & participants (Pertemuan 10 & 12)
│       ├── Participant.php                <-- Model Participant dengan relasi enrollments & courses (Pertemuan 12)
│       └── Enrollment.php                 <-- Model Pivot Relasi dengan belongsTo participant & course (Pertemuan 12)
├── database/
│   ├── migrations/
│   │   ├── 2026_11_18_232500_create_courses_table.php      <-- Migrasi skema tabel courses (Pertemuan 10)
│   │   ├── 2026_11_19_000001_create_participants_table.php <-- Migrasi skema tabel participants (Pertemuan 12)
│   │   └── 2026_11_19_000002_create_enrollments_table.php  <-- Migrasi skema tabel enrollments (Pertemuan 12)
│   └── seeders/
│       ├── DatabaseSeeder.php
│       └── ParticipantSeeder.php          <-- Seeder peserta dummy aman berbasis firstOrCreate (Pertemuan 12)
├── public/
│   ├── css/
│   │   └── style.css                     <-- Stylesheet tema Ungu & Putih KursusKu
│   └── images/
│       └── hero-kursus.jpg               <-- Aset visual ilustrasi landing page
├── resources/
│   ├── css/
│   │   └── app.css                       <-- Entrypoint CSS Vite (Impor Bootstrap CSS)
│   ├── js/
│   │   ├── bootstrap.js                  <-- Inisialisasi library pendukung
│   │   └── app.js                        <-- Entrypoint JS Vite (Bootstrap, jQuery, Ajax Logic)
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php             <-- Master layout reusable (Pertemuan 9, 10 & 12)
│       ├── partials/
│       │   └── navbar.blade.php          <-- Komponen navigasi responsif Bootstrap (Pertemuan 9, 10 & 12)
│       ├── courses/
│       │   ├── index.blade.php           <-- View daftar kursus dengan tombol aksi (Pertemuan 10 & 11)
│       │   ├── create.blade.php          <-- View formulir tambah kursus baru (Pertemuan 10 & 11)
│       │   ├── show.blade.php            <-- View rincian detail data kursus (Pertemuan 11)
│       │   └── edit.blade.php            <-- View formulir edit kursus (Pertemuan 11)
│       └── enrollments/
│           ├── index.blade.php           <-- Laporan pendaftaran relasional eager loading (Pertemuan 12)
│           ├── create.blade.php          <-- Formulir pendaftaran enrollment dua dropdown (Pertemuan 12)
│           └── pendaftaran.blade.php     <-- Formulir pendaftaran interaktif publik Ajax (Pertemuan 9)
├── routes/
│   └── web.php                           <-- Definisi rute web aplikasi
├── tests/
│   └── Feature/
│       ├── HomeControllerTest.php        <-- Uji otomatis modul beranda Pertemuan 7
│       ├── CourseCrudTest.php            <-- Uji otomatis 15 skenario CRUD Kursus Pertemuan 10 & 11
│       └── EnrollmentTest.php            <-- Uji otomatis modul relasi, pendaftaran, dan cascade Pertemuan 9 & 12
├── evidence/
│   ├── week-7/                           <-- Evidence dokumentasi Pertemuan 7
│   ├── week-09/                          <-- Evidence pengujian & log AI Pertemuan 9
│   ├── week-10/                          <-- Evidence pengujian 8 test case & log AI Pertemuan 10
│   ├── week-11/                          <-- Evidence pengujian 15 skenario & log AI Pertemuan 11
│   └── week-12/                          <-- Evidence relasi, 8 skenario & cascade delete Pertemuan 12
│       ├── erd.md                        <-- Dokumentasi ERD visual dan tabel constraint
│       ├── test-matrix.md                <-- Hasil detail 8 skenario pengujian & cascade delete
│       └── ai-usage-log.md               <-- Log transparan penggunaan bantuan AI
└── README.md
```

---

## 7. Rincian Modul Pertemuan 12 (Relational Database)

### A. Tiga Entitas Basis Data Relasional
Sistem mengelola tiga entitas utama yang saling terhubung di MySQL:
1. **`courses`**: Menyimpan katalog program pelatihan aktif (ID, kode unik, nama, biaya, kuota, tanggal mulai, status `draft/open/closed`).
2. **`participants`**: Menyimpan data identitas peserta kursus:
   - `id`: Primary key (`BIGINT UNSIGNED AUTO_INCREMENT`)
   - `name`: Nama peserta (`VARCHAR(100)`)
   - `email`: Alamat email unik (`VARCHAR(120) UNIQUE`)
   - `phone`: Nomor telepon (`VARCHAR(20) NULLABLE`)
3. **`enrollments`**: Tabel perantara (*pivot table*) yang menghubungkan peserta dan kursus:
   - `id`: Primary key (`BIGINT UNSIGNED AUTO_INCREMENT`)
   - `participant_id`: Foreign key ke `participants(id)` dengan `ON DELETE CASCADE`
   - `course_id`: Foreign key ke `courses(id)` dengan `ON DELETE CASCADE`
   - `status`: Status pendaftaran (`VARCHAR(20)`, default `'registered'`)
   - `created_at` & `updated_at`: Waktu pencatatan
   - **Composite Unique Index:** `UNIQUE KEY (participant_id, course_id)`

### B. Karakteristik Integritas Data & Foreign Key Cascade
- **Aturan Keunikan:** `participant_id` dan `course_id` tidak unik secara individual sehingga seorang peserta dapat mendaftar banyak kursus, dan sebuah kursus dapat diikuti oleh banyak peserta. Hanya kombinasi pasangan keduanya yang unik (*Composite Unique*), mencegah pendaftaran ganda pada kursus yang sama.
- **Cascade Delete:** Konfigurasi `ON DELETE CASCADE` memastikan bila master data (`Course` atau `Participant`) dihapus, seluruh data pendaftaran terkait di tabel `enrollments` akan otomatis terhapus oleh database tanpa meninggalkan *orphan records*.

### C. Model dan Relasi Eloquent
1. **`Participant` (`app/Models/Participant.php`):**
   ```php
   public function enrollments(): HasMany
   {
       return $this->hasMany(Enrollment::class);
   }

   public function courses(): BelongsToMany
   {
       return $this->belongsToMany(Course::class, 'enrollments')
           ->withPivot('status')
           ->withTimestamps();
   }
   ```
2. **`Course` (`app/Models/Course.php`):**
   ```php
   public function enrollments(): HasMany
   {
       return $this->hasMany(Enrollment::class);
   }

   public function participants(): BelongsToMany
   {
       return $this->belongsToMany(Participant::class, 'enrollments')
           ->withPivot('status')
           ->withTimestamps();
   }
   ```
3. **`Enrollment` (`app/Models/Enrollment.php`):**
   ```php
   public function participant(): BelongsTo
   {
       return $this->belongsTo(Participant::class);
   }

   public function course(): BelongsTo
   {
       return $this->belongsTo(Course::class);
   }
   ```

### D. Data Dummy Participant
Penyemaian data peserta dummy pada `database/seeders/ParticipantSeeder.php` menggunakan metode aman `firstOrCreate`:
1. **Alya Rahma** — `alya@example.test` — `081200000001`
2. **Bima Putra** — `bima@example.test` — `081200000002`
3. **Citra Nabila** — `citra@example.test` — *(nomor telepon kosong)*

### E. Manajemen Controller `EnrollmentController`
- **`create()`**: Mengambil data peserta diurutkan berdasarkan nama (`Participant::orderBy('name')->get()`) dan kursus aktif berstatus `open` (`Course::where('status', 'open')->orderBy('name')->get()`).
- **`store()`**: Menerapkan proteksi dua lapis terhadap duplikasi:
  - *Lapis 1 (Aplikasi):* Pemeriksaan keberadaan pasangan peserta-kursus sebelum proses simpan.
  - *Lapis 2 (Database):* Penanganan *exception* composite unique database.
  - Menetapkan status awal pendaftaran secara otomatis sebagai `'registered'`.
- **`index()`**: Mengambil seluruh pendaftaran menggunakan *Eager Loading* `Enrollment::with(['participant', 'course'])->latest()->get()` untuk mencegah isu N+1 query problem. Kolom tanggal mulai dibaca langsung dari relasi `course.start_date`.

---

## 8. Rincian Rute Aplikasi Lengkap

| Metode | URI | Nama Rute | Controller Action | Keterangan |
|:---|:---|:---|:---|:---|
| `GET` | `/` | `home` | `HomeController@index` | Menampilkan beranda & katalog kursus (Pertemuan 7) |
| `GET` | `/courses` | `courses.index` | `CourseController@index` | Menampilkan tabel daftar kursus (Pertemuan 10 & 11) |
| `GET` | `/courses/create` | `courses.create` | `CourseController@create` | Menampilkan form tambah kursus baru (Pertemuan 10 & 11) |
| `POST` | `/courses` | `courses.store` | `CourseController@store` | Menyimpan kursus baru via `StoreCourseRequest` (Pertemuan 10 & 11) |
| `GET` | `/courses/{course}` | `courses.show` | `CourseController@show` | Menampilkan rincian detail data kursus (Pertemuan 11) |
| `GET` | `/courses/{course}/edit` | `courses.edit` | `CourseController@edit` | Menampilkan formulir edit kursus (Pertemuan 11) |
| `PUT/PATCH` | `/courses/{course}` | `courses.update` | `CourseController@update` | Memperbarui data via `UpdateCourseRequest` (Pertemuan 11) |
| `DELETE` | `/courses/{course}` | `courses.destroy` | `CourseController@destroy` | Menghapus kursus dengan cascade konfirmasi (Pertemuan 11) |
| `GET` | `/courses/{course}/availability` | `courses.availability` | `CourseController@availability` | Endpoint JSON ketersediaan kuota kursi (Pertemuan 9, 10 & 11) |
| `GET` | `/enrollments` | `enrollments.index` | `EnrollmentController@index` | Laporan data enrollment relasional (Pertemuan 12) |
| `GET` | `/enrollments/create` | `enrollments.create` | `EnrollmentController@create` | Form pendaftaran enrollment dua dropdown (Pertemuan 12) |
| `POST` | `/enrollments` | `enrollments.store` | `EnrollmentController@store` | Menyimpan enrollment baru status registered (Pertemuan 12) |
| `GET` | `/pendaftaran` | `pendaftaran` | `EnrollmentController@pendaftaran` | Form pendaftaran interaktif publik dengan Ajax (Pertemuan 9) |
| `POST` | `/pendaftaran/preview` | `enrollments.preview` | `EnrollmentController@preview` | Memvalidasi formulir pendaftaran publik (Pertemuan 9) |

---

## 9. Ringkasan Pengujian Pertemuan 12

Seluruh skenario pengujian fungsional relasional dan *cascade delete* teruji **PASS (100%)**:

1. **Buka `/enrollments/create`** &rarr; **PASS** (Kedua dropdown menampilkan data database secara dinamis).
2. **Participant kosong ditolak** &rarr; **PASS** (Validasi `required` memunculkan pesan error *"Silakan pilih peserta."*).
3. **Course kosong ditolak** &rarr; **PASS** (Validasi `required` memunculkan pesan error *"Silakan pilih program kursus."*).
4. **Alya mendaftar Laravel Dasar** &rarr; **PASS** (Berhasil tersimpan ke MySQL dengan status `'registered'`).
5. **Alya mendaftar Laravel Dasar sekali lagi** &rarr; **PASS** (Ditolak oleh sistem sebagai duplikasi pendaftaran).
6. **Alya mendaftar PHP Dasar** &rarr; **PASS** (Berhasil diterima karena kursus berbeda).
7. **Bima mendaftar Laravel Dasar** &rarr; **PASS** (Berhasil diterima karena peserta berbeda).
8. **Buka `/enrollments`** &rarr; **PASS** (Laporan menampilkan nama peserta, email, nama kursus, tanggal mulai dari relasi kursus, dan status).
9. **Cascade Delete Uji A** &rarr; **PASS** (Ketika Course dummy dihapus, Enrollment terkait ikut terhapus otomatis, Participant tetap ada).
10. **Cascade Delete Uji B** &rarr; **PASS** (Ketika Participant dummy dihapus, Enrollment terkait ikut terhapus otomatis, Course tetap ada).

Dokumentasi lengkap hasil pengujian dan ERD tersedia pada:
- [evidence/week-12/test-matrix.md](file:///D:/laragon/www/kursusku-laravel/evidence/week-12/test-matrix.md)
- [evidence/week-12/erd.md](file:///D:/laragon/www/kursusku-laravel/evidence/week-12/erd.md)
- [evidence/week-12/ai-usage-log.md](file:///D:/laragon/www/kursusku-laravel/evidence/week-12/ai-usage-log.md)
