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


    </div>
</div>
@endsection