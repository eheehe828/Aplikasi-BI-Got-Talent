<?php

namespace App\Http\Controllers;

use App\Models\Lomba;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaController extends Controller
{
    public function index()
    {
        $lombas = Lomba::orderBy('nama_lomba')->get();

        $riwayat = Pendaftaran::with('lomba')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('siswa.dashboard', compact('lombas', 'riwayat'));
    }

    public function daftarLomba(Request $request)
    {
        $validated = $request->validate([
            'lomba_id' => ['required', 'integer', 'exists:lombas,id'],
            'kelas' => ['required', 'string', 'max:50'],
            // Nomor telepon harus berupa string agar angka 0 di awal tidak hilang.
            'no_wa' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
        ], [
            'lomba_id.exists' => 'Mata lomba yang dipilih tidak ditemukan.',
            'no_wa.regex' => 'Nomor WhatsApp hanya boleh berisi angka dan simbol telepon yang umum.',
        ]);

        $sudahDaftar = Pendaftaran::where('user_id', Auth::id())
            ->where('lomba_id', $validated['lomba_id'])
            ->exists();

        if ($sudahDaftar) {
            return back()
                ->withInput()
                ->with('error', 'Anda sudah terdaftar pada mata lomba ini.');
        }

        Pendaftaran::create([
            'user_id' => Auth::id(),
            'lomba_id' => $validated['lomba_id'],
            'kelas' => $validated['kelas'],
            'no_wa' => $validated['no_wa'],
            'status' => 'Dokumen dalam Tinjauan',
        ]);

        return redirect()->route('siswa.dashboard')
            ->with('success', 'Berhasil mendaftar! Silakan pantau status Anda di bawah.');
    }
}
