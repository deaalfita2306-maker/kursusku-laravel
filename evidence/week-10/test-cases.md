# Hasil Pengujian Manual (8 Test Case) — Pertemuan 10 KursusKu Laravel
**Topik:** Laravel Database & CRUD I — Model, Migration, Tampil, Insert  
**Database:** MySQL (`kursus_dea`)  
**Tabel:** `courses`  
**Model:** `App\Models\Course` (Eloquent ORM)  
**Tanggal Pengujian:** 18 November 2026  
**Lingkungan:** PHP 8.3 (CLI & Artisan Serve), MySQL 8.0, Laravel 11/12, PHPUnit 11  

---

## Ringkasan Hasil Pengujian

| Total Test Case | Berhasil (PASS) | Gagal (FAIL) | Tingkat Keberhasilan |
|:---:|:---:|:---:|:---:|
| **8** | **8** | **0** | **100%** |

---

## Rincian 8 Test Case Manual

### Test Case 1: Simpan Data Valid
- **Nomor Test Case:** TC-10-01
- **Skenario Pengujian:** Memastikan form tambah kursus baru dapat menyimpan data valid ke dalam database MySQL dan mengarahkan kembali ke daftar kursus dengan pesan sukses.
- **Langkah Pengujian:**
  1. Akses halaman form tambah kursus pada URL `/courses/create`.
  2. Isi semua field formulir dengan data yang valid dan memenuhi semua aturan validasi.
  3. Klik tombol submit **"Simpan Kursus"**.
  4. Amati kode status HTTP dan header redirect.
  5. Periksa kemunculan alert flash message sukses pada halaman tujuan.
  6. Periksa keberadaan baris data baru secara langsung di database MySQL `kursus_dea` pada tabel `courses`.
  7. Periksa apakah data baru langsung tampil pada tabel daftar kursus di `/courses`.
- **Input Data:**
  - Kode Kursus (`code`): `WEB-101`
  - Nama Kursus (`name`): `Web Dasar`
  - Deskripsi (`description`): `Materi HTML5, CSS3 modern, layouting Flexbox dan Grid, serta responsive design.`
  - Biaya (`fee`): `300000`
  - Kuota (`quota`): `25`
  - Tanggal Mulai (`start_date`): `2026-11-20`
  - Status Kursus (`status`): `open`
- **Output yang Diharapkan:**
  - HTTP Status: `302 Found` (Redirect ke `/courses`).
  - Session Flash: Pesan sukses *"Data kursus berhasil disimpan ke database!"* muncul pada banner alert hijau di `/courses`.
  - Database MySQL: Record baru tersimpan dengan id otomatis, `code = 'WEB-101'`, `name = 'Web Dasar'`, `fee = 300000`, `quota = 25`, `start_date = '2026-11-20'`, dan `status = 'open'`.
  - Tampilan Tabel: Baris data `WEB-101` muncul di tabel dengan badge status Open, kuota 25 Peserta, dan biaya Rp 300.000.
- **Output Aktual:**
  - HTTP Status: `302 Found` (Redirect ke `http://127.0.0.1:8000/courses`).
  - Session Flash: Alert hijau tampil berisi pesan *"Data kursus berhasil disimpan ke database!"*.
  - Database MySQL: Record tersimpan dengan `id = 1`, `code = WEB-101`, `name = Web Dasar`, `fee = 300000`, `quota = 25`, `start_date = 2026-11-20`, `status = open`.
  - Tampilan Tabel: Data muncul rapi pada baris tabel pertama dengan badge status `Open` dan format mata uang `Rp 300.000`.
- **Status:** **PASS**

---

### Test Case 2: Validasi Kode Kosong
- **Nomor Test Case:** TC-10-02
- **Skenario Pengujian:** Memastikan sistem menolak penyimpanan data jika input kode kursus dikosongkan.
- **Langkah Pengujian:**
  1. Buka form `/courses/create`.
  2. Kosongkan field **Kode Kursus** (`code = ''`).
  3. Isi field lainnya dengan data valid (Nama: PHP Fundamental, Biaya: 400000, Kuota: 25, Mulai: 2026-11-22, Status: open).
  4. Klik tombol **"Simpan Kursus"**.
  5. Amati penanganan validasi oleh Laravel.
