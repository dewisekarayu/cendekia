@extends('layouts.portal')

@section('title', 'AI Assistant')
@section('activeMenu', 'AI Assistant')

@section('content')
<div x-data="{
    activeTab: 'chat',
    inputMessage: '',
    messages: [],
    isLoading: false,
    scrollToBottom() {
        setTimeout(() => {
            const container = document.getElementById('chat-container');
            if (container) container.scrollTop = container.scrollHeight;
        }, 100);
    },
    resetInput() {
        if (this.$refs.chatInput) this.$refs.chatInput.style.height = 'auto';
    },
    async sendMessage() {
        if (this.inputMessage.trim() === '' || this.isLoading) return;

        const msg = this.inputMessage;
        this.messages.push({ role: 'user', content: msg });
        this.inputMessage = '';
        this.resetInput();
        this.isLoading = true;
        this.scrollToBottom();

        try {
            const response = await fetch('{{ route('dosen.ai-assistant.chat') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    message: msg,
                    history: this.messages.slice(0, -1)
                })
            });

            const data = await response.json();

            if (data.success) {
                this.messages.push({ role: 'assistant', content: data.message });
            } else {
                this.messages.push({ role: 'assistant', content: 'Terjadi kesalahan: ' + (data.error || 'Unknown error') });
            }
        } catch (error) {
            this.messages.push({ role: 'assistant', content: 'Koneksi gagal. Pastikan internet Anda aktif dan API terhubung.' });
        } finally {
            this.isLoading = false;
            this.scrollToBottom();
        }
    }
}" class="h-[calc(100dvh-6rem)] min-h-[480px] flex flex-col bg-white dark:bg-slate-900 rounded-2xl shadow-sm overflow-hidden border border-gray-200 dark:border-slate-700">

    <!-- Header -->
    <div class="bg-gradient-to-r from-[#321270] to-[#4a1fa8] dark:from-indigo-950 dark:to-purple-900 px-4 py-3 sm:px-6 sm:py-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between text-white">
        <div class="flex items-center gap-3 min-w-0">
            <div class="w-9 h-9 sm:w-10 sm:h-10 shrink-0 rounded-xl bg-white/10 border border-white/20 flex items-center justify-center">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <div class="min-w-0">
                <h1 class="text-base sm:text-xl font-bold leading-tight truncate">Asisten AI Cendekia</h1>
                <p class="text-[11px] sm:text-xs text-purple-200/90 leading-tight">Teman diskusi untuk perkuliahan Anda</p>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="flex gap-1 bg-purple-900/40 p-1 rounded-xl backdrop-blur-sm w-full sm:w-auto">
            <button @click="activeTab = 'chat'" :class="{'bg-white text-purple-800 shadow': activeTab === 'chat', 'text-purple-100 hover:bg-white/10': activeTab !== 'chat'}" class="flex-1 sm:flex-none px-3 sm:px-4 py-2 sm:py-1.5 rounded-lg text-xs sm:text-sm font-semibold transition-all duration-200 whitespace-nowrap">
                Chat
            </button>
            <button @click="activeTab = 'material'" :class="{'bg-white text-purple-800 shadow': activeTab === 'material', 'text-purple-100 hover:bg-white/10': activeTab !== 'material'}" class="flex-1 sm:flex-none px-3 sm:px-4 py-2 sm:py-1.5 rounded-lg text-xs sm:text-sm font-semibold transition-all duration-200 whitespace-nowrap">
                Buat Materi
            </button>
            <button @click="activeTab = 'exam'" :class="{'bg-white text-purple-800 shadow': activeTab === 'exam', 'text-purple-100 hover:bg-white/10': activeTab !== 'exam'}" class="flex-1 sm:flex-none px-3 sm:px-4 py-2 sm:py-1.5 rounded-lg text-xs sm:text-sm font-semibold transition-all duration-200 whitespace-nowrap">
                Buat Soal
            </button>
        </div>
    </div>

    <!-- Content Area -->
    <div class="flex-1 min-h-0 overflow-hidden relative bg-[#FAFAFA] dark:bg-slate-900">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[320px] h-[320px] sm:w-[500px] sm:h-[500px] bg-purple-500/20 rounded-full blur-[100px] pointer-events-none opacity-60"></div>

        <!-- 1. Chat Tab -->
        <div x-show="activeTab === 'chat'" x-transition.opacity class="absolute inset-0 flex flex-col z-10">
            <div id="chat-container" class="flex-1 min-h-0 overflow-y-auto px-3 py-4 sm:p-6 flex flex-col scroll-smooth">

                <!-- Greeting (Empty State) -->
                <div x-show="messages.length === 0" class="flex-1 flex flex-col items-center justify-center text-center space-y-5 sm:space-y-6 py-4">
                    <div class="relative w-20 h-20 sm:w-24 sm:h-24 mx-auto flex items-center justify-center">
                        <div class="absolute inset-0 bg-purple-500/30 rounded-full blur-2xl"></div>
                        <div class="relative w-16 h-16 sm:w-20 sm:h-20 bg-white dark:bg-slate-800 rounded-full flex items-center justify-center shadow-lg border border-purple-100 dark:border-slate-700 z-10">
                            <svg class="w-8 h-8 sm:w-10 sm:h-10 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                    </div>

                    <div class="px-2">
                        <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white mb-1.5">
                            Halo, ada yang bisa dibantu?
                        </h2>
                        <p class="text-sm sm:text-base text-gray-500 dark:text-gray-400 max-w-md mx-auto">
                            Diskusikan RPS, ide metode pembelajaran, atau tanyakan referensi materi untuk perkuliahan Anda.
                        </p>
                    </div>

                    <!-- Suggestions -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 sm:gap-4 mt-2 sm:mt-6 w-full max-w-2xl">
                        <button @click="inputMessage = 'Bantu buatkan draf RPS untuk mata kuliah baru'; sendMessage()" class="flex flex-col items-start p-3.5 sm:p-4 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl hover:border-purple-300 dark:hover:border-purple-500 hover:shadow-md active:scale-[0.99] transition-all text-left group">
                            <span class="text-sm sm:text-base font-semibold text-gray-800 dark:text-gray-200 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Bantu buatkan RPS</span>
                            <span class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-0.5 sm:mt-1">Struktur RPS untuk mata kuliah baru</span>
                        </button>
                        <button @click="inputMessage = 'Berikan ide studi kasus untuk materi algoritma'; sendMessage()" class="flex flex-col items-start p-3.5 sm:p-4 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl hover:border-purple-300 dark:hover:border-purple-500 hover:shadow-md active:scale-[0.99] transition-all text-left group">
                            <span class="text-sm sm:text-base font-semibold text-gray-800 dark:text-gray-200 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Ide studi kasus</span>
                            <span class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-0.5 sm:mt-1">Untuk topik algoritma & pemrograman</span>
                        </button>
                    </div>
                </div>

                <!-- Chat Messages -->
                <div x-show="messages.length > 0" class="max-w-4xl mx-auto w-full space-y-4 sm:space-y-6 pb-4 flex-1">
                    <template x-for="(msg, index) in messages" :key="index">
                        <div :class="msg.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                            <!-- AI Avatar -->
                            <template x-if="msg.role === 'assistant'">
                                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-purple-600 flex items-center justify-center text-white mr-2 sm:mr-3 flex-shrink-0 mt-1">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                </div>
                            </template>

                            <!-- Message Bubble -->
                            <div :class="msg.role === 'user' ? 'bg-[#321270] dark:bg-purple-600 text-white rounded-2xl rounded-tr-sm' : 'bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 rounded-2xl rounded-tl-sm shadow-sm'"
                                 class="py-2.5 px-4 sm:py-3 sm:px-5 max-w-[85%] sm:max-w-[80%] min-w-0 text-sm sm:text-base">
                                <p class="whitespace-pre-wrap break-words" x-text="msg.content"></p>
                            </div>
                        </div>
                    </template>

                    <!-- Indikator AI sedang mengetik -->
                    <div x-show="isLoading" class="flex justify-start">
                        <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-purple-600 flex items-center justify-center text-white mr-2 sm:mr-3 flex-shrink-0 mt-1">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl rounded-tl-sm py-3.5 px-4 shadow-sm flex items-center gap-1.5" aria-label="AI sedang mengetik">
                            <span class="w-2 h-2 rounded-full bg-purple-400 animate-bounce" style="animation-delay: 0ms"></span>
                            <span class="w-2 h-2 rounded-full bg-purple-400 animate-bounce" style="animation-delay: 150ms"></span>
                            <span class="w-2 h-2 rounded-full bg-purple-400 animate-bounce" style="animation-delay: 300ms"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Input Area -->
            <div class="px-3 pt-2 sm:p-4 bg-gradient-to-t from-[#FAFAFA] dark:from-slate-900 to-transparent" style="padding-bottom: max(0.75rem, env(safe-area-inset-bottom));">
                <div class="max-w-3xl mx-auto flex flex-col bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl shadow-sm overflow-hidden focus-within:ring-1 focus-within:ring-[#321270] dark:focus-within:ring-purple-500 focus-within:border-[#321270] dark:focus-within:border-purple-500 transition-all">
                    <textarea x-ref="chatInput" x-model="inputMessage"
                              @input="$el.style.height = 'auto'; $el.style.height = Math.min($el.scrollHeight, 140) + 'px'"
                              @keydown.enter="if (!$event.shiftKey) { $event.preventDefault(); sendMessage() }"
                              rows="1"
                              class="w-full bg-transparent border-0 focus:ring-0 resize-none py-3 sm:py-4 px-4 text-sm sm:text-base text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 max-h-[140px]"
                              placeholder="Ketik pesan untuk AI Asisten..."></textarea>
                    <div class="flex justify-between items-center px-2.5 pb-2.5 sm:px-3 sm:pb-3">
                        <div class="flex space-x-2 text-gray-400 dark:text-gray-500">
                            <button type="button" aria-label="Lampirkan file" class="p-2 hover:text-[#321270] dark:hover:text-purple-400 hover:bg-[#321270]/10 dark:hover:bg-slate-700 rounded-lg transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg></button>
                        </div>
                        <button type="button" @click="sendMessage()" :disabled="isLoading || inputMessage.trim() === ''" aria-label="Kirim pesan" class="p-2.5 bg-[#321270] dark:bg-purple-600 hover:bg-[#321270]/90 dark:hover:bg-purple-700 text-white rounded-xl transition-colors disabled:opacity-40 disabled:cursor-not-allowed">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        </button>
                    </div>
                </div>
                <p class="text-[10px] sm:text-xs text-center text-gray-400 dark:text-gray-500 mt-2">AI dapat membuat kesalahan. Harap periksa kembali informasi penting.</p>
            </div>
        </div>

        <!-- 2. Material Generator Tab -->
        <div x-show="activeTab === 'material'" x-transition.opacity class="absolute inset-0 overflow-y-auto p-3 sm:p-6 z-10" style="display: none;">
            <div class="max-w-3xl mx-auto bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-700 p-4 sm:p-8">
                <h2 class="text-lg sm:text-xl font-bold text-gray-800 dark:text-white mb-1 flex items-center">
                    <svg class="w-6 h-6 shrink-0 text-[#321270] dark:text-purple-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    Pembuat Materi Kuliah
                </h2>
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-5 sm:mb-6">Isi data di bawah, lalu AI akan menyusun materi untuk satu pertemuan.</p>

                <form class="space-y-4 sm:space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mata Kuliah</label>
                        <select class="w-full bg-white dark:bg-slate-900 border-gray-300 dark:border-slate-600 text-gray-900 dark:text-white rounded-lg shadow-sm focus:border-[#321270] dark:focus:border-purple-500 focus:ring-[#321270] dark:focus:ring-purple-500">
                            <option>Pilih Mata Kuliah...</option>
                            <option>Pemrograman Web Lanjut</option>
                            <option>Kecerdasan Buatan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Topik Pertemuan</label>
                        <input type="text" class="w-full bg-white dark:bg-slate-900 border-gray-300 dark:border-slate-600 text-gray-900 dark:text-white rounded-lg shadow-sm focus:border-[#321270] dark:focus:border-purple-500 focus:ring-[#321270] dark:focus:ring-purple-500 placeholder-gray-400 dark:placeholder-gray-500" placeholder="Contoh: Pengenalan Laravel Middleware">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Target Pembelajaran / Instruksi Khusus</label>
                        <textarea rows="3" class="w-full bg-white dark:bg-slate-900 border-gray-300 dark:border-slate-600 text-gray-900 dark:text-white rounded-lg shadow-sm focus:border-[#321270] dark:focus:border-purple-500 focus:ring-[#321270] dark:focus:ring-purple-500 placeholder-gray-400 dark:placeholder-gray-500" placeholder="Materi harus mencakup contoh implementasi autentikasi..."></textarea>
                    </div>

                    <div class="pt-4 border-t border-gray-100 dark:border-slate-700 flex sm:justify-end">
                        <button type="button" class="w-full sm:w-auto justify-center bg-[#321270] dark:bg-purple-600 hover:bg-[#321270]/90 dark:hover:bg-purple-700 text-white px-6 py-2.5 rounded-lg shadow font-medium flex items-center transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                            Buat Materi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 3. Exam Generator Tab -->
        <div x-show="activeTab === 'exam'" x-transition.opacity class="absolute inset-0 overflow-y-auto p-3 sm:p-6 z-10" style="display: none;">
            <div class="max-w-3xl mx-auto bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-700 p-4 sm:p-8">
                <h2 class="text-lg sm:text-xl font-bold text-gray-800 dark:text-white mb-1 flex items-center">
                    <svg class="w-6 h-6 shrink-0 text-[#321270] dark:text-purple-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    Pembuat Soal & Kuis
                </h2>
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-5 sm:mb-6">Pilih mata kuliah dan jenis soal, lalu AI akan membuatkan soalnya.</p>

                <form class="space-y-4 sm:space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mata Kuliah</label>
                            <select class="w-full bg-white dark:bg-slate-900 border-gray-300 dark:border-slate-600 text-gray-900 dark:text-white rounded-lg shadow-sm focus:border-[#321270] dark:focus:border-purple-500 focus:ring-[#321270] dark:focus:ring-purple-500">
                                <option>Pilih Mata Kuliah...</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Materi Acuan (Opsional)</label>
                            <select class="w-full bg-white dark:bg-slate-900 border-gray-300 dark:border-slate-600 text-gray-900 dark:text-white rounded-lg shadow-sm focus:border-[#321270] dark:focus:border-purple-500 focus:ring-[#321270] dark:focus:ring-purple-500">
                                <option>Pilih Materi PDF/Word...</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipe Soal</label>
                            <select class="w-full bg-white dark:bg-slate-900 border-gray-300 dark:border-slate-600 text-gray-900 dark:text-white rounded-lg shadow-sm focus:border-[#321270] dark:focus:border-purple-500 focus:ring-[#321270] dark:focus:ring-purple-500">
                                <option>Pilihan Ganda (Multiple Choice)</option>
                                <option>Esai Singkat</option>
                                <option>Studi Kasus</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jumlah Soal</label>
                            <input type="number" value="10" min="1" max="50" class="w-full bg-white dark:bg-slate-900 border-gray-300 dark:border-slate-600 text-gray-900 dark:text-white rounded-lg shadow-sm focus:border-[#321270] dark:focus:border-purple-500 focus:ring-[#321270] dark:focus:ring-purple-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tingkat Kesulitan & Konteks Tambahan</label>
                        <textarea rows="2" class="w-full bg-white dark:bg-slate-900 border-gray-300 dark:border-slate-600 text-gray-900 dark:text-white rounded-lg shadow-sm focus:border-[#321270] dark:focus:border-purple-500 focus:ring-[#321270] dark:focus:ring-purple-500 placeholder-gray-400 dark:placeholder-gray-500" placeholder="Tingkat soal mudah-sedang, fokuskan pada konsep dasar algoritma."></textarea>
                    </div>

                    <div class="pt-4 border-t border-gray-100 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center gap-3">
                        <label class="flex items-center text-sm text-gray-600 dark:text-gray-300 sm:mr-auto">
                            <input type="checkbox" class="rounded text-[#321270] dark:text-purple-600 focus:ring-[#321270] dark:focus:ring-purple-500 mr-2 border-gray-300 dark:border-slate-600 dark:bg-slate-900" checked> Sertakan Kunci Jawaban
                        </label>
                        <button type="button" class="w-full sm:w-auto justify-center bg-[#321270] dark:bg-purple-600 hover:bg-[#321270]/90 dark:hover:bg-purple-700 text-white px-6 py-2.5 rounded-lg shadow font-medium flex items-center transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            Buat Soal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection