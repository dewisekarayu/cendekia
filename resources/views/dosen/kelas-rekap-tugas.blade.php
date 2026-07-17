@extends('layouts.portal')

@section('title', 'Rekap Nilai Tugas')
@section('activeMenu', 'Kelas Saya')

@section('content')

    <style>
        @media print {
            /* 1. Sembunyikan SEMUA elemen layout utama bawaan portal (sidebar, navbar, footer) */
            aside, nav, .sidebar, .navbar, .no-print, [role="navigation"] {
                display: none !important;
            }

            /* 2. Paksa konten utama agar lepas dari grid/flex layout bawaan template */
            body, main, .content-wrapper, #app, .print-full-width {
                background: white !important;
                color: black !important;
                width: 100% !important;
                max-width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
                position: absolute !important;
                left: 0 !important;
                top: 0 !important;
                display: block !important;
            }

            /* 3. Atur format tabel agar memenuhi kertas landscape A4 */
            table {
                page-break-inside: auto;
                width: 100% !important;
                border-collapse: collapse !important;
            }
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            th, td {
                padding: 6px 4px !important;
                font-size: 10px !important;
                border: 1px solid #cbd5e1 !important;
                background: transparent !important;
                color: black !important;
                position: static !important;
            }
        }
    </style>

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
        $passingGrade = 60; 
        $menungguCount = 0;
        
        // Variabel pembantu untuk perhitungan Statistik Ringkas
        $validRataRata = collect($rataRata)->filter(fn($val) => $val !== null);
        $avgKelas = $validRataRata->count() > 0 ? $validRataRata->avg() : 0;
        $lulusCount = $validRataRata->filter(fn($val) => $val >= $passingGrade)->count();
        $persenLulus = $validRataRata->count() > 0 ? round(($lulusCount / $validRataRata->count()) * 100) : 0;
        $dibawahPGradeCount = $validRataRata->filter(fn($val) => $val < $passingGrade)->count();

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

    {{-- FITUR BARU 1: QUICK ANALYTICS CARDS --}}
    @if (!$tugasList->isEmpty())
        <div class="no-print grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">
            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-gray-100 dark:border-slate-700 shadow-sm flex flex-col justify-between">
                <p class="text-xs text-gray-400 dark:text-slate-500 font-medium uppercase tracking-wider">Rata-rata Kelas</p>
                <div class="flex items-baseline gap-2 mt-2">
                    <p class="text-2xl font-black text-gray-800 dark:text-white">{{ number_format($avgKelas, 1) }}</p>
                    <p class="text-xs text-gray-400">/ 100</p>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-gray-100 dark:border-slate-700 shadow-sm flex flex-col justify-between">
                <p class="text-xs text-gray-400 dark:text-slate-500 font-medium uppercase tracking-wider">Tingkat Kelulusan (&ge;{{ $passingGrade }})</p>
                <div class="flex items-baseline gap-2 mt-2">
                    <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400">{{ $persenLulus }}%</p>
                    <p class="text-xs text-gray-400">({{ $lulusCount }} dari {{ $validRataRata->count() }} mhs)</p>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-gray-100 dark:border-slate-700 shadow-sm flex flex-col justify-between">
                <p class="text-xs text-gray-400 dark:text-slate-500 font-medium uppercase tracking-wider">Perlu Evaluasi (<{{ $passingGrade }})</p>
                <div class="flex items-baseline gap-2 mt-2">
                    <p class="text-2xl font-black {{ $dibawahPGradeCount > 0 ? 'text-red-500 dark:text-red-400' : 'text-gray-800 dark:text-white' }}">
                        {{ $dibawahPGradeCount }}
                    </p>
                    <p class="text-xs text-gray-400">mahasiswa</p>
                </div>
            </div>
        </div>
    @endif

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
            <div class="flex items-center gap-2 flex-grow max-w-xl w-full">
                {{-- Input Cari Nama/NIM --}}
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

                {{-- FITUR BARU 2: DROPDOWN FILTER STATUS NILAI --}}
                <div class="w-48">
                    <select 
                        id="filterStatus" 
                        class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-[#321270]/30 dark:focus:ring-purple-500/30 transition"
                    >
                        <option value="all">Semua Mahasiswa</option>
                        <option value="lulus">Lulus (Rata-rata &ge; {{ $passingGrade }})</option>
                        <option value="tidak_lulus">Belum Lulus (Rata-rata < {{ $passingGrade }})</option>
                        <option value="belum_lengkap">Belum Kumpul Semua</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-2">
                {{-- FITUR BARU 4: BUTTON PRINT / CETAK PDF --}}
                <button type="button" onclick="printKhususRekap()" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-700 dark:text-slate-200 bg-gray-100 hover:bg-gray-200 dark:bg-slate-700 dark:hover:bg-slate-650 px-3.5 py-2 rounded-xl transition shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak PDF
                </button>

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
        </div>

        <div class="print-full-width bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden transition-colors duration-200">
            <div class="overflow-x-auto">
                <table id="rekapTable" class="w-full text-sm min-w-[720px]">
                    <thead>
                        <tr class="text-left text-gray-400 dark:text-slate-500 text-xs border-b border-gray-100 dark:border-slate-700 bg-gray-50/70 dark:bg-slate-900/30">
                            <th class="px-5 py-3 font-medium sticky left-0 bg-gray-50/70 dark:bg-slate-900/90 backdrop-blur-sm z-10 min-w-[180px]">Nama Mahasiswa</th>
                            @foreach ($tugasList as $t)
                                <th class="px-4 py-3 font-medium text-center min-w-[110px]">
                                    <a href="{{ route('dosen.tugas.submissions', [$kelas->id, $t->id]) }}" class="no-print hover:text-[#321270] dark:hover:text-purple-400 hover:underline">
                                        {{ Str::limit($t->judul, 18) }}
                                    </a>
                                    {{-- Judul Plain Text khusus saat cetak PDF agar link tidak rusak --}}
                                    <span class="hidden print:inline">{{ $t->judul }}</span>
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
                            @php
                                // Mengumpulkan status kelengkapan data per mahasiswa untuk filter JS
                                $hasBelumKumpul = false;
                                foreach($tugasList as $t) {
                                    $checkP = $matrix[$mhs->id][$t->id] ?? null;
                                    if(!$checkP || $checkP->status === \App\Models\PengumpulanTugas::STATUS_BELUM_DIKUMPUL) {
                                        $hasBelumKumpul = true;
                                        break;
                                    }
                                }
                                $statusNilai = ($rataRata[$mhs->id] !== null && $rataRata[$mhs->id] >= $passingGrade) ? 'lulus' : 'tidak_lulus';
                            @endphp
                            <tr
                                class="mahasiswa-row hover:bg-gray-50/50 dark:hover:bg-slate-900/20 transition duration-150"
                                data-search="{{ Str::lower($mhs->name . ' ' . ($mhs->nim ?? $mhs->email)) }}"
                                data-kelulusan="{{ $statusNilai }}"
                                data-lengkap="{{ $hasBelumKumpul ? 'belum_lengkap' : 'lengkap' }}"
                            >
                                <td class="px-5 py-3 sticky left-0 bg-white dark:bg-slate-800 z-10 transition-colors duration-200">
                                    <p class="font-bold text-gray-800 dark:text-white nama-mhs-text">{{ $mhs->name }}</p>
                                    <p class="text-xs text-gray-400 dark:text-slate-500 nim-mhs-text">{{ $mhs->nim ?? $mhs->email }}</p>
                                </td>
                                @foreach ($tugasList as $t)
                                    @php
                                        $p = $matrix[$mhs->id][$t->id] ?? null;
                                    @endphp
                                    <td class="px-4 py-3 text-center">
                                        @if (!$p || $p->status === \App\Models\PengumpulanTugas::STATUS_BELUM_DIKUMPUL)
                                            <span class="text-[11px] text-gray-300 dark:text-slate-600 italic">Blm kumpul</span>
                                        @elseif ($p->status === \App\Models\PengumpulanTugas::STATUS_DINILAI)
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
                Tidak ada mahasiswa yang cocok dengan kriteria pencarian dan filter.
            </p>
        </div>

        <p class="no-print text-xs text-gray-400 dark:text-slate-500 mt-3">
            Klik nama tugas di header tabel untuk membuka halaman penilaian tugas tersebut. Kolom "Rata-rata" dihitung tertimbang berdasarkan bobot tiap tugas yang sudah dinilai.
            Nilai berwarna merah menandakan nilai di bawah {{ $passingGrade }}.
        </p>

        {{-- JAVASCRIPT FILTER KELAS --}}
        <script>
            const searchInput = document.getElementById('searchMahasiswa');
            const filterSelect = document.getElementById('filterStatus');

            function applyFilter() {
                const keyword = searchInput ? searchInput.value.trim().toLowerCase() : '';
                const filterValue = filterSelect ? filterSelect.value : 'all';
                const rows = document.querySelectorAll('#mahasiswaTableBody .mahasiswa-row');
                let visibleCount = 0;

                rows.forEach(function (row) {
                    const matchSearch = row.dataset.search.includes(keyword);
                    let matchFilter = true;

                    if (filterValue === 'lulus') {
                        matchFilter = (row.dataset.kelulusan === 'lulus');
                    } else if (filterValue === 'tidak_lulus') {
                        matchFilter = (row.dataset.kelulusan === 'tidak_lulus');
                    } else if (filterValue === 'belum_lengkap') {
                        matchFilter = (row.dataset.lengkap === 'belum_lengkap');
                    }

                    const isVisible = matchSearch && matchFilter;
                    row.classList.toggle('hidden', !isVisible);
                    
                    if (isVisible) visibleCount++;
                });

                const noResult = document.getElementById('noSearchResult');
                if (noResult) {
                    noResult.classList.toggle('hidden', visibleCount !== 0);
                }
            }

            if(searchInput) searchInput.addEventListener('input', applyFilter);
            if(filterSelect) filterSelect.addEventListener('change', applyFilter);
        </script>
    @endif

    {{-- ==================== EXPORT: EXCEL ==================== --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <script>
        function exportRekapExcel() {
            const table = document.getElementById('rekapTable');
            if (!table) return;

            const aoa = [];

            // 1. Ambil nama tugas versi LENGKAP dari array PHP (via backend object injection ke JS)
            // Kita buat mapping judul tugas asli berdasarkan urutan kolom
            const judulTugasAsli = [
                @foreach ($tugasList as $t)
                    "{{($t->judul) ?? $t->judul }}",
                @endforeach
            ];

            // 2. Susun Header Excel
            const headerRow = ["Nama Mahasiswa", "NIM / Email"];
            judulTugasAsli.forEach(function(judul) {
                headerRow.push(judul);
            });
            headerRow.push("Rata-rata"); // Kolom terakhir
            aoa.push(headerRow);

            // 3. Susun Data Body Excel
            Array.from(table.tBodies[0].rows).forEach(function (row) {
                if (row.classList.contains('hidden')) return; // Lewati data yang sedang terfilter pencarian

                const cells = Array.from(row.cells);
                const dataRow = [];

                cells.forEach(function (td, idx) {
                    if (idx === 0) {
                        // Ambil Nama dan NIM/Email secara terpisah
                        const namaEl = td.querySelector('.nama-mhs-text');
                        const nimEl = td.querySelector('.nim-mhs-text');
                        
                        const namaText = namaEl ? namaEl.textContent.trim() : td.textContent.trim();
                        const identitasText = nimEl ? nimEl.textContent.trim() : '';

                        dataRow.push(namaText);
                        dataRow.push(identitasText);
                    } else if (idx < cells.length - 1) {
                        // Kolom Nilai Tugas
                        const text = td.textContent.trim();
                        const num = Number(text);
                        
                        // Jika teks berisi nilai angka (0-100), masukkan sebagai number agar bisa di-sum/average di Excel
                        dataRow.push(text !== '' && !isNaN(num) ? num : text);
                    } else {
                        // Kolom Rata-rata paling kanan
                        const text = td.textContent.trim();
                        const num = Number(text);
                        dataRow.push(text !== '' && !isNaN(num) ? num : text);
                    }
                });
                aoa.push(dataRow);
            });

            // 4. Proses Pembuatan Sheet Excel via SheetJS
            const ws = XLSX.utils.aoa_to_sheet(aoa);

            // Set lebar kolom otomatis agar tidak terpotong (wch = width character)
            ws['!cols'] = aoa[0].map((_, i) => {
                if (i === 0) return { wch: 25 }; // Kolom Nama
                if (i === 1) return { wch: 18 }; // Kolom NIM / Email
                return { wch: 22 };              // Kolom Judul Tugas (diberi space lebih lebar agar muat judul panjang)
            });

            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Rekap Nilai');

            // Beri nama file dinamis sesuai kode kelas dan tanggal hari ini
            const namaFile = 'Rekap-Nilai-Tugas-{{ $kelas->kode_kelas }}-{{ now()->format("Y-m-d") }}.xlsx';
            XLSX.writeFile(wb, namaFile);
        }

        function printKhususRekap() {
            // 1. Ambil html dari header dan tabel rekap saja
            const headerHTML = document.querySelector('.rekap-header').innerHTML;
            const tableHTML = document.getElementById('rekapTable').outerHTML;

            // 2. Buka window/tab baru di browser secara invisible untuk cetak
            const winPrint = window.open('', '', 'left=0,top=0,width=900,height=700,toolbar=0,scrollbars=0,status=0');
            
            // 3. Tulis struktur HTML murni tanpa gangguan CSS layout portal Anda
            winPrint.document.write(`
                <html>
                <head>
                    <title>Cetak Rekap Nilai Tugas</title>
                    <style>
                        body { font-family: sans-serif; padding: 20px; color: #333; background: #fff; }
                        
                        /* Styling Header Tempel */
                        .header-print { 
                            background-color: #321270; 
                            color: white; 
                            padding: 20px; 
                            border-radius: 8px; 
                            margin-bottom: 20px;
                        }
                        .header-print h1 { margin: 0; font-size: 18px; }
                        .header-print p { margin: 5px 0 0 0; font-size: 13px; opacity: 0.8; }
                        .badge-kode { background: rgba(255,255,255,0.2); padding: 2px 6px; border-radius: 4px; font-size: 11px; font-weight: bold; }
                        
                        /* Styling Tabel Utama agar Pas di Kertas */
                        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 11px; }
                        th, td { border: 1px solid #cbd5e1; padding: 8px 6px; text-align: center; }
                        th { background-color: #f8fafc; font-weight: 600; color: #475569; }
                        td:first-child, th:first-child { text-align: left; font-weight: bold; }
                        
                        /* Utilitas teks */
                        .font-bold { font-weight: bold; }
                        .font-black { font-weight: 900; }
                        .text-red-500 { color: #ef4444 !important; }
                        .text-amber-600 { color: #d97706 !important; font-weight: 650; }
                        .italic { font-style: italic; color: #94a3b8; }
                        
                        /* Hilangkan link/tombol action di dalam tabel saat diprint */
                        a { color: inherit; text-decoration: none; pointer-events: none; }
                        .no-print, p.no-print { display: none !important; }
                        
                        @media print {
                            @page { size: landscape; margin: 10mm; }
                            body { padding: 0; }
                        }
                    </style>
                </head>
                <body>
                    <div class="header-print">
                        ${headerHTML}
                    </div>
                    <div>
                        ${tableHTML}
                    </div>
                </body>
                </html>
            `);

            // 4. Jalankan perintah cetak setelah dokumen selesai ditulis
            winPrint.document.close();
            winPrint.focus();
            
            // Beri sedikit delay 500ms agar browser sempat me-render style baru sebelum dialog print muncul
            setTimeout(function() {
                winPrint.print();
                winPrint.close();
            }, 500);
        }
    </script>

@endsection