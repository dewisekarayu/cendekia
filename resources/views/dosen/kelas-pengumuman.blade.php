@extends('layouts.portal')

@section('title', 'Announcements')
@section('activeMenu', 'Announcements')

@section('content')

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-bold text-[#0f172a]">Announcements</h1>
            <p class="text-sm text-gray-500 mt-1">Manage and broadcast important information to your students and faculty.</p>
        </div>
        <button onclick="toggleAnnouncementModal('modalAnnouncement')" class="shrink-0 bg-[#321270] hover:bg-opacity-90 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition flex items-center gap-2 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Create New Announcement
        </button>
    </div>

    <div class="space-y-4">
        
        <div class="bg-white rounded-2xl border border-gray-200/80 p-6 shadow-sm hover:shadow-md transition">
            <div class="flex items-center gap-2 mb-3">
                <span class="text-xs font-semibold bg-amber-50 text-amber-600 px-2.5 py-1 rounded-lg">Important</span>
                <span class="text-xs font-semibold bg-emerald-50 text-emerald-600 px-2.5 py-1 rounded-lg">Academic</span>
            </div>

            <h2 class="text-lg font-bold text-[#0f172a] hover:text-[#321270] transition cursor-pointer">Perubahan Jadwal Ujian Tengah Semester (UTS) Ganjil</h2>
            <p class="text-sm text-gray-500 mt-2 leading-relaxed">Diberitahukan kepada seluruh mahasiswa Fakultas Ilmu Komputer bahwa pelaksanaan UTS Mata Kuliah Pemrograman Web yang semula dijadwalkan hari Senin, dialihkan menjadi hari Rabu dikarenakan adanya agenda rapat akreditasi fakultas. Jam dan ruangan tetap sama.</p>
            
            <div class="mt-3 flex items-center">
                <a href="#" onclick="alert('Ini link mockup dokumen lampiran')" class="inline-flex items-center gap-1.5 text-xs text-blue-600 hover:underline font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    Lihat Dokumen Lampiran
                </a>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-2 mt-4 pt-4 border-t border-gray-50 text-xs text-gray-500">
                <div class="flex flex-wrap items-center gap-x-6 gap-y-2">
                    <span class="flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4 text-gray-400"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        Posted by: <strong class="text-gray-700">Prof. Dr. Ir. Budi Santoso</strong>
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4 text-gray-400"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        Target: <strong class="text-gray-700">All Students</strong>
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4 text-gray-400"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        Date: <strong class="text-gray-700">07 Jul 2026</strong>
                    </span>
                </div>

                <button type="button" onclick="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini? (Mockup)')" class="text-red-500 hover:text-red-700 font-medium transition flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    Hapus
                </button>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200/80 p-6 shadow-sm hover:shadow-md transition">
            <div class="flex items-center gap-2 mb-3">
                <span class="text-xs font-semibold bg-blue-50 text-blue-600 px-2.5 py-1 rounded-lg">System Updates</span>
            </div>

            <h2 class="text-lg font-bold text-[#0f172a] hover:text-[#321270] transition cursor-pointer">Pemeliharaan Rutin Server Portal Akademik</h2>
            <p class="text-sm text-gray-500 mt-2 leading-relaxed">Kami akan melakukan maintenance sistem pada hari Sabtu mulai pukul 23:00 WIB hingga Minggu pukul 04:00 WIB. Selama jendela waktu tersebut, Portal Akademik tidak akan dapat diakses untuk sementara waktu. Terima kasih atas pengertiannya.</p>
            
            <div class="flex flex-wrap items-center justify-between gap-2 mt-4 pt-4 border-t border-gray-50 text-xs text-gray-500">
                <div class="flex flex-wrap items-center gap-x-6 gap-y-2">
                    <span class="flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4 text-gray-400"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        Posted by: <strong class="text-gray-700">IT Support Team</strong>
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4 text-gray-400"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        Target: <strong class="text-gray-700">All Faculty</strong>
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4 text-gray-400"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        Date: <strong class="text-gray-700">05 Jul 2026</strong>
                    </span>
                </div>

                <button type="button" onclick="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini? (Mockup)')" class="text-red-500 hover:text-red-700 font-medium transition flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    Hapus
                </button>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6 rounded-2xl border">
            <div class="flex flex-1 justify-between sm:hidden">
                <a href="#" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Previous</a>
                <a href="#" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Next</a>
            </div>
            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing <span class="font-medium">1</span> to <span class="font-medium">2</span> of <span class="font-medium">2</span> results
                    </p>
                </div>
                <div>
                    <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                        <a href="#" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                            <span class="sr-only">Previous</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.83 10l3.94 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" /></svg>
                        </a>
                        <a href="#" aria-current="page" class="relative z-10 inline-flex items-center bg-[#321270] px-4 py-2 text-sm font-semibold text-white focus:z-20 focus:visible:outline focus:visible:outline-2 focus:visible:outline-offset-2 focus:visible:outline-indigo-600">1</a>
                        <a href="#" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                            <span class="sr-only">Next</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.17 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>
                        </a>
                    </nav>
                </div>
            </div>
        </div>

    </div>

    <div id="modalAnnouncement" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="fixed inset-0 bg-black bg-opacity-40 transition-opacity" onclick="toggleAnnouncementModal('modalAnnouncement')"></div>

        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white w-full max-w-3xl rounded-2xl shadow-xl overflow-hidden">
                
                <div class="px-8 py-5 bg-gray-50/50 border-b border-gray-100 flex items-center gap-3">
                    <div class="bg-[#321270] p-2 rounded-lg text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-[#1e293b]">Informasi Pengumuman</h3>
                </div>

                <form action="{{ route('dosen.kelas-pengumuman.store', ['id' => $kelas_perkuliahan_id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="px-8 py-6 space-y-5">
                        
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-slate-700">Announcement Title</label>
                            <input type="text" name="judul" required placeholder="Contoh: Perubahan Jadwal Kuliah Semester Ganjil" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:outline-none transition placeholder-gray-300">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="space-y-1.5">
                                <label class="text-sm font-bold text-slate-700">Category</label>
                                <div class="relative">
                                    <select name="kategori" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm appearance-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:outline-none transition text-gray-500 bg-white">
                                        <option value="Academic">Academic</option>
                                        <option value="Administrative">Administrative</option>
                                        <option value="System Updates">System Updates</option>
                                        <option value="Events">Events</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-sm font-bold text-slate-700">Target Audience</label>
                                <div class="relative">
                                    <select name="target" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm appearance-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:outline-none transition text-gray-500 bg-white">
                                        <option value="All Students">All Students</option>
                                        <option value="Lecturers">Lecturers</option>
                                        <option value="All Faculty">All Faculty</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 pt-1">
                            <input type="checkbox" name="untuk_semua" id="untuk_semua" value="1" class="rounded text-[#321270] focus:ring-[#321270] w-4 h-4 border-gray-300">
                            <label for="untuk_semua" class="text-sm text-slate-700 font-medium select-none">Tandai pengumuman ini sebagai info penting (Important Badge)</label>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-slate-700">Content / Message</label>
                            <div class="border border-gray-200 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-blue-500/20 focus-within:border-blue-500 transition">
                                <div class="bg-gray-50/80 px-4 py-2 border-b border-gray-200 flex gap-4 text-gray-400 text-sm">
                                    <button type="button" class="hover:text-black font-bold font-serif">B</button>
                                    <button type="button" class="hover:text-black italic">I</button>
                                    <button type="button" class="hover:text-black underline">U</button>
                                    <span class="border-r border-gray-200"></span>
                                    <button type="button"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.828a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg></button>
                                    <button type="button"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.587-1.587a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></button>
                                </div>
                                <textarea name="isi" rows="5" required placeholder="Tuliskan isi pengumuman atau informasi penting secara mendetail di sini..." class="w-full px-4 py-3 text-sm focus:outline-none resize-none placeholder-gray-300"></textarea>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-slate-700">Attachments (Optional)</label>
                            <div class="border-2 border-dashed border-gray-200 rounded-2xl p-6 flex flex-col items-center justify-center text-center hover:border-[#321270]/50 transition cursor-pointer bg-slate-50/50" onclick="document.getElementById('announcementFileInput').click()">
                                <div class="bg-purple-50 p-2.5 rounded-xl mb-2 text-[#321270]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                </div>
                                <p class="text-sm font-bold text-slate-800">Klik atau seret file ke sini</p>
                                <p class="text-xs text-gray-400 mt-0.5">PDF, DOCX, ZIP, PNG, atau JPG (Maksimal 25MB)</p>
                                <input type="file" id="announcementFileInput" name="lampiran" class="hidden" onchange="displaySelectedFile(this)">
                                <p id="fileCheckDisplay" class="text-xs text-emerald-600 font-semibold mt-2 hidden"></p>
                            </div>
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
            modal.classList.toggle("hidden");
            document.body.classList.toggle("overflow-hidden");
        }

        function displaySelectedFile(input) {
            const label = document.getElementById('fileCheckDisplay');
            if (input.files && input.files.length > 0) {
                label.innerText = "✓ Terpilih: " + input.files[0].name;
                label.classList.remove('hidden');
            } else {
                label.classList.add('hidden');
            }
        }
    </script>

@endsection