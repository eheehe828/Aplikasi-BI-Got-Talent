<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Peserta
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="status">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <p class="font-bold mb-1">Ada data yang perlu diperbaiki:</p>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- KOTAK 1: Form Pendaftaran -->
            <div class="p-6 bg-white shadow sm:rounded-lg border-t-4 border-blue-600">
                <h3 class="text-lg font-bold mb-4 text-gray-800">Formulir Pendaftaran Lomba</h3>

                <form action="{{ route('siswa.daftar') }}" method="POST">
                    @csrf

                    <div class="space-y-5">
                        <div>
                            <label for="lomba_id" class="block text-sm font-bold text-gray-700 mb-2">
                                Pilih Kategori Lomba *
                            </label>
                            <select id="lomba_id" name="lomba_id"
                                class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm w-full bg-gray-50"
                                required>
                                <option value="">-- Silakan Pilih --</option>
                                @foreach ($lombas as $lomba)
                                    <option value="{{ $lomba->id }}"
                                        @selected(old('lomba_id') == $lomba->id)>
                                        {{ $lomba->nama_lomba }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="kelas" class="block text-sm font-bold text-gray-700 mb-2">
                                Asal Kelas *
                            </label>
                            <input id="kelas" type="text" name="kelas" value="{{ old('kelas') }}"
                                placeholder="Contoh: X TKJ"
                                class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm w-full"
                                maxlength="50" required>
                        </div>

                        <div>
                            <label for="no_wa" class="block text-sm font-bold text-gray-700 mb-2">
                                No. WhatsApp Aktif *
                            </label>
                            <input id="no_wa" type="tel" name="no_wa" value="{{ old('no_wa') }}"
                                placeholder="Contoh: 081234567890"
                                class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm w-full"
                                maxlength="20" autocomplete="tel" required>
                            <p class="text-xs text-gray-500 mt-1">
                                Pastikan nomor aktif untuk undangan grup koordinasi.
                            </p>
                        </div>
                    </div>

                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-md shadow transition duration-150 mt-5">
                        Kirim Formulir Pendaftaran
                    </button>
                </form>
            </div>

            <!-- KOTAK 2: Tracking Status -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-bold mb-4 text-gray-800">Tracking Status Pendaftaran Saya</h3>

                @if ($riwayat->isEmpty())
                    <p class="text-gray-500 italic">
                        Anda belum mendaftar lomba apa pun. Silakan mendaftar melalui form di atas.
                    </p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($riwayat as $item)
                            <div class="relative overflow-hidden border border-gray-200 p-4 pl-6 rounded-lg shadow-sm hover:shadow-md transition bg-gray-50">
                                <!-- Aksen warna di kiri kartu -->
                                <div class="absolute left-0 top-0 bottom-0 w-1
                                    @if ($item->status === 'Jadwal Briefing')
                                        bg-blue-500
                                    @elseif ($item->status === 'Tahap Pelatihan')
                                        bg-purple-500
                                    @elseif ($item->status === 'Final')
                                        bg-green-500
                                    @elseif ($item->status === 'Gagal')
                                        bg-red-500
                                    @else
                                        bg-gray-400
                                    @endif
                                "></div>

                                <h4 class="font-bold text-lg text-blue-700 mb-1">
                                    {{ $item->lomba?->nama_lomba ?? 'Mata lomba tidak tersedia' }}
                                </h4>
                                <p class="text-sm text-gray-500 mb-3">
                                    Terdaftar pada: {{ $item->created_at?->format('d M Y') ?? '-' }}
                                </p>

                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="text-sm font-medium">Status Saat Ini:</span>
                                    <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-sm font-bold border border-indigo-200">
                                        {{ $item->status }}
                                    </span>
                                </div>

                                <div class="mt-3 text-sm text-gray-600 space-y-1">
                                    <p><span class="font-semibold">Kelas:</span> {{ $item->kelas }}</p>
                                    <p><span class="font-semibold">No. WhatsApp:</span> {{ $item->no_wa }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
