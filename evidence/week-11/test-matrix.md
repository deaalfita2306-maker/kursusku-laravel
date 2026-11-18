# Matriks Pengujian Pertemuan 11 — KursusKu Laravel
**Topik:** Laravel CRUD Lanjutan: Show, Validation, Edit, Update & Delete  
**Database:** MySQL (`kursus_dea`)  
**Tabel:** `courses`  
**Model:** `App\Models\Course` (Eloquent ORM)  
**Tanggal Pengujian:** 18 November 2026  
**Lingkungan:** PHP 8.3 (CLI & Built-in Artisan Server), MySQL 8.0, Laravel 11/12, PHPUnit 11, Vite 8  

---

## Ringkasan Hasil Pengujian

| Total Skenario Diuji | Berhasil (PASS) | Gagal (FAIL) | Tingkat Keberhasilan |
|:---:|:---:|:---:|:---:|
| **15 Skenario** | **15** | **0** | **100%** |

---

## Rincian Matriks Pengujian 15 Skenario

### 1. Duplicate Code saat Create
- **ID Pengujian:** TC-11-01
- **Skenario:** Mengirim data kursus baru dengan kode yang sudah ada di database (`WEB-101`).
- **Langkah:**
  1. Akses form `/courses/create`.
  2. Masukkan kode `WEB-101` dan lengkapi field lainnya dengan data valid.
  3. Submit form.
- **Input Data:**
  - `code`: `WEB-101` (duplikat)
  - `name`: `Web Desain Baru`
  - `fee`: `300000`
  - `quota`: `20`
  - `start_date`: `2026-12-01`
  - `status`: `open`
- **Expected Result:** HTTP 302 redirect kembali ke form, field `code` mendapatkan class `.is-invalid` dan menampilkan pesan error *"Kode kursus sudah terdaftar, silakan gunakan kode lain."*. Data tidak tersimpan di database.
- **Actual Result:** HTTP 302 redirect ke `/courses/create`, pesan *"Kode kursus sudah terdaftar, silakan gunakan kode lain."* tampil tepat di bawah input `code`. Data tidak bertambah.
- **Status:** **PASS**

---

### 2. Nama Kosong saat Create
- **ID Pengujian:** TC-11-02
- **Skenario:** Mengirim data kursus baru dengan mengosongkan nama kursus.
- **Langkah:**
  1. Akses form `/courses/create`.
  2. Isi kode `TEST-002`, kosongkan nama (`name = ''`), isi field lain.
  3. Submit form.
- **Input Data:**
  - `code`: `TEST-002`
  - `name`: `""`
  - `fee`: `300000`
  - `quota`: `20`
  - `start_date`: `2026-12-01`
  - `status`: `open`
- **Expected Result:** HTTP 302 redirect, validasi gagal pada field `name` dengan pesan *"Nama kursus wajib diisi."*.
- **Actual Result:** HTTP 302 redirect kembali ke form, pesan *"Nama kursus wajib diisi."* muncul pada input nama.
- **Status:** **PASS**

---

### 3. Nama Terlalu Pendek ('AI') saat Create
- **ID Pengujian:** TC-11-03
- **Skenario:** Mengisi nama kursus hanya dengan 2 karakter (`AI`), melanggar aturan `min:3`.
- **Langkah:**
  1. Akses form `/courses/create`.
  2. Isi kode `TEST-003`, isi nama `AI` (2 karakter).
  3. Submit form.
- **Input Data:**
  - `code`: `TEST-003`
  - `name`: `AI`
  - `fee`: `300000`
  - `quota`: `20`
  - `start_date`: `2026-12-01`
  - `status`: `open`
- **Expected Result:** HTTP 302 redirect, validasi `min:3` gagal dengan pesan *"Nama kursus minimal 3 karakter."*.
- **Actual Result:** HTTP 302 redirect, input `name` ditandai error dengan pesan *"Nama kursus minimal 3 karakter."*.
- **Status:** **PASS**

---

### 4. Fee Negatif ('-1000')
- **ID Pengujian:** TC-11-04
- **Skenario:** Mengisi biaya kursus bernilai negatif (`-1000`).
- **Langkah:**
  1. Akses form `/courses/create`.
  2. Isi field dengan `fee = -1000`.
  3. Submit form.
- **Input Data:**
  - `code`: `TEST-004`
  - `name`: `Test Biaya Negatif`
  - `fee`: `-1000`
  - `quota`: `20`
  - `start_date`: `2026-12-01`
  - `status`: `open`
- **Expected Result:** HTTP 302 redirect, validasi `min:0` menolak input dengan pesan *"Biaya kursus minimal 0 (tidak boleh bernilai negatif)."*.
- **Actual Result:** HTTP 302 redirect, pesan *"Biaya kursus minimal 0 (tidak boleh bernilai negatif)."* tampil di bawah input `fee`.
- **Status:** **PASS**

