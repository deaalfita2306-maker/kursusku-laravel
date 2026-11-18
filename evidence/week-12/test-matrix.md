# Matriks Pengujian Pertemuan 12 — Relational Database KursusKu
**Topik:** Relational Database: Three Entities (Participants, Courses, Enrollments), Eloquent Relations, Composite Unique, Cascade Delete, dan Eager Loading  
**Database:** MySQL (`kursus_dea`)  
**Tabel Terlibat:** `courses`, `participants`, `enrollments`  
**Model Terlibat:** `App\Models\Course`, `App\Models\Participant`, `App\Models\Enrollment`  
**Tanggal Pengujian:** 19 November 2026  
**Lingkungan:** PHP 8.3 (CLI & Artisan Built-in Server), MySQL 8.4, Laravel 11/12, PHPUnit 11, Vite 8  

---

## Ringkasan Hasil Pengujian

| Komponen Pengujian | Total Skenario | Berhasil (PASS) | Gagal (FAIL) | Tingkat Keberhasilan |
|---|:---:|:---:|:---:|:---:|
| **Skenario Wajib Form & Laporan** | 8 | 8 | 0 | **100%** |
| **Pengujian Cascade Delete (Uji A & B)** | 2 | 2 | 0 | **100%** |
| **Automated Feature & Unit Testing** | 44 | 44 | 0 | **100%** |

---

## Rincian Matriks 8 Skenario Pengujian Wajib

### Skenario 1: Buka `/enrollments/create`, Kedua Dropdown Menampilkan Data Database
- **ID Pengujian:** TC-12-01
- **Deskripsi:** Membuka formulir pendaftaran enrollment dan memastikan dropdown peserta (`#participant_id`) serta dropdown kursus (`#course_id`) menampilkan data aktual dari MySQL.
- **Langkah:**
  1. Akses URL `GET /enrollments/create`.
  2. Periksa pilihan pada dropdown Peserta.
  3. Periksa pilihan pada dropdown Kursus.
- **Data yang Diuji:**
  - Peserta di MySQL: Alya Rahma (`alya@example.test`), Bima Putra (`bima@example.test`), Citra Nabila (`citra@example.test`).
  - Kursus berstatus `open`: `WEB-101`, `PHP-101`, `LRV-101`, `SQL-101`, `JS-101`.
  - Kursus berstatus `draft` / `closed`: tidak boleh dimuat ke opsi pilihan.
- **Hasil yang Diharapkan:** HTTP 200 OK, dropdown peserta memuat nama dan email peserta terdaftar, dropdown kursus memuat kode dan nama kursus dengan status `open`.
- **Hasil Aktual:** HTTP 200 OK. Dropdown peserta menampilkan opsi lengkap: *"Alya Rahma (alya@example.test)"*, *"Bima Putra (bima@example.test)"*, *"Citra Nabila (citra@example.test)"*. Dropdown kursus memuat daftar kursus open seperti *"LRV-101 — Laravel Dasar"* dan kursus status draft/closed tidak ditampilkan.
- **Status:** **PASS**

---

### Skenario 2: Participant Kosong Ditolak
- **ID Pengujian:** TC-12-02
- **Deskripsi:** Mengirim form pendaftaran enrollment tanpa memilih peserta (`participant_id = ''`).
- **Langkah:**
  1. Akses form `/enrollments/create`.
  2. Kosongkan pilihan peserta (`participant_id`).
  3. Pilih kursus yang valid (misal: Laravel Dasar).
  4. Submit form (POST `/enrollments`).
- **Input Data:**
  - `participant_id`: `""` (kosong)
  - `course_id`: `3` (`LRV-101`)
- **Hasil yang Diharapkan:** HTTP 302 redirect kembali ke form, field `participant_id` mendapatkan class `.is-invalid` dan menampilkan pesan validasi *"Silakan pilih peserta."*. Data tidak tersimpan ke database.
- **Hasil Aktual:** HTTP 302 redirect ke `/enrollments/create`, pesan error *"Silakan pilih peserta."* tampil tepat di bawah dropdown peserta. Tidak ada penambahan record di tabel `enrollments`.
- **Status:** **PASS**

---

### Skenario 3: Course Kosong Ditolak
- **ID Pengujian:** TC-12-03
- **Deskripsi:** Mengirim form pendaftaran enrollment tanpa memilih kursus (`course_id = ''`).
- **Langkah:**
  1. Akses form `/enrollments/create`.
  2. Pilih peserta Alya Rahma.
  3. Kosongkan pilihan kursus (`course_id = ''`).
  4. Submit form (POST `/enrollments`).
- **Input Data:**
  - `participant_id`: `1` (Alya Rahma)
  - `course_id`: `""` (kosong)