- **Input Data:**
  - `code`: `""` (string kosong)
  - `name`: `PHP Fundamental`
  - `fee`: `400000`
  - `quota`: `25`
  - `start_date`: `2026-11-22`
  - `status`: `open`
- **Output yang Diharapkan:**
  - HTTP Status: `302 Found` (Redirect kembali ke `/courses/create`).
  - Session Errors: Error validasi field `code` bernilai *"Kode kursus wajib diisi."*.
  - UI Feedback: Input kode kursus mendapatkan class `.is-invalid` dan menampilkan pesan feedback error di bawah field.
  - Database: Tidak ada penambahan record baru di tabel `courses`.
- **Output Aktual:**
  - HTTP Status: `302 Found` (Redirect kembali ke form).
  - Pesan Error: *"Kode kursus wajib diisi."* tampil di bawah input `code`.
  - Database: Jumlah record tetap, tidak ada data kosong yang tersimpan.
- **Status:** **PASS**

---

### Test Case 3: Validasi Kode Duplikat
- **Nomor Test Case:** TC-10-03
- **Skenario Pengujian:** Memastikan aturan keunikan (`unique:courses,code`) berjalan dan menolak kode kursus yang sudah terdaftar di database MySQL.
- **Langkah Pengujian:**
  1. Buka form `/courses/create`.
  2. Masukkan kode kursus yang sudah ada di database, yaitu `WEB-101`.
  3. Isi field nama dan data lainnya dengan nilai yang berbeda.
  4. Klik tombol **"Simpan Kursus"**.
  5. Amati respon validasi keunikan.
- **Input Data:**
  - `code`: `WEB-101` (sudah digunakan oleh kursus Web Dasar)
  - `name`: `Web Dasar Lanjutan`
  - `fee`: `350000`
  - `quota`: `20`
  - `start_date`: `2026-11-25`
  - `status`: `open`
- **Output yang Diharapkan:**
  - HTTP Status: `302 Found` (Redirect kembali ke form).
  - Session Errors: Error validasi keunikan muncul dengan pesan kustom: *"Kode kursus sudah terdaftar, silakan gunakan kode lain."*.
  - Database: Data duplikat ditolak, tidak ada pelanggaran integritas constraint database MySQL.
- **Output Aktual:**
  - HTTP Status: `302 Found`.
  - Pesan Error: *"Kode kursus sudah terdaftar, silakan gunakan kode lain."* tampil jelas di bawah field `code`.
  - Database: Tabel `courses` tetap mempertahankan integritas data unik.
- **Status:** **PASS**

---

### Test Case 4: Validasi Nama Kosong
- **Nomor Test Case:** TC-10-04
- **Skenario Pengujian:** Memastikan sistem menolak penyimpanan data jika input nama kursus dikosongkan.
- **Langkah Pengujian:**
  1. Buka form `/courses/create`.
  2. Isi kode dengan nilai baru `PHP-101`.
  3. Kosongkan field **Nama Kursus** (`name = ''`).
  4. Isi field lainnya dengan nilai valid.
  5. Klik tombol **"Simpan Kursus"**.
- **Input Data:**
  - `code`: `PHP-101`
  - `name`: `""` (string kosong)
  - `fee`: `400000`
  - `quota`: `25`
  - `start_date`: `2026-11-22`
  - `status`: `open`
- **Output yang Diharapkan:**
  - HTTP Status: `302 Found` (Redirect kembali).
  - Session Errors: Field `name` gagal validasi dengan pesan: *"Nama kursus wajib diisi."*.
  - UI Feedback: Input `name` ditandai merah dengan feedback invalid.
- **Output Aktual:**
  - HTTP Status: `302 Found`.
  - Pesan Error: *"Nama kursus wajib diisi."* muncul tepat di bawah input nama kursus.
  - Nilai field lain yang sudah diisi dipertahankan via helper `old()`.
- **Status:** **PASS**

---

