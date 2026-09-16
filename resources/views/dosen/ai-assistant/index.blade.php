@extends('layouts.portal')

@section('content')
<div x-data="{ 
    activeTab: 'chat',
    inputMessage: '',
    messages: [],
    isLoading: false,
    async sendMessage() {
        if (this.inputMessage.trim() === '' || this.isLoading) return;
        
        const msg = this.inputMessage;
        this.messages.push({ role: 'user', content: msg });
        this.inputMessage = '';
        this.isLoading = true;
        
        // Auto scroll to bottom
        setTimeout(() => {
            const container = document.getElementById('chat-container');
            if(container) container.scrollTop = container.scrollHeight;
        }, 100);

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
            setTimeout(() => {
                const container = document.getElementById('chat-container');
                if(container) container.scrollTop = container.scrollHeight;
            }, 100);
        }
    }
}" class="h-[calc(100vh-6rem)] flex flex-col bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-800 to-purple-600 px-6 py-4 flex justify-between items-center text-white">
        <div class="flex items-center space-x-3">
            <svg class="w-8 h-8 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            <h1 class="text-xl font-bold">Asisten AI Cendekia</h1>
        </div>
        
        <!-- Tabs Navigation -->
        <div class="flex space-x-1 bg-purple-900/40 p-1 rounded-lg backdrop-blur-sm">
            <button @click="activeTab = 'chat'" :class="{'bg-white text-purple-800 shadow': activeTab === 'chat', 'text-purple-100 hover:bg-white/10': activeTab !== 'chat'}" class="px-4 py-1.5 rounded-md text-sm font-medium transition-all duration-200">
                Chat
            </button>
            <button @click="activeTab = 'material'" :class="{'bg-white text-purple-800 shadow': activeTab === 'material', 'text-purple-100 hover:bg-white/10': activeTab !== 'material'}" class="px-4 py-1.5 rounded-md text-sm font-medium transition-all duration-200">
                Material Gen
            </button>
            <button @click="activeTab = 'exam'" :class="{'bg-white text-purple-800 shadow': activeTab === 'exam', 'text-purple-100 hover:bg-white/10': activeTab !== 'exam'}" class="px-4 py-1.5 rounded-md text-sm font-medium transition-all duration-200">
                Exam Gen
            </button>
        </div>
    </div>

    <!-- Content Area -->
    <div class="flex-1 overflow-hidden relative bg-[#FAFAFA]">
        <!-- Ultra Modern Background Element (Glowing Orb) like in ai-chat -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-purple-500/20 rounded-full blur-[100px] pointer-events-none opacity-60 mix-blend-screen"></div>
        
        <!-- 1. Chat Tab -->
        <div x-show="activeTab === 'chat'" x-transition.opacity class="absolute inset-0 flex flex-col h-full z-10">
            <div id="chat-container" class="flex-1 overflow-y-auto p-6 flex flex-col scroll-smooth">
                
                <!-- Greeting (Empty State) -->
                <div x-show="messages.length === 0" class="flex-1 flex flex-col items-center justify-center text-center space-y-6">
                    <!-- Clean Modern Logo Container -->
                    <div class="relative w-24 h-24 mx-auto mb-2 flex items-center justify-center">
                        <div class="absolute inset-0 bg-purple-500/30 rounded-full blur-2xl"></div>
                        <div class="relative w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-lg border border-purple-100 z-10">
                            <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                    </div>
                    
                    <div>
                        <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 mb-2">
                            Asisten Cendekia
                        </h1>
                        <h2 class="text-xl font-semibold text-gray-700 mb-2">
                            Halo, Selamat Datang
                        </h2>
                        <p class="text-gray-500 max-w-md mx-auto">
                            Diskusikan RPS, ide metode pembelajaran, atau tanyakan referensi materi untuk perkuliahan Anda.
                        </p>
                    </div>

                    <!-- Suggestions like ai-chat -->
                    <div class="grid grid-cols-2 gap-4 mt-8 w-full max-w-2xl">
                        <button @click="inputMessage = 'Bantu buatkan draf RPS untuk mata kuliah baru'; sendMessage()" class="flex flex-col items-start p-4 bg-white border border-gray-200 rounded-xl hover:border-purple-300 hover:shadow-md transition-all text-left">
                            <span class="font-medium text-gray-800">Bantu buatkan RPS</span>
                            <span class="text-sm text-gray-500 mt-1">Struktur RPS untuk mata kuliah baru</span>
                        </button>
                        <button @click="inputMessage = 'Berikan ide studi kasus untuk materi algoritma'; sendMessage()" class="flex flex-col items-start p-4 bg-white border border-gray-200 rounded-xl hover:border-purple-300 hover:shadow-md transition-all text-left">
                            <span class="font-medium text-gray-800">Ide Studi Kasus</span>
                            <span class="text-sm text-gray-500 mt-1">Untuk topik algoritma & pemrograman</span>
                        </button>
                    </div>
                </div>

                <!-- Chat Messages -->
                <div x-show="messages.length > 0" class="max-w-4xl mx-auto w-full space-y-6 pb-6 flex-1">
                    <template x-for="(msg, index) in messages" :key="index">
                        <div :class="msg.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                            <!-- AI Avatar -->
                            <template x-if="msg.role === 'assistant'">
                                <div class="w-8 h-8 rounded-full bg-purple-600 flex items-center justify-center text-white mr-3 flex-shrink-0 mt-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                </div>
                            </template>
                            
                            <!-- Message Bubble -->
                            <div :class="msg.role === 'user' ? 'bg-purple-600 text-white rounded-2xl rounded-tr-sm py-3 px-5 max-w-[80%]' : 'bg-white border border-gray-200 text-gray-800 rounded-2xl rounded-tl-sm py-3 px-5 max-w-[80%] shadow-sm'">
                                <p class="whitespace-pre-wrap" x-text="msg.content"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
            
            <!-- Modern Input Area -->
            <div class="p-4 pb-6 bg-gradient-to-t from-[#FAFAFA] to-transparent">
                <div class="max-w-3xl mx-auto relative flex flex-col bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden focus-within:ring-1 focus-within:ring-purple-500 focus-within:border-purple-500 transition-all">
                    <textarea x-model="inputMessage" @keydown.enter.prevent="sendMessage()" rows="1" class="w-full bg-transparent border-0 focus:ring-0 resize-none py-4 pl-4 pr-12 text-gray-800 placeholder-gray-400" placeholder="Ketik pesan untuk AI Asisten..."></textarea>
                    <div class="flex justify-between items-center px-3 pb-3">
                        <div class="flex space-x-2 text-gray-400">
                            <button class="p-2 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg></button>
                        </div>
                        <button @click="sendMessage()" class="p-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors disabled:opacity-50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        </button>
                    </div>
                </div>
                <p class="text-xs text-center text-gray-400 mt-3">AI dapat membuat kesalahan. Harap periksa kembali informasi penting.</p>
            </div>
        </div>
        <!-- 2. Material Generator Tab -->
        <div x-show="activeTab === 'material'" x-transition.opacity class="absolute inset-0 overflow-y-auto p-6" style="display: none;">
            <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <svg class="w-6 h-6 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    AI Course Material Generator
                </h2>
                
                <form class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mata Kuliah</label>
                        <select class="w-full border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            <option>Pilih Mata Kuliah...</option>
                            <option>Pemrograman Web Lanjut</option>
                            <option>Kecerdasan Buatan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Topik Pertemuan</label>
                        <input type="text" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500" placeholder="Contoh: Pengenalan Laravel Middleware">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Target Pembelajaran / Instruksi Khusus</label>
                        <textarea rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500" placeholder="Materi harus mencakup contoh implementasi autentikasi..."></textarea>
                    </div>
                    
                    <div class="pt-4 border-t border-gray-100 flex justify-end">
                        <button type="button" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg shadow font-medium flex items-center transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                            Generate Materi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 3. Exam Generator Tab -->
        <div x-show="activeTab === 'exam'" x-transition.opacity class="absolute inset-0 overflow-y-auto p-6" style="display: none;">
            <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <svg class="w-6 h-6 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    AI Exam & Quiz Generator
                </h2>
                
                <form class="space-y-5">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mata Kuliah</label>
                            <select class="w-full border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                <option>Pilih Mata Kuliah...</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Materi Acuan (Opsional)</label>
                            <select class="w-full border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                <option>Pilih Materi PDF/Word...</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Soal</label>
                            <select class="w-full border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                <option>Pilihan Ganda (Multiple Choice)</option>
                                <option>Esai Singkat</option>
                                <option>Studi Kasus</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Soal</label>
                            <input type="number" value="10" min="1" max="50" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tingkat Kesulitan & Konteks Tambahan</label>
                        <textarea rows="2" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500" placeholder="Tingkat soal mudah-sedang, fokuskan pada konsep dasar algoritma."></textarea>
                    </div>
                    
                    <div class="pt-4 border-t border-gray-100 flex justify-end space-x-3">
                        <label class="flex items-center text-sm text-gray-600 mr-auto">
                            <input type="checkbox" class="rounded text-purple-600 focus:ring-purple-500 mr-2" checked> Sertakan Kunci Jawaban
                        </label>
                        <button type="button" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg shadow font-medium flex items-center transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            Generate Soal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
