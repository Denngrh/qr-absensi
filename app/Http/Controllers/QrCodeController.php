<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QrCodeController extends Controller
{
    public function index()
    {
        $qrcodes = QrCode::latest()->paginate(10);
        return view('qrcode.index', compact('qrcodes'));
    }

    public function create()
    {
        return view('qrcode.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_name' => 'required|string|max:255',
            'event_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $validated['code'] = 'QR-' . strtoupper(Str::random(10));
        $validated['is_active'] = true;

        QrCode::create($validated);

        return redirect()->route('qrcode.index')
            ->with('success', 'QR Code berhasil dibuat.');
    }

    public function show(QrCode $qrcode)
    {
        return view('qrcode.show', compact('qrcode'));
    }

    public function edit(QrCode $qrcode)
    {
        return view('qrcode.edit', compact('qrcode'));
    }

    public function update(Request $request, QrCode $qrcode)
    {
        $validated = $request->validate([
            'event_name' => 'required|string|max:255',
            'event_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        $qrcode->update($validated);

        return redirect()->route('qrcode.index')
            ->with('success', 'QR Code berhasil diupdate.');
    }

    public function destroy(QrCode $qrcode)
    {
        $qrcode->delete();

        return redirect()->route('qrcode.index')
            ->with('success', 'QR Code berhasil dihapus.');
    }

    public function scan()
    {
        $mahasiswas = \App\Models\Mahasiswa::all();
        $panitias = \App\Models\Panitia::all();
        return view('qrcode.scan', compact('mahasiswas', 'panitias'));
    }

    public function processScan(Request $request)
    {
        $validated = $request->validate([
            'qr_code' => 'required|string',
            'participant_type' => 'required|in:mahasiswa,panitia',
            'participant_id' => 'required|integer',
        ]);

        $qrCode = QrCode::where('code', $validated['qr_code'])->first();

        if (!$qrCode) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code tidak ditemukan.'
            ], 404);
        }

        if (!$qrCode->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code sudah tidak aktif.'
            ], 400);
        }

        // Check if already scanned
        $existingAbsensi = \App\Models\Absensi::where([
            'qr_code_id' => $qrCode->id,
            'participant_type' => $validated['participant_type'],
            'participant_id' => $validated['participant_id'],
        ])->first();

        if ($existingAbsensi) {
            return response()->json([
                'success' => false,
                'message' => 'Peserta sudah melakukan absensi untuk event ini.'
            ], 400);
        }

        $absensi = \App\Models\Absensi::create([
            'qr_code_id' => $qrCode->id,
            'participant_type' => $validated['participant_type'],
            'participant_id' => $validated['participant_id'],
            'scan_time' => now(),
            'status' => 'hadir',
        ]);

        $participant = $validated['participant_type'] === 'mahasiswa'
            ? \App\Models\Mahasiswa::find($validated['participant_id'])
            : \App\Models\Panitia::find($validated['participant_id']);

        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil dicatat.',
            'data' => [
                'nama' => $participant->nama,
                'event' => $qrCode->event_name,
                'waktu' => $absensi->scan_time->format('d/m/Y H:i:s')
            ]
        ]);
    }
}
