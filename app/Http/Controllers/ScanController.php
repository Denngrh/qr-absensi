<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Panitia;
use App\Models\Absensi;
use Illuminate\Http\Request;

class ScanController extends Controller
{
    public function index()
    {
        return view('scan.index');
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'qr_code' => 'required|string',
        ]);

        // Check if it's a mahasiswa QR code
        $mahasiswa = Mahasiswa::where('qr_code', $validated['qr_code'])->first();
        if ($mahasiswa) {
            return $this->recordAbsensi('mahasiswa', $mahasiswa->id, $mahasiswa);
        }

        // Check if it's a panitia QR code
        $panitia = Panitia::where('qr_code', $validated['qr_code'])->first();
        if ($panitia) {
            return $this->recordAbsensi('panitia', $panitia->id, $panitia);
        }

        return response()->json([
            'success' => false,
            'message' => 'QR Code tidak ditemukan. Pastikan data peserta sudah terdaftar.'
        ], 404);
    }

    private function recordAbsensi($type, $id, $participant)
    {
        // Check if already scanned today
        $today = date('Y-m-d');
        $existingAbsensi = Absensi::where([
            'participant_type' => $type,
            'participant_id' => $id,
        ])->whereDate('scan_time', $today)->first();

        if ($existingAbsensi) {
            return response()->json([
                'success' => false,
                'message' => $participant->nama . ' sudah melakukan absensi hari ini pada ' . $existingAbsensi->scan_time->format('H:i:s')
            ], 400);
        }

        $absensi = Absensi::create([
            'participant_type' => $type,
            'participant_id' => $id,
            'event_name' => null,
            'event_date' => null,
            'scan_time' => now(),
            'status' => 'hadir',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil dicatat!',
            'data' => [
                'nama' => $participant->nama,
                'tipe' => ucfirst($type),
                'id_peserta' => $type === 'mahasiswa' ? $participant->nim : $participant->nip,
                'email' => $participant->email,
                'waktu' => $absensi->scan_time->format('d/m/Y H:i:s')
            ]
        ]);
    }
}
