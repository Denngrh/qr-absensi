@extends('layouts.admin')

@section('title', 'Rekap Absensi')

@section('content')
<div class="mb-3 mb-md-4">
    <h2 class="h4 h-md-2"><i class="bi bi-clipboard-check"></i> Rekap Absensi</h2>
</div>

<div class="card mb-3 mb-md-4 shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">Filter Data</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('absensi.index') }}">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="date" class="form-label">Tanggal</label>
                    <input type="date" class="form-control" id="date" name="date" value="{{ request('date') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="participant_type" class="form-label">Tipe Peserta</label>
                    <select class="form-select" id="participant_type" name="participant_type">
                        <option value="">Semua Tipe</option>
                        <option value="mahasiswa" {{ request('participant_type') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                        <option value="panitia" {{ request('participant_type') == 'panitia' ? 'selected' : '' }}>Panitia</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="search" class="form-label">Cari Nama</label>
                    <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Cari nama peserta...">
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Filter
                </button>
                <a href="{{ route('absensi.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Reset
                </a>
                <a href="{{ route('absensi.export') }}?{{ http_build_query(request()->all()) }}" class="btn btn-success">
                    <i class="bi bi-file-earmark-pdf"></i> Export PDF
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Nama</th>
                        <th class="d-none d-md-table-cell">Tipe</th>
                        <th class="d-none d-lg-table-cell">ID Peserta</th>
                        <th>Waktu Scan</th>
                        <th class="d-none d-md-table-cell">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensis as $absensi)
                    <tr>
                        <td class="ps-3">
                            @if($absensi->participant)
                                <strong class="d-block">{{ $absensi->participant->nama }}</strong>
                                <small class="text-muted d-md-none">
                                    {{ ucfirst($absensi->participant_type) }}
                                    @if($absensi->participant_type == 'mahasiswa')
                                        - {{ $absensi->participant->nim }}
                                    @else
                                        - {{ $absensi->participant->nip }}
                                    @endif
                                </small>
                            @else
                                <strong class="d-block text-muted">Data tidak ditemukan</strong>
                                <small class="text-muted d-md-none">{{ ucfirst($absensi->participant_type) }}</small>
                            @endif
                        </td>
                        <td class="d-none d-md-table-cell">
                            <span class="badge bg-{{ $absensi->participant_type == 'mahasiswa' ? 'primary' : 'success' }}">
                                {{ ucfirst($absensi->participant_type) }}
                            </span>
                        </td>
                        <td class="d-none d-lg-table-cell">
                            @if($absensi->participant)
                                @if($absensi->participant_type == 'mahasiswa')
                                    {{ $absensi->participant->nim }}
                                @else
                                    {{ $absensi->participant->nip }}
                                @endif
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="d-none d-md-inline">{{ $absensi->scan_time->format('d/m/Y H:i:s') }}</span>
                            <span class="d-md-none">{{ $absensi->scan_time->format('d/m H:i') }}</span>
                        </td>
                        <td class="d-none d-md-table-cell">
                            <span class="badge bg-success">{{ $absensi->status }}</span>
                        </td>
                        <td class="text-center">
                            <form action="{{ route('absensi.destroy', $absensi) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger btn-delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada data absensi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($absensis->hasPages())
        <div class="p-3">
            {{ $absensis->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    // SweetAlert for delete confirmation
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('.delete-form');

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data absensi yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Success message if exists
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif
</script>
@endsection
