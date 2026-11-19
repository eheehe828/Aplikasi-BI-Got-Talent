<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JuriController extends Controller
{
    // Dashboard juri: daftar semua pendaftaran peserta.
    public function index()
    {
        $pendaftarans = Pendaftaran::with(['user', 'lomba'])->latest()->get();

        return view('juri.dashboard', compact('pendaftarans'));
    }

    // Juri memberi nilai (lolos / tidak lolos) + catatan.
    public function nilai(Request $request, $id)
    {
        $validated = $request->validate([
            'hasil' => ['required', Rule::in(['lolos', 'tidak_lolos'])],
            'catatan_juri' => ['required_if:hasil,tidak_lolos', 'nullable', 'string', 'max:1000'],
        ], [
            'catatan_juri.required_if' => 'Catatan wajib diisi jika peserta tidak lolos.',
        ]);

        $pendaftaran = Pendaftaran::findOrFail($id);
        $pendaftaran->update([
            'status' => $validated['hasil'] === 'lolos' ? 'Jadwal Briefing' : 'Gagal',
            'catatan_juri' => $validated['catatan_juri'] ?? null,
        ]);

        return redirect()->route('juri.dashboard')
            ->with('success', 'Penilaian berhasil disimpan!');
    }
}
