@extends('layouts.admin')

@section('title', 'Scan QR Code')

@section('content')
<style>
    #reader {
        border: 3px solid #667eea;
        border-radius: 10px;
        overflow: hidden;
    }
    #reader video {
        width: 100% !important;
        height: auto !important;
    }
    .scan-status {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(0,0,0,0.8);
        color: white;
        padding: 20px 40px;
        border-radius: 10px;
        z-index: 1000;
        display: none;
    }
</style>

<div class="row mb-3">
    <div class="col">
        <h4 class="mb-2"><i data-feather="camera"></i> Scan QR Code Absensi</h4>
        <p class="text-muted">Scan QR Code peserta untuk mencatat kehadiran</p>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0"><i data-feather="video"></i> Kamera Scanner</h5>
                    <button class="btn btn-sm btn-outline-primary" id="switchCamera" style="display: none;">
                        <i data-feather="repeat"></i> Ganti Kamera
                    </button>
                </div>

                <div class="position-relative">
                    <div id="reader" class="w-100"></div>
                    <div class="scan-status" id="scanStatus">
                        <i data-feather="clock"></i> Processing...
                    </div>
                </div>

                <div class="alert alert-info mt-3 mb-0" role="alert">
                    <i data-feather="info" style="width: 16px; height: 16px;"></i>
                    <strong>Petunjuk:</strong> Arahkan kamera ke QR Code peserta untuk scan otomatis
                </div>
            </div>
        </div>

        <div class="alert alert-warning mt-3" role="alert">
            <i data-feather="alert-triangle" style="width: 16px; height: 16px;"></i>
            Pastikan kamera memiliki izin akses dan QR Code dalam kondisi baik untuk hasil terbaik
        </div>

        <!-- Recent Scans -->
        <div class="card mt-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0"><i data-feather="clock"></i> Riwayat Scan Hari Ini</h6>
                    <span class="badge bg-primary" id="todayCount">0</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th class="d-none d-md-table-cell">Tipe</th>
                                <th class="d-none d-md-table-cell">ID</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody id="recent_scans">
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">Belum ada scan hari ini</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let html5QrCode;
    let recentScans = [];
    let isProcessing = false;
    let cameras = [];
    let currentCameraIndex = 0;

    // Initialize scanner on page load
    $(document).ready(function() {
        startScanner();
        loadTodayScans();
    });

    function startScanner() {
        html5QrCode = new Html5Qrcode("reader");

        Html5Qrcode.getCameras().then(devices => {
            if (devices && devices.length) {
                cameras = devices;

                // Show switch camera button if multiple cameras
                if (cameras.length > 1) {
                    $('#switchCamera').show();
                }

                // Try to use back camera first (usually better for QR scanning)
                let cameraId = cameras[0].id;
                for (let i = 0; i < cameras.length; i++) {
                    if (cameras[i].label.toLowerCase().includes('back') ||
                        cameras[i].label.toLowerCase().includes('rear')) {
                        cameraId = cameras[i].id;
                        currentCameraIndex = i;
                        break;
                    }
                }

                startCameraScanning(cameraId);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Kamera Tidak Ditemukan',
                    text: 'Tidak ada kamera yang tersedia pada perangkat ini'
                });
            }
        }).catch(err => {
            console.error('Error getting cameras:', err);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Gagal mengakses kamera. Pastikan izin kamera sudah diberikan.'
            });
        });
    }

    function startCameraScanning(cameraId) {
        html5QrCode.start(
            cameraId,
            {
                fps: 10,
                qrbox: { width: 250, height: 250 }
            },
            onScanSuccess,
            onScanFailure
        ).catch(err => {
            console.error('Error starting camera:', err);
        });
    }

    function onScanSuccess(decodedText, decodedResult) {
        if (isProcessing) return;

        isProcessing = true;
        $('#scanStatus').fadeIn();

        // Vibrate if supported
        if (navigator.vibrate) {
            navigator.vibrate(200);
        }

        processAbsensi(decodedText);
    }

    function onScanFailure(error) {
        // Ignore scan failures (normal when no QR in view)
    }

    function processAbsensi(qrCode) {
        $.ajax({
            url: '{{ route("scan.process") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                qr_code: qrCode
            },
            success: function(response) {
                $('#scanStatus').fadeOut();

                if (response.success) {
                    // Show success alert
                    Swal.fire({
                        icon: 'success',
                        title: 'Absensi Berhasil!',
                        html: `
                            <div class="text-start">
                                <p class="mb-2"><strong>Nama:</strong> ${response.data.nama}</p>
                                <p class="mb-2"><strong>Tipe:</strong> <span class="badge bg-${response.data.tipe === 'Mahasiswa' ? 'primary' : 'success'}">${response.data.tipe}</span></p>
                                <p class="mb-2"><strong>ID:</strong> ${response.data.id_peserta}</p>
                                <p class="mb-0"><strong>Waktu:</strong> ${response.data.waktu}</p>
                            </div>
                        `,
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        allowOutsideClick: false
                    }).then(() => {
                        isProcessing = false;
                    });

                    // Add to recent scans
                    addToRecentScans(response.data);

                    // Play success sound
                    playSuccessSound();
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Gagal!',
                        text: response.message,
                        timer: 3000,
                        timerProgressBar: true
                    }).then(() => {
                        isProcessing = false;
                    });
                }
            },
            error: function(xhr) {
                $('#scanStatus').fadeOut();
                const message = xhr.responseJSON?.message || 'Terjadi kesalahan saat memproses absensi';

                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: message,
                    timer: 3000,
                    timerProgressBar: true
                }).then(() => {
                    isProcessing = false;
                });
            }
        });
    }

    function addToRecentScans(data) {
        recentScans.unshift(data);
        if (recentScans.length > 10) recentScans.pop();
        updateRecentScansTable();
    }

    function updateRecentScansTable() {
        if (recentScans.length === 0) return;

        let html = '';
        recentScans.forEach((scan, index) => {
            const time = scan.waktu.split(' ')[1];
            html += `
                <tr>
                    <td class="ps-3">
                        <strong class="d-block">${scan.nama}</strong>
                        <small class="text-muted d-md-none">${scan.tipe} - ${scan.id_peserta}</small>
                    </td>
                    <td class="d-none d-md-table-cell">
                        <span class="badge bg-${scan.tipe === 'Mahasiswa' ? 'primary' : 'success'}">${scan.tipe}</span>
                    </td>
                    <td class="d-none d-md-table-cell">${scan.id_peserta}</td>
                    <td><small>${time}</small></td>
                </tr>
            `;
        });
        $('#recent_scans').html(html);
        $('#todayCount').text(recentScans.length);
    }

    function loadTodayScans() {
        // Could load from server, for now just count what we have
        $('#todayCount').text('0');
    }

    function playSuccessSound() {
        try {
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();

            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);

            oscillator.frequency.value = 1000;
            oscillator.type = 'sine';

            gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.15);

            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.15);
        } catch(e) {
            console.log('Audio not supported');
        }
    }

    // Switch camera button
    $('#switchCamera').on('click', function() {
        if (cameras.length < 2) return;

        html5QrCode.stop().then(() => {
            currentCameraIndex = (currentCameraIndex + 1) % cameras.length;
            startCameraScanning(cameras[currentCameraIndex].id);

            // Reinitialize feather icons
            feather.replace();
        });
    });

    // Stop camera when leaving page
    window.addEventListener('beforeunload', function() {
        if (html5QrCode) {
            html5QrCode.stop();
        }
    });

    // Initialize feather icons after page load
    feather.replace();
</script>
@endsection
