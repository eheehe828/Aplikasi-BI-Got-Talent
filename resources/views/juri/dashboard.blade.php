<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard Juri</h2>
    </x-slot>

    <div class="py-12"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif

        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="border border-gray-300 p-3">Peserta</th>
                        <th class="border border-gray-300 p-3">Mata Lomba</th>
                        <th class="border border-gray-300 p-3">Status</th>
                        <th class="border border-gray-300 p-3">Penilaian</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendaftarans as $daftar)
                    <tr>
                        <td class="border border-gray-300 p-3">
                            {{ $daftar->user->name }}<br>
                            <span class="text-xs text-gray-500">{{ $daftar->kelas }}</span>
                        </td>
                        <td class="border border-gray-300 p-3 font-semibold">{{ $daftar->lomba->nama_lomba }}</td>
                        <td class="border border-gray-300 p-3">
                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-sm font-bold">{{ $daftar->status }}</span>
                        </td>
                        <td class="border border-gray-300 p-3">
                            <form action="{{ route('juri.nilai', $daftar->id) }}" method="POST" class="space-y-2">
                                @csrf
                                <label class="mr-3"><input type="radio" name="hasil" value="lolos" required> Lolos</label>
                                <label><input type="radio" name="hasil" value="tidak_lolos"> Tidak Lolos</label>
                                <textarea name="catatan_juri" rows="2" class="w-full border-gray-300 rounded-md text-sm"
                                    placeholder="Catatan (wajib jika tidak lolos)">{{ $daftar->catatan_juri }}</textarea>
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">Simpan</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="p-4 text-center text-gray-500">Belum ada pendaftaran.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div></div>
</x-app-layout>
