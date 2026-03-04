@extends('layouts.app')

@section('title', 'Data Panitia')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-person-badge"></i> Data Panitia</h2>
    <a href="{{ route('panitia.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Panitia
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
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
                    @forelse($panitias as $panitia)
                    <tr>
                        <td>{{ $panitia->nip }}</td>
                        <td>{{ $panitia->nama }}</td>
                        <td>{{ $panitia->jabatan }}</td>
                        <td>{{ $panitia->email }}</td>
                        <td>{{ $panitia->no_hp ?? '-' }}</td>
                        <td>
                            <a href="{{ route('panitia.qrcode', $panitia) }}" class="btn btn-sm btn-info" title="Lihat QR Code">
                                <i class="bi bi-qr-code"></i>
                            </a>
                            <a href="{{ route('panitia.edit', $panitia) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('panitia.destroy', $panitia) }}" method="POST" class="d-inline delete-form">
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
                        <td colspan="6" class="text-center text-muted">Belum ada data panitia</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $panitias->links() }}
        </div>
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
