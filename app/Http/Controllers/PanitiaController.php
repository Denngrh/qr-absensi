<?php

namespace App\Http\Controllers;

use App\Models\Panitia;
use Illuminate\Http\Request;

class PanitiaController extends Controller
{
    public function index()
    {
        $panitias = Panitia::latest()->get();
        return view('panitia.index', compact('panitias'));
    }

    public function create()
    {
        return view('panitia.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip' => 'required|unique:panitias',
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'email' => 'required|email|unique:panitias',
            'no_hp' => 'nullable|string',
        ]);

        // Generate unique QR code
        $validated['qr_code'] = 'PNT-' . strtoupper(\Illuminate\Support\Str::random(10));

        Panitia::create($validated);

        return redirect()->route('panitia.index')
            ->with('success', 'Data panitia berhasil ditambahkan.');
    }

    public function show(Panitia $panitia)
    {
        return view('panitia.show', compact('panitia'));
    }

    public function edit(Panitia $panitia)
    {
        return view('panitia.edit', compact('panitia'));
    }

    public function update(Request $request, Panitia $panitia)
    {
        $validated = $request->validate([
            'nip' => 'required|unique:panitias,nip,' . $panitia->id,
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'email' => 'required|email|unique:panitias,email,' . $panitia->id,
            'no_hp' => 'nullable|string',
        ]);

        $panitia->update($validated);

        return redirect()->route('panitia.index')
            ->with('success', 'Data panitia berhasil diupdate.');
    }

    public function destroy(Panitia $panitia)
    {
        $panitia->delete();

        return redirect()->route('panitia.index')
            ->with('success', 'Data panitia berhasil dihapus.');
    }

    public function showQrCode(Panitia $panitia)
    {
        return view('panitia.qrcode', compact('panitia'));
    }
}
