# ARAP SPPG Kalisapu — Sistem Presensi Mesin Fingerprint & Rekapitulasi Kehadiran Relawan

ARAP (Aplikasi Relawan & Pegawai) versi 2.0 merupakan modernisasi menyeluruh sistem presensi dan manajemen kehadiran bagi **Satuan Pelayanan Pemenuhan Gizi (SPPG) Kalisapu**, Kabupaten Tegal. Dibangun dengan arsitektur modern berbasis **Laravel 13.x**, **PHP 8.5**, **Bootstrap 5.3 + SCSS**, dan **Vite 8**.

Sistem ini dirancang 100% berfokus pada operasional fisik dapur: integrasi langsung dengan **Mesin Sidik Jari (Fingerprint) ZKTeco**, pengelolaan permohonan izin/sakit dengan bukti surat dokter, manajemen data pegawai/Officer, serta mesin rekapitulasi poin kehadiran periode bulanan (cut-off 20–19) & mingguan dengan ekspor resmi Excel dan PDF.

---

## 📚 Dokumentasi Resmi Proyek

| Dokumen | Deskripsi | Tautan Berkas |
| :--- | :--- | :---: |
| **Buku Manual Pengguna & Admin** | Panduan operasional ramah staf untuk Relawan, Officer, dan Tata Usaha (Pak Aslap) | [`docs/USER_ADMIN_MANUAL.md`](docs/USER_ADMIN_MANUAL.md) |
| **Panduan Deployment Server** | Konfigurasi production Linux, Nginx, PHP-FPM 8.5, migrasi DB non-destruktif & backup | [`docs/DEPLOYMENT_GUIDE.md`](docs/DEPLOYMENT_GUIDE.md) |
| **Laporan QA & Keamanan** | Hasil audit keamanan, benchmark query data nyata (11.000+ pools), dan automated test suite | [`docs/PHASE6_QA_REPORT.md`](docs/PHASE6_QA_REPORT.md) |
| **Template Server Produksi** | Contoh konfigurasi environment produksi, server block Nginx, dan skrip backup database | [`.env.production.example`](.env.production.example) & [`deploy/`](deploy/) |

---

## 🛠️ Stack Teknologi

- **Backend Framework:** Laravel 13.x
- **Runtime Environment:** PHP 8.5 (NTS Win32 / Linux x64)
- **Ekstensi PHP Aktif:** `sockets`, `pdo_mysql`, `pdo_sqlite`, `sqlite3`, `mbstring`, `fileinfo`, `gd`, `openssl`, `intl`, `bcmath`, `zip`
- **Autentikasi & RBAC:** Laravel Fortify v1.43 (Mendukung login fleksibel via Email atau Nomor Induk / NIK, Role Gates: `ultraman`, `staff-access`, `user`)
- **Frontend & Assets:** Bootstrap 5.3, Bootstrap Icons, Custom SCSS Theme, Rollup/Vite 8
- **Export & Pelaporan:** `barryvdh/laravel-dompdf:^3.0` (PDF Landscape A4 & Portrait Slip), `maatwebsite/excel:^3.1` (OpenXML Spreadsheet)
- **Komunikasi Mesin Fisik:** Driver UDP Socket ZKTeco Native PHP 8.5 (`app/Services/ZKTeco/`) tanpa dependensi abandoned
- **Penyimpanan Berkas Privat:** Private disk `izin_attachments` (`storage/app/private/izin_attachments`) dengan otorisasi Anti-IDOR

---

## ✨ Fitur-Fitur Utama

### 1. Integrasi Mesin Fingerprint ZKTeco Native (PHP 8.5)
- **Koneksi UDP Langsung:** Terhubung langsung ke perangkat ZKTeco (seperti unit `FPDAPUR1` di IP `192.168.1.8:4370`) via raw socket.
- **Check Koneksi:** Tombol uji koneksi real-time langsung dari panel admin.
- **Tarik Data Anti-Duplikasi:** Sinkronisasi log presensi dari mesin ke tabel historis `pools` secara cerdas tanpa membuat duplikasi data.
- **Fallback Upload USB (`.dat`):** Mendukung unggah berkas log offline Flashdisk jika jaringan kabel/WiFi terputus.

