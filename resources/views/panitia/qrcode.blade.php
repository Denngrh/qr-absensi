@extends('layouts.app')

@section('title', 'QR Code - ' . $panitia->nama)

@section('content')
<div class="mb-4">
    <h2><i class="bi bi-qr-code"></i> QR Code Panitia</h2>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Informasi Panitia</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="150"><strong>NIP</strong></td>
                        <td>{{ $panitia->nip }}</td>
                    </tr>
                    <tr>
                        <td><strong>Nama</strong></td>
                        <td>{{ $panitia->nama }}</td>
                    </tr>
                    <tr>
                        <td><strong>Jabatan</strong></td>
                        <td>{{ $panitia->jabatan }}</td>
                    </tr>
                    <tr>
                        <td><strong>Email</strong></td>
                        <td>{{ $panitia->email }}</td>
                    </tr>
                    <tr>
                        <td><strong>No HP</strong></td>
                        <td>{{ $panitia->no_hp ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Kode QR</strong></td>
                        <td><code>{{ $panitia->qr_code }}</code></td>
                    </tr>
                </table>
                <a href="{{ route('panitia.index') }}" class="btn btn-secondary">
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
                <div id="qrcode" class="d-inline-block mb-3"></div>
                <p class="mt-3"><strong>{{ $panitia->nama }}</strong></p>
                <p class="text-muted">{{ $panitia->nip }} - {{ $panitia->jabatan }}</p>
                <button class="btn btn-primary" onclick="downloadQR()">
                    <i class="bi bi-download"></i> Download QR Code
                </button>
                <button class="btn btn-success" onclick="printQR()">
                    <i class="bi bi-printer"></i> Print QR Code
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    // Generate QR Code
    new QRCode(document.getElementById("qrcode"), {
        text: "{{ $panitia->qr_code }}",
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
        link.download = 'qrcode-{{ $panitia->nip }}-{{ $panitia->nama }}.png';
        link.href = url;
        link.click();
    }

    function printQR() {
        const printWindow = window.open('', '_blank');
        const canvas = document.querySelector('#qrcode canvas');
        const dataUrl = canvas.toDataURL('image/png');

        printWindow.document.write(`
            <html>
            <head>
                <title>QR Code - {{ $panitia->nama }}</title>
                <style>
                    body {
                        text-align: center;
                        font-family: Arial, sans-serif;
                        padding: 20px;
                    }
                    img {
                        margin: 20px auto;
                        display: block;
                    }
                    h2 { margin: 10px 0; }
                    p { margin: 5px 0; color: #666; }
                </style>
            </head>
            <body>
                <h2>{{ $panitia->nama }}</h2>
                <p>NIP: {{ $panitia->nip }}</p>
                <p>{{ $panitia->jabatan }}</p>
                <img src="${dataUrl}" />
                <p><strong>{{ $panitia->qr_code }}</strong></p>
            </body>
            </html>
        `);

        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 250);
    }
</script>
@endsection
