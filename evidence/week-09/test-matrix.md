# Matriks Pengujian Pertemuan 9 — KursusKu Laravel
**Topik:** Laravel Layout, Bootstrap, jQuery Ajax & Form  
**Tanggal Pengujian:** 18 November 2026  
**Lingkungan:** PHP 8.3 (CLI / Built-in Server), Node.js v22, Laravel 11/12, Chromium Headless, PHPUnit 11  

---

## Ringkasan Hasil Pengujian

| Total Skenario | Berhasil (PASS) | Gagal (FAIL) | Tingkat Keberhasilan |
|:---:|:---:|:---:|:---:|
| **10** | **10** | **0** | **100%** |

---

## Rincian Matriks Pengujian

| No | Skenario Pengujian | Kriteria Keberhasilan | Metode Pengujian | Hasil Pengujian Sebenarnya | Status |
|:---|:---|:---|:---|:---|:---:|
| **1** | Beranda terbuka dan layout tampil | Halaman beranda `/` dapat diakses (HTTP 200), master layout terpasang dengan `@include('partials.navbar')`, `@yield('content')`, footer konsisten, aset Vite & CSS termuat, serta 6 katalog kursus tampil utuh. | Pengujian otomatis Headless Browser & PHPUnit Feature Test (`HomeControllerTest`) | Halaman beranda termuat sempurna, seluruh data 6 kursus tampil lengkap, master layout dan footer terpasang rapi tanpa error konsol. | **PASS** |
| **2** | Navbar responsive bekerja | Pada viewport mobile (< 768px atau 394px), tombol hamburger `.navbar-toggler` terlihat; saat diklik menu navigasi `.collapse` terbuka (kelas `show` aktif); menu dapat ditutup kembali; tidak ada horizontal overflow. | Pengujian interaktif Headless Chrome Emulation (394 × 844 px) dengan event klik toggler | Hamburger bar merespons klik, drawer menu menampilkan 6 menu navigasi dan tombol CTA dengan rapi, horizontal overflow tepat 0px. | **PASS** |
| **3** | Halaman pendaftaran terbuka dengan layout yang sama | Rute `/pendaftaran` (`enrollments.create`) mengembalikan HTTP 200, menggunakan master layout yang sama (`layouts/app.blade.php`), memuat form dengan field nama, email, pilihan kursus, dan elemen `#availability`. | Browser request & Feature Test (`EnrollmentTest::test_enrollment_route_is_accessible_and_named`) | Tampilan halaman pendaftaran konsisten dengan tema ungu-putih, mewarisi navbar dan footer yang sama, form input dan opsi kursus tampil lengkap. | **PASS** |
| **4** | Select kosong menampilkan instruksi | Pada saat form dibuka atau saat dropdown `#course_id` bernilai kosong (`""`), area `#availability` menampilkan instruksi panduan memilih kursus. | Evaluasi DOM awal via Headless Browser JavaScript (`#availability`) | Elemen `#availability` menampilkan teks: *"Silakan pilih kursus untuk melihat sisa ketersediaan kursi."* dalam alert informatif. | **PASS** |
| **5** | Memilih Web Dasar menampilkan 12 kursi tersisa | Memilih opsi "Web Dasar" (ID 1) memicu event change dan menampilkan nama kursus beserta sisa 12 kursi tanpa reload halaman. | Trigger event change jQuery `#course_id` value `1` pada browser aktif | Area `#availability` langsung diperbarui secara asinkron menampilkan: *"Kursus: Web Dasar — 12 kursi tersisa"* (badge hijau). | **PASS** |
| **6** | Memilih PHP Dasar menampilkan 8 kursi tersisa | Memilih opsi "PHP Dasar" (ID 2) memicu request Ajax dan menampilkan sisa 8 kursi secara dinamis. | Trigger event change jQuery `#course_id` value `2` pada browser aktif | Area `#availability` menampilkan: *"Kursus: PHP Dasar — 8 kursi tersisa"* secara asinkron tanpa reload halaman. | **PASS** |
| **7** | Memilih Laravel Dasar menampilkan 4 kursi tersisa | Memilih opsi "Laravel Dasar" (ID 3) memicu request Ajax dan menampilkan sisa 4 kursi secara dinamis. | Trigger event change jQuery `#course_id` value `3` pada browser aktif | Area `#availability` menampilkan: *"Kursus: Laravel Dasar — 4 kursi tersisa"* secara asinkron tanpa reload halaman. | **PASS** |
| **8** | Submit nama dan email valid menampilkan success message | Mengirim form dengan nama valid (min 3 karakter), email valid, dan ID kursus valid mengembalikan status redirect dengan flash message success dan nilai input dipertahankan (`withInput`). | Form submission valid via Headless Chrome & PHPUnit POST test | Muncul pesan sukses: *"Formulir pendaftaran berhasil divalidasi!"*, nilai nama dan email tetap tersimpan di input form, tidak ada penyimpanan database. | **PASS** |
| **9** | Submit email tidak valid menampilkan validation error | Mengirim form dengan format email tidak valid (misal: `bukan-email-valid`) memicu validasi Laravel `$request->validate()` dan memunculkan class `is-invalid` serta teks pesan error validasi. | Form submission invalid via Headless Chrome & PHPUnit POST error test | Input email mendapatkan kelas `is-invalid` dan menampilkan pesan: *"Format alamat email tidak valid."* di bawah input. | **PASS** |
| **10** | Network memperlihatkan GET availability status 200 dan response JSON | Pemanggilan Ajax ke endpoint `/courses/{id}/availability` terekam pada panel Network dengan metode GET, HTTP status 200, dan format respons `application/json`. | Analisis DevTools Network Events via Chrome DevTools Protocol (CDP) | Permintaan jaringan tercatat: `GET /courses/1/availability` &rarr; Status 200 OK, MIME type `application/json`, payload: `{"id":1,"name":"Web Dasar","remaining":12}`. | **PASS** |

---

## Verifikasi Tambahan

- **JavaScript Console:** Bersih, tidak ada error JavaScript (`Console Errors: None`).
- **jQuery Availability:** `window.$` dan `window.jQuery` aktif dan dapat diakses secara global.
- **Bootstrap Component:** Interaktivitas dropdown/collapse Bootstrap 5 berfungsi dengan baik.
- **Horizontal Overflow Check:**
  - Viewport 394 × 844 px: `Overflow = 0px` (PASS)
  - Viewport 375 × 812 px: `Overflow = 0px` (PASS)
  - Viewport 320 × 720 px: `Overflow = 0px` (PASS)
  - Desktop 1280 × 800 px: `Overflow = 0px` (PASS)
- **Suite Uji Otomatis:** PHPUnit menjalankan 14 tests dengan 131 assertions (100% Passed).
