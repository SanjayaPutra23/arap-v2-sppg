<?php

namespace App\Http\Controllers;

use App\Models\Mesin;
use App\Models\PermohonanIzin;
use App\Models\Peserta;
use App\Models\Pool;
use App\Services\AttendanceCalculationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Divisi utama yang dipantau dalam ringkasan dashboard.
     *
     * @var list<string>
     */
    public const DIVISIONS = [
        'Officer',
        'Persiapan',
        'Pengolahan',
        'Pemorsian',
        'Distribusi',
        'Kebersihan',
        'Security',
        'Ompreng',
    ];

    public function __construct(
        protected AttendanceCalculationService $calculationService
    ) {}

    /**
     * Tampilkan halaman utama dashboard ARAP v2 (Fokus Mesin Fingerprint + Izin).
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $roles = $user ? $user->roles->pluck('name')->toArray() : [];

        $totalPeserta = Peserta::active()->count();
        $totalOfficers = Peserta::active()->whereRaw('LOWER(jabatan) = ?', ['officer'])->count();
        $totalPools = Pool::count();
        $latestPool = Pool::orderByDesc('date')->orderByDesc('time')->first();
        $totalMesin = Mesin::count();
        $today = Carbon::now('Asia/Jakarta')->toDateString();

        $data = [
            'user' => $user,
            'roles' => $roles,
            'totalPeserta' => $totalPeserta,
            'totalOfficers' => $totalOfficers,
            'totalPools' => $totalPools,
            'latestPool' => $latestPool,
            'totalMesin' => $totalMesin,
            'todayDate' => $today,
        ];

        // Jika user memiliki akses staf (Ultraman, Umum, Officer, Tata Usaha, Akademik)
        if (Gate::allows('staff-access')) {
            // Pengajuan Izin/Sakit yang menunggu verifikasi
            $izinMenunggu = PermohonanIzin::where('status', PermohonanIzin::STATUS_MENUNGGU)->count();

            // Deteksi Orphan UID (UID di mesin pools yang belum ada di pesertas)
            $registeredUids = Peserta::whereNotNull('uid')->pluck('uid')->map(fn ($u) => (string) $u)->toArray();
            $orphanUidsCount = empty($registeredUids)
                ? Pool::distinct('iduser')->count('iduser')
                : Pool::whereNotIn('iduser', $registeredUids)->distinct('iduser')->count('iduser');

            $data['kpi'] = [
                'total_peserta' => $totalPeserta,
                'total_officers' => $totalOfficers,
                'total_pools' => $totalPools,
                'total_mesin' => $totalMesin,
                'izin_menunggu' => $izinMenunggu,
                'orphan_uids_count' => $orphanUidsCount,
            ];

            // Ringkasan jumlah pegawai per 8 divisi operasional
            $allPesertas = Peserta::active()->get(['id', 'jabatan']);
            $divisionSummary = [];

            foreach (self::DIVISIONS as $divisi) {
                $divLower = strtolower($divisi);

                $count = $allPesertas->filter(function ($p) use ($divLower) {
                    $jab = strtolower(trim((string) $p->jabatan));
                    if ($divLower === 'security') {
                        return $jab === 'security' || $jab === 'scurity';
                    }

                    return $jab === $divLower;
                })->count();

                $divisionSummary[] = [
                    'name' => $divisi,
                    'total' => $count,
                ];
            }

            $data['divisionSummary'] = $divisionSummary;

            // 10 Log Fingerprint Terakhir dari Mesin
            $data['recentPools'] = Pool::with('peserta')
                ->orderByDesc('date')
                ->orderByDesc('time')
                ->limit(10)
                ->get();
        } else {
            // Profil & jam kerja untuk relawan biasa
            $peserta = $user?->peserta;
            $workingHours = $peserta ? $this->calculationService->resolveWorkingHours($peserta) : null;

            $data['peserta'] = $peserta;
            $data['workingHours'] = $workingHours;
        }

        return view('dashboard', $data);
    }
}
