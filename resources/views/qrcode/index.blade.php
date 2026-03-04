@extends('layouts.app')

@section('title', 'Data QR Code')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-qr-code"></i> Data QR Code</h2>
    <a href="{{ route('qrcode.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Generate QR Code
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Event</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($qrcodes as $qrcode)
                    <tr>
                        <td><code>{{ $qrcode->code }}</code></td>
                        <td>{{ $qrcode->event_name }}</td>
                        <td>{{ $qrcode->event_date->format('d/m/Y') }}</td>
                        <td>{{ $qrcode->start_time }} - {{ $qrcode->end_time }}</td>
                        <td>{{ $qrcode->location ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $qrcode->is_active ? 'success' : 'secondary' }}">
                                {{ $qrcode->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('qrcode.show', $qrcode) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('qrcode.edit', $qrcode) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('qrcode.destroy', $qrcode) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Belum ada QR Code</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $qrcodes->links() }}
        </div>
    </div>
</div>
@endsection