---

### 5. Fee Bukan Integer ('abc')
- **ID Pengujian:** TC-11-05
- **Skenario:** Mengisi biaya kursus dengan karakter non-numerik (`abc`).
- **Langkah:**
  1. Kirim payload POST ke `/courses` dengan `fee = 'abc'`.
  2. Amati respon validasi.
- **Input Data:**
  - `code`: `TEST-005`
  - `name`: `Test Biaya String`
  - `fee`: `abc`
  - `quota`: `20`
  - `start_date`: `2026-12-01`
  - `status`: `open`
- **Expected Result:** HTTP 302 redirect, validasi `integer` gagal dengan pesan *"Biaya kursus harus berupa angka bulat."*.
- **Actual Result:** HTTP 302 redirect, pesan *"Biaya kursus harus berupa angka bulat."* tampil di bawah input biaya.
- **Status:** **PASS**

---

### 6. Quota 0
- **ID Pengujian:** TC-11-06
- **Skenario:** Mengisi kuota bernilai `0` (kurang dari batas bawah rentang 1..500).
- **Langkah:**
  1. Akses form `/courses/create`.
  2. Isi `quota = 0`.
  3. Submit form.
- **Input Data:**
  - `code`: `TEST-006`
  - `name`: `Test Kuota 0`
  - `fee`: `250000`
  - `quota`: `0`
  - `start_date`: `2026-12-01`
  - `status`: `open`
- **Expected Result:** HTTP 302 redirect, aturan `between:1,500` menolak nilai 0 dengan pesan *"Kuota peserta harus berada dalam rentang 1 sampai 500."*.
- **Actual Result:** HTTP 302 redirect, pesan *"Kuota peserta harus berada dalam rentang 1 sampai 500."* muncul pada input kuota.
- **Status:** **PASS**

---

### 7. Quota 501
- **ID Pengujian:** TC-11-07
- **Skenario:** Mengisi kuota bernilai `501` (melebihi batas maksimal 500).
- **Langkah:**
  1. Akses form `/courses/create`.
  2. Isi `quota = 501`.
  3. Submit form.
- **Input Data:**
  - `code`: `TEST-007`
  - `name`: `Test Kuota 501`
  - `fee`: `250000`
  - `quota`: `501`
  - `start_date`: `2026-12-01`
  - `status`: `open`
- **Expected Result:** HTTP 302 redirect, aturan `between:1,500` menolak nilai 501 dengan pesan *"Kuota peserta harus berada dalam rentang 1 sampai 500."*.
- **Actual Result:** HTTP 302 redirect, pesan *"Kuota peserta harus berada dalam rentang 1 sampai 500."* tampil di bawah input kuota.
- **Status:** **PASS**

---

### 8. Quota Minimum 1 (Diterima)
- **ID Pengujian:** TC-11-08
- **Skenario:** Memasukkan batas bawah kuota yang sah (`quota = 1`).
- **Langkah:**
  1. Akses form `/courses/create`.
  2. Masukkan data valid dengan `quota = 1`.
  3. Submit form dan periksa database.
- **Input Data:**
  - `code`: `MIN-001`
  - `name`: `Kursus Privat 1 On 1`
  - `fee`: `1000000`
  - `quota`: `1`
  - `start_date`: `2026-12-15`
  - `status`: `open`
- **Expected Result:** HTTP 302 redirect ke `/courses`, flash success muncul, record berhasil disimpan ke MySQL dengan `quota = 1`.
- **Actual Result:** HTTP 302 redirect ke `/courses`, alert sukses muncul, data tersimpan di MySQL dengan `quota = 1`.
- **Status:** **PASS**

---

### 9. Quota Maksimum 500 (Diterima)
- **ID Pengujian:** TC-11-09
- **Skenario:** Memasukkan batas atas kuota yang sah (`quota = 500`).
- **Langkah:**
  1. Akses form `/courses/create`.
  2. Masukkan data valid dengan `quota = 500`.
  3. Submit form dan periksa database.
- **Input Data:**
  - `code`: `MAX-500`
  - `name`: `Webinar Akbar Teknologi`
  - `fee`: `50000`
  - `quota`: `500`
  - `start_date`: `2026-12-20`
  - `status`: `open`
- **Expected Result:** HTTP 302 redirect ke `/courses`, flash success muncul, record tersimpan di MySQL dengan `quota = 500`.
- **Actual Result:** HTTP 302 redirect, alert sukses muncul, data tersimpan di MySQL dengan `quota = 500`.
- **Status:** **PASS**

---