### 2. Engine Rekapitulasi Presensi Periode 20–19 & Mingguan
- **Cut-Off Bulanan Resmi (20 s.d. 19):** Otomatis menghitung kehadiran per siklus bulanan SPPG (misal periode 20 Oktober s.d. 19 November).
- **Mode Rekap Mingguan:** Mode alternatif Senin s.d. Minggu untuk evaluasi cepat mingguan dapur.
- **Prioritas Penilaian Kehadiran:**
  1. *Mesin Fingerprint (`pools`):* Evaluasi jam tap masuk vs jam kerja divisi.
  2. *Surat Izin / Sakit Disetujui (`permohonan_izins`):* Diakui resmi tanpa penalti alpha.
  3. *Hari Libur Nasional (`liburs`):* Libur resmi SPPG (tidak dihitung alpha).
  4. *Alpha:* Hari kerja tanpa tap sidik jari dan tanpa izin resmi.
- **Sistem Poin Kinerja:**
  - `1.00 Poin` : Hadir Tepat Waktu
  - `0.80 Poin` : Terlambat Datang atau Pulang Lebih Awal
  - `0.00 Poin` : Izin, Sakit, atau Alpha
- **Ekspor Dokumen Resmi:** Cetak Rekapitulasi Bulanan ke format Microsoft Excel (.xlsx) dan PDF Resmi Landscape A4, serta Cetak PDF Rincian Presensi Individu.

### 3. Pengelolaan Master Pegawai / Relawan & Officer
- **Data Induk Lengkap:** Pengelolaan nama, Nomor Induk (NIK), UID mesin, divisi/bagian, nomor telepon, dan status akun login.
- **Penyederhanaan Form:** Penggunaan nomor induk/UID pendek (misal `1051`) dan pembuatan akun otomatis dengan password default yang seragam.
- **Soft-Deactivate (`is_active`):** Pegawai yang sudah tidak aktif dapat dinonaktifkan tanpa merusak riwayat log sidik jari historis (proteksi integritas basis data).
- **Deteksi Orphan UID:** Sistem secara otomatis mendeteksi jika ada sidik jari tak dikenal yang melakukan tap di mesin tetapi nomor UID-nya belum dipetakan ke profil relawan.

### 4. Modul Permohonan Izin / Sakit Mandiri
- **Pengajuan Mandiri oleh Relawan:** Relawan dapat mengajukan izin atau sakit dari akun masing-masing dengan mengunggah lampiran surat dokter (PDF, JPG, PNG).
- **Verifikasi Tata Usaha:** Tim TU / Admin memiliki panel khusus untuk menyetujui (*Approve*) atau menolak (*Reject*) pengajuan izin dengan catatan verifikator.
- **Proteksi Akses Berkas (Anti-IDOR):** Berkas surat dokter disimpan pada private disk dan hanya dapat diunduh oleh pemilik akun atau staf TU yang berwenang.

### 5. Kelola Hari Libur Nasional & Jam Kerja
- **Manajemen Hari Libur (`liburs`):** CRUD hari libur nasional serta tombol sinkronisasi otomatis via API Libur Nasional Indonesia.
- **Jam Kerja Dinamis per 8 Divisi:** Pengaturan jam masuk, jam pulang, dan toleransi keterlambatan untuk divisi: Officer, Persiapan, Pengolahan, Pemorsian, Distribusi, Kebersihan, Security, dan Ompreng.

### 6. Profil & Ganti Password Mandiri
- Setiap relawan dan staf dapat mengganti kata sandi akun login mereka secara mandiri dari menu profil.

---

## 🚀 Panduan Instalasi & Eksekusi Lokal

Gunakan environment Laragon dengan PHP 8.5.12:

