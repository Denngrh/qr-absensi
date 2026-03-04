<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\QrCode;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $query = Absensi::with(['mahasiswa', 'panitia']);

        if ($request->has('date') && $request->date) {
            $query->whereDate('scan_time', $request->date);
        }

        if ($request->has('participant_type') && $request->participant_type) {
            $query->where('participant_type', $request->participant_type);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('mahasiswa', function($mq) use ($search) {
                    $mq->where('nama', 'like', '%' . $search . '%');
                })->orWhereHas('panitia', function($pq) use ($search) {
                    $pq->where('nama', 'like', '%' . $search . '%');
                });
            });
        }

        $absensis = $query->latest('scan_time')->paginate(20);

        return view('absensi.index', compact('absensis'));
    }

    public function export(Request $request)
    {
        $query = Absensi::with(['mahasiswa', 'panitia']);

        if ($request->has('date') && $request->date) {
            $query->whereDate('scan_time', $request->date);
        }

        $absensis = $query->latest('scan_time')->get();

        $pdf = Pdf::loadView('absensi.pdf', compact('absensis'));
        return $pdf->download('rekap-absensi-' . date('Y-m-d') . '.pdf');
    }

    public function destroy(Absensi $absensi)
    {
        $absensi->delete();
        return redirect()->route('absensi.index')->with('success', 'Data absensi berhasil dihapus!');
    }
}
