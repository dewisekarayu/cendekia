@extends('layouts.portal')

@section('title', 'Edit Kehadiran Manual - Pertemuan ' . $absensi->pertemuan_ke)

@section('content')
<div class="space-y-6 max-w-7xl mx-auto p-4 sm:p-6 bg-slate-50/50 rounded-2xl border border-slate-100 shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200/60">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-800 flex items-center gap-3">
                <div class="p-2 bg-blue-50 text-blue-600 rounded-xl border border-blue-100 shadow-sm">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <span>Edit Kehadiran Manual</span>
            </h1>
            <p class="mt-2 text-sm text-slate-500 flex items-center flex-wrap gap-2">
                <span class="font-semibold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-md border border-blue-100/70">{{ $kelas->mataKuliah->nama_mk }}</span>
                <span class="text-slate-400">•</span>
                <span class="font-medium text-slate-600">Kelas {{ $kelas->kode_kelas }}</span>
                <span class="text-slate-400">•</span>
                <span class="bg-slate-100 text-slate-700 px-2.5 py-1 rounded-md font-medium">Pertemuan {{ $absensi->pertemuan_ke }}</span>
            </p>
        </div>
        <div class="flex items-center">
            <a href="{{ route('dosen.absensi.show', ['kelasId' => $kelas->id, 'absensiId' => $absensi->id]) }}" 
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl font-semibold text-sm shadow-sm hover:shadow transition-all duration-300">
                <svg class="w-4 4-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-sm flex items-center gap-3 hover:border-blue-200 transition-all duration-300">
            <div class="p-2.5 bg-sky-50 text-sky-600 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tanggal</p>
                <p class="text-sm sm:text-base font-bold text-slate-700 mt-0.5">{{ $absensi->tanggal->format('d M Y') }}</p>
            </div>
        </div>
        
        <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-sm flex items-center gap-3 hover:border-blue-200 transition-all duration-300">
            <div class="p-2.5 bg-indigo-50 text-indigo-600 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Waktu Pelaksanaan</p>
                <p class="text-sm sm:text-base font-bold text-slate-700 mt-0.5">{{ $absensi->jam_mulai }} - {{ $absensi->jam_selesai }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-sm flex items-center gap-3 hover:border-blue-200 transition-all duration-300">
            @php
                $statusClass = match($absensi->session_status) {
                    'draft' => 'bg-amber-50 text-amber-700 border-amber-200',
                    'buka' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    'tutup' => 'bg-rose-50 text-rose-700 border-rose-200',
                    default => 'bg-slate-50 text-slate-700 border-slate-200',
                };
                $statusLabel = match($absensi->session_status) {
                    'draft' => 'Draft',
                    'buka' => 'Dibuka / Aktif',
                    'tutup' => 'Ditutup',
                    default => 'Unknown',
                };
            @endphp
            <div class="p-2.5 bg-slate-50 text-slate-500 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Status Sesi</p>
                <span class="inline-flex items-center px-2.5 py-0.5 mt-1 rounded-md text-xs font-bold border {{ $statusClass }}">{{ $statusLabel }}</span>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-sm flex items-center gap-3 hover:border-blue-200 transition-all duration-300">
            <div class="p-2.5 bg-blue-50 text-blue-600 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Mahasiswa</p>
                <p class="text-sm sm:text-base font-bold text-blue-600 mt-0.5"><span class="text-xl font-extrabold">{{ $kelas->mahasiswa->count() }}</span> Orang</p>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-r from-amber-50/80 to-orange-50/50 rounded-xl border border-amber-200/70 p-4 shadow-sm">
        <div class="flex items-start gap-3">
            <div class="p-1.5 bg-amber-100 text-amber-700 rounded-lg mt-0.5">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="space-y-1">
                <h3 class="font-bold text-amber-900 text-sm sm:text-base">Panduan Pengisian Kehadiran Manual</h3>
                <ul class="text-xs sm:text-sm text-amber-800/90 space-y-1.5 list-disc pl-4">
                    <li>Pilih status kehadiran untuk masing-masing mahasiswa menggunakan komponen menu dropdown warna.</li>
                    <li>Gunakan kolom <span class="font-semibold">Keterangan</span> untuk menambahkan catatan khusus medis atau kedinasan (opsional).</li>
                    <li>Sistem secara default menetapkan status <span class="font-semibold text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded border border-rose-100">Alpha</span> apabila data presensi bernilai kosong.</li>
                    <li>Pastikan menekan tombol <span class="font-semibold">Simpan Perubahan</span> di bagian paling bawah untuk memperbarui database secara permanen.</li>
                </ul>
            </div>
        </div>
    </div>

    <form action="{{ route('dosen.absensi.updateAttendance', ['kelasId' => $kelas->id, 'absensiId' => $absensi->id]) }}" method="POST">
        @csrf

        <div class="bg-white rounded-xl shadow-sm border border-slate-200/80 overflow-hidden transition-all duration-300 hover:shadow-md">
            <div class="bg-gradient-to-r from-slate-50 to-blue-50/30 px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-4 bg-blue-500 rounded-full"></span>
                    <h2 class="text-lg font-bold text-slate-800">Daftar Presensi Kelas</h2>
                </div>
                <div class="text-xs font-semibold text-slate-400 bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200">
                    Gunakan Mode Landscape pada Mobile
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200/80">
                            <th class="px-5 py-3.5 text-center text-xs font-bold text-slate-500 uppercase tracking-wider w-16">No</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider min-w-[200px]">Nama Lengkap</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-40">NIM</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-56">Status Kehadiran</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider min-w-[240px]">Keterangan Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @php
                            $statusColors = [
                                'hadir' => 'bg-emerald-50 text-emerald-700 border-emerald-200 focus:ring-emerald-400',
                                'izin' => 'bg-sky-50 text-sky-700 border-sky-200 focus:ring-sky-400',
                                'sakit' => 'bg-amber-50 text-amber-700 border-amber-200 focus:ring-amber-400',
                                'alpha' => 'bg-rose-50 text-rose-700 border-rose-200 focus:ring-rose-400',
                            ];
                        @endphp

                        @foreach($kelas->mahasiswa as $index => $mahasiswa)
                            @php
                                $attendance = $absensi->absensiMahasiswa->where('mahasiswa_id', $mahasiswa->id)->first();
                                $currentStatus = $attendance->status ?? 'alpha';
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition duration-150 group">
                                <td class="px-5 py-4 text-center text-sm font-bold text-slate-400 group-hover:text-slate-700 transition">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center text-xs font-bold text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                                            {{ strtoupper(substr($mahasiswa->name, 0, 2)) }}
                                        </div>
                                        <div class="font-bold text-slate-700 group-hover:text-blue-600 transition">
                                            {{ $mahasiswa->name }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-500 tracking-medium">
                                    {{ $mahasiswa->nim ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="relative max-w-[190px]">
                                        <select name="attendance[{{ $mahasiswa->id }}]" 
                                                onchange="this.className='w-full pl-3 pr-8 py-2 text-sm font-bold rounded-xl border appearance-none focus:ring-2 focus:outline-none transition cursor-pointer ' + (this.value === 'hadir' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : this.value === 'izin' ? 'bg-sky-50 text-sky-700 border-sky-200' : this.value === 'sakit' ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-rose-50 text-rose-700 border-rose-200')"
                                                class="w-full pl-3 pr-8 py-2 text-sm font-bold rounded-xl border {{ $statusColors[$currentStatus] }} appearance-none focus:ring-2 focus:outline-none transition cursor-pointer">
                                            <option value="hadir" class="bg-white text-emerald-700 font-bold" {{ $currentStatus === 'hadir' ? 'selected' : '' }}>✓ Hadir</option>
                                            <option value="izin" class="bg-white text-sky-700 font-bold" {{ $currentStatus === 'izin' ? 'selected' : '' }}>✉ Izin</option>
                                            <option value="sakit" class="bg-white text-amber-700 font-bold" {{ $currentStatus === 'sakit' ? 'selected' : '' }}>✚ Sakit</option>
                                            <option value="alpha" class="bg-white text-rose-700 font-bold" {{ $currentStatus === 'alpha' ? 'selected' : '' }}>✗ Alpha</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400 group-hover:text-slate-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <input type="text" 
                                           name="keterangan[{{ $mahasiswa->id }}]" 
                                           value="{{ $attendance->keterangan ?? '' }}"
                                           placeholder="Tulis alasan izin, nomor surat sakit..."
                                           class="w-full px-3.5 py-2 text-sm text-slate-700 bg-slate-50 hover:bg-white focus:bg-white rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition duration-200"
                                           maxlength="255">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <div class="flex items-center gap-3 p-3 rounded-xl bg-white border border-slate-200/60 shadow-sm">
                <span class="w-3.5 h-3.5 rounded-full bg-emerald-500 ring-4 ring-emerald-100 flex-shrink-0"></span>
                <span class="text-xs sm:text-sm font-bold text-slate-600">Hadir (Present)</span>
            </div>
            <div class="flex items-center gap-3 p-3 rounded-xl bg-white border border-slate-200/60 shadow-sm">
                <span class="w-3.5 h-3.5 rounded-full bg-sky-500 ring-4 ring-sky-100 flex-shrink-0"></span>
                <span class="text-xs sm:text-sm font-bold text-slate-600">Izin (Permitted)</span>
            </div>
            <div class="flex items-center gap-3 p-3 rounded-xl bg-white border border-slate-200/60 shadow-sm">
                <span class="w-3.5 h-3.5 rounded-full bg-amber-500 ring-4 ring-amber-100 flex-shrink-0"></span>
                <span class="text-xs sm:text-sm font-bold text-slate-600">Sakit (Sick Leave)</span>
            </div>
            <div class="flex items-center gap-3 p-3 rounded-xl bg-white border border-slate-200/60 shadow-sm">
                <span class="w-3.5 h-3.5 rounded-full bg-rose-500 ring-4 ring-rose-100 flex-shrink-0"></span>
                <span class="text-xs sm:text-sm font-bold text-slate-600">Alpha (Absent)</span>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-4 border-t border-slate-200/60">
            <button type="reset" 
                    class="w-full sm:w-auto px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-sm shadow-sm transition-all duration-300 hover:scale-[1.01]">
                Reset Form
            </button>
            <button type="submit" 
                    class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl font-bold text-sm shadow-md hover:shadow-lg transition-all duration-300 hover:scale-[1.01]">
                Simpan Perubahan Data
            </button>
        </div>
    </form>
</div>
@endsection