@extends('layouts.portal')

@section('title', 'Announcements')
@section('activeMenu', 'Announcements')

@section('content')

    {{-- NAVBAR TABS SINKRONISASI --}}
    <div class="flex items-center gap-0 mb-6 border-b border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-lg transition-colors duration-200">
        <a href="{{ route('dosen.kelas-pengumuman.index') }}" class="px-5 py-3.5 text-sm font-semibold text-[#002B6B] dark:text-purple-400 border-b-2 border-[#002B6B] dark:border-purple-500 transition-all duration-200 text-decoration-none flex items-center gap-2 white-space-nowrap">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
            </svg>
            Announcements
        </a>
    </div>

    {{-- HEADER SECTION --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">Pengumuman Kelas</h1>
            <p class="text-sm font-medium text-slate-400 dark:text-slate-500 mt-0.5">Semua pengumuman dari kelas-kelas aktif yang Anda ampu.</p>
        </div>
        <button onclick="toggleAnnouncementModal('modalAnnouncement')" class="shrink-0 bg-[#321270] dark:bg-[#6c2bd9] hover:bg-[#230c50] dark:hover:bg-[#5b21b6] text-white text-xs font-bold px-5 py-2.5 rounded-xl transition duration-150 flex items-center gap-2 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Buat Pengumuman Baru
        </button>
    </div>

    <x-flash-message />

    {{-- LIST CARD PENGUMUMAN --}}
    <div class="space-y-4">
        @forelse($pengumuman as $p)
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/60 dark:border-slate-700 p-6 shadow-sm hover:shadow-md transition duration-150">
                <div class="flex items-center justify-between gap-2 mb-3">
                    <div class="flex items-center gap-2">
                        @if($p->untuk_semua)
                            <span class="text-[10px] font-bold bg-amber-50 dark:bg-amber-950/40 border border-amber-200/40 dark:border-amber-900/30 text-amber-600 dark:text-amber-300 px-2.5 py-0.5 rounded-md">Penting</span>
                        @endif
                        <span class="text-[10px] font-bold bg-purple-50 dark:bg-purple-950/40 border border-purple-100 dark:border-purple-900/30 text-[#321270] dark:text-purple-300 px-2.5 py-0.5 rounded-md">
                            {{ $p->kelasPerkuliahan->kode_kelas ?? 'Kelas' }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button"
                            onclick="openEditModal({{ $p->id }}, @js($p->judul), @js($p->isi), {{ $p->untuk_semua ? 'true' : 'false' }})"
                            class="text-slate-400 dark:text-slate-500 hover:text-[#321270] dark:hover:text-purple-400 transition duration-150" title="Edit Pengumuman">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        </button>
                    </div>
                </div>

                <h2 class="text-base font-bold text-slate-800 dark:text-white tracking-tight">{{ $p->judul }}</h2>
                <p class="text-sm text-slate-600 dark:text-slate-300 mt-2 leading-relaxed whitespace-pre-line">{{ $p->isi }}</p>

                <div class="flex flex-wrap items-center justify-between gap-2 mt-4 pt-4 border-t border-slate-100 dark:border-slate-700 text-[11px] font-medium text-slate-400 dark:text-slate-500">
                    <div class="flex flex-wrap items-center gap-x-6 gap-y-2">
                        <span class="flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-3.5 h-3.5 text-slate-400 dark:text-slate-500"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            Oleh: <strong class="text-slate-600 dark:text-slate-300 font-semibold">{{ $p->pembuat->name ?? 'Dosen' }}</strong>
                        </span>
                        <span class="flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-3.5 h-3.5 text-slate-400 dark:text-slate-500"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            Tanggal Terbit: <strong class="text-slate-600 dark:text-slate-300 font-semibold">{{ $p->created_at->format('d M Y') }}</strong>
                        </span>
                    </div>

                    <form action="{{ route('dosen.kelas-pengumuman.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 font-bold transition duration-150 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/60 dark:border-slate-700 py-12 text-center text-slate-400 dark:text-slate-500 text-sm font-semibold shadow-sm">
                Belum ada pengumuman kelas yang diterbitkan semester ini.
            </div>
        @endforelse

        <div class="mt-6">
            {{ $pengumuman->links() }}
        </div>
    </div>

    {{-- MODAL CREATE --}}
    <div id="modalAnnouncement" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="fixed inset-0 bg-black bg-opacity-40 transition-opacity" onclick="toggleAnnouncementModal('modalAnnouncement')"></div>

        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white dark:bg-slate-850 w-full max-w-2xl rounded-2xl shadow-xl overflow-hidden">
                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900 border-b border-slate-100 dark:border-slate-800 flex items-center gap-3">
                    <div class="bg-[#321270] dark:bg-purple-600 p-1.5 rounded-lg text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 dark:text-white">Buat Pengumuman Kelas Baru</h3>
                </div>

                <form id="formCreateAnnouncement" method="POST" action="{{ route('dosen.kelas-pengumuman.store') }}">
                    @csrf
                    <div class="px-6 py-5 space-y-4">
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Judul Pengumuman</label>
                            <input type="text" name="judul" required placeholder="Contoh: Pergeseran Jam Kuliah Pengganti" class="w-full px-4 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 rounded-xl text-sm focus:ring-2 focus:ring-purple-500/10 focus:border-[#321270] dark:focus:border-purple-500 focus:outline-none transition placeholder-gray-300 dark:placeholder-gray-600 text-slate-700 dark:text-gray-100 font-medium">
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Target Kelas Kuliah</label>
                            <select name="kelas_perkuliahan_id" id="create_kelas_id" required class="w-full px-4 py-2 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-purple-500/10 focus:border-[#321270] dark:focus:border-purple-500 focus:outline-none transition text-slate-700 dark:text-gray-100 bg-white dark:bg-slate-900 font-semibold">
                                <option value="" disabled selected class="dark:bg-slate-900 dark:text-gray-400">Pilih target kelas mengajar...</option>
                                @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas->id }}" class="dark:bg-slate-900 dark:text-gray-100">{{ $kelas->kode_kelas }} - {{ $kelas->mataKuliah?->nama_mk ?? 'Mata Kuliah' }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center gap-2 pt-1">
                            <input type="checkbox" name="untuk_semua" id="untuk_semua" value="1" class="rounded text-[#321270] dark:text-purple-600 focus:ring-[#321270] dark:bg-slate-900 dark:border-slate-700 w-4 h-4 border-slate-300">
                            <label for="untuk_semua" class="text-xs text-slate-600 dark:text-slate-400 font-bold select-none cursor-pointer">Tandai sebagai informasi penting (Important Badge)</label>
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Isi Pesan Pengumuman</label>
                            <textarea name="isi" rows="5" required placeholder="Tulis rincian info instruksi kuliah di sini..." class="w-full px-4 py-3 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 rounded-xl text-sm focus:ring-2 focus:ring-purple-500/10 focus:border-[#321270] dark:focus:border-purple-500 focus:outline-none transition resize-none placeholder-gray-300 dark:placeholder-gray-600 text-slate-700 dark:text-gray-100"></textarea>
                        </div>
                    </div>

                    <div class="bg-slate-50/50 dark:bg-slate-900/50 px-6 py-3 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-2.5">
                        <button type="button" onclick="toggleAnnouncementModal('modalAnnouncement')" class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition duration-150">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-[#321270] dark:bg-[#6c2bd9] text-xs font-bold text-white hover:bg-[#220b4d] dark:hover:bg-[#5b21b6] transition duration-150 shadow-sm">
                            Terbitkan Pengumuman
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT --}}
    <div id="modalEditAnnouncement" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="fixed inset-0 bg-black bg-opacity-40 transition-opacity" onclick="toggleAnnouncementModal('modalEditAnnouncement')"></div>

        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white dark:bg-slate-850 w-full max-w-2xl rounded-2xl shadow-xl overflow-hidden">
                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900 border-b border-slate-100 dark:border-slate-800 flex items-center gap-3">
                    <div class="bg-[#321270] dark:bg-purple-600 p-1.5 rounded-lg text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 dark:text-white">Modifikasi Data Pengumuman</h3>
                </div>

                <form id="formEditAnnouncement" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="px-6 py-5 space-y-4">
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Judul Pengumuman</label>
                            <input type="text" name="judul" id="edit_judul" required class="w-full px-4 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 rounded-xl text-sm focus:ring-2 focus:ring-purple-500/10 focus:border-[#321270] dark:focus:border-purple-500 focus:outline-none transition font-medium text-slate-700 dark:text-gray-100">
                        </div>

                        <div class="flex items-center gap-2 pt-1">
                            <input type="checkbox" name="untuk_semua" id="edit_untuk_semua" value="1" class="rounded text-[#321270] dark:text-purple-600 focus:ring-[#321270] dark:bg-slate-900 dark:border-slate-700 w-4 h-4 border-slate-300">
                            <label for="edit_untuk_semua" class="text-xs text-slate-600 dark:text-slate-400 font-bold select-none cursor-pointer">Tandai sebagai informasi penting (Important Badge)</label>
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Isi Pesan Pengumuman</label>
                            <textarea name="isi" id="edit_isi" rows="5" required class="w-full px-4 py-3 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 rounded-xl text-sm focus:ring-2 focus:ring-purple-500/10 focus:border-[#321270] dark:focus:border-purple-500 focus:outline-none transition resize-none text-slate-700 dark:text-gray-100"></textarea>
                        </div>
                    </div>

                    <div class="bg-slate-50/50 dark:bg-slate-900/50 px-6 py-3 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-2.5">
                        <button type="button" onclick="toggleAnnouncementModal('modalEditAnnouncement')" class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition duration-150">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-[#0f172a] dark:bg-[#6c2bd9] text-xs font-bold text-white hover:bg-slate-800 dark:hover:bg-[#5b21b6] transition duration-150 shadow-sm">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- CLEAN JAVASCRIPT MODAL INTERACTION (Aman dari Konflik Git) --}}
    <script>
        function toggleAnnouncementModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.toggle('hidden');
                document.body.classList.toggle('overflow-hidden');
            }
        }

        function openEditModal(id, judul, isi, untukSemua) {
            const form = document.getElementById('formEditAnnouncement');
            if (form) {
                form.action = `/dosen/pengumuman/${id}`;
                document.getElementById('edit_judul').value = judul;
                document.getElementById('edit_isi').value = isi;
                document.getElementById('edit_untuk_semua').checked = untukSemua;

                toggleAnnouncementModal('modalEditAnnouncement');
            }
        }
    </script>

@endsection