### Test Case 5: Validasi Biaya Negatif
- **Nomor Test Case:** TC-10-05
- **Skenario Pengujian:** Memastikan sistem menolak nilai biaya (`fee`) yang bernilai negatif (`< 0`).
- **Langkah Pengujian:**
  1. Buka form `/courses/create`.
  2. Masukkan kode `PHP-101` dan nama `PHP Fundamental`.
  3. Masukkan biaya bernilai negatif `-50000`.
  4. Lengkapi kuota, tanggal, dan status.
  5. Klik tombol **"Simpan Kursus"**.
- **Input Data:**
  - `code`: `PHP-101`
  - `name`: `PHP Fundamental`
  - `fee`: `-50000` (bilangan negatif)
  - `quota`: `25`
  - `start_date`: `2026-11-22`
  - `status`: `open`
- **Output yang Diharapkan:**
  - HTTP Status: `302 Found` (Redirect kembali).
  - Session Errors: Aturan `min:0` gagal dengan pesan: *"Biaya kursus minimal 0 (tidak boleh bernilai negatif)."*.
  - Database: Data tidak disimpan.
- **Output Aktual:**
  - HTTP Status: `302 Found`.
  - Pesan Error: *"Biaya kursus minimal 0 (tidak boleh bernilai negatif)."* tampil pada input biaya.
- **Status:** **PASS**

---

### Test Case 6: Validasi Kuota 0
- **Nomor Test Case:** TC-10-06
- **Skenario Pengujian:** Memastikan sistem menolak nilai kuota peserta (`quota`) yang bernilai 0 atau kurang dari 1.
- **Langkah Pengujian:**
  1. Buka form `/courses/create`.
  2. Masukkan data kursus valid dengan nilai kuota `0`.
  3. Klik tombol **"Simpan Kursus"**.
- **Input Data:**
  - `code`: `PHP-101`
  - `name`: `PHP Fundamental`
  - `fee`: `400000`
  - `quota`: `0` (kurang dari batas minimum 1)
  - `start_date`: `2026-11-22`
  - `status`: `open`
- **Output yang Diharapkan:**
  - HTTP Status: `302 Found` (Redirect kembali).
  - Session Errors: Aturan `min:1` gagal dengan pesan: *"Kuota minimal 1 peserta."*.
  - Database: Data tidak disimpan.
- **Output Aktual:**
  - HTTP Status: `302 Found`.
  - Pesan Error: *"Kuota minimal 1 peserta."* tampil pada input kuota.
- **Status:** **PASS**

---

### Test Case 7: Validasi Status Tidak Valid
- **Nomor Test Case:** TC-10-07
- **Skenario Pengujian:** Memastikan sistem menolak status kursus di luar pilihan enum yang diperbolehkan (`draft`, `open`, `closed`).
- **Langkah Pengujian:**
  1. Kirim request POST ke `/courses` dengan memanipulasi parameter `status = 'archived'`.
  2. Amati penolakan aturan validasi `in:draft,open,closed`.
- **Input Data:**
  - `code`: `PHP-101`
  - `name`: `PHP Fundamental`
  - `fee`: `400000`
  - `quota`: `25`
  - `start_date`: `2026-11-22`
  - `status`: `archived` (tidak terdaftar dalam enum)
- **Output yang Diharapkan:**
  - HTTP Status: `302 Found` (Redirect kembali).
  - Session Errors: Pesan validasi in-rule muncul: *"Status kursus harus berupa salah satu dari: draft, open, closed."*.
  - Database: Nilai ilegal ditolak dan tidak disimpan ke kolom enum MySQL.
- **Output Aktual:**
  - HTTP Status: `302 Found`.
  - Pesan Error: *"Status kursus harus berupa salah satu dari: draft, open, closed."* muncul.
- **Status:** **PASS**

---

### Test Case 8: Daftar Ditampilkan Ulang
- **Nomor Test Case:** TC-10-08
- **Skenario Pengujian:** Memastikan halaman daftar kursus `/courses` berhasil memuat seluruh data dari tabel `courses` database MySQL secara dinamis, rapi, dan responsif.
- **Langkah Pengujian:**
  1. Buka URL `/courses` melalui browser.
  2. Verifikasi status respon HTTP bernilai 200 OK.
  3. Periksa seluruh kolom tabel (No, Kode, Nama, Biaya, Kuota, Tanggal Mulai, Status).
  4. Verifikasi bahwa 8 data kursus yang ada di MySQL tertampil lengkap tanpa data terpotong.
  5. Periksa footer kartu yang menampilkan informasi total data kursus.
  6. Periksa responsivitas tabel pada resolusi desktop dan mobile (`table-responsive`).
