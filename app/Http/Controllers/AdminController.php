<?php

namespace App\Http\Controllers;

use App\Models\Lomba;
use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    // Dashboard utama admin.
    public function index()
    {
        return view('admin.dashboard');
    }

    // Menampilkan daftar mata lomba.
    public function kelolaLomba()
    {
        $lombas = Lomba::latest()->get();

        return view('admin.kelola_lomba', compact('lombas'));
    }

    // Menampilkan akun siswa saja, bukan akun admin.
    public function kelolaSiswa()
    {
        $siswas = User::where('role', 'siswa')->latest()->get();

        return view('admin.kelola_siswa', compact('siswas'));
    }

    // Menampilkan seluruh pendaftaran beserta data siswa dan lombanya.
    public function kelolaStatus()
    {
        $pendaftarans = Pendaftaran::with(['user', 'lomba'])->latest()->get();

        return view('admin.kelola_status', compact('pendaftarans'));
    }

    // CREATE: menambahkan mata lomba.
    public function storeLomba(Request $request)
    {
        $validated = $request->validate([
            'nama_lomba' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        Lomba::create($validated);

        return redirect()->route('admin.lomba')
            ->with('success', 'Mata lomba berhasil ditambahkan!');
    }

    // Menampilkan form edit lomba.
    public function editLomba($id)
    {
        $lomba = Lomba::findOrFail($id);

        return view('admin.edit_lomba', compact('lomba'));
    }

    // UPDATE: menyimpan perubahan mata lomba.
    public function updateLomba(Request $request, $id)
    {
        $lomba = Lomba::findOrFail($id);

        $validated = $request->validate([
            'nama_lomba' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        $lomba->update($validated);

        return redirect()->route('admin.lomba')
            ->with('success', 'Mata lomba berhasil diperbarui!');
    }

    // DELETE: menghapus mata lomba.
    public function destroyLomba($id)
    {
        $lomba = Lomba::findOrFail($id);
        $lomba->delete();

        return redirect()->route('admin.lomba')
            ->with('success', 'Mata lomba berhasil dihapus!');
    }

    // UPDATE: mengubah status pendaftaran.
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in([
                    'Dokumen dalam Tinjauan',
                    'Jadwal Briefing',
                    'Tahap Pelatihan',
                    'Final',
                    'Gagal',
                ]),
            ],
        ]);

        $pendaftaran = Pendaftaran::findOrFail($id);
        $pendaftaran->update(['status' => $validated['status']]);

        return redirect()->route('admin.status')
            ->with('success', 'Status pendaftaran berhasil diubah!');
    }

    // CREATE: membuat akun siswa dari panel admin.
    public function storeSiswa(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'siswa',
        ]);

        return redirect()->route('admin.siswa')
            ->with('success', 'Akun siswa berhasil dibuat!');
    }

    // Menampilkan form edit akun siswa.
    public function editSiswa($id)
    {
        $siswa = User::where('role', 'siswa')->findOrFail($id);

        return view('admin.edit_siswa', compact('siswa'));
    }

    // UPDATE: mengubah data akun siswa.
    public function updateSiswa(Request $request, $id)
    {
        $siswa = User::where('role', 'siswa')->findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($siswa->id),
            ],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $siswa->name = $validated['name'];
        $siswa->email = $validated['email'];

        if (!empty($validated['password'])) {
            $siswa->password = Hash::make($validated['password']);
        }

        $siswa->save();

        return redirect()->route('admin.siswa')
            ->with('success', 'Data siswa berhasil diperbarui!');
    }

    // DELETE: menghapus akun siswa, bukan akun admin.
    public function destroySiswa($id)
    {
        $siswa = User::where('role', 'siswa')->findOrFail($id);
        $siswa->delete();

        return redirect()->route('admin.siswa')
            ->with('success', 'Akun siswa berhasil dihapus!');
    }
}
