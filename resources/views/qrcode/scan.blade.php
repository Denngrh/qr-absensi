@extends('layouts.app')

@section('title', 'Scan QR Code')

@section('content')
<div class="mb-4">
    <h2><i class="bi bi-camera"></i> Scan QR Code</h2>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Scan QR Code</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="qr_code_input" class="form-label">Masukkan Kode QR <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="qr_code_input" placeholder="QR-XXXXXXXXXX" autofocus>
                    <small class="text-muted">Scan QR Code atau ketik kode secara manual</small>
                </div>
                <div class="mb-3">
                    <label for="participant_type" class="form-label">Tipe Peserta <span class="text-danger">*</span></label>
                    <select class="form-select" id="participant_type">
                        <option value="">Pilih Tipe</option>
                        <option value="mahasiswa">Mahasiswa</option>
                        <option value="panitia">Panitia</option>
                    </select>
                </div>
                <div class="mb-3" id="participant_select_container" style="display: none;">
                    <label for="participant_id" class="form-label">Pilih Peserta <span class="text-danger">*</span></label>
                    <select class="form-select" id="participant_id">
                        <option value="">Pilih Peserta</option>
                    </select>
                </div>
                <button type="button" class="btn btn-primary w-100" onclick="processScan()">
                    <i class="bi bi-check-circle"></i> Proses Absensi
                </button>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Hasil Scan</h5>
            </div>
            <div class="card-body" id="scan_result">
                <div class="text-center text-muted py-5">
                    <i class="bi bi-qr-code-scan" style="font-size: 3rem;"></i>
                    <p class="mt-3">Belum ada hasil scan</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const mahasiswas = @json($mahasiswas);
    const panitias = @json($panitias);

    $('#participant_type').on('change', function() {
        const type = $(this).val();
        const select = $('#participant_id');
        select.empty().append('<option value="">Pilih Peserta</option>');

        if (type === 'mahasiswa') {
            mahasiswas.forEach(m => {
                select.append(`<option value="${m.id}">${m.nim} - ${m.nama}</option>`);
            });
            $('#participant_select_container').show();
        } else if (type === 'panitia') {
            panitias.forEach(p => {
                select.append(`<option value="${p.id}">${p.nip} - ${p.nama}</option>`);
            });
            $('#participant_select_container').show();
        } else {
            $('#participant_select_container').hide();
        }
    });

    function processScan() {
        const qrCode = $('#qr_code_input').val();
        const participantType = $('#participant_type').val();
        const participantId = $('#participant_id').val();

        if (!qrCode || !participantType || !participantId) {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian!',
                text: 'Semua field harus diisi!'
            });
            return;
        }

        $.ajax({
            url: '{{ route("qrcode.process") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                qr_code: qrCode,
                participant_type: participantType,
                participant_id: participantId
            },
            success: function(response) {
                if (response.success) {
                    $('#scan_result').html(`
                        <div class="alert alert-success">
                            <h5><i class="bi bi-check-circle"></i> Absensi Berhasil!</h5>
                            <hr>
                            <p class="mb-1"><strong>Nama:</strong> ${response.data.nama}</p>
                            <p class="mb-1"><strong>Event:</strong> ${response.data.event}</p>
                            <p class="mb-0"><strong>Waktu:</strong> ${response.data.waktu}</p>
                        </div>
                    `);

                    // Reset form
                    $('#qr_code_input').val('').focus();
                    $('#participant_type').val('');
                    $('#participant_id').val('');
                    $('#participant_select_container').hide();
                } else {
                    $('#scan_result').html(`
                        <div class="alert alert-danger">
                            <h5><i class="bi bi-x-circle"></i> Gagal!</h5>
                            <p class="mb-0">${response.message}</p>
                        </div>
                    `);
                }
            },
            error: function(xhr) {
                const message = xhr.responseJSON?.message || 'Terjadi kesalahan saat memproses absensi';
                $('#scan_result').html(`
                    <div class="alert alert-danger">
                        <h5><i class="bi bi-x-circle"></i> Error!</h5>
                        <p class="mb-0">${message}</p>
                    </div>
                `);
            }
        });
    }

    // Auto focus and submit on enter
    $('#qr_code_input').on('keypress', function(e) {
        if (e.which === 13 && $('#participant_type').val() && $('#participant_id').val()) {
            processScan();
        }
    });
</script>
@endsection