- **Input Data:**
  - Akses URL: `GET /courses`
  - Data di MySQL: 8 records (WEB-101, PHP-101, LRV-101, SQL-101, UI-101, JS-101, AJAX-101, API-101).
- **Output yang Diharapkan:**
  - HTTP Status: `200 OK`.
  - View: `resources/views/courses/index.blade.php`.
  - Tabel memuat minimal 8 baris data kursus dari MySQL `kursus_dea`.
  - Footer menampilkan *"Total 8 kursus terdaftar di database MySQL."*.
  - Desain konsisten tema ungu-putih, tombol "Tambah Kursus" tersedia di kanan atas header.
- **Output Aktual:**
  - HTTP Status: `200 OK`.
  - Seluruh 8 data kursus tampil utuh dengan penomoran berurutan 1 sampai 8, format mata uang rupiah rapi, format tanggal jelas (`d M Y`), dan badge status yang sesuai (`Open`, `Draft`, `Closed`).
  - Footer kartu menampilkan: *"Total 8 kursus terdaftar di database MySQL."*.
- **Status:** **PASS**

---

## Pengujian Integrasi Tambahan

### Integrasi Endpoint Ajax Ketersediaan Kursi
- **Endpoint:** `GET /courses/{course}/availability`
- **Tujuan:** Memastikan endpoint ketersediaan kursi membaca langsung data dari tabel `courses` di MySQL via Model Eloquent `Course`, sekaligus menjaga kompatibilitas payload JSON dengan JavaScript Pertemuan 9.
- **Hasil Pengujian:**
  1. Akses via ID angka (`GET /courses/1/availability`):
     - HTTP Status: `200 OK`
     - Response Body: `{"id":1,"code":"WEB-101","name":"Web Dasar","quota":25,"status":"open","remaining":25}`
     - Status: **PASS**
  2. Akses via Kode String (`GET /courses/WEB-101/availability`):
     - HTTP Status: `200 OK`
     - Response Body: `{"id":1,"code":"WEB-101","name":"Web Dasar","quota":25,"status":"open","remaining":25}`
     - Status: **PASS**
  3. Akses ID tidak terdaftar (`GET /courses/999/availability`):
     - HTTP Status: `404 Not Found`
     - Status: **PASS**

### Pengujian Halaman Pendaftaran Pertemuan 9
- **Endpoint:** `GET /pendaftaran`
- **Tujuan:** Memastikan form pendaftaran kursus tetap berfungsi normal dan membaca daftar kursus secara dinamis dari database MySQL.
- **Hasil:** Dropdown kursus memuat daftar kursus dari database MySQL, integrasi Ajax jQuery berjalan real-time tanpa reload halaman, dan horizontal overflow pada viewport mobile tetap 0px.
- **Status:** **PASS**

---

## Verifikasi PHPUnit Automated Test Suite

Seluruh skenario pengujian di atas juga diintegrasikan ke dalam automated test suite Laravel pada `tests/Feature/CourseCrudTest.php`.

```text
Tests\Feature\CourseCrudTest
  ✔ course routes are registered and named
  ✔ course index renders successfully with empty state
  ✔ course create form renders all required inputs
  ✔ case 1 store valid course redirects and saves to database
  ✔ case 2 validation fails when code is empty
  ✔ case 3 validation fails when code is duplicate
  ✔ case 4 validation fails when name is empty
  ✔ case 5 validation fails when fee is negative
  ✔ case 6 validation fails when quota is zero
  ✔ case 7 validation fails when status is invalid
  ✔ case 8 index displays multiple courses from database
  ✔ availability route reads from database eloquent

Tests: 26 passed, 26 total (194 assertions)
Duration: 1.01s
Status: PASS (100%)
```
