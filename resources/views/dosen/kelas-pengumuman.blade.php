@extends('layouts.portal')

@section('title', 'Announcements')
@section('activeMenu', 'Announcements')

@section('content')

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-bold text-[#0f172a]">Announcements</h1>
            <p class="text-sm text-gray-500 mt-1">Manage and broadcast important information to your students.</p>
        </div>
        <button onclick="toggleAnnouncementModal('modalAnnouncement')" class="shrink-0 bg-[#321270] hover:bg-opacity-90 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition flex items-center gap-2 shadow-sm">
            Create New Announcement
        </button>
    </div>

    <div class="space-y-4">
        @forelse ($pengumuman as $item)
            <div class="bg-white rounded-2xl border border-gray-200/80 p-6 shadow-sm hover:shadow-md transition">
                <div class="flex items-center gap-2 mb-3">
                    <span class="text-xs font-semibold bg-emerald-50 text-emerald-600 px-2.5 py-1 rounded-lg">Academic</span>
                    @if($item->untuk_semua)
                        <span class="text-xs font-semibold bg-amber-50 text-amber-600 px-2.5 py-1 rounded-lg">Important</span>
                    @endif
                </div>

                <h2 class="text-lg font-bold text-[#0f172a]">{{ $item->judul }}</h2>
                <p class="text-sm text-gray-500 mt-2 leading-relaxed">{{ $item->isi }}</p>

                <div class="flex flex-wrap items-center justify-between gap-2 mt-4 pt-4 border-t border-gray-50 text-xs text-gray-500">
                    <div class="flex flex-wrap items-center gap-x-6 gap-y-2">
                        <span>Posted by: <strong class="text-gray-700">{{ $item->pembuat?->name ?? '-' }}</strong></span>
                        <span>Target: <strong class="text-gray-700">Students in this class</strong></span>
                        <span>Date: <strong class="text-gray-700">{{ $item->created_at?->format('d M Y') }}</strong></span>
                    </div>

                    <form action="{{ route('dosen.kelas-pengumuman.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 font-medium transition">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-dashed border-gray-200 p-8 text-center text-sm text-gray-500">
                Belum ada pengumuman untuk kelas ini.
            </div>
        @endforelse

        <div class="mt-6">
            {{ $pengumuman->links() }}
        </div>
    </div>

    <div id="modalAnnouncement" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="fixed inset-0 bg-black bg-opacity-40 transition-opacity" onclick="toggleAnnouncementModal('modalAnnouncement')"></div>

        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white w-full max-w-3xl rounded-2xl shadow-xl overflow-hidden">
                <div class="px-8 py-5 bg-gray-50/50 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-[#1e293b]">Informasi Pengumuman</h3>
                </div>

                <form action="{{ route('dosen.kelas-pengumuman.store', ['id' => $kelas_perkuliahan_id]) }}" method="POST">
                    @csrf
                    <div class="px-8 py-6 space-y-5">
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-slate-700">Announcement Title</label>
                            <input type="text" name="judul" required placeholder="Contoh: Perubahan jadwal kuliah" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:outline-none transition placeholder-gray-300">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-slate-700">Content / Message</label>
                            <textarea name="isi" rows="6" required placeholder="Tuliskan isi pengumuman secara mendetail..." class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:outline-none resize-none placeholder-gray-300"></textarea>
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

    <script>
        function toggleAnnouncementModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.toggle('hidden');
            document.body.classList.toggle('overflow-hidden');
        }
    </script>

@endsection
