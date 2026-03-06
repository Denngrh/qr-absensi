@extends('layouts.admin')

@section('title', 'Data Panitia')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">Data Panitia</h5>
                    <a href="{{ route('panitia.create') }}" class="btn btn-primary btn-sm">
                        <i data-feather="plus" style="width: 16px; height: 16px;"></i> Tambah Panitia
                    </a>
                </div>
                <div class="table-responsive">
                    <table id="panitia-table" class="table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>NIP</th>
                                <th>Nama</th>
                                <th>Jabatan</th>
                                <th>Email</th>
                                <th>No HP</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($panitias as $panitia)
                            <tr>
                                <td>{{ $panitia->nip }}</td>
                                <td>{{ $panitia->nama }}</td>
                                <td>{{ $panitia->jabatan }}</td>
                                <td>{{ $panitia->email }}</td>
                                <td>{{ $panitia->no_hp ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('panitia.qrcode', $panitia) }}" class="btn btn-sm btn-info px-2 py-1" title="Lihat QR Code">
                                        <i data-feather="aperture" style="width: 12px; height: 12px;"></i>
                                    </a>
                                    <a href="{{ route('panitia.edit', $panitia) }}" class="btn btn-sm btn-warning px-2 py-1" title="Edit">
                                        <i data-feather="edit" style="width: 12px; height: 12px;"></i>
                                    </a>
                                    <form action="{{ route('panitia.destroy', $panitia) }}" method="POST" class="d-inline delete-form">
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
        var table = $('#panitia-table').DataTable({
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
