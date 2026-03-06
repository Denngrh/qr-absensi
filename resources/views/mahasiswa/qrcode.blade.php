@extends('layouts.admin')

@section('title', 'QR Code - ' . $mahasiswa->nama)

@section('content')
<div class="mb-4">
    <h2><i class="bi bi-qr-code"></i> QR Code Mahasiswa</h2>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Informasi Mahasiswa</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="150"><strong>NIM</strong></td>
                        <td>{{ $mahasiswa->nim }}</td>
                    </tr>
                    <tr>
                        <td><strong>Nama</strong></td>
                        <td>{{ $mahasiswa->nama }}</td>
                    </tr>
                    <tr>
                        <td><strong>Jurusan</strong></td>
                        <td>{{ $mahasiswa->jurusan }}</td>
                    </tr>
                    <tr>
                        <td><strong>Semester</strong></td>
                        <td>{{ $mahasiswa->semester }}</td>
                    </tr>
                    <tr>
                        <td><strong>Email</strong></td>
                        <td>{{ $mahasiswa->email }}</td>
                    </tr>
                    <tr>
                        <td><strong>No HP</strong></td>
                        <td>{{ $mahasiswa->no_hp ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Kode QR</strong></td>
                        <td><code>{{ $mahasiswa->qr_code }}</code></td>
                    </tr>
                </table>
                <a href="{{ route('mahasiswa.index') }}" class="btn btn-secondary">
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
                <p class="mt-3"><strong>{{ $mahasiswa->nama }}</strong></p>
                <p class="text-muted">{{ $mahasiswa->nim }} - {{ $mahasiswa->jurusan }}</p>
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
    // Generate QR Code
    new QRCode(document.getElementById("qrcode"), {
        text: "{{ $mahasiswa->qr_code }}",
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
        link.download = 'qrcode-{{ $mahasiswa->nim }}-{{ $mahasiswa->nama }}.png';
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
                <title>QR Code - {{ $mahasiswa->nama }}</title>
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
                <h2>{{ $mahasiswa->nama }}</h2>
                <p>NIM: {{ $mahasiswa->nim }}</p>
                <p>{{ $mahasiswa->jurusan }} - Semester {{ $mahasiswa->semester }}</p>
                <img src="${dataUrl}" />
                <p><strong>{{ $mahasiswa->qr_code }}</strong></p>
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
