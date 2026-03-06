@extends('layouts.admin')

@section('title', 'Data Mahasiswa')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">Data Mahasiswa</h5>
                    <a href="{{ route('mahasiswa.create') }}" class="btn btn-primary btn-sm">
                        <i data-feather="plus" style="width: 16px; height: 16px;"></i> Tambah Mahasiswa
                    </a>
                </div>
                <div class="table-responsive">
                    <table id="mahasiswa-table" class="table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Jurusan</th>
                                <th>Semester</th>
                                <th>Email</th>
                                <th>No HP</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mahasiswas as $mahasiswa)
                            <tr>
                                <td>{{ $mahasiswa->nim }}</td>
                                <td>{{ $mahasiswa->nama }}</td>
                                <td>{{ $mahasiswa->jurusan }}</td>
                                <td>{{ $mahasiswa->semester }}</td>
                                <td>{{ $mahasiswa->email }}</td>
                                <td>{{ $mahasiswa->no_hp ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('mahasiswa.qrcode', $mahasiswa) }}" class="btn btn-sm btn-info px-2 py-1" title="Lihat QR Code">
                                        <i data-feather="aperture" style="width: 12px; height: 12px;"></i>
                                    </a>
                                    <a href="{{ route('mahasiswa.edit', $mahasiswa) }}" class="btn btn-sm btn-warning px-2 py-1" title="Edit">
                                        <i data-feather="edit" style="width: 12px; height: 12px;"></i>
                                    </a>
                                    <form action="{{ route('mahasiswa.destroy', $mahasiswa) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger px-2 py-1 btn-delete" title="Hapus">
                                            <i data-feather="trash-2" style="width: 12px; height: 12px;"></i>
                                        </button>
                                    </form>
                                </td>
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
    // Initialize DataTable
    $(document).ready(function() {
        var table = $('#mahasiswa-table').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            },
            "pageLength": 10,
            "order": [[1, "asc"]],
            "responsive": true
        });

        // Reinitialize feather icons after DataTable draws
        table.on('draw', function() {
            feather.replace();
        });

        // Initialize feather icons
        feather.replace();
    });

    // SweetAlert for delete confirmation
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        const form = $(this).closest('.delete-form');

        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
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
