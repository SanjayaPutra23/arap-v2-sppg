# BUKU PANDUAN PENGGUNA & ADMINISTRATOR (USER MANUAL)
## Sistem Presensi Relawan & Pegawai: ARAP v2
### Satuan Pelayanan Pemenuhan Gizi (SPPG) Kalisapu

---

## DAFTAR ISI
1. [BAGIAN 1 — PANDUAN OPERASIONAL RELAWAN & OFFICER](#bagian-1--panduan-operasional-relawan--officer)
   - 1.1 [Cara Masuk ke Sistem (Login dengan NIK / Email)](#11-cara-masuk-ke-sistem-login-dengan-nik--email)
   - 1.2 [Cara Presensi Harian Menggunakan Mesin Sidik Jari (FPDAPUR1 di Dapur)](#12-cara-presensi-harian-menggunakan-mesin-sidik-jari-fpdapur1-di-dapur)
   - 1.3 [Memahami Skor & Perolehan Poin Kehadiran](#13-memahami-skor--perolehan-poin-kehadiran)
   - 1.4 [Mengajukan Izin atau Sakit dengan Lampiran Surat Dokter](#14-mengajukan-izin-atau-sakit-dengan-lampiran-surat-dokter)
   - 1.5 [Ganti Kata Sandi Mandiri (Self-Service Password)](#15-ganti-kata-sandi-mandiri-self-service-password)
   - 1.6 [Melihat Jadwal Jam Kerja Divisi di Dashboard](#16-melihat-jadwal-jam-kerja-divisi-di-dashboard)
2. [BAGIAN 2 — PANDUAN ADMINISTRATOR & TATA USAHA (PAK ASLAP)](#bagian-2--panduan-administrator--tata-usaha-pak-aslap)
   - 2.1 [Membaca Dashboard Operasional & Monitoring Mesin Fingerprint](#21-membaca-dashboard-operasional--monitoring-mesin-fingerprint)
   - 2.2 [Kelola Mesin Fingerprint (IP & Port) & Tarik Data ZKTeco](#22-kelola-mesin-fingerprint-ip--port--tarik-data-zkteco)
   - 2.3 [Manajemen Master Data Pegawai, Officer & Deteksi Orphan UID](#23-manajemen-master-data-pegawai-officer--deteksi-orphan-uid)
   - 2.4 [Verifikasi & Persetujuan Permohonan Izin / Sakit](#24-verifikasi--persetujuan-permohonan-izin--sakit)
   - 2.5 [Penelusuran Arsip Log Fingerprint Mesin (Pools)](#25-penelusuran-arsip-log-fingerprint-mesin-pools)
   - 2.6 [Mencetak Rekapitulasi Kehadiran Bulanan 20–19 & Mingguan (Excel & PDF Resmi)](#26-mencetak-rekapitulasi-kehadiran-bulanan-2019--mingguan-excel--pdf-resmi)
   - 2.7 [Cetak PDF Detail Kehadiran Individu untuk Klarifikasi Poin](#27-cetak-pdf-detail-kehadiran-individu-untuk-klarifikasi-poin)
   - 2.8 [Kelola Hari Libur Nasional & Sinkronisasi API](#28-kelola-hari-libur-nasional--sinkronisasi-api)
   - 2.9 [Mengatur Jam Kerja per 8 Divisi & Officer](#29-mengatur-jam-kerja-per-8-divisi--officer)
3. [BAGIAN 3 — STANDAR OPERASIONAL PROSEDUR (SOP) PENDAFTARAN RELAWAN BARU](#bagian-3--standar-operasional-prosedur-sop-pendaftaran-relawan-baru)
   - 3.1 [Gambaran Alur Pendaftaran](#31-gambaran-alur-pendaftaran)
   - 3.2 [Langkah Demi Langkah Pendaftaran Relawan Baru](#32-langkah-demi-langkah-pendaftaran-relawan-baru)

---

# BAGIAN 1 — PANDUAN OPERASIONAL RELAWAN & OFFICER

Panduan ini ditujukan bagi seluruh Relawan Operasional Dapur dan staf Officer SPPG Kalisapu dalam menggunakan sistem ARAP v2.

---

### 1.1 Cara Masuk ke Sistem (Login dengan NIK / Email)
1. Buka peramban internet di ponsel atau komputer Anda (Google Chrome, Safari, atau Microsoft Edge).
2. Kunjungi alamat resmi ARAP SPPG Kalisapu: **`https://presensi.sppgkalisapu.id`** (atau alamat server lokal yang ditetapkan).
3. Pada halaman login:
   - **Nomor Identitas / Email:** Masukkan **Nomor Induk / NIK** Anda (misal ID pendek `1051` atau 16 digit NIK KTP), atau gunakan alamat email akun Anda.
   - **Kata Sandi:** Masukkan kata sandi akun Anda (default awal biasanya sama dengan Nomor Induk / UID Anda jika dibuatkan oleh Admin).
   - Ketuk tombol **Masuk ke Aplikasi**.
4. Sistem akan otomatis mengarahkan Anda ke halaman Dashboard ARAP v2.

---

### 1.2 Cara Presensi Harian Menggunakan Mesin Sidik Jari (FPDAPUR1 di Dapur)
Presensi harian kehadiran operasional di SPPG Kalisapu dilakukan secara fisik menggunakan **Mesin Sidik Jari ZKTeco (`FPDAPUR1`)** yang terletak di area operasional dapur SPPG Kalisapu:

1. **Presensi Masuk (Check-in):**
   - Datanglah ke dapur SPPG Kalisapu sebelum jam kerja divisi Anda dimulai.
   - Tempelkan jari telunjuk Anda (yang telah didaftarkan saat orientasi) pada sensor scanner mesin fingerprint.
   - Pastikan layar mesin menampilkan nama/UID Anda dan mengeluarkan suara konfirmasi: *"Terima Kasih"* (lampu hijau menyala).
2. **Presensi Pulang (Check-out):**
   - Setelah jam kerja divisi Anda selesai dan tugas operasional dapur rampung, tempelkan kembali jari Anda pada mesin fingerprint sebelum meninggalkan SPPG.
   - Mesin akan mencatat jam kepulangan Anda.

> **PENTING:** Selalu lakukan tap sidik jari 2 kali setiap hari kerja: **1 kali saat datang (Masuk)** dan **1 kali saat selesai kerja (Pulang)** agar jam kerja Anda terhitung penuh (Skor 1.00 Poin).

---

### 1.3 Memahami Skor & Perolehan Poin Kehadiran
Sistem ARAP v2 menerapkan aturan perhitungan poin otomatis yang adil berdasarkan evaluasi jam tap mesin vs jam kerja divisi:
- **1.00 Poin (Hadir Penuh Tepat Waktu):** Anda melakukan tap masuk sebelum batas jam datang divisi dan tap pulang setelah jam kepulangan divisi.
- **0.80 Poin (Terlambat atau Pulang Lebih Awal):** Anda melakukan tap masuk melewati toleransi jam kerja divisi, atau melakukan tap pulang sebelum jam kerja divisi berakhir.
- **0.00 Poin (Izin Resmi / Sakit):** Tidak hadir karena izin atau sakit yang disetujui Tata Usaha (tidak dikenakan penalti alpha).
- **0.00 Poin (Alpha):** Hari kerja tanpa tap sidik jari dan tanpa surat permohonan izin/sakit resmi.
- **Hari Libur Nasional:** Tanggal merah resmi SPPG Kalisapu tidak dihitung sebagai hari kerja (bebas penalti alpha).

---

### 1.4 Mengajukan Izin atau Sakit dengan Lampiran Surat Dokter
Jika Anda berhalangan hadir karena sakit atau ada keperluan mendesak:
1. Buka menu **Izin & Sakit** pada sidebar sebelah kiri.
2. Ketuk tombol **"Ajukan Izin / Sakit Baru"**.
3. Isi formulir pengajuan:
   - **Jenis:** Pilih *Izin* atau *Sakit*.
   - **Tanggal Mulai & Selesai:** Tentukan rentang hari tidak hadir.
   - **Keterangan:** Tuliskan alasan dengan jelas dan sopan.
   - **Surat Dokter / Bukti:** Khusus untuk sakit, lampirkan foto surat keterangan dokter/klinik (format JPG, PNG, atau PDF maksimal 3 MB).
4. Ketuk **Kirim Permohonan**.
5. Status pengajuan akan berstatus **"Menunggu"** hingga diverifikasi oleh staf Tata Usaha. Selama masih berstatus menunggu, Anda dapat membatalkan pengajuan jika sewaktu-waktu batal izin.

---

### 1.5 Ganti Kata Sandi Mandiri (Self-Service Password)
Setiap relawan dan staf dapat memperbarui kata sandi akunnya sendiri kapan saja demi keamanan:
1. Klik nama profil Anda di pojok kanan atas layar (topbar) lalu pilih **Ganti Password**, atau klik menu **Ganti Password** pada sidebar sebelah kiri.
2. Pada formulir yang muncul:
   - **Kata Sandi Saat Ini:** Masukkan kata sandi lama Anda untuk verifikasi identitas.
   - **Kata Sandi Baru:** Masukkan kata sandi baru (minimal 8 karakter kombinasi huruf dan angka).
   - **Konfirmasi Kata Sandi Baru:** Masukkan ulang kata sandi baru persis sama.
3. Klik tombol **Perbarui Kata Sandi**.
4. Gunakan kata sandi baru ini untuk login berikutnya.

---

### 1.6 Melihat Jadwal Jam Kerja Divisi di Dashboard
Bagi relawan yang login ke sistem:
- Halaman **Dashboard** langsung menyajikan kartu profil lengkap Anda: Nama, NIK, UID Mesin, Divisi Kerja, serta **Jadwal Jam Kerja Resmi SPPG** (Jam Masuk, Jam Pulang, dan Toleransi Telat dalam menit).
- Terdapat tombol pintasan cepat untuk **Ajukan Izin / Sakit** dan **Ganti Kata Sandi**.

---

# BAGIAN 2 — PANDUAN ADMINISTRATOR & TATA USAHA (PAK ASLAP)

Panduan ini ditujukan bagi Staf Tata Usaha, Koordinator SPPG Kalisapu (Pak Aslap), dan Administrator dalam memantau kehadiran, memverifikasi permohonan, mengelola master data, serta mencetak laporan rekapitulasi.

---

### 2.1 Membaca Dashboard Operasional & Monitoring Mesin Fingerprint
Saat login dengan akun berhak akses staf/admin, halaman utama **Dashboard** menyajikan ringkasan operasional secara langsung:
1. **5 Kartu KPI Utama:**
   - *Pegawai / Relawan Aktif:* Jumlah seluruh staf aktif dan jumlah Officer terdaftar.
   - *Total Log Mesin (Pools):* Akumulasi seluruh rekaman sidik jari dari mesin beserta waktu tap terakhir.
   - *Perangkat Fingerprint:* Jumlah mesin terdaftar di sistem.
   - *Izin Menunggu Review:* Jumlah permohonan izin/sakit yang membutuhkan verifikasi Tata Usaha.
   - *UID Mesin Tak Dikenal:* Indikator peringatan jika terdeteksi tap sidik jari dari UID yang belum terpetakan ke nama pegawai.
2. **Distribusi Pegawai per 8 Divisi SPPG:** Tabel jumlah personel per divisi: Officer, Persiapan, Pengolahan, Pemorsian, Distribusi, Kebersihan, Security, dan Ompreng.
3. **10 Tap Mesin Fingerprint Terakhir:** Menampilkan 10 aktivitas tap sidik jari terbaru langsung dari mesin fisik beserta nama pegawai, tanggal, dan jam tap.

---

### 2.2 Kelola Mesin Fingerprint (IP & Port) & Tarik Data ZKTeco
ARAP v2 dilengkapi driver native PHP 8.5 untuk terhubung langsung ke mesin ZKTeco di menu **Kelola Mesin & Sync** (`/admin/mesin`):

1. **Daftar Mesin:**
   - Menampilkan mesin terdaftar (seperti unit `FPDAPUR1` di IP `192.168.1.8:4370`, tipe Ethernet UDP Port 4370).
2. **Uji Koneksi Mesin (Check Koneksi):**
   - Klik tombol **Check Koneksi** pada kartu mesin.
   - Sistem akan melakukan handshake UDP langsung ke mesin, menampilkan respon latensi dan nama perangkat.
3. **Tarik Data / Sinkronkan Data Mesin ke Database:**
   - Klik tombol **Sinkronkan Data** pada baris mesin, atau klik **Sinkronkan Semua Mesin IP**.
   - Sistem menggunakan *file lock* atomik dan filter anti-duplikasi sehingga log dijamin tidak terduplikasi pada tabel `pools`.
4. **Fallback Upload File USB Flashdisk (`.dat`):**
   - Jika jaringan kabel LAN terputus, unduh log dari mesin ke flashdisk (file `attlog.dat`), lalu klik tombol **Upload Log USB (.dat)** di web ARAP untuk mengunggahnya.
5. **Perintah Terjadwal (Cron / CLI):**
   - Sistem dapat menarik data secara otomatis via task scheduler:
     ```bash
     php artisan arap:sync-machine
     ```

---

### 2.3 Manajemen Master Data Pegawai, Officer & Deteksi Orphan UID
Menu **Master Pegawai / Officer** (`/admin/pegawai`) digunakan untuk mengelola data relawan:
1. **Pencarian & Filter Cepat:**
   - Filter status: *Pegawai Aktif* vs *Nonaktif / Diarsipkan*.
   - Filter divisi dan filter kepemilikan akun login.
   - Pencarian berdasarkan Nama, Nomor Induk/NIK, atau UID Mesin.
2. **Tambah Pegawai & Akun Sekaligus:**
   - Masukkan Nama, Nomor Induk (NIK/ID pendek seperti `1051`), UID Mesin Fingerprint (misal `1051`), Divisi, dan No Telepon.
   - Jika membuatkan akun login: kolom password **dapat dikosongkan** (sistem otomatis menetapkan password default sama dengan NIK/UID).
3. **Fitur Nonaktifkan Pegawai (Soft-Deactivate):**
   - Pegawai yang memiliki riwayat log sidik jari di database diproteksi dari penghapusan permanen agar arsip laporan masa lalu tetap utuh.
   - Gunakan tombol kuning **Nonaktifkan Pegawai**. Pegawai akan diarsipkan, akses login diblokir, dan tidak lagi muncul di rekap aktif.
   - Pegawai dapat diaktifkan kembali kapan saja dengan tombol hijau **Aktifkan**.
4. **Deteksi Orphan UID (UID Belum Terdaftar):**
   - Jika mesin membaca nomor UID baru yang belum dipetakan ke profil relawan, panel peringatan kuning akan muncul di menu Arsip Fingerprint. Admin cukup memasukkan nomor UID tersebut ke profil pegawai di Master Pegawai agar seluruh log otomatis terhubung.

---

### 2.4 Verifikasi & Persetujuan Permohonan Izin / Sakit
Menu **Verifikasi Izin** (`/admin/izin`) menampilkan antrean izin relawan:
1. Tinjau alasan izin dan unduh lampiran surat dokter (file tersimpan privat aman dari akses luar).
2. Klik **Tinjau Permohonan**:
   - Pilih **Setujui** atau **Tolak**.
   - Berikan catatan verifikator TU.
   - Klik **Kirim Keputusan**.
3. Izin yang disetujui otomatis diperhitungkan pada rekapitulasi bulanan sehingga staf tidak dikenakan penalti Alpha.

---

### 2.5 Penelusuran Arsip Log Fingerprint Mesin (Pools)
Seluruh rekaman scan sidik jari dari mesin tersimpan pada menu **Arsip Fingerprint** (`/admin/arsip-fingerprint`):
- Filter berdasarkan rentang tanggal, mesin, atau pencarian nama / UID.
- Tampilan bersifat *read-only* untuk menjamin integritas riwayat presensi.

---

### 2.6 Mencetak Rekapitulasi Kehadiran Bulanan 20–19 & Mingguan (Excel & PDF Resmi)
Sistem rekapitulasi SPPG Kalisapu menerapkan siklus cut-off resmi **tanggal 20 s.d. tanggal 19**:
1. Buka menu **Rekapitulasi (20–19)** (`/admin/rekap`).
2. Pilih mode: **Mode Bulanan (Cut-off 20–19)** atau **Mode Mingguan (Senin–Minggu)**.
3. Pilih Bulan & Tahun periode laporan.
4. **Ekspor Microsoft Excel (`.xlsx`):**
   - Klik tombol hijau **Export Excel** untuk mengunduh laporan spreadsheet lengkap dengan kolom tepat waktu, terlambat, izin, sakit, alpha, total hadir, dan total poin.
5. **Ekspor Dokumen PDF Resmi:**
   - Klik tombol merah **Export PDF**.
   - Berkas PDF format **Landscape A4** akan terbuat otomatis dengan Kop Resmi **Satuan Pelayanan Pemenuhan Gizi (SPPG) Kalisapu**, tabel metrik rapi, dan kolom tanda tangan untuk **Pak Aslap (Pengelola SPPG)** dan **Petugas Tata Usaha**.

---

### 2.7 Cetak PDF Detail Kehadiran Individu untuk Klarifikasi Poin
Jika relawan memerlukan bukti rincian presensi perorangan untuk evaluasi poin atau slip kehadiran:
1. Pada tabel Rekapitulasi, klik tombol **Harian** di samping nama relawan yang dituju.
2. Halaman detail harian per tanggal akan terbuka (`/admin/rekap/{peserta}`).
3. Klik tombol merah **Cetak PDF Detail** di kanan atas.
4. Dokumen resmi **PDF Detail Individu** akan terunduh otomatis lengkap dengan Kop SPPG Kalisapu, profil pegawai, tabel presensi harian per tanggal (jam masuk, jam pulang, sumber mesin fisik, skor harian), dan 3 kolom tanda tangan: Relawan, Tata Usaha, dan Pak Aslap.

---

### 2.8 Kelola Hari Libur Nasional & Sinkronisasi API
Daftar hari libur nasional digunakan secara otomatis oleh engine rekapitulasi agar pada tanggal merah resmi relawan tidak dicatat sebagai Alpha:
1. Buka menu **Hari Libur Nasional** (`/admin/libur`).
2. **Tambah Manual:** Klik *Tambah Hari Libur*, masukkan tanggal dan keterangan libur.
3. **Sinkronisasi Otomatis dari API:** Klik tombol **Sinkronkan Libur Nasional (API)**, pilih tahun target (misal 2026 atau 2027), sistem akan otomatis menarik dan menyimpan seluruh hari libur resmi Republik Indonesia tanpa duplikasi data.

---

### 2.9 Mengatur Jam Kerja per 8 Divisi & Officer
Setiap divisi memiliki jadwal jam operasional yang dapat diatur dinamis di menu **Jam Kerja Divisi** (`/admin/settings/working-hours`):
- Tersedia pengaturan jam masuk, jam pulang, dan toleransi keterlambatan untuk:
  - **Officer SPPG**
  - **Divisi Persiapan**
  - **Divisi Pengolahan**
  - **Divisi Pemorsian**
  - **Divisi Distribusi**
  - **Divisi Kebersihan**
  - **Divisi Security**
  - **Divisi Ompreng**
- Klik **Simpan Seluruh Pengaturan Jam Kerja**. Perhitungan status tepat waktu dan terlambat pada rekapitulasi akan otomatis mengikuti jadwal tersebut.

---

# BAGIAN 3 — STANDAR OPERASIONAL PROSEDUR (SOP) PENDAFTARAN RELAWAN BARU

SOP ini mengatur alur pendaftaran relawan baru di SPPG Kalisapu dari pendaftaran sidik jari di mesin fisik hingga terhubung ke sistem ARAP v2.

---

### 3.1 Gambaran Alur Pendaftaran

```
+-----------------------------------------------------------------------------------+
| 1. DAFTARKAN DI MESIN FISIK -> Catat nomor UID (contoh: ID 1051)                  |
+-----------------------------------------------------------------------------------+
                                          |
                                          v
+-----------------------------------------------------------------------------------+
| 2. INPUT MASTER PEGAWAI DI WEB -> Masukkan Nama, NIK, Divisi, dan UID: 1051       |
+-----------------------------------------------------------------------------------+
                                          |
                                          v
+-----------------------------------------------------------------------------------+
| 3. BUATKAN AKUN LOGIN -> Centang buatkan akun, password default = UID (1051)      |
+-----------------------------------------------------------------------------------+
                                          |
                                          v
+-----------------------------------------------------------------------------------+
| 4. TEST SCAN SIDIK JARI -> Minta relawan tap di mesin, klik Sinkronkan Data       |
+-----------------------------------------------------------------------------------+
                                          |
                                          v
+-----------------------------------------------------------------------------------+
| 5. SELESAI -> Log mesin masuk ke pools, otomatis terhubung ke rekapitulasi         |
+-----------------------------------------------------------------------------------+
```

---

### 3.2 Langkah Demi Langkah Pendaftaran Relawan Baru

#### Langkah 1: Pendaftaran Sidik Jari pada Mesin Fisik ZKTeco `FPDAPUR1`
1. Datangi mesin absensi fingerprint di area dapur SPPG Kalisapu.
2. Tekan tombol **`M/OK`** pada mesin untuk membuka menu admin mesin.
3. Pilih menu **User Mgt** (Manajemen Pengguna) -> pilih **New User** (Pengguna Baru).
4. Mesin akan menampilkan nomor ID pengguna baru (contoh: **`1051`**).
   > **PENTING:** Catat nomor ID ini! Nomor ini adalah **UID Mesin** yang wajib dimasukkan ke website ARAP v2.
5. Arahkan kursor ke opsi **Fingerprint** (Sidik Jari), lalu tekan `M/OK`.
6. Minta relawan menempelkan jari telunjuk (disarankan mendaftarkan 2 jari: telunjuk kanan dan kiri) sebanyak 3 kali pada scanner hingga mesin berbunyi *"Terima Kasih"*.
7. Tekan tombol `ESC` hingga kembali ke layar utama mesin.

#### Langkah 2: Pendaftaran Data Master Pegawai di Web ARAP v2
1. Buka komputer Tata Usaha, kunjungi website ARAP v2, dan login sebagai Admin.
2. Buka menu **Master Pegawai / Officer** (`/admin/pegawai`).
3. Klik tombol **Tambah Pegawai / Officer Baru**.
4. Lengkapi isian data:
   - **Nama Lengkap:** Nama relawan sesuai KTP.
   - **Nomor Induk / NIK:** Nomor Induk atau NIK KTP relawan (bisa menggunakan nomor UID misal `1051`).
   - **UID Mesin Fingerprint:** Masukkan nomor ID mesin dari Langkah 1 (contoh: **`1051`**).
   - **Divisi / Jabatan:** Pilih divisi kerja relawan (misal: *Persiapan*, *Pengolahan*, *Pemorsian*, *Distribusi*, dll.).
   - **Nomor Telepon:** Nomor WhatsApp aktif relawan.

#### Langkah 3: Pembuatan Akun Login Relawan
1. Pada form yang sama, centang opsi **"Buatkan Akun Login Sekaligus"**.
2. Masukkan alamat email relawan (misal `relawan1051@arap.local`).
3. Kolom password **dapat dikosongkan** agar sistem otomatis menetapkan password awal sama dengan NIK/UID (`1051`).
4. Pilih Role: **User** (untuk relawan biasa) atau **Officer** (untuk staf pengawas).
5. Klik **Simpan Data Pegawai**.

#### Langkah 4: Uji Coba & Sinkronisasi
1. Minta relawan melakukan tap sidik jari di mesin dapur `FPDAPUR1`.
2. Di web ARAP v2, buka menu **Kelola Mesin & Sync** (`/admin/mesin`), lalu klik **Sinkronkan Data**.
3. Buka menu **Arsip Fingerprint** (`/admin/arsip-fingerprint`) dan pastikan rekaman tap barusan sudah tercatat dengan nama relawan yang bersangkutan.
4. Minta relawan login ke web untuk mencoba mengajukan izin jika diperlukan dan memperbarui kata sandi awal mereka via menu **Ganti Password**.

#### Langkah 5: Selesai
Relawan kini resmi terdaftar di SPPG Kalisapu. Seluruh tap kehadiran sidik jari di mesin fisik dapur akan secara otomatis dihitung dan direkapitulasi secara akurat oleh sistem ARAP v2.
