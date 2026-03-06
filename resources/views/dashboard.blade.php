@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h4 class="mb-2">Dashboard</h4>
        <p class="text-muted">Selamat datang, {{ Auth::user()->name }}</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-md-6 col-xl-3">
        <div class="card stat-widget h-100">
            <div class="card-body">
                <h5 class="card-title">Total Mahasiswa</h5>
                <h2>{{ $totalMahasiswa }}</h2>
                <p>Data mahasiswa terdaftar</p>
                <div class="progress">
                    <div class="progress-bar bg-primary progress-bar-striped" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-6 col-xl-3">
        <div class="card stat-widget h-100">
            <div class="card-body">
                <h5 class="card-title">Total Panitia</h5>
                <h2>{{ $totalPanitia }}</h2>
                <p>Data panitia terdaftar</p>
                <div class="progress">
                    <div class="progress-bar bg-success progress-bar-striped" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-6 col-xl-3">
        <div class="card stat-widget h-100">
            <div class="card-body">
                <h5 class="card-title">Total QR Code</h5>
                <h2>{{ $totalQrCode }}</h2>
                <p>QR code yang dihasilkan</p>
                <div class="progress">
                    <div class="progress-bar bg-info progress-bar-striped" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-6 col-xl-3">
        <div class="card stat-widget h-100">
            <div class="card-body">
                <h5 class="card-title">Total Absensi</h5>
                <h2>{{ $totalAbsensi }}</h2>
                <p>Total scan absensi</p>
                <div class="progress">
                    <div class="progress-bar bg-danger progress-bar-striped" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card table-widget">
            <div class="card-body">
                <h5 class="card-title mb-3">Absensi Terbaru</h5>
                <div class="table-responsive">
                    <table id="absensi-table" class="table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Tipe</th>
                                <th>Waktu Scan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentAbsensi as $absensi)
                            <tr>
                                <td>
                                    @if($absensi->participant)
                                        {{ $absensi->participant->nama }}
                                    @else
                                        <span class="text-muted">Data tidak ditemukan</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $absensi->participant_type == 'mahasiswa' ? 'primary' : 'success' }}">
                                        {{ ucfirst($absensi->participant_type) }}
                                    </span>
                                </td>
                                <td>{{ $absensi->scan_time->format('d/m/Y H:i:s') }}</td>
                                <td><span class="badge bg-success">{{ $absensi->status }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#absensi-table').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            },
            "pageLength": 10,
            "order": [[2, "desc"]],
            "responsive": true
        });
    });
</script>
@endsection
