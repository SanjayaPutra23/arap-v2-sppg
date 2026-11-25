<?php

namespace Tests\Feature;

use App\Models\Libur;
use App\Models\PermohonanIzin;
use App\Models\Peserta;
use App\Models\Pool;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use App\Services\AttendanceRecapService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EndToEndAttendanceIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected Peserta $peserta1;

    protected Peserta $peserta2;

    protected Peserta $peserta3;

    protected User $user1;

    protected User $user2;

    protected User $adminTU;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Roles
        $roleUser = Role::create(['name' => 'User']);
        $roleOfficer = Role::create(['name' => 'Officer']);
        $roleUltraman = Role::create(['name' => 'Ultraman']);

        // 2. Settings Jam Kerja
        Setting::create(['name' => 'jam_datang_persiapan', 'value' => '08:00:00']);
        Setting::create(['name' => 'jam_pulang_persiapan', 'value' => '17:00:00']);
        Setting::create(['name' => 'jam_datang_officer', 'value' => '08:00:00']);
        Setting::create(['name' => 'jam_pulang_officer', 'value' => '17:00:00']);

        // 3. Data Peserta
        $this->peserta1 = Peserta::create([
            'name' => 'Soni Setiawan',
            'nik' => '3328010101900001',
            'uid' => 1001,
            'jabatan' => 'Persiapan',
            'type' => 'relawan',
            'telp' => '081234567891',
        ]);

        $this->peserta2 = Peserta::create([
            'name' => 'Dewi Lestari',
            'nik' => '3328010101900002',
            'uid' => 1002,
            'jabatan' => 'Persiapan',
            'type' => 'relawan',
            'telp' => '081234567892',
        ]);

        $this->peserta3 = Peserta::create([
            'name' => 'Kapten Aslap',
            'nik' => '3328010101900003',
            'uid' => 1003,
            'jabatan' => 'Officer',
            'type' => 'officer',
            'telp' => '081234567893',
        ]);

        // 4. Akun Pengguna
        $this->user1 = User::create([
            'name' => 'Soni Setiawan',
            'email' => 'soni@arap.local',
            'password' => Hash::make('password123'),
            'peserta_id' => $this->peserta1->id,
        ]);
        $this->user1->roles()->attach($roleUser);

        $this->user2 = User::create([
            'name' => 'Dewi Lestari',
            'email' => 'dewi@arap.local',
            'password' => Hash::make('password123'),
            'peserta_id' => $this->peserta2->id,
        ]);
        $this->user2->roles()->attach($roleUser);

        $this->adminTU = User::create([
            'name' => 'Staf Tata Usaha',
            'email' => 'tu@arap.local',
            'password' => Hash::make('password123'),
        ]);
        $this->adminTU->roles()->attach([$roleUltraman->id]);
    }

    /**
     * Skenario Lengkap Integrasi End-to-End:
     * 1. Login Relawan via NIK
     * 2. Presensi Masuk & Pulang via Mesin Fingerprint (Pools)
     * 3. Verifikasi Log Kehadiran di Dashboard Admin (Recent Pools & Division breakdown)
     * 4. Relawan 2 (Dewi) Pengajuan Izin/Sakit Mandiri dengan Lampiran Surat
     * 5. Admin TU Menyetujui Izin
     * 6. Presensi Mesin Fingerprint untuk Officer (Terlambat)
     * 7. Hari Libur Nasional Ditetapkan
     * 8. Rekapitulasi Kehadiran Gabungan 20-19 & Ekspor Excel / PDF
     * 9. Relawan Ganti Kata Sandi Akun Mandiri.
     */
    public function test_full_attendance_lifecycle_end_to_end(): void
    {
        Storage::fake('izin_attachments');

        // --- TAHAP 1: Login Relawan via NIK ---
        $loginResponse = $this->post('/login', [
            'email' => '3328010101900001', // Identitas NIK Soni
            'password' => 'password123',
        ]);
        $loginResponse->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($this->user1);

        $today = '2026-11-10';

        // --- TAHAP 2: Tap Mesin Fingerprint (Pools) Check-in & Check-out Soni ---
        Pool::create([
            'iduser' => $this->peserta1->uid,
            'date' => $today,
            'time' => '07:50:00',
        ]);
        Pool::create([
            'iduser' => $this->peserta1->uid,
            'date' => $today,
            'time' => '17:05:00',
        ]);

        $this->assertDatabaseHas('pools', [
            'iduser' => $this->peserta1->uid,
            'time' => '07:50:00',
        ]);
        $this->assertEquals(2, Pool::where('iduser', $this->peserta1->uid)->count());

        // --- TAHAP 3: Verifikasi Muncul di Dashboard Admin ---
        $dashResponse = $this->actingAs($this->adminTU)->get('/dashboard');
        $dashResponse->assertOk();
        $dashResponse->assertSee('Soni Setiawan');
        $dashResponse->assertSee('Persiapan');

        // --- TAHAP 4: Relawan 2 (Dewi) Mengajukan Sakit Mandiri ---
        $suratDokter = UploadedFile::fake()->createWithContent(
            'surat_dokter.pdf',
            "%PDF-1.4\n1 0 obj\n<<>>\nendobj\ntrailer\n<<>>\n%%EOF"
        );
        $izinResponse = $this->actingAs($this->user2)->post('/izin', [
            'jenis' => 'sakit',
            'tgl_mulai' => $today,
            'tgl_selesai' => $today,
            'alasan' => 'Sakit demam dan flu berat',
            'surat_dokter' => $suratDokter,
        ]);
        $izinResponse->assertRedirect('/izin');

        $izinDewi = PermohonanIzin::where('peserta_id', $this->peserta2->id)->first();
        $this->assertNotNull($izinDewi);
        $this->assertEquals(PermohonanIzin::STATUS_MENUNGGU, $izinDewi->status);
        $this->assertNotNull($izinDewi->surat_dokter);

        // --- TAHAP 5: Admin TU Menyetujui Izin Dewi ---
        $reviewResponse = $this->actingAs($this->adminTU)->post("/admin/izin/{$izinDewi->id}/review", [
            'status' => 'disetujui',
            'catatan_admin' => 'Disetujui berdasarkan surat dokter.',
        ]);
        $reviewResponse->assertRedirect();
        $this->assertEquals(PermohonanIzin::STATUS_DISETUJUI, $izinDewi->fresh()->status);

        // --- TAHAP 6: Tap Mesin Fingerprint untuk Officer (Terlambat) ---
        Pool::create([
            'iduser' => $this->peserta3->uid,
            'date' => $today,
            'time' => '08:30:00',
        ]);

        // --- TAHAP 7: Hari Libur Nasional ---
        Libur::create([
            'tanggal' => Carbon::parse($today)->addDays(2)->toDateString(),
            'ket' => 'Hari Pahlawan Nasional',
        ]);

        // --- TAHAP 8: Rekapitulasi Kehadiran Gabungan 20-19 & Ekspor Excel/PDF ---
        $now = Carbon::parse($today);
        $recapService = app(AttendanceRecapService::class);
        $recap = $recapService->generateRecap($now->year, $now->month);

        $this->assertNotNull($recap);
        $this->assertNotEmpty($recap['records']);

        // Soni: 1 Hadir Tepat Waktu (Skor 1.0)
        $soniRecap = collect($recap['records'])->firstWhere('peserta.id', $this->peserta1->id);
        $this->assertNotNull($soniRecap);
        $this->assertEquals(1, $soniRecap['hadir_tepat_waktu']);
        $this->assertEquals(1.0, $soniRecap['total_points']);

        // Dewi: 1 Hari Sakit Disetujui
        $dewiRecap = collect($recap['records'])->firstWhere('peserta.id', $this->peserta2->id);
        $this->assertNotNull($dewiRecap);
        $this->assertEquals(1, $dewiRecap['sakit']);

        // Kapten Aslap (Officer): 1 Hadir Terlambat (Skor 0.8)
        $aslapRecap = collect($recap['records'])->firstWhere('peserta.id', $this->peserta3->id);
        $this->assertNotNull($aslapRecap);
        $this->assertEquals(1, $aslapRecap['hadir_terlambat_early']);
        $this->assertEquals(0.8, $aslapRecap['total_points']);

        // Ekspor Excel & PDF
        $excelResponse = $this->actingAs($this->adminTU)->get("/admin/rekap/export/excel?year={$now->year}&month={$now->month}");
        $excelResponse->assertOk();
        $this->assertStringContainsString('spreadsheet', (string) $excelResponse->headers->get('content-type'));

        $pdfResponse = $this->actingAs($this->adminTU)->get("/admin/rekap/export/pdf?year={$now->year}&month={$now->month}");
        $pdfResponse->assertOk();
        $this->assertStringContainsString('application/pdf', (string) $pdfResponse->headers->get('content-type'));

        // --- TAHAP 9: Relawan Ganti Kata Sandi Mandiri ---
        $pwdResponse = $this->actingAs($this->user1)->put('/profile/password', [
            'current_password' => 'password123',
            'password' => 'passwordBaru456',
            'password_confirmation' => 'passwordBaru456',
        ]);
        $pwdResponse->assertRedirect('/profile/password');
        $this->assertTrue(Hash::check('passwordBaru456', $this->user1->fresh()->password));
    }
}
