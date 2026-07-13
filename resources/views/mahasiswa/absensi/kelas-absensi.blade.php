@extends('layouts.portal')

@section('title', 'Presensi - ' . $kelas->mataKuliah->nama_mk)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Presensi Kelas
            </h1>
            <p class="mt-1 text-gray-500">{{ $kelas->mataKuliah->nama_mk }} <span class="font-semibold">({{ $kelas->kode_kelas }})</span></p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('mahasiswa.absensi.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
            <a href="{{ route('mahasiswa.absensi.show', $kelas->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Riwayat
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-start gap-3">
            <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
            <p class="font-semibold text-green-900">{{ session('success') }}</p>
        </div>
    @endif
    @if(session('warning'))
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 flex items-start gap-3">
            <svg class="w-5 h-5 text-yellow-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.487 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
            <p class="font-semibold text-yellow-900">{{ session('warning') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main -->
        <div class="lg:col-span-2">
            @if($absensiAktif)
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl border border-green-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4 text-white">
                        <h2 class="text-lg font-semibold flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                            Sesi Presensi Aktif
                        </h2>
                    </div>

                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-4 gap-4">
                            <div class="text-center">
                                <p class="text-gray-600 text-sm font-medium mb-2">Pertemuan</p>
                                <p class="text-4xl font-bold text-green-600">{{ $absensiAktif->pertemuan_ke }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-gray-600 text-sm font-medium mb-2">Tanggal</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $absensiAktif->tanggal->format('d M') }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-gray-600 text-sm font-medium mb-2">Mulai</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $absensiAktif->jam_mulai }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-gray-600 text-sm font-medium mb-2">Selesai</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $absensiAktif->jam_selesai }}</p>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg p-4">
                            @if($sudahAbsen)
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-green-900">Anda Sudah Presensi</p>
                                        <p class="text-sm text-green-700">Status: <strong>{{ $sudahAbsen->getStatusLabel() }}</strong></p>
                                        @if($sudahAbsen->waktu_absensi)
                                            <p class="text-xs text-green-600 mt-1">{{ $sudahAbsen->waktu_absensi->format('H:i:s') }}</p>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0 animate-pulse">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-blue-900">Sesi Masih Terbuka</p>
                                        <p class="text-sm text-blue-700">Lakukan presensi sekarang jika Anda hadir</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if(!$sudahAbsen)
                            <form action="{{ route('mahasiswa.absensi.masuk', ['kelasId' => $kelas->id, 'absensiId' => $absensiAktif->id]) }}" method="POST" x-data="{ status: 'hadir' }">
                                @csrf

                                <label class="block text-sm font-semibold text-gray-700 mb-3">Pilih Status Kehadiran</label>
                                <div class="grid grid-cols-3 gap-3 mb-4">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="status" value="hadir" x-model="status" class="peer sr-only">
                                        <div class="text-center py-3 rounded-xl border-2 border-gray-200 peer-checked:border-green-500 peer-checked:bg-green-50 transition">
                                            <svg class="w-6 h-6 mx-auto mb-1 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                            <span class="text-sm font-semibold text-gray-800">Hadir</span>
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="status" value="izin" x-model="status" class="peer sr-only">
                                        <div class="text-center py-3 rounded-xl border-2 border-gray-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition">
                                            <svg class="w-6 h-6 mx-auto mb-1 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                            <span class="text-sm font-semibold text-gray-800">Izin</span>
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="status" value="sakit" x-model="status" class="peer sr-only">
                                        <div class="text-center py-3 rounded-xl border-2 border-gray-200 peer-checked:border-yellow-500 peer-checked:bg-yellow-50 transition">
                                            <svg class="w-6 h-6 mx-auto mb-1 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" /></svg>
                                            <span class="text-sm font-semibold text-gray-800">Sakit</span>
                                        </div>
                                    </label>
                                </div>

                                <div x-show="status === 'izin' || status === 'sakit'" x-cloak class="mb-4">
                                    <label for="keterangan" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Keterangan <span class="text-red-500">*</span>
                                    </label>
                                    <textarea id="keterangan" name="keterangan" rows="2" :required="status === 'izin' || status === 'sakit'"
                                        placeholder="Jelaskan alasan izin/sakit Anda..."
                                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-none @error('keterangan') border-red-500 @enderror"></textarea>
                                    @error('keterangan')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <button type="submit" class="w-full font-bold py-4 rounded-xl transition shadow-lg hover:shadow-xl flex items-center justify-center gap-2 text-lg text-white"
                                    :class="{
                                        'bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700': status === 'hadir',
                                        'bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700': status === 'izin',
                                        'bg-gradient-to-r from-yellow-600 to-amber-600 hover:from-yellow-700 hover:to-amber-700': status === 'sakit',
                                    }">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span x-text="status === 'hadir' ? 'PRESENSI / ABSEN MASUK' : (status === 'izin' ? 'KIRIM IZIN' : 'KIRIM SAKIT')"></span>
                                </button>
                            </form>
                            <p class="text-center text-gray-500 text-sm mt-2">Pilih status kehadiran Anda lalu kirim</p>
                        @endif
                    </div>
                </div>
            @else
                <div class="bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl border border-gray-200 p-12 text-center">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Tidak Ada Sesi Presensi Aktif</h3>
                    <p class="text-gray-600">Sesi presensi belum dibuka oleh dosen untuk hari ini. Silakan cek kembali nanti.</p>
                </div>
            @endif

            <!-- Info Kelas -->
            <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Informasi Kelas
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600 text-sm font-medium mb-1">Pengajar</p>
                        <p class="font-semibold text-gray-900">{{ $kelas->dosen->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-medium mb-1">Ruangan</p>
                        <p class="font-semibold text-gray-900">{{ $kelas->ruangan ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-medium mb-1">Hari</p>
                        <p class="font-semibold text-gray-900">{{ $kelas->hari ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-medium mb-1">Waktu</p>
                        <p class="font-semibold text-gray-900">{{ $kelas->jam_mulai }} - {{ $kelas->jam_selesai }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-4">
            @if($riwayatAbsensi->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Presensi Terakhir
                    </h3>
                    <div class="space-y-2">
                        @foreach($riwayatAbsensi->take(5) as $absensi)
                            @php $attendance = $absensi->absensiMahasiswa->first(); @endphp
                            <div class="flex items-center justify-between p-2 rounded-lg bg-gray-50">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">Pertemuan {{ $absensi->pertemuan_ke }}</p>
                                    <p class="text-xs text-gray-600">{{ $absensi->tanggal->format('d M Y') }}</p>
                                </div>
                                @if($attendance)
                                    <span @class([
                                        'inline-flex items-center px-2 py-1 rounded text-xs font-semibold',
                                        'bg-green-100 text-green-800' => $attendance->status === 'hadir',
                                        'bg-blue-100 text-blue-800' => $attendance->status === 'izin',
                                        'bg-yellow-100 text-yellow-800' => $attendance->status === 'sakit',
                                        'bg-red-100 text-red-800' => $attendance->status === 'alpha',
                                    ])>
                                        {{ $attendance->getStatusLabel() }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-red-100 text-red-800">Alpha</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-200 p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <h4 class="font-semibold text-blue-900 mb-2">Tips Presensi</h4>
                        <ul class="text-xs text-blue-800 space-y-1">
                            <li>✓ Presensi hanya saat sesi dibuka</li>
                            <li>✓ Satu kali presensi per sesi</li>
                            <li>✓ Status default: Hadir</li>
                            <li>✓ Cek riwayat secara berkala</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection