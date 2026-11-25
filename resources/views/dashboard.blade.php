@extends('layouts.app')

@section('title', 'Dashboard Utama')
@section('page_title', 'Dashboard Operasional')

@section('content')
<div class="container-fluid p-0">
    <!-- Welcome Header Card -->
    <div class="card card-dashboard border-0 bg-primary-arap text-white mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <span class="badge bg-light text-primary mb-2">ARAP SPPG Kalisapu v2.0</span>
                    <h3 class="fw-bold mb-1">Selamat Datang, {{ $user->name ?? 'Pengguna' }}!</h3>
                    <p class="mb-2 text-white-50">
                        Sistem Presensi Mesin Fingerprint & Izin Relawan Satuan Pelayanan Pemenuhan Gizi (SPPG) Kalisapu.
                    </p>
                    <div class="d-flex flex-wrap gap-2 align-items-center pt-1">
                        <span class="small text-white-50"><i class="bi bi-shield-lock me-1"></i> Peran Aktif:</span>
                        @forelse($roles as $role)
                            <span class="badge bg-white text-primary fw-semibold">{{ $role }}</span>
                        @empty
                            <span class="badge bg-secondary">Relawan</span>
                        @endforelse
                    </div>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <div class="d-inline-flex flex-column align-items-md-end">
                        <div class="small text-white-50"><i class="bi bi-calendar3 me-1"></i> {{ \Carbon\Carbon::parse($todayDate)->translatedFormat('l, d F Y') }}</div>
                        <div class="h5 fw-bold mb-0 text-white"><i class="bi bi-clock me-1"></i> <span id="liveClock">{{ date('H:i:s') }} WIB</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @can('staff-access')
    <!-- Operational Monitoring Section (Staff & Admins) -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h5 class="fw-bold mb-0 text-dark">
            <i class="bi bi-speedometer2 text-primary me-2"></i>Ringkasan Operasional Presensi Mesin & Izin
        </h5>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.rekap.index') }}" class="btn btn-sm btn-primary-arap fw-semibold">
                <i class="bi bi-file-earmark-spreadsheet me-1"></i> Buka Rekapitulasi (20-19)
            </a>
        </div>
    </div>

    <!-- 5 KPI Cards Grid -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4 col-xl">
            <div class="card border-0 shadow-sm h-100 bg-white">
                <div class="card-body p-3">
                    <div class="text-muted small fw-semibold">Pegawai / Relawan Aktif</div>
                    <div class="h3 fw-bold text-dark my-1">{{ $kpi['total_peserta'] ?? 0 }}</div>
                    <div class="small text-secondary"><i class="bi bi-people me-1"></i> Termasuk {{ $kpi['total_officers'] ?? 0 }} Officer</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-xl">
            <div class="card border-0 shadow-sm h-100 bg-white border-start border-primary border-4">
                <div class="card-body p-3">
                    <div class="text-muted small fw-semibold">Total Log Mesin (Pools)</div>
                    <div class="h3 fw-bold text-primary my-1">{{ number_format($kpi['total_pools'] ?? 0) }}</div>
                    <div class="small text-muted text-truncate" title="Terakhir: {{ $latestPool ? $latestPool->date.' '.$latestPool->time : '-' }}">
                        <i class="bi bi-hdd-network me-1"></i> Terakhir: {{ $latestPool ? $latestPool->time : '-' }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-xl">
            <div class="card border-0 shadow-sm h-100 bg-white border-start border-info border-4">
                <div class="card-body p-3">
                    <div class="text-muted small fw-semibold">Perangkat Fingerprint</div>
                    <div class="h3 fw-bold text-info my-1">{{ $kpi['total_mesin'] ?? 0 }}</div>
                    <div class="small text-info"><i class="bi bi-cpu me-1"></i> FPDAPUR1 & Unit Cadangan</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-xl">
            <div class="card border-0 shadow-sm h-100 bg-white border-start border-warning border-4">
                <div class="card-body p-3">
                    <div class="text-muted small fw-semibold">Izin Menunggu Review</div>
                    <div class="h3 fw-bold text-warning my-1">{{ $kpi['izin_menunggu'] ?? 0 }}</div>
                    <div class="small text-warning"><i class="bi bi-hourglass-split me-1"></i> Perlu Verifikasi TU</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-xl">
            <div class="card border-0 shadow-sm h-100 bg-white border-start {{ ($kpi['orphan_uids_count'] ?? 0) > 0 ? 'border-danger' : 'border-success' }} border-4">
                <div class="card-body p-3">
                    <div class="text-muted small fw-semibold">UID Mesin Tak Dikenal</div>
                    <div class="h3 fw-bold {{ ($kpi['orphan_uids_count'] ?? 0) > 0 ? 'text-danger' : 'text-success' }} my-1">
                        {{ $kpi['orphan_uids_count'] ?? 0 }}
                    </div>
                    <div class="small {{ ($kpi['orphan_uids_count'] ?? 0) > 0 ? 'text-danger' : 'text-success' }}">
                        <i class="bi {{ ($kpi['orphan_uids_count'] ?? 0) > 0 ? 'bi-exclamation-triangle' : 'bi-check-circle' }} me-1"></i>
                        {{ ($kpi['orphan_uids_count'] ?? 0) > 0 ? 'Belum dipetakan ke staf' : 'Semua UID terpetakan' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Division Breakdown & Recent Pools Row -->
    <div class="row g-3 mb-4">
        <!-- Division Breakdown Table -->
        <div class="col-12 col-xl-6">
            <div class="card border-0 shadow-sm h-100 bg-white">
                <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                    <h6 class="fw-bold mb-0 text-primary-arap">
                        <i class="bi bi-diagram-3-fill me-2"></i>Distribusi Pegawai per 8 Divisi SPPG
                    </h6>
                    <span class="badge bg-light text-dark">Aktif</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Divisi / Jabatan</th>
                                <th class="text-center">Jumlah Pegawai</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($divisionSummary ?? [] as $index => $div)
                                <tr>
                                    <td class="text-muted" style="width: 40px;">{{ $index + 1 }}</td>
                                    <td class="fw-semibold">
                                        @if(strtolower($div['name']) === 'officer')
                                            <span class="badge bg-primary me-1"><i class="bi bi-star-fill text-warning me-1"></i> Officer</span>
                                        @else
                                            {{ $div['name'] }}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border px-2 py-1 fs-6">
                                            {{ $div['total'] }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        @can('umum')
                                        <a href="{{ route('admin.pegawai.index', ['jabatan' => $div['name']]) }}" class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size: 0.75rem;">
                                            Lihat Pegawai
                                        </a>
                                        @else
                                        <span class="text-muted small">-</span>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Tidak ada data divisi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent 10 Fingerprint Logs from Mesin -->
        <div class="col-12 col-xl-6">
            <div class="card border-0 shadow-sm h-100 bg-white">
                <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                    <h6 class="fw-bold mb-0 text-primary-arap">
                        <i class="bi bi-fingerprint me-2"></i>10 Tap Mesin Fingerprint Terakhir
                    </h6>
                    <a href="{{ route('admin.arsip-fingerprint.index') }}" class="small text-primary text-decoration-none">
                        Buka Arsip Lengkap <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                        <thead class="table-light">
                            <tr>
                                <th>Pegawai / UID</th>
                                <th>Tanggal</th>
                                <th>Waktu Tap</th>
                                <th class="text-center">Mesin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPools ?? [] as $pool)
                                <tr>
                                    <td>
                                        @if($pool->peserta)
                                            <div class="fw-semibold text-dark">{{ $pool->peserta->name }}</div>
                                            <small class="text-muted">{{ $pool->peserta->jabatan }} &bull; UID: {{ $pool->iduser }}</small>
                                        @else
                                            <div class="fw-semibold text-danger">UID {{ $pool->iduser }} (Belum Terdaftar)</div>
                                            <small class="text-muted">Tap Mesin Tanpa Nama Pegawai</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-secondary">{{ \Carbon\Carbon::parse($pool->date)->translatedFormat('d M Y') }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border font-mono fs-6">
                                            <i class="bi bi-clock me-1 text-primary"></i>{{ $pool->time }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.7rem;">
                                            ID {{ $pool->idmesin ?? 'FPDAPUR1' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Belum ada catatan log mesin fingerprint.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endcan

    <!-- Non-Staff Relawan Information Card -->
    @cannot('staff-access')
    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm bg-white h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold mb-0 text-primary-arap">
                        <i class="bi bi-person-badge-fill me-2"></i>Informasi Profil & Jam Kerja Saya
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <small class="text-muted d-block">Nama Lengkap</small>
                            <span class="fw-bold fs-6 text-dark">{{ $peserta?->name ?? $user->name }}</span>
                        </div>
                        <div class="col-sm-6">
                            <small class="text-muted d-block">Nomor Induk / NIK</small>
                            <span class="fw-semibold text-dark">{{ $peserta?->nik ?? '-' }}</span>
                        </div>
                        <div class="col-sm-6">
                            <small class="text-muted d-block">Divisi / Bagian</small>
                            <span class="badge bg-primary-subtle text-primary">{{ $peserta?->jabatan ?? 'Relawan' }}</span>
                        </div>
                        <div class="col-sm-6">
                            <small class="text-muted d-block">UID Mesin Sidik Jari</small>
                            <span class="badge bg-light text-dark border">{{ $peserta?->uid ?? '-' }}</span>
                        </div>
                        <div class="col-12 border-top pt-3">
                            <small class="text-muted d-block mb-1">Jadwal Jam Kerja Resmi SPPG</small>
                            <div class="p-3 bg-light rounded d-flex flex-wrap gap-4 align-items-center">
                                <div>
                                    <i class="bi bi-box-arrow-in-right text-success me-1"></i> Jam Masuk:
                                    <strong class="text-dark">{{ $workingHours['start'] ?? '06:00' }} WIB</strong>
                                </div>
                                <div>
                                    <i class="bi bi-box-arrow-right text-danger me-1"></i> Jam Pulang:
                                    <strong class="text-dark">{{ $workingHours['end'] ?? '14:00' }} WIB</strong>
                                </div>
                                <div>
                                    <i class="bi bi-info-circle text-info me-1"></i> Toleransi Telat:
                                    <strong class="text-dark">{{ $workingHours['late_tolerance_minutes'] ?? 0 }} menit</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm bg-white h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold mb-0 text-primary-arap">
                        <i class="bi bi-lightning-charge-fill me-2"></i>Aksi Cepat Relawan
                    </h6>
                </div>
                <div class="card-body p-3 d-flex flex-column gap-2 justify-content-center">
                    <a href="{{ route('izin.index') }}" class="btn btn-outline-warning text-start py-2 px-3 d-flex align-items-center justify-content-between">
                        <div>
                            <div class="fw-bold text-dark"><i class="bi bi-file-earmark-medical me-2 text-warning"></i>Ajukan Izin / Sakit</div>
                            <small class="text-muted">Upload surat dokter atau keterangan izin</small>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>

                    <a href="{{ route('profile.password.edit') }}" class="btn btn-outline-secondary text-start py-2 px-3 d-flex align-items-center justify-content-between">
                        <div>
                            <div class="fw-bold text-dark"><i class="bi bi-shield-lock me-2 text-secondary"></i>Ganti Kata Sandi</div>
                            <small class="text-muted">Perbarui kata sandi akun login Anda</small>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endcannot

    <!-- Quick Action Cards Grid for Staff -->
    @can('staff-access')
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-dashboard h-100 bg-white">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small fw-semibold">Mesin Fingerprint</span>
                        <div class="bg-primary-subtle text-primary p-2 rounded">
                            <i class="bi bi-hdd-network-fill fs-5"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-1">Tarik Data Presensi</h5>
                    <p class="text-muted small mb-3">Tarik log presensi langsung via UDP socket atau upload USB `.dat`.</p>
                    @can('umum')
                    <a href="{{ route('admin.mesin.index') }}" class="btn btn-sm btn-outline-primary w-100 fw-semibold">
                        <i class="bi bi-arrow-repeat me-1"></i> Buka Kelola Mesin & Sync
                    </a>
                    @else
                    <a href="{{ route('admin.arsip-fingerprint.index') }}" class="btn btn-sm btn-outline-primary w-100 fw-semibold">
                        <i class="bi bi-hdd-network me-1"></i> Buka Arsip Log Mesin
                    </a>
                    @endcan
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-dashboard h-100 bg-white">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small fw-semibold">Laporan Kehadiran</span>
                        <div class="bg-success-subtle text-success p-2 rounded">
                            <i class="bi bi-file-earmark-spreadsheet-fill fs-5"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-1">Rekap Periode 20–19</h5>
                    <p class="text-muted small mb-3">Kalkulasi poin kehadiran (1.00 tepat waktu, 0.80 telat/awal, 0 alpha) & ekspor PDF/Excel.</p>
                    <a href="{{ route('admin.rekap.index') }}" class="btn btn-sm btn-outline-success w-100 fw-semibold">
                        <i class="bi bi-table me-1"></i> Buka Rekapitulasi
                    </a>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-dashboard h-100 bg-white">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small fw-semibold">Permohonan Izin</span>
                        <div class="bg-warning-subtle text-warning p-2 rounded">
                            <i class="bi bi-file-earmark-medical-fill fs-5"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-1">Verifikasi Izin & Sakit</h5>
                    <p class="text-muted small mb-3">Tinjau surat dokter dan setujui / tolak pengajuan izin relawan.</p>
                    <a href="{{ route('admin.izin.index') }}" class="btn btn-sm btn-outline-warning w-100 fw-semibold">
                        <i class="bi bi-check2-circle me-1"></i> Verifikasi Izin Relawan
                    </a>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-dashboard h-100 bg-white">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small fw-semibold">Data Induk Pegawai</span>
                        <div class="bg-info-subtle text-info p-2 rounded">
                            <i class="bi bi-people-fill fs-5"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-1">Kelola Pegawai / Officer</h5>
                    <p class="text-muted small mb-3">Kelola data pegawai, sinkronisasi UID fingerprint, dan status aktif.</p>
                    @can('umum')
                    <a href="{{ route('admin.pegawai.index') }}" class="btn btn-sm btn-outline-info w-100 fw-semibold">
                        <i class="bi bi-people me-1"></i> Master Pegawai / Officer
                    </a>
                    @else
                    <a href="{{ route('profile.password.edit') }}" class="btn btn-sm btn-outline-info w-100 fw-semibold">
                        <i class="bi bi-key me-1"></i> Ganti Password
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    @endcan
</div>
@endsection

@push('scripts')
<script>
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) + ' WIB';
        const el = document.getElementById('liveClock');
        if (el) el.textContent = timeString;
    }
    setInterval(updateClock, 1000);
</script>
@endpush
