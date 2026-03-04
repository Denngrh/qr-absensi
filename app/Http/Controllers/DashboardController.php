<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMahasiswa = \App\Models\Mahasiswa::count();
        $totalPanitia = \App\Models\Panitia::count();
        $totalQrCode = \App\Models\Mahasiswa::whereNotNull('qr_code')->count() + \App\Models\Panitia::whereNotNull('qr_code')->count();
        $totalAbsensi = \App\Models\Absensi::count();

        $recentAbsensi = \App\Models\Absensi::with(['mahasiswa', 'panitia'])->orderBy('scan_time', 'desc')->take(10)->get();

        return view('dashboard', compact('totalMahasiswa', 'totalPanitia', 'totalQrCode', 'totalAbsensi', 'recentAbsensi'));
    }
}