- **Hasil yang Diharapkan:** HTTP 302 redirect kembali ke form, field `course_id` mendapatkan class `.is-invalid` dan menampilkan pesan validasi *"Silakan pilih program kursus."*. Data tidak tersimpan.
- **Hasil Aktual:** HTTP 302 redirect ke form, pesan error *"Silakan pilih program kursus."* tampil di bawah dropdown kursus. Nilai input peserta dipertahankan via `old('participant_id')`.
- **Status:** **PASS**

---

### Skenario 4: Alya Mendaftar Laravel Dasar (Berhasil dengan Status Registered)
- **ID Pengujian:** TC-12-04
- **Deskripsi:** Peserta Alya Rahma mendaftar kursus Laravel Dasar untuk pertama kali.
- **Langkah:**
  1. Buka form `/enrollments/create`.
  2. Pilih Peserta: **Alya Rahma**.
  3. Pilih Kursus: **LRV-101 — Laravel Dasar**.
  4. Klik tombol **"Simpan Enrollment"**.
  5. Periksa redirect, flash message, dan baris data di MySQL.
- **Input Data:**
  - `participant_id`: ID Alya Rahma (`1`)
  - `course_id`: ID Laravel Dasar (`3`)
- **Hasil yang Diharapkan:** HTTP 302 redirect ke `route('enrollments.index')`, alert sukses *"Pendaftaran kursus (enrollment) berhasil disimpan!"* muncul, record tersimpan di tabel `enrollments` dengan `participant_id = 1`, `course_id = 3`, dan `status = 'registered'`.
- **Hasil Aktual:** HTTP 302 redirect ke `/enrollments`, flash message sukses tampil, record tersimpan di MySQL dengan `status = registered`.
- **Status:** **PASS**

---

### Skenario 5: Alya Mendaftar Laravel Dasar Sekali Lagi (Ditolak Sebagai Duplikasi)
- **ID Pengujian:** TC-12-05
- **Deskripsi:** Menguji perlindungan ganda (validasi aplikasi + composite unique database) dengan mencoba mendaftarkan kembali Alya Rahma pada kursus Laravel Dasar.
- **Langkah:**
  1. Buka form `/enrollments/create`.
  2. Pilih Peserta: **Alya Rahma**.
  3. Pilih Kursus: **LRV-101 — Laravel Dasar** (sudah pernah didaftar pada Skenario 4).
  4. Submit form.
- **Input Data:**
  - `participant_id`: `1` (Alya Rahma)
  - `course_id`: `3` (Laravel Dasar)
- **Hasil yang Diharapkan:** HTTP 302 redirect kembali ke form, sistem menolak duplikasi dengan pesan error *"Peserta sudah terdaftar pada kursus ini. Silakan pilih kursus lain."*. Database tidak bertambah baris baru.
- **Hasil Aktual:** HTTP 302 redirect ke `/enrollments/create`, pesan error *"Peserta sudah terdaftar pada kursus ini. Silakan pilih kursus lain."* tampil jelas di bawah pilihan kursus. Jumlah enrollment untuk pasangan Alya-Laravel tetap 1.
- **Status:** **PASS**

---

### Skenario 6: Alya Mendaftar PHP Dasar (Boleh Berhasil)
- **ID Pengujian:** TC-12-06
- **Deskripsi:** Memastikan peserta yang sama (Alya Rahma) diperbolehkan mendaftar ke kursus yang berbeda (PHP Fundamental). Menegaskan bahwa `participant_id` tidak di-unique-kan secara individual.
- **Langkah:**
  1. Buka form `/enrollments/create`.
  2. Pilih Peserta: **Alya Rahma**.
  3. Pilih Kursus: **PHP-101 — PHP Fundamental**.
  4. Submit form.
- **Input Data:**
  - `participant_id`: `1` (Alya Rahma)
  - `course_id`: `2` (PHP Fundamental)
- **Hasil yang Diharapkan:** Pendaftaran berhasil diterima, redirect ke `/enrollments` dengan flash success, record baru tersimpan di tabel `enrollments`.
- **Hasil Aktual:** HTTP 302 redirect ke `/enrollments`, flash message sukses muncul, record kedua untuk Alya berhasil tersimpan di MySQL.
- **Status:** **PASS**

---

### Skenario 7: Bima Mendaftar Laravel Dasar (Boleh Berhasil)
- **ID Pengujian:** TC-12-07
- **Deskripsi:** Memastikan peserta yang berbeda (Bima Putra) diperbolehkan mendaftar ke kursus yang sama (Laravel Dasar). Menegaskan bahwa `course_id` tidak di-unique-kan secara individual.
- **Langkah:**
  1. Buka form `/enrollments/create`.
  2. Pilih Peserta: **Bima Putra**.
  3. Pilih Kursus: **LRV-101 — Laravel Dasar**.
  4. Submit form.
