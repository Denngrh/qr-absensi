@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-4">
    <h2><i class="bi bi-speedometer2"></i> Dashboard</h2>
    <p class="text-muted">Selamat datang, {{ Auth::user()->name }}</p>
</div>

<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stat-card">
            <div class="card-body">
                <h6><i class="bi bi-people"></i> Total Mahasiswa</h6>
                <h2>{{ $totalMahasiswa }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card">
            <div class="card-body">
                <h6><i class="bi bi-person-badge"></i> Total Panitia</h6>
                <h2>{{ $totalPanitia }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card">
            <div class="card-body">
                <h6><i class="bi bi-qr-code"></i> Total QR Code</h6>
                <h2>{{ $totalQrCode }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card">
            <div class="card-body">
                <h6><i class="bi bi-clipboard-check"></i> Total Absensi</h6>
                <h2>{{ $totalAbsensi }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="bi bi-clock-history"></i> Absensi Terbaru</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Tipe</th>
                        <th>Waktu Scan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentAbsensi as $absensi)
                    <tr>
                        <td>{{ $absensi->participant->nama }}</td>
                        <td>
                            <span class="badge bg-{{ $absensi->participant_type == 'mahasiswa' ? 'primary' : 'success' }}">
                                {{ ucfirst($absensi->participant_type) }}
                            </span>
                        </td>
                        <td>{{ $absensi->scan_time->format('d/m/Y H:i:s') }}</td>
                        <td><span class="badge bg-success">{{ $absensi->status }}</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">Belum ada data absensi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
