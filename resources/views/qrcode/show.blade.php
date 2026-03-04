@extends('layouts.app')

@section('title', 'Detail QR Code')

@section('content')
<div class="mb-4">
    <h2><i class="bi bi-qr-code"></i> Detail QR Code</h2>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Informasi Event</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="150"><strong>Kode QR</strong></td>
                        <td><code>{{ $qrcode->code }}</code></td>
                    </tr>
                    <tr>
                        <td><strong>Nama Event</strong></td>
                        <td>{{ $qrcode->event_name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal</strong></td>
                        <td>{{ $qrcode->event_date->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Waktu</strong></td>
                        <td>{{ $qrcode->start_time }} - {{ $qrcode->end_time }}</td>
                    </tr>
                    <tr>
                        <td><strong>Lokasi</strong></td>
                        <td>{{ $qrcode->location ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Deskripsi</strong></td>
                        <td>{{ $qrcode->description ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status</strong></td>
                        <td>
                            <span class="badge bg-{{ $qrcode->is_active ? 'success' : 'secondary' }}">
                                {{ $qrcode->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </td>
                    </tr>
                </table>
                <a href="{{ route('qrcode.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">QR Code</h5>
            </div>
            <div class="card-body text-center">
                <div id="qrcode" class="d-inline-block"></div>
                <p class="mt-3"><strong>{{ $qrcode->event_name }}</strong></p>
                <p class="text-muted">Scan QR Code untuk absensi</p>
                <button class="btn btn-primary" onclick="downloadQR()">
                    <i class="bi bi-download"></i> Download QR Code
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    const qrcode = new QRCode(document.getElementById("qrcode"), {
        text: "{{ $qrcode->code }}",
        width: 300,
        height: 300,
        colorDark: "#000000",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });

    function downloadQR() {
        const canvas = document.querySelector('#qrcode canvas');
        const url = canvas.toDataURL('image/png');
        const link = document.createElement('a');
        link.download = 'qrcode-{{ $qrcode->code }}.png';
        link.href = url;
        link.click();
    }
</script>
@endsection
