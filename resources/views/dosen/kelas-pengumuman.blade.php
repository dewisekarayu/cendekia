@extends('layouts.portal')

@section('title', 'Announcements')
@section('activeMenu', 'Announcements')

@section('content')

    <div class="bg-[#321270] rounded-xl px-8 py-6 mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            @if(isset($kelas))
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-xs font-semibold bg-white/15 text-white px-2.5 py-1 rounded">{{ $kelas->mataKuliah->kode_mk ?? '-' }}</span>
                    <span class="text-xs text-white/80">Semester {{ $kelas->semester->nama_semester ?? '-' }}</span>
                </div>
                <h1 class="text-xl font-bold text-white">{{ $kelas->mataKuliah->nama_mk ?? '-' }}</h1>
            @else
                <h1 class="text-xl font-bold text-white">Announcements</h1>
                <p class="text-sm text-white/80 mt-1">Semua pengumuman dari kelas-kelas yang Anda ampu.</p>
            @endif
        </div>
    </div>

    <x-flash-message />

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-bold text-[#0f172a]">Announcements</h1>
            <p class="text-sm text-gray-500 mt-1">Semua pengumuman dari kelas-kelas yang Anda ampu.</p>
            <h2 class="text-lg font-bold text-[#0f172a]">Daftar Pengumuman</h2>
            <p class="text-xs text-gray-500 mt-1">Kelola pengumuman untuk semua kelas Anda dari satu tempat.</p>
        </div>
        <button onclick="toggleAnnouncementModal('modalAnnouncement')" class="shrink-0 bg-[#321270] hover:bg-opacity-90 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition flex items-center gap-2 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Create New Announcement
        </button>
    </div>

    <div class="space-y-4">
        @forelse($pengumuman as $p)
            <div class="bg-white rounded-2xl border border-gray-200/80 p-6 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between gap-2 mb-3">
                    <div class="flex items-center gap-2">
                        @if($p->untuk_semua)
                            <span class="text-xs font-semibold bg-amber-50 text-amber-600 px-2.5 py-1 rounded-lg">Important</span>
                        @endif
                        <span class="text-xs font-semibold bg-emerald-50 text-emerald-600 px-2.5 py-1 rounded-lg">
                            {{ $p->kelasPerkuliahan->kode_kelas ?? 'Kelas' }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button"
                            onclick="openEditModal({{ $p->id }}, @js($p->judul), @js($p->isi), {{ $p->untuk_semua ? 'true' : 'false' }})"
                            class="text-gray-400 hover:text-[#321270] transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        </button>
                    </div>
                </div>

                <h2 class="text-lg font-bold text-[#0f172a] hover:text-[#321270] transition cursor-pointer">{{ $p->judul }}</h2>
                <p class="text-sm text-gray-500 mt-2 leading-relaxed">{{ $p->isi }}</p>

                <div class="flex flex-wrap items-center justify-between gap-2 mt-4 pt-4 border-t border-gray-50 text-xs text-gray-500">
                    <div class="flex flex-wrap items-center gap-x-6 gap-y-2">
                        <span class="flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4 text-gray-400"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            Posted by: <strong class="text-gray-700">{{ $p->pembuat->name ?? 'Dosen' }}</strong>
                        </span>
                        <span class="flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4 text-gray-400"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            Date: <strong class="text-gray-700">{{ $p->created_at->format('d M Y') }}</strong>
                        </span>
                    </div>

                    <form action="{{ route('dosen.kelas-pengumuman.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 font-medium transition flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-gray-100 p-8 text-center text-gray-500 text-sm shadow-sm">
                Belum ada informasi pengumuman yang diterbitkan.
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
            <div class="relative bg-white w-full max-w-3xl rounded-2xl shadow-xl overflow-hidden">
                <div class="px-8 py-5 bg-gray-50/50 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-[#1e293b]">Informasi Pengumuman</h3>
                </div>

                <form action="{{ route('dosen.kelas-pengumuman.store', ['id' => $kelas_perkuliahan_id]) }}" method="POST">
            <div class="relative bg-white w-full max-w-2xl rounded-2xl shadow-xl overflow-hidden">

                <div class="px-8 py-5 bg-gray-50/50 border-b border-gray-100 flex items-center gap-3">
                    <div class="bg-[#321270] p-2 rounded-lg text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-[#1e293b]">Buat Pengumuman</h3>
                </div>

                <form action="{{ route('dosen.kelas-pengumuman.store') }}" method="POST">
                    @csrf
                    <div class="px-8 py-6 space-y-5">
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-slate-700">Announcement Title</label>
                            <input type="text" name="judul" required placeholder="Contoh: Perubahan Jadwal Kuliah Semester Ganjil" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:outline-none transition placeholder-gray-300">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-slate-700">Kelas</label>
                            <select name="kelas_perkuliahan_id" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:outline-none transition text-gray-700 bg-white">
                                <option value="" disabled selected>Pilih kelas...</option>
                                @foreach($kelasList as $kelasOption)
                                    <option value="{{ $kelasOption->id }}">{{ $kelasOption->kode_kelas }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center gap-2 pt-1">
                            <input type="checkbox" name="untuk_semua" id="untuk_semua" value="1" class="rounded text-[#321270] focus:ring-[#321270] w-4 h-4 border-gray-300">
                            <label for="untuk_semua" class="text-sm text-slate-700 font-medium select-none">Tandai pengumuman ini sebagai info penting (Important Badge)</label>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-slate-700">Content / Message</label>
                            <textarea name="isi" rows="6" required placeholder="Tuliskan isi pengumuman secara mendetail..." class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:outline-none resize-none placeholder-gray-300"></textarea>
                            <textarea name="isi" rows="5" required placeholder="Tuliskan isi pengumuman atau informasi penting secara mendetail di sini..." class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:outline-none transition resize-none placeholder-gray-300"></textarea>
                        </div>
                    </div>

                    <div class="bg-gray-50/70 px-8 py-4 border-t border-gray-100 flex justify-end gap-3">
                        <button type="button" onclick="toggleAnnouncementModal('modalAnnouncement')" class="px-5 py-2 rounded-lg border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-100 transition">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-lg bg-[#0f172a] text-sm font-semibold text-white hover:bg-opacity-90 transition">
                            Publikasikan Pengumuman
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
            <div class="relative bg-white w-full max-w-2xl rounded-2xl shadow-xl overflow-hidden">

                <div class="px-8 py-5 bg-gray-50/50 border-b border-gray-100 flex items-center gap-3">
                    <div class="bg-[#321270] p-2 rounded-lg text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-[#1e293b]">Edit Pengumuman</h3>
                </div>

                <form id="formEditAnnouncement" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="px-8 py-6 space-y-5">
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-slate-700">Announcement Title</label>
                            <input type="text" name="judul" id="edit_judul" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:outline-none transition">
                        </div>

                        <div class="flex items-center gap-2 pt-1">
                            <input type="checkbox" name="untuk_semua" id="edit_untuk_semua" value="1" class="rounded text-[#321270] focus:ring-[#321270] w-4 h-4 border-gray-300">
                            <label for="edit_untuk_semua" class="text-sm text-slate-700 font-medium select-none">Tandai pengumuman ini sebagai info penting (Important Badge)</label>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-slate-700">Content / Message</label>
                            <textarea name="isi" id="edit_isi" rows="5" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:outline-none transition resize-none"></textarea>
                        </div>
                    </div>

                    <div class="bg-gray-50/70 px-8 py-4 border-t border-gray-100 flex justify-end gap-3">
                        <button type="button" onclick="toggleAnnouncementModal('modalEditAnnouncement')" class="px-5 py-2 rounded-lg border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-100 transition">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-lg bg-[#0f172a] text-sm font-semibold text-white hover:bg-opacity-90 transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleAnnouncementModal(modalId) {
            const modal = document.getElementById(modalId);

            modal.classList.toggle('hidden');
            document.body.classList.toggle('overflow-hidden');

            modal.classList.toggle("hidden");
            document.body.classList.toggle("overflow-hidden");
        }

        function openEditModal(id, judul, isi, untukSemua) {
            const form = document.getElementById('formEditAnnouncement');
            form.action = `/dosen/pengumuman/${id}`;

            document.getElementById('edit_judul').value = judul;
            document.getElementById('edit_isi').value = isi;
            document.getElementById('edit_untuk_semua').checked = untukSemua;

            toggleAnnouncementModal('modalEditAnnouncement');
        }
    </script>

@endsection