### 10. Tanggal Invalid
- **ID Pengujian:** TC-11-10
- **Skenario:** Mengirim format string tanggal yang tidak valid (`bukan-tanggal-valid`).
- **Langkah:**
  1. Kirim payload POST ke `/courses` dengan `start_date = 'bukan-tanggal-valid'`.
  2. Amati penolakan validasi `date`.
- **Input Data:**
  - `code`: `TEST-010`
  - `name`: `Test Tanggal Invalid`
  - `fee`: `200000`
  - `quota`: `20`
  - `start_date`: `bukan-tanggal-valid`
  - `status`: `open`
- **Expected Result:** HTTP 302 redirect, validasi `date` gagal dengan pesan *"Format tanggal mulai tidak valid."*.
- **Actual Result:** HTTP 302 redirect, pesan *"Format tanggal mulai tidak valid."* muncul di bawah field `start_date`.
- **Status:** **PASS**

---

### 11. Status Invalid ('active')
- **ID Pengujian:** TC-11-11
- **Skenario:** Mengirim nilai status di luar daftar enum (`draft`, `open`, `closed`).
- **Langkah:**
  1. Kirim payload POST ke `/courses` dengan `status = 'active'`.
  2. Amati respon validasi `in`.
- **Input Data:**
  - `code`: `TEST-011`
  - `name`: `Test Status Invalid`
  - `fee`: `200000`
  - `quota`: `20`
  - `start_date`: `2026-12-01`
  - `status`: `active`
- **Expected Result:** HTTP 302 redirect, validasi gagal dengan pesan *"Status kursus harus berupa salah satu dari: draft, open, closed."*.
- **Actual Result:** HTTP 302 redirect, pesan *"Status kursus harus berupa salah satu dari: draft, open, closed."* muncul.
- **Status:** **PASS**

---

### 12. Update dengan Code Sendiri Tanpa Mengubahnya (Berhasil)
- **ID Pengujian:** TC-11-12
- **Skenario:** Mengedit kursus `WEB-101` dan hanya mengubah nama/deskripsi/biaya tanpa mengubah nilai kode. Validasi unique harus mengabaikan record milik sendiri (`Rule::unique('courses', 'code')->ignore($course)`).
- **Langkah:**
  1. Buka form `/courses/{id}/edit` untuk kursus `WEB-101`.
  2. Pertahankan `code = 'WEB-101'`.
  3. Ubah nama menjadi `Web Dasar Modern & Responsif`.
  4. Submit form (HTTP PUT).
- **Input Data:**
  - `_method`: `PUT`
  - `code`: `WEB-101` (milik record sendiri)
  - `name`: `Web Dasar Modern & Responsif`
  - `fee`: `320000`
  - `quota`: `28`
  - `start_date`: `2026-11-20`
  - `status`: `open`
- **Expected Result:** Validasi berhasil (tidak dianggap duplikat), HTTP 302 redirect ke `/courses/{id}`, flash success *"Data kursus berhasil diperbarui!"* muncul, perubahan nama tersimpan di MySQL.
- **Actual Result:** HTTP 302 redirect ke `/courses/{id}`, flash success muncul, record di MySQL berhasil terupdate dengan nama baru tanpa error unique code.
- **Status:** **PASS**

---

### 13. Update dengan Code Milik Course Lain (Ditolak)
- **ID Pengujian:** TC-11-13
- **Skenario:** Mengedit kursus `PHP-101` dan mencoba mengganti kodenya menjadi `WEB-101` (milik kursus lain).
- **Langkah:**
  1. Buka form edit untuk kursus `PHP-101`.
  2. Ubah field kode menjadi `WEB-101`.
  3. Submit form (HTTP PUT).
- **Input Data:**
  - `_method`: `PUT`
  - `code`: `WEB-101` (kode milik kursus lain)
  - `name`: `PHP Fundamental`
  - `fee`: `400000`
  - `quota`: `25`
  - `start_date`: `2026-11-22`
  - `status`: `open`
- **Expected Result:** HTTP 302 redirect kembali ke form edit, validasi keunikan mendeteksi bahwa kode telah digunakan oleh kursus lain, pesan *"Kode kursus sudah terdaftar, silakan gunakan kode lain."* ditampilkan.
- **Actual Result:** HTTP 302 redirect ke form edit, pesan *"Kode kursus sudah terdaftar, silakan gunakan kode lain."* tampil jelas di bawah input `code`. Perubahan ditolak.
- **Status:** **PASS**

---

