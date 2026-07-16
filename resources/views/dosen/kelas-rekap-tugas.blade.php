@extends('layouts.portal')

@section('title', 'Rekap Nilai Tugas')
@section('activeMenu', 'Kelas Saya')

@section('content')

    <div class="rekap-header bg-[#321270] dark:bg-gradient-to-r dark:from-indigo-950 dark:to-purple-900 rounded-xl px-8 py-6 mb-6 shadow-sm">
        <div class="flex items-center gap-2 mb-2">
            <span class="badge-kode text-xs font-semibold bg-white/15 text-white px-2.5 py-1 rounded">{{ $kelas->mataKuliah->kode_mk ?? '-' }}</span>
        </div>
        <h1 class="text-xl font-bold text-white">Rekap Nilai Tugas — {{ $kelas->kode_kelas }}</h1>
        <p class="text-sm text-blue-200 dark:text-purple-250 mt-1">{{ $kelas->mataKuliah->nama_mk ?? '-' }}</p>
    </div>

    <x-flash-message />

    {{-- TABS --}}
    @php
        $tabLinks = [
            'Beranda'      => ['url' => route('dosen.kelas-detail', $kelas->id), 'active' => request()->routeIs('dosen.kelas-detail')],
            'Absensi'      => ['url' => route('dosen.absensi.index', $kelas->id),  'active' => request()->routeIs('dosen.absensi.*')],
            'Materi'       => ['url' => route('dosen.kelas-materi', $kelas->id), 'active' => request()->routeIs('dosen.kelas-materi')],
            'Tugas'        => ['url' => route('dosen.kelas-tugas', $kelas->id),  'active' => request()->routeIs('dosen.kelas-tugas')],
            'Forum'        => ['url' => route('dosen.kelas-forum', $kelas->id),  'active' => request()->routeIs('dosen.kelas-forum')],
            'Penilaian' => ['url' => route('dosen.kelas-tugas.rekap', $kelas->id), 'active' => request()->routeIs('dosen.kelas-tugas.rekap')],
        ];
    @endphp
    <div class="no-print mb-5 flex items-center gap-1 overflow-x-auto rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 p-1.5 shadow-sm transition-colors duration-200">
        @foreach ($tabLinks as $label => $tab)
            <a href="{{ $tab['url'] }}"
            class="whitespace-nowrap rounded-xl px-4 py-2 text-xs font-bold transition
                {{ $tab['active']
                    ? 'bg-[#321270] dark:bg-purple-650 text-white shadow-sm shadow-purple-900/20'
                    : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700 hover:text-gray-800 dark:hover:text-white' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    @php
        $passingGrade = 60; // NO. 3: ambang batas nilai, sesuaikan kalau perlu
        $menungguCount = 0;
        foreach ($mahasiswaList as $mhs) {
            foreach ($tugasList as $t) {
                $p = $matrix[$mhs->id][$t->id] ?? null;
                if ($p && $p->status !== \App\Models\PengumpulanTugas::STATUS_BELUM_DIKUMPUL
                        && $p->status !== \App\Models\PengumpulanTugas::STATUS_DINILAI) {
                    $menungguCount++;
                }
            }
        }
    @endphp

    <div class="no-print flex items-center justify-between mb-4 gap-3 flex-wrap">
        <div class="flex items-center gap-3">
            @if ($menungguCount > 0)
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/40 px-3 py-1.5 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ $menungguCount }} submission menunggu dinilai
                </span>
            @endif
            <p class="text-sm text-gray-500 dark:text-slate-400">{{ $tugasList->count() }} tugas &middot; {{ $mahasiswaList->count() }} mahasiswa</p>
        </div>
    </div>

    @if ($tugasList->isEmpty())
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700 shadow-sm p-10 text-center text-sm text-gray-400 dark:text-slate-500 transition-colors duration-200">
            Belum ada tugas yang dibuat di kelas ini.
        </div>
    @else
        <div class="no-print mb-3 flex items-center justify-between gap-3 flex-wrap">
            <div class="relative max-w-xs w-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-350 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
                </svg>
                <input
                    type="text"
                    id="searchMahasiswa"
                    placeholder="Cari nama / NIM mahasiswa..."
                    class="w-full pl-9 pr-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-700 dark:text-slate-200 placeholder-gray-350 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-[#321270]/30 dark:focus:ring-purple-500/30 transition"
                >
            </div>

            <button
                type="button"
                onclick="exportRekapExcel()"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-white bg-[#321270] hover:bg-[#291060] dark:bg-purple-650 px-3.5 py-2 rounded-xl transition shrink-0"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export Excel
            </button>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden transition-colors duration-200">
            <div class="overflow-x-auto">
                <table id="rekapTable" class="w-full text-sm min-w-[720px]">
                    <thead>
                        <tr class="text-left text-gray-400 dark:text-slate-500 text-xs border-b border-gray-100 dark:border-slate-700 bg-gray-50/70 dark:bg-slate-900/30">
                            <th class="px-5 py-3 font-medium sticky left-0 bg-gray-50/70 dark:bg-slate-900/90 backdrop-blur-sm z-10 min-w-[180px]">Nama Mahasiswa</th>
                            @foreach ($tugasList as $t)
                                <th class="px-4 py-3 font-medium text-center min-w-[110px]">
                                    <a href="{{ route('dosen.tugas.submissions', [$kelas->id, $t->id]) }}" class="hover:text-[#321270] dark:hover:text-purple-400 hover:underline">
                                        {{ Str::limit($t->judul, 18) }}
                                    </a>
                                    @if ($t->bobot_nilai != 100)
                                        <p class="text-[10px] text-gray-300 dark:text-slate-550 font-normal mt-0.5">Bobot {{ $t->bobot_nilai }}%</p>
                                    @endif
                                </th>
                            @endforeach
                            <th class="px-4 py-3 font-medium text-center min-w-[90px] bg-purple-50/50 dark:bg-purple-950/40">Rata-rata</th>
                        </tr>
                    </thead>
                    <tbody id="mahasiswaTableBody" class="divide-y divide-gray-50 dark:divide-slate-700/50">
                        @forelse ($mahasiswaList as $mhs)
                            <tr
                                class="mahasiswa-row hover:bg-gray-50/50 dark:hover:bg-slate-900/20 transition duration-150"
                                data-search="{{ Str::lower($mhs->name . ' ' . ($mhs->nim ?? $mhs->email)) }}"
                            >
                                <td class="px-5 py-3 sticky left-0 bg-white dark:bg-slate-800 z-10 transition-colors duration-200">
                                    <p class="font-bold text-gray-800 dark:text-white">{{ $mhs->name }}</p>
                                    <p class="text-xs text-gray-400 dark:text-slate-500">{{ $mhs->nim ?? $mhs->email }}</p>
                                </td>
                                @foreach ($tugasList as $t)
                                    @php
                                        $p = $matrix[$mhs->id][$t->id] ?? null;
                                    @endphp
                                    <td class="px-4 py-3 text-center">
                                        @if (!$p || $p->status === \App\Models\PengumpulanTugas::STATUS_BELUM_DIKUMPUL)
                                            <span class="text-[11px] text-gray-300 dark:text-slate-600 italic">Blm kumpul</span>
                                        @elseif ($p->status === \App\Models\PengumpulanTugas::STATUS_DINILAI)
                                            {{-- NO. 3: highlight nilai di bawah passing grade --}}
                                            <span class="font-bold {{ $p->nilai < $passingGrade ? 'text-red-500 dark:text-red-400' : 'text-gray-850 dark:text-slate-200' }}">
                                                {{ $p->nilai }}
                                            </span>
                                        @else
                                            <span class="text-[11px] text-amber-600 dark:text-amber-400 font-semibold">Menunggu</span>
                                        @endif
                                    </td>
                                @endforeach
                                <td class="px-4 py-3 text-center bg-purple-50/30 dark:bg-purple-950/20">
                                    @if ($rataRata[$mhs->id] !== null)
                                        <span class="font-black {{ $rataRata[$mhs->id] < $passingGrade ? 'text-red-500 dark:text-red-400' : 'text-[#321270] dark:text-purple-300' }}">
                                            {{ $rataRata[$mhs->id] }}
                                        </span>
                                    @else
                                        <span class="text-[11px] text-gray-300 dark:text-slate-650">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $tugasList->count() + 2 }}" class="text-center text-gray-400 dark:text-slate-500 py-10 text-sm">
                                    Belum ada mahasiswa di kelas ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Pesan kalau hasil pencarian kosong --}}
            <p id="noSearchResult" class="hidden text-center text-gray-400 dark:text-slate-500 py-8 text-sm">
                Tidak ada mahasiswa yang cocok dengan pencarian.
            </p>
        </div>

        <p class="no-print text-xs text-gray-400 dark:text-slate-500 mt-3">
            Klik nama tugas di header tabel untuk membuka halaman penilaian tugas tersebut. Kolom "Rata-rata" dihitung tertimbang berdasarkan bobot tiap tugas yang sudah dinilai.
            Nilai berwarna merah menandakan nilai di bawah {{ $passingGrade }}.
        </p>

        <script>
            document.getElementById('searchMahasiswa')?.addEventListener('input', function (e) {
                const keyword = e.target.value.trim().toLowerCase();
                const rows = document.querySelectorAll('#mahasiswaTableBody .mahasiswa-row');
                let visibleCount = 0;

                rows.forEach(function (row) {
                    const match = row.dataset.search.includes(keyword);
                    row.classList.toggle('hidden', !match);
                    if (match) visibleCount++;
                });

                const noResult = document.getElementById('noSearchResult');
                if (noResult) {
                    noResult.classList.toggle('hidden', visibleCount !== 0);
                }
            });
        </script>
    @endif

    {{-- ==================== EXPORT: EXCEL ==================== --}}

    {{-- SheetJS dipakai untuk export Excel langsung dari tabel yang tampil, tanpa perlu route/controller baru --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <script>
        function exportRekapExcel() {
            const table = document.getElementById('rekapTable');
            if (!table) return;

            const aoa = [];

            // Header: ambil judul tugas saja, tanpa baris "Bobot X%"
            const headerCells = table.tHead.rows[0].cells;
            const headerRow = Array.from(headerCells).map(function (th) {
                const link = th.querySelector('a');
                return (link ? link.textContent : th.textContent).trim();
            });
            aoa.push(headerRow);

            // Body: kolom pertama ambil nama mahasiswa saja (tanpa email)
            Array.from(table.tBodies[0].rows).forEach(function (row) {
                if (row.classList.contains('hidden')) return; // skip yang lagi difilter search

                const cells = Array.from(row.cells);
                const dataRow = cells.map(function (td, idx) {
                    if (idx === 0) {
                        const namaEl = td.querySelector('p.font-bold');
                        return (namaEl ? namaEl.textContent : td.textContent).trim();
                    }
                    const text = td.textContent.trim();
                    const num = Number(text);
                    return text !== '' && !isNaN(num) ? num : text;
                });
                aoa.push(dataRow);
            });

            const ws = XLSX.utils.aoa_to_sheet(aoa);

            // Lebar kolom biar nggak kepotong pas dibuka di Excel
            ws['!cols'] = aoa[0].map((_, i) => ({ wch: i === 0 ? 26 : 14 }));

            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Rekap Nilai');

            const namaFile = 'Rekap-Nilai-Tugas-{{ $kelas->kode_kelas }}-{{ now()->format("Y-m-d") }}.xlsx';
            XLSX.writeFile(wb, namaFile);
        }

    </script>

@endsection