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
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PerformanceQueryCountTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $roleUltraman = Role::create(['name' => 'Ultraman']);
        $roleOfficer = Role::create(['name' => 'Officer']);
        $roleUser = Role::create(['name' => 'User']);

        // Settings jam kerja
        Setting::create(['name' => 'jam_datang_officer', 'value' => '08:00:00']);
        Setting::create(['name' => 'jam_pulang_officer', 'value' => '17:00:00']);
        Setting::create(['name' => 'jam_datang_persiapan', 'value' => '08:00:00']);
        Setting::create(['name' => 'jam_pulang_persiapan', 'value' => '17:00:00']);
        Setting::create(['name' => 'jam_datang_pengolahan', 'value' => '07:00:00']);
        Setting::create(['name' => 'jam_pulang_pengolahan', 'value' => '15:00:00']);

        // Seed 15 peserta across different divisions
        $divs = ['Officer', 'Persiapan', 'Pengolahan', 'Pemorsian', 'Distribusi'];
        for ($i = 1; $i <= 15; $i++) {
            $peserta = Peserta::create([
                'name' => "Peserta Test {$i}",
                'nik' => sprintf('332801010190%04d', $i),
                'uid' => 2000 + $i,
                'jabatan' => $divs[$i % count($divs)],
                'type' => $i === 1 ? 'officer' : 'relawan',
            ]);

            // Sebagian punya user account
            if ($i <= 10) {
                $user = User::factory()->create([
                    'peserta_id' => $peserta->id,
                    'email' => "user{$i}@arap.local",
                ]);
                $user->roles()->attach($i === 1 ? $roleOfficer : $roleUser);
            }

            // Buat historical pool log fingerprint
            Pool::create([
                'mesin_id' => 1,
                'iduser' => $peserta->uid,
                'date' => Carbon::now('Asia/Jakarta')->subDays(2)->toDateString(),
                'time' => '07:50:00',
            ]);
            Pool::create([
                'mesin_id' => 1,
                'iduser' => $peserta->uid,
                'date' => Carbon::now('Asia/Jakarta')->subDays(2)->toDateString(),
                'time' => '17:05:00',
            ]);

            // Buat permohonan izin
            if ($i === 6 && isset($user)) {
                PermohonanIzin::create([
                    'user_id' => $user->id,
                    'peserta_id' => $peserta->id,
                    'jenis' => 'sakit',
                    'tgl_mulai' => Carbon::now('Asia/Jakarta')->subDays(4)->toDateString(),
                    'tgl_selesai' => Carbon::now('Asia/Jakarta')->subDays(3)->toDateString(),
                    'alasan' => 'Demam',
                    'status' => 'disetujui',
                    'approved_at' => now(),
                ]);
            }
        }

        Libur::create([
            'tanggal' => Carbon::now('Asia/Jakarta')->subDays(7)->toDateString(),
            'ket' => 'Libur Nasional Uji',
        ]);

        $this->adminUser = User::factory()->create(['email' => 'admin@arap.local']);
        $this->adminUser->roles()->attach($roleUltraman);
    }

    /**
     * Memastikan engine AttendanceRecapService bebas N+1 query (jumlah query <= 10).
     */
    public function test_attendance_recap_service_has_bounded_query_count(): void
    {
        Setting::clearRuntimeCache();

        DB::flushQueryLog();
        DB::enableQueryLog();

        $service = app(AttendanceRecapService::class);
        $recap = $service->generateRecap(
            (int) Carbon::now('Asia/Jakarta')->year,
            (int) Carbon::now('Asia/Jakarta')->month
        );

        $queries = DB::getQueryLog();
        $queryCount = count($queries);

        $this->assertNotEmpty($recap['records']);
        $this->assertLessThanOrEqual(
            10,
            $queryCount,
            "AttendanceRecapService query count ({$queryCount}) exceeds maximum allowed (10 queries). Possible N+1 query!"
        );
    }

    /**
     * Memastikan halaman Dashboard bebas N+1 query (jumlah query <= 15).
     */
    public function test_dashboard_page_has_bounded_query_count(): void
    {
        DB::flushQueryLog();
        DB::enableQueryLog();

        $response = $this->actingAs($this->adminUser)->get('/dashboard');
        $response->assertOk();

        $queryCount = count(DB::getQueryLog());
        $this->assertLessThanOrEqual(
            15,
            $queryCount,
            "Dashboard query count ({$queryCount}) exceeds maximum allowed (15 queries)."
        );
    }

    /**
     * Memastikan halaman Master Pegawai bebas N+1 query (jumlah query <= 15).
     */
    public function test_admin_pegawai_page_has_bounded_query_count(): void
    {
        DB::flushQueryLog();
        DB::enableQueryLog();

        $response = $this->actingAs($this->adminUser)->get('/admin/pegawai');
        $response->assertOk();

        $queryCount = count(DB::getQueryLog());
        $this->assertLessThanOrEqual(
            15,
            $queryCount,
            "Pegawai page query count ({$queryCount}) exceeds maximum allowed (15 queries)."
        );
    }

    /**
     * Memastikan halaman Arsip Fingerprint bebas N+1 query (jumlah query <= 15).
     */
    public function test_admin_arsip_fingerprint_page_has_bounded_query_count(): void
    {
        DB::flushQueryLog();
        DB::enableQueryLog();

        $response = $this->actingAs($this->adminUser)->get('/admin/arsip-fingerprint');
        $response->assertOk();

        $queryCount = count(DB::getQueryLog());
        $this->assertLessThanOrEqual(
            15,
            $queryCount,
            "Arsip Fingerprint query count ({$queryCount}) exceeds maximum allowed (15 queries)."
        );
    }

    /**
     * Memastikan halaman Rekapitulasi bebas N+1 query (jumlah query <= 15).
     */
    public function test_admin_rekap_page_has_bounded_query_count(): void
    {
        Setting::clearRuntimeCache();

        DB::flushQueryLog();
        DB::enableQueryLog();

        $response = $this->actingAs($this->adminUser)->get('/admin/rekap');
        $response->assertOk();

        $queryCount = count(DB::getQueryLog());
        $this->assertLessThanOrEqual(
            15,
            $queryCount,
            "Rekap page query count ({$queryCount}) exceeds maximum allowed (15 queries)."
        );
    }
}