### 14. Update Normal (Perubahan Tersimpan dan Tampil di Detail)
- **ID Pengujian:** TC-11-14
- **Skenario:** Melakukan pembaruan menyeluruh pada data kursus dan memastikan halaman detail (`courses.show`) menampilkan data terbaru.
- **Langkah:**
  1. Buat kursus sementara `TMP-101`.
  2. Buka form edit `/courses/{id}/edit`.
  3. Ubah kode menjadi `TMP-201`, nama menjadi `Kursus Berhasil Diperbarui`, biaya `250000`, kuota `18`, status `open`.
  4. Submit form.
  5. Buka halaman detail `/courses/{id}`.
- **Input Data:**
  - `_method`: `PUT`
  - `code`: `TMP-201`
  - `name`: `Kursus Berhasil Diperbarui`
  - `fee`: `250000`
  - `quota`: `18`
  - `start_date`: `2026-12-05`
  - `status`: `open`
- **Expected Result:** HTTP 302 redirect ke `/courses/{id}`, flash success *"Data kursus berhasil diperbarui!"* tampil, halaman detail menampilkan kode `TMP-201`, nama baru, biaya Rp 250.000, kuota 18 Peserta, dan badge Open.
- **Actual Result:** HTTP 302 redirect ke show, seluruh data baru tampil sempurna pada kartu detail, database MySQL terupdate presisi.
- **Status:** **PASS**

---

### 15. Delete Kursus Dummy (Data Hilang dari Index dan Flash Tampil)
- **ID Pengujian:** TC-11-15
- **Skenario:** Menghapus kursus dummy khusus pengujian (`DEL-001`) menggunakan form HTTP DELETE dengan konfirmasi JavaScript, dan memastikan data hilang dari daftar kursus.
- **Langkah:**
  1. Siapkan kursus dummy `DEL-001`.
  2. Kirim request DELETE via form POST dengan `@method('DELETE')` ke `/courses/{id}`.
  3. Amati redirect ke `/courses`.
  4. Periksa flash message sukses.
  5. Periksa tabel index dan database MySQL.
- **Input Data:**
  - Route: `DELETE /courses/{id}`
  - Target: Kursus `DEL-001` (Kursus Dummy Hapus)
- **Expected Result:** HTTP 302 redirect ke `/courses`, flash success *"Kursus 'Kursus Dummy Hapus' berhasil dihapus dari database!"* muncul, record `DEL-001` terhapus permanen dari MySQL, baris kursus tidak lagi muncul di tabel.
- **Actual Result:** HTTP 302 redirect ke `/courses`, alert hijau sukses tampil, record hilang dari MySQL (`find(id) === null`), dan tidak muncul di tabel index.
- **Status:** **PASS**

---

## Verifikasi Tambahan Halaman Detail (Show) & 404 Handling

1. **Akses Kursus Terdaftar (`GET /courses/1`):**
   - HTTP Status: `200 OK`
   - View: `resources/views/courses/show.blade.php`
   - Konten: Menampilkan kode `WEB-101`, nama `Web Dasar`, biaya, kuota, tanggal mulai, deskripsi materi, tombol kembali, tombol edit, dan tombol hapus.
   - Status: **PASS**

2. **Akses Kursus Tidak Terdaftar (`GET /courses/99999`):**
   - HTTP Status: `404 Not Found` (Route Model Binding otomatis mengembalikan HTTP 404).
   - Status: **PASS**

---

## Verifikasi Automated Test Suite (PHPUnit)

Seluruh 15 skenario pengujian di atas diimplementasikan dan diotomasi pada test suite Laravel di [tests/Feature/CourseCrudTest.php](file:///D:/laragon/www/kursusku-laravel/tests/Feature/CourseCrudTest.php):

```text
Tests\Feature\CourseCrudTest
  ✔ course routes are registered and named
  ✔ course index renders successfully with empty state
  ✔ course create form renders all required inputs
  ✔ sc 01 duplicate code on create is rejected
  ✔ sc 02 empty name is rejected
  ✔ sc 03 name too short is rejected
  ✔ sc 04 negative fee is rejected
  ✔ sc 05 non integer fee is rejected
  ✔ sc 06 zero quota is rejected
  ✔ sc 07 quota 501 is rejected
  ✔ sc 08 minimum quota 1 is accepted
  ✔ sc 09 maximum quota 500 is accepted
  ✔ sc 10 invalid date is rejected
  ✔ sc 11 invalid status is rejected
  ✔ sc 12 update with own code succeeds
  ✔ sc 13 update with another course code is rejected
  ✔ sc 14 normal update persists and renders on show
  ✔ sc 15 delete dummy course removes data and shows flash
  ✔ show route renders successfully and returns 404 for unknown
  ✔ edit route renders with existing database values
  ✔ availability route reads from database eloquent

Tests: 35 passed, 35 total (241 assertions)
Duration: 1.76s
Status: PASS (100%)
```