- **Input Data:**
  - `participant_id`: `2` (Bima Putra)
  - `course_id`: `3` (Laravel Dasar)
- **Hasil yang Diharapkan:** Pendaftaran berhasil diterima, redirect ke `/enrollments` dengan flash success, record baru tersimpan di tabel `enrollments`.
- **Hasil Aktual:** HTTP 302 redirect ke `/enrollments`, flash message sukses muncul, record pendaftaran Bima ke Laravel Dasar tersimpan di MySQL.
- **Status:** **PASS**

---

### Skenario 8: Buka `/enrollments`, Laporan Menampilkan Data Relasional Lengkap
- **ID Pengujian:** TC-12-08
- **Deskripsi:** Memastikan halaman laporan `/enrollments` menampilkan data relasi hasil eager loading `with(['participant', 'course'])`.
- **Langkah:**
  1. Akses URL `GET /enrollments`.
  2. Periksa kolom-kolom tabel laporan:
     - Nama Participant (dari `participants.name`)
     - Email Participant (dari `participants.email`)
     - Nama Course (dari `courses.name`)
     - Tanggal Mulai (dari `courses.start_date`, bukan disalin ke enrollments)
     - Status Enrollment (dari `enrollments.status`)
- **Hasil yang Diharapkan:** Seluruh kolom menampilkan data relasional dengan tepat, tanggal mulai terformat rapi (`d M Y`), badge status *"Registered"* tampil dengan warna tema ungu-putih.
- **Hasil Aktual:** HTTP 200 OK. Tabel laporan menampilkan baris:
  1. Alya Rahma | `alya@example.test` | Laravel Dasar | 25 Nov 2026 | Registered
  2. Alya Rahma | `alya@example.test` | PHP Fundamental | 22 Nov 2026 | Registered
  3. Bima Putra | `bima@example.test` | Laravel Dasar | 25 Nov 2026 | Registered
- **Status:** **PASS**

---

## Rincian Pengujian Cascade Delete (Data Dummy Khusus)

### Uji Cascade Delete A: Course Dihapus
- **Tujuan:** Memastikan bahwa ketika sebuah Course dihapus, seluruh data Enrollment yang merujuk padanya ikut terhapus otomatis oleh database MySQL (`ON DELETE CASCADE`), sementara entitas Participant tetap utuh.
- **Langkah & Data Dummy:**
  1. Membuat Course dummy: `TEST-CASCADE-COURSE` (ID 14, Status: `open`).
  2. Membuat Participant dummy: `dummy.a@example.test` (ID 4).
  3. Membuat Enrollment yang menghubungkan keduanya (ID 4).
  4. Menghapus Course dummy via `$course->delete()`.
- **Hasil Pemeriksaan MySQL:**
  - `App\Models\Enrollment::find(4)` &rarr; `null` (**IKUT TERHAPUS / CASCADE BERFUNGSI**).
  - `App\Models\Participant::find(4)` &rarr; Tetap ada di database (**PARTICIPANT AMAN**).
- **Status:** **PASS**

---

### Uji Cascade Delete B: Participant Dihapus
- **Tujuan:** Memastikan bahwa ketika seorang Participant dihapus, seluruh data Enrollment yang merujuk padanya ikut terhapus otomatis (`ON DELETE CASCADE`), sementara entitas Course tetap utuh.
- **Langkah & Data Dummy:**
  1. Membuat Course dummy: `TEST-CASCADE-PART` (ID 15, Status: `open`).
  2. Membuat Participant dummy: `dummy.b@example.test` (ID 5).
  3. Membuat Enrollment yang menghubungkan keduanya (ID 5).
  4. Menghapus Participant dummy via `$participant->delete()`.
- **Hasil Pemeriksaan MySQL:**
  - `App\Models\Enrollment::find(5)` &rarr; `null` (**IKUT TERHAPUS / CASCADE BERFUNGSI**).
  - `App\Models\Course::find(15)` &rarr; Tetap ada di database (**COURSE AMAN**).
- **Status:** **PASS**

---

## Struktur Foreign Key Database MySQL (`kursus_dea`)

Berdasarkan pengecekan skema database pada tabel `enrollments`:
```sql
CONSTRAINT `enrollments_participant_id_foreign` 
    FOREIGN KEY (`participant_id`) REFERENCES `participants` (`id`) ON DELETE CASCADE;

CONSTRAINT `enrollments_course_id_foreign` 
    FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

UNIQUE KEY `enrollments_participant_id_course_id_unique` (`participant_id`, `course_id`);
```

Integritas referensial dan aturan cascading terpasang di level *storage engine* InnoDB MySQL, menjamin konsistensi data secara absolut.