```powershell
# 1. Clone repositori resmi & masuk ke direktori proyek
git clone https://github.com/SanjayaPutra23/arap-v2-sppg.git arap-v2
cd arap-v2

# 2. Salin environment dan buat application key
copy .env.example .env
& "C:\laragon\bin\php\php-8.5.12-nts-Win32-vs17-x64\php.exe" artisan key:generate

# 3. Setup Database arap_v2_dev & Migrasi
mysql -u root -e "CREATE DATABASE IF NOT EXISTS arap_v2_dev CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
cmd /c "mysql -u root --default-character-set=utf8mb4 arap_v2_dev < database_sql\arap.sql"
& "C:\laragon\bin\php\php-8.5.12-nts-Win32-vs17-x64\php.exe" artisan migrate

# 4. Instalasi Dependensi PHP & JavaScript
& "C:\laragon\bin\php\php-8.5.12-nts-Win32-vs17-x64\php.exe" C:\ProgramData\ComposerSetup\bin\composer.phar install
npm install
npm run build

# 5. Jalankan Automated Test Suite
& "C:\laragon\bin\php\php-8.5.12-nts-Win32-vs17-x64\php.exe" artisan test --compact

# 6. Menjalankan Server Lokal
& "C:\laragon\bin\php\php-8.5.12-nts-Win32-vs17-x64\php.exe" artisan serve
```

---

## 🧪 Pengujian Otomatis (Automated Testing)

Aplikasi memiliki rangkaian automated tests komprehensif pada database SQLite in-memory (`:memory:`) tanpa menyentuh database operasional MySQL:

```powershell
& "C:\laragon\bin\php\php-8.5.12-nts-Win32-vs17-x64\php.exe" artisan test --compact
```

**Status Pengujian:** `132 passed (613 assertions)` — 100% lulus.

Cakupan pengujian meliputi:
- Autentikasi multi-identitas (Login via NIK dan Email, Fortify rate-limiting)
- Role-Based Access Control (RBAC) pada setiap guard dan route admin
- Driver ZKTeco native parser dan UDP packet handling
- Sinkronisasi data pools fingerprint anti-duplikasi & parsing berkas USB `.dat`
- Pengujian isolasi data IDOR pada lampiran surat dokter
- Integritas data pegawai, relasi akun pengguna, dan soft-deactivation
- Uji presisi kalkulasi poin rekapitulasi 20–19 dan mode mingguan
- Verifikasi bebas N+1 query pada seluruh halaman utama
- Pengujian ekspor berkas Excel dan PDF
- Pengujian siklus hidup kehadiran end-to-end (Tap Mesin + Izin + Rekap + Ganti Password)

---

## 🔒 Kebijakan Keamanan (Security Hardening)

- **Permissions-Policy:** Header HTTP disetel ke `camera=(), geolocation=(), microphone=()` untuk menutup penuh celah sensor perangkat pada server.
- **Enterprise Headers:** `X-Frame-Options: SAMEORIGIN`, `X-Content-Type-Options: nosniff`, `Referrer-Policy: strict-origin-when-cross-origin`.
- **Anti-IDOR File Streaming:** Seluruh dokumen lampiran disimpan di luar web root (`storage/app/private/`) dan diakses melalui controller yang memvalidasi kepemilikan data atau hak akses staf Tata Usaha.
- **Anti SQL Injection & XSS:** Query binding Eloquent, validasi FormRequest yang ketat, dan sanitasi output Blade.

---

## 👥 Hak Akses & Peran Pengguna (Roles)

1. **Ultraman (Super Admin / Pengelola SPPG):** Memiliki hak akses penuh ke seluruh modul sistem: kelola mesin fingerprint & sinkronisasi data, master data pegawai & akun login, approval permohonan izin/sakit, jam kerja 8 divisi, kelola hari libur nasional, dan seluruh rekapitulasi.
2. **Officer (Tata Usaha & Pengawas Lapangan):** Staf operasional dengan hak akses pemantauan dashboard kehadiran harian, penelusuran arsip log fingerprint, verifikasi izin relawan, dan pencetakan laporan rekapitulasi bulanan/mingguan (Excel & PDF).
3. **User (Relawan Operasional Dapur):** Relawan dapur dengan hak akses personal untuk melihat jadwal jam kerja divisi di dashboard, mengajukan permohonan izin/sakit mandiri dengan lampiran surat dokter, dan memperbarui kata sandi akun login.

---

## 🏢 Kepemilikan & Hak Cipta

**Satuan Pelayanan Pemenuhan Gizi (SPPG) Kalisapu**  
Kecamatan Slawi, Kabupaten Tegal, Jawa Tengah  
*Modernisasi Sistem Presensi ARAP v2.0*
