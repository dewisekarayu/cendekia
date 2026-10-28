@extends('layouts.portal')

@section('title', 'AI Assistant')
@section('activeMenu', 'AI Assistant')

@push('styles')
<style>
    /* Styling scrollbar halus ala ChatGPT */
    .chat-scrollbar::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    .chat-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .chat-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(156, 163, 175, 0.4);
        border-radius: 9999px;
    }
    .chat-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(156, 163, 175, 0.7);
    }

    /* Markdown prose styles for AI responses */
    .ai-markdown-content h1, 
    .ai-markdown-content h2, 
    .ai-markdown-content h3, 
    .ai-markdown-content h4 {
        font-weight: 700;
        color: inherit;
        margin-top: 1.25rem;
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }
    .ai-markdown-content h1 { font-size: 1.35rem; }
    .ai-markdown-content h2 { font-size: 1.2rem; }
    .ai-markdown-content h3 { font-size: 1.05rem; }
    .ai-markdown-content h4 { font-size: 0.95rem; }

    .ai-markdown-content p {
        margin-bottom: 0.75rem;
        line-height: 1.65;
    }
    .ai-markdown-content p:last-child {
        margin-bottom: 0;
    }

    .ai-markdown-content ul {
        list-style-type: disc;
        padding-left: 1.25rem;
        margin-bottom: 0.75rem;
        line-height: 1.6;
    }
    .ai-markdown-content ol {
        list-style-type: decimal;
        padding-left: 1.25rem;
        margin-bottom: 0.75rem;
        line-height: 1.6;
    }
    .ai-markdown-content li {
        margin-bottom: 0.35rem;
    }

    .ai-markdown-content blockquote {
        border-left: 3px solid #720c16;
        padding-left: 0.85rem;
        margin: 0.75rem 0;
        font-style: italic;
        opacity: 0.9;
        background: rgba(114, 12, 22, 0.04);
        border-radius: 0 0.5rem 0.5rem 0;
        padding-top: 0.35rem;
        padding-bottom: 0.35rem;
    }
    html.dark .ai-markdown-content blockquote {
        border-left-color: #f87171;
        background: rgba(248, 113, 113, 0.06);
    }

    /* Table styling */
    .ai-markdown-content .table-wrap {
        overflow-x: auto;
        margin: 0.85rem 0;
        border-radius: 0.75rem;
        border: 1px solid rgba(226, 232, 240, 0.8);
    }
    html.dark .ai-markdown-content .table-wrap {
        border-color: rgba(51, 65, 85, 0.8);
    }
    .ai-markdown-content table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.825rem;
        text-align: left;
    }
    .ai-markdown-content th {
        background-color: rgba(241, 245, 249, 0.9);
        padding: 0.6rem 0.85rem;
        font-weight: 700;
        border-bottom: 1px solid rgba(203, 213, 225, 0.8);
    }
    html.dark .ai-markdown-content th {
        background-color: rgba(30, 41, 59, 0.9);
        border-bottom-color: rgba(51, 65, 85, 0.8);
    }
    .ai-markdown-content td {
        padding: 0.55rem 0.85rem;
        border-bottom: 1px solid rgba(241, 245, 249, 0.8);
    }
    html.dark .ai-markdown-content td {
        border-bottom-color: rgba(30, 41, 59, 0.8);
    }
    .ai-markdown-content tr:nth-child(even) {
        background-color: rgba(248, 250, 252, 0.5);
    }
    html.dark .ai-markdown-content tr:nth-child(even) {
        background-color: rgba(15, 23, 42, 0.4);
    }

    /* Code block container */
    .ai-code-block {
        position: relative;
        margin: 0.85rem 0;
        border-radius: 0.75rem;
        overflow: hidden;
        background: #0f172a;
        color: #e2e8f0;
        border: 1px solid #1e293b;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    .ai-code-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.45rem 0.85rem;
        background: #1e293b;
        font-size: 0.7rem;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-weight: 700;
        color: #94a3b8;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    }
    .ai-code-copy-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.725rem;
        font-weight: 600;
        color: #cbd5e1;
        padding: 0.2rem 0.5rem;
        border-radius: 0.375rem;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.12);
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .ai-code-copy-btn:hover {
        background: rgba(255, 255, 255, 0.16);
        color: #ffffff;
    }
    .ai-code-pre {
        padding: 0.9rem 1rem;
        overflow-x: auto;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 0.815rem;
        line-height: 1.6;
        margin: 0;
        white-space: pre;
    }

    /* Inline code snippet */
    .ai-inline-code {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 0.825em;
        padding: 0.15rem 0.35rem;
        border-radius: 0.375rem;
        background: rgba(114, 12, 22, 0.08);
        color: #720c16;
        font-weight: 600;
    }
    html.dark .ai-inline-code {
        background: rgba(248, 113, 113, 0.12);
        color: #fca5a5;
    }

    /* Pulsing glowing indicator */
    @keyframes subtle-pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.75; transform: scale(1.05); }
    }
    .ai-pulse-aura {
        animation: subtle-pulse 3s infinite ease-in-out;
    }
</style>
@endpush

@section('content')
<div x-data="cendekiaGptApp()" x-init="initApp()" class="h-[calc(100dvh-5.5rem)] min-h-[580px] flex rounded-2xl shadow-sm overflow-hidden border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 relative font-sans">
    <input x-ref="fileInput" type="file" accept=".pdf,.docx,.txt,.md,.csv,.png,.jpg,.jpeg,.webp" class="hidden" @change="handleFileSelect($event)">

    {{-- =========================================================================
         1. SIDEBAR RIWAYAT CHAT (CHATGPT STYLE)
         ========================================================================= --}}
    <!-- Backdrop Mobile -->
    <div x-show="sidebarOpen"
         @click="sidebarOpen = false"
         x-transition:enter="transition-opacity ease-linear duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-30 lg:hidden"></div>

    <aside :class="sidebarOpen ? 'translate-x-0 w-72 sm:w-80' : '-translate-x-full lg:translate-x-0 lg:w-0 lg:border-r-0 lg:overflow-hidden'"
           class="fixed lg:static inset-y-0 left-0 z-40 lg:z-10 flex flex-col bg-slate-50 dark:bg-slate-950 border-r border-slate-200 dark:border-slate-800 transition-all duration-300 shrink-0 select-none">
        
        {{-- Sidebar Header & New Chat Button --}}
        <div class="p-3.5 sm:p-4 border-b border-slate-200/80 dark:border-slate-800/80 space-y-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-[#720c16] text-white flex items-center justify-center font-bold text-xs shadow-xs">
                        <i class="bi bi-chat-dots-fill"></i>
                    </div>
                    <span class="font-bold text-sm text-slate-800 dark:text-white">Riwayat Obrolan</span>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg">
                    <i class="bi bi-x-lg text-sm"></i>
                </button>
            </div>

            {{-- New Chat Button --}}
            <button @click="createNewChat()"
                    class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-[#720c16] hover:bg-[#8a1824] active:scale-[0.98] text-white text-xs sm:text-sm font-bold shadow-xs transition group">
                <span class="flex items-center gap-2">
                    <i class="bi bi-plus-lg text-sm group-hover:rotate-90 transition-transform duration-200"></i>
                    <span>Obrolan Baru</span>
                </span>
                <span class="text-[10px] bg-white/20 px-1.5 py-0.5 rounded text-white/90 font-mono">Baru</span>
            </button>

            {{-- Search Chat History --}}
            <div class="relative">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>
                <input type="text"
                       x-model="searchQuery"
                       placeholder="Cari obrolan..."
                       class="w-full pl-8 pr-3 py-1.5 text-xs rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:border-[#720c16] focus:ring-1 focus:ring-[#720c16]/20 transition">
                <button x-show="searchQuery" @click="searchQuery = ''" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-xs">
                    <i class="bi bi-x-circle-fill"></i>
                </button>
            </div>
        </div>

        {{-- Conversations List Grouped --}}
        <div class="flex-1 overflow-y-auto chat-scrollbar p-2 space-y-4">
            
            <template x-if="filteredConversations.length === 0">
                <div class="py-12 px-4 text-center text-slate-400 dark:text-slate-500">
                    <i class="bi bi-chat-square-text text-3xl mb-2 inline-block opacity-40"></i>
                    <p class="text-xs font-medium" x-text="searchQuery ? 'Tidak ada obrolan yang cocok' : 'Belum ada riwayat obrolan'"></p>
                    <p class="text-[11px] mt-1 opacity-70">Mulai diskusi dengan mengetik pesan pertama Anda.</p>
                </div>
            </template>

            {{-- Group: Hari Ini --}}
            <template x-if="groupedConversations.today.length > 0">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 px-2.5 mb-1.5">Hari Ini</p>
                    <div class="space-y-1">
                        <template x-for="chat in groupedConversations.today" :key="chat.id">
                            <div @click="selectChat(chat.id)"
                                 :class="activeChatId === chat.id 
                                     ? 'bg-white dark:bg-slate-900 text-[#720c16] dark:text-red-400 border-slate-200 dark:border-slate-800 shadow-xs font-bold' 
                                     : 'text-slate-600 dark:text-slate-300 hover:bg-slate-200/60 dark:hover:bg-slate-900/50 border-transparent'"
                                 class="group flex items-center justify-between px-2.5 py-2 rounded-xl border text-xs cursor-pointer transition duration-150">
                                
                                <div class="flex items-center gap-2 min-w-0 flex-1">
                                    <i class="bi bi-chat-left-text shrink-0 text-xs opacity-70"></i>
                                    <span class="truncate" x-text="chat.title"></span>
                                </div>

                                {{-- Action Buttons (Rename / Delete) --}}
                                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity ml-1.5 shrink-0" @click.stop>
                                    <button @click="renameChat(chat.id)" title="Ubah Judul" class="p-1 hover:text-[#720c16] dark:hover:text-red-400 rounded">
                                        <i class="bi bi-pencil text-[11px]"></i>
                                    </button>
                                    <button @click="deleteChat(chat.id)" title="Hapus Obrolan" class="p-1 hover:text-rose-600 rounded">
                                        <i class="bi bi-trash text-[11px]"></i>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            {{-- Group: Kemarin --}}
            <template x-if="groupedConversations.yesterday.length > 0">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 px-2.5 mb-1.5">Kemarin</p>
                    <div class="space-y-1">
                        <template x-for="chat in groupedConversations.yesterday" :key="chat.id">
                            <div @click="selectChat(chat.id)"
                                 :class="activeChatId === chat.id 
                                     ? 'bg-white dark:bg-slate-900 text-[#720c16] dark:text-red-400 border-slate-200 dark:border-slate-800 shadow-xs font-bold' 
                                     : 'text-slate-600 dark:text-slate-300 hover:bg-slate-200/60 dark:hover:bg-slate-900/50 border-transparent'"
                                 class="group flex items-center justify-between px-2.5 py-2 rounded-xl border text-xs cursor-pointer transition duration-150">
                                <div class="flex items-center gap-2 min-w-0 flex-1">
                                    <i class="bi bi-chat-left-text shrink-0 text-xs opacity-70"></i>
                                    <span class="truncate" x-text="chat.title"></span>
                                </div>
                                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity ml-1.5 shrink-0" @click.stop>
                                    <button @click="renameChat(chat.id)" title="Ubah Judul" class="p-1 hover:text-[#720c16] dark:hover:text-red-400 rounded">
                                        <i class="bi bi-pencil text-[11px]"></i>
                                    </button>
                                    <button @click="deleteChat(chat.id)" title="Hapus Obrolan" class="p-1 hover:text-rose-600 rounded">
                                        <i class="bi bi-trash text-[11px]"></i>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            {{-- Group: 7 Hari Terakhir --}}
            <template x-if="groupedConversations.previous7Days.length > 0">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 px-2.5 mb-1.5">7 Hari Terakhir</p>
                    <div class="space-y-1">
                        <template x-for="chat in groupedConversations.previous7Days" :key="chat.id">
                            <div @click="selectChat(chat.id)"
                                 :class="activeChatId === chat.id 
                                     ? 'bg-white dark:bg-slate-900 text-[#720c16] dark:text-red-400 border-slate-200 dark:border-slate-800 shadow-xs font-bold' 
                                     : 'text-slate-600 dark:text-slate-300 hover:bg-slate-200/60 dark:hover:bg-slate-900/50 border-transparent'"
                                 class="group flex items-center justify-between px-2.5 py-2 rounded-xl border text-xs cursor-pointer transition duration-150">
                                <div class="flex items-center gap-2 min-w-0 flex-1">
                                    <i class="bi bi-chat-left-text shrink-0 text-xs opacity-70"></i>
                                    <span class="truncate" x-text="chat.title"></span>
                                </div>
                                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity ml-1.5 shrink-0" @click.stop>
                                    <button @click="renameChat(chat.id)" title="Ubah Judul" class="p-1 hover:text-[#720c16] dark:hover:text-red-400 rounded">
                                        <i class="bi bi-pencil text-[11px]"></i>
                                    </button>
                                    <button @click="deleteChat(chat.id)" title="Hapus Obrolan" class="p-1 hover:text-rose-600 rounded">
                                        <i class="bi bi-trash text-[11px]"></i>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            {{-- Group: Sebelumnya --}}
            <template x-if="groupedConversations.older.length > 0">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 px-2.5 mb-1.5">Sebelumnya</p>
                    <div class="space-y-1">
                        <template x-for="chat in groupedConversations.older" :key="chat.id">
                            <div @click="selectChat(chat.id)"
                                 :class="activeChatId === chat.id 
                                     ? 'bg-white dark:bg-slate-900 text-[#720c16] dark:text-red-400 border-slate-200 dark:border-slate-800 shadow-xs font-bold' 
                                     : 'text-slate-600 dark:text-slate-300 hover:bg-slate-200/60 dark:hover:bg-slate-900/50 border-transparent'"
                                 class="group flex items-center justify-between px-2.5 py-2 rounded-xl border text-xs cursor-pointer transition duration-150">
                                <div class="flex items-center gap-2 min-w-0 flex-1">
                                    <i class="bi bi-chat-left-text shrink-0 text-xs opacity-70"></i>
                                    <span class="truncate" x-text="chat.title"></span>
                                </div>
                                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity ml-1.5 shrink-0" @click.stop>
                                    <button @click="renameChat(chat.id)" title="Ubah Judul" class="p-1 hover:text-[#720c16] dark:hover:text-red-400 rounded">
                                        <i class="bi bi-pencil text-[11px]"></i>
                                    </button>
                                    <button @click="deleteChat(chat.id)" title="Hapus Obrolan" class="p-1 hover:text-rose-600 rounded">
                                        <i class="bi bi-trash text-[11px]"></i>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

        </div>

        {{-- Sidebar Footer --}}
        <div class="p-3 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
            <span class="text-[11px]" x-text="conversations.length + ' obrolan'"></span>
            <button x-show="conversations.length > 0"
                    @click="clearAllChats()"
                    class="text-[11px] text-rose-600 hover:text-rose-700 font-semibold flex items-center gap-1 hover:underline">
                <i class="bi bi-trash"></i>
                <span>Hapus Semua</span>
            </button>
        </div>
    </aside>

    {{-- =========================================================================
         2. MAIN CHAT AREA
         ========================================================================= --}}
    <main class="flex-1 flex flex-col min-w-0 bg-white dark:bg-slate-900 relative">
        
        {{-- Top Navigation Bar --}}
        <header class="h-14 sm:h-16 px-3 sm:px-6 border-b border-slate-200/80 dark:border-slate-800 flex items-center justify-between gap-3 bg-white/95 dark:bg-slate-900/95 backdrop-blur-sm z-10 shrink-0">
            <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                {{-- Toggle Sidebar Button --}}
                <button @click="sidebarOpen = !sidebarOpen"
                        title="Tampilkan / Sembunyikan Riwayat"
                        class="p-2 rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 transition shrink-0">
                    <i class="bi bi-layout-sidebar-inset text-base sm:text-lg"></i>
                </button>

                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <h2 class="text-xs sm:text-sm font-extrabold text-slate-800 dark:text-white truncate cursor-pointer hover:text-[#720c16] dark:hover:text-red-400 flex items-center gap-1.5"
                            @click="renameChat(activeChatId)"
                            title="Klik untuk mengubah judul">
                            <span x-text="currentChatTitle"></span>
                            <i class="bi bi-pencil-square text-[11px] text-slate-400"></i>
                        </h2>
                    </div>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-[10px] text-slate-400 font-medium">Cendekia AI &bull; Model Perkuliahan</span>
                    </div>
                </div>
            </div>

            {{-- Right Actions --}}
            <div class="flex items-center gap-1.5 sm:gap-2 shrink-0">
                {{-- New Chat Button --}}
                <button @click="createNewChat()"
                        title="Obrolan Baru"
                        class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 transition">
                    <i class="bi bi-plus-lg"></i>
                    <span>Baru</span>
                </button>

                {{-- Export Chat --}}
                <button @click="exportChat()"
                        x-show="messages.length > 0"
                        title="Simpan / Ekspor Teks Obrolan"
                        class="p-2 sm:px-3 sm:py-1.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 transition flex items-center gap-1.5">
                    <i class="bi bi-download"></i>
                    <span class="hidden sm:inline">Ekspor</span>
                </button>

                {{-- Clear Chat Messages --}}
                <button @click="clearCurrentChat()"
                        x-show="messages.length > 0"
                        title="Kosongkan pesan saat ini"
                        class="p-2 sm:px-3 sm:py-1.5 text-xs font-semibold rounded-lg text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition flex items-center gap-1.5">
                    <i class="bi bi-trash"></i>
                    <span class="hidden sm:inline">Bersihkan</span>
                </button>
            </div>
        </header>

        {{-- Toast Feedback Notification --}}
        <div x-show="toast.show"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-2 scale-95"
             class="absolute top-18 right-4 z-50 flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-900 text-white dark:bg-white dark:text-slate-900 text-xs font-bold shadow-lg">
            <i class="bi bi-check-circle-fill text-emerald-400 dark:text-emerald-600"></i>
            <span x-text="toast.message"></span>
        </div>

        {{-- Messages Container with Drag and Drop Support --}}
        <div id="chat-messages-container"
             @dragover.prevent="isDragging = true"
             @dragleave.prevent="isDragging = false"
             @drop.prevent="handleFileDrop($event)"
             class="flex-1 overflow-y-auto chat-scrollbar px-3 py-4 sm:px-6 sm:py-6 relative scroll-smooth">
            
            {{-- Drag & Drop Overlay Animation --}}
            <div x-show="isDragging"
                 x-cloak
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="fixed inset-4 sm:inset-10 z-50 rounded-3xl bg-[#720c16]/15 dark:bg-red-950/40 backdrop-blur-sm border-2 border-dashed border-[#720c16] dark:border-red-400 flex flex-col items-center justify-center p-6 text-center pointer-events-none shadow-2xl">
                <div class="w-18 h-18 rounded-2xl bg-white dark:bg-slate-800 text-[#720c16] dark:text-red-400 flex items-center justify-center text-3xl shadow-xl animate-bounce mb-3 border border-red-200 dark:border-red-900/60">
                    <i class="bi bi-cloud-arrow-up-fill"></i>
                </div>
                <h3 class="text-base sm:text-lg font-black text-slate-900 dark:text-white">Lepaskan Berkas di Sini</h3>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 max-w-md mt-1">
                    Mendukung dokumen perkuliahan (PDF, DOCX, XLSX, CSV, Teks, Kode) serta foto/gambar untuk dianalisis langsung oleh AI.
                </p>
            </div>

            {{-- Welcome Screen / Empty State --}}
            <div x-show="messages.length === 0" class="max-w-2xl mx-auto py-6 sm:py-10 text-center flex flex-col items-center justify-center">
                
                {{-- Glowing AI Icon --}}
                <div class="relative w-20 h-20 sm:w-24 sm:h-24 mb-5 flex items-center justify-center">
                    <div class="absolute inset-0 bg-[#720c16]/20 dark:bg-red-500/10 rounded-full blur-2xl ai-pulse-aura"></div>
                    <div class="relative w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-gradient-to-br from-[#720c16] to-[#9a1622] text-white flex items-center justify-center shadow-lg border-2 border-white/40 dark:border-slate-800">
                        <i class="bi bi-stars text-2xl sm:text-3xl text-amber-200"></i>
                    </div>
                </div>

                <h1 class="text-xl sm:text-2xl font-black text-slate-800 dark:text-white tracking-tight">
                    Halo, {{ auth()->user()->name }}!
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1.5 max-w-md leading-relaxed">
                    Asisten cerdas dosen untuk bedah dokumen, analisis file, pembuatan gambar ilustrasi materi, draf RPS, dan evaluasi perkuliahan.
                </p>

                {{-- 6 Prompt Suggestion Cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 sm:gap-3 mt-6 sm:mt-8 w-full text-left">

                    {{-- 1. Analisis Berkas --}}
                    <button @click="$refs.fileInput.click()"
                            class="p-3.5 rounded-2xl border border-dashed border-[#720c16]/40 dark:border-red-500/40 hover:border-[#720c16] dark:hover:border-red-500 hover:bg-red-50/40 dark:hover:bg-red-950/20 shadow-xs hover:shadow-md transition text-left group">
                        <div class="flex items-center gap-2 mb-1 text-[#720c16] dark:text-red-400 font-bold text-xs">
                            <i class="bi bi-paperclip text-sm"></i>
                            <span>Unggah & Analisis File</span>
                        </div>
                        <p class="text-xs text-slate-600 dark:text-slate-300 line-clamp-2">Lampirkan PDF, Word, Excel, Kode, atau Foto untuk dibedah dan dirangkum oleh AI.</p>
                    </button>

                    {{-- 2. Generate Gambar AI --}}
                    <button @click="isImageMode = true; setInputText('Diagram alur interaktif sistem perkuliahan terdistribusi modern dengan konsep clean modern vector')"
                            class="p-3.5 rounded-2xl border border-slate-200/90 dark:border-slate-800 hover:border-amber-500/50 hover:bg-amber-50/40 dark:hover:bg-amber-950/20 shadow-xs hover:shadow-md transition text-left group">
                        <div class="flex items-center gap-2 mb-1 text-amber-600 dark:text-amber-400 font-bold text-xs">
                            <i class="bi bi-palette-fill"></i>
                            <span>Buat Gambar AI</span>
                        </div>
                        <p class="text-xs text-slate-600 dark:text-slate-300 line-clamp-2">Generate ilustrasi visual, diagram konsep, atau infografis materi bahan ajar.</p>
                    </button>

                    {{-- 3. Draf RPS --}}
                    <button @click="sendSuggestion('Bantu buatkan draf Capaian Pembelajaran Lulusan (CPL) dan rincian mingguan RPS untuk mata kuliah baru.')"
                            class="p-3.5 rounded-2xl border border-slate-200/90 dark:border-slate-800 hover:border-[#720c16]/50 dark:hover:border-red-500/50 hover:bg-slate-50/80 dark:hover:bg-slate-800/50 shadow-xs hover:shadow-md transition text-left group">
                        <div class="flex items-center gap-2 mb-1 text-[#720c16] dark:text-red-400 font-bold text-xs">
                            <i class="bi bi-journal-bookmark-fill"></i>
                            <span>Draf RPS Perkuliahan</span>
                        </div>
                        <p class="text-xs text-slate-600 dark:text-slate-300 line-clamp-2">Susun silabus, CPL, CPMK, dan matriks 16 pertemuan kuliah.</p>
                    </button>

                    {{-- 4. Ide Studi Kasus --}}
                    <button @click="sendSuggestion('Berikan 3 skenario studi kasus interaktif dan menantang untuk topik diskusi mahasiswa di kelas.')"
                            class="p-3.5 rounded-2xl border border-slate-200/90 dark:border-slate-800 hover:border-[#720c16]/50 dark:hover:border-red-500/50 hover:bg-slate-50/80 dark:hover:bg-slate-800/50 shadow-xs hover:shadow-md transition text-left group">
                        <div class="flex items-center gap-2 mb-1 text-sky-600 dark:text-sky-400 font-bold text-xs">
                            <i class="bi bi-lightbulb-fill"></i>
                            <span>Ide Studi Kasus Riil</span>
                        </div>
                        <p class="text-xs text-slate-600 dark:text-slate-300 line-clamp-2">Skenario nyata industri untuk memicu diskusi aktif mahasiswa.</p>
                    </button>

                    {{-- 5. Soal HOTS --}}
                    <button @click="sendSuggestion('Buatkan 5 soal esai tingkat analisis (HOTS) lengkap beserta panduan rubrik penilaiannya.')"
                            class="p-3.5 rounded-2xl border border-slate-200/90 dark:border-slate-800 hover:border-[#720c16]/50 dark:hover:border-red-500/50 hover:bg-slate-50/80 dark:hover:bg-slate-800/50 shadow-xs hover:shadow-md transition text-left group">
                        <div class="flex items-center gap-2 mb-1 text-emerald-600 dark:text-emerald-400 font-bold text-xs">
                            <i class="bi bi-check2-square"></i>
                            <span>Soal Ujian & Rubrik</span>
                        </div>
                        <p class="text-xs text-slate-600 dark:text-slate-300 line-clamp-2">Rancang soal evaluasi analitis lengkap dengan skor rubrik.</p>
                    </button>

                    {{-- 6. Contoh Kode --}}
                    <button @click="sendSuggestion('Buatkan contoh kode implementasi bersih (clean code) dan studi kasus pemrograman untuk bahan ajar.')"
                            class="p-3.5 rounded-2xl border border-slate-200/90 dark:border-slate-800 hover:border-[#720c16]/50 dark:hover:border-red-500/50 hover:bg-slate-50/80 dark:hover:bg-slate-800/50 shadow-xs hover:shadow-md transition text-left group">
                        <div class="flex items-center gap-2 mb-1 text-purple-600 dark:text-purple-400 font-bold text-xs">
                            <i class="bi bi-code-slash"></i>
                            <span>Contoh Kode & Praktikum</span>
                        </div>
                        <p class="text-xs text-slate-600 dark:text-slate-300 line-clamp-2">Sintaks program bersih dengan blok kode dan tombol salin.</p>
                    </button>
                </div>
            </div>

            {{-- Message Stream --}}
            <div x-show="messages.length > 0" class="max-w-3xl mx-auto space-y-6">
                <template x-for="(msg, index) in messages" :key="index">
                    <div>
                        
                        {{-- USER MESSAGE --}}
                        <template x-if="msg.role === 'user'">
                            <div class="flex items-start justify-end gap-2.5">
                                <div class="group relative flex flex-col items-end max-w-[88%] sm:max-w-[80%]">

                                    {{-- Attached File inside user bubble if present --}}
                                    <template x-if="msg.file_info">
                                        <div class="mb-2">
                                            {{-- If image attachment --}}
                                            <template x-if="msg.file_info.is_image">
                                                <div class="flex items-center gap-3 p-2.5 px-3.5 rounded-2xl bg-white/10 border border-white/20 text-white max-w-[280px] sm:max-w-[340px]">
                                                    <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center text-base shrink-0">
                                                        <i class="bi bi-file-earmark-image-fill text-pink-300"></i>
                                                    </div>
                                                    <div class="min-w-0 text-left">
                                                        <p class="text-xs font-bold truncate" x-text="msg.file_info.name"></p>
                                                        <p class="text-[10px] text-white/70" x-text="msg.file_info.size + ' • Gambar terlampir'"></p>
                                                    </div>
                                                </div>
                                            </template>

                                            {{-- If document attachment --}}
                                            <template x-if="!msg.file_info.is_image">
                                                <div class="flex items-center gap-3 p-2.5 px-3.5 rounded-2xl bg-white/10 border border-white/20 text-white max-w-[280px] sm:max-w-[340px]">
                                                    <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center text-base shrink-0">
                                                        <i :class="getFileIconClass(msg.file_info.type, msg.file_info.extension)"></i>
                                                    </div>
                                                    <div class="min-w-0 text-left">
                                                        <p class="text-xs font-bold truncate" x-text="msg.file_info.name"></p>
                                                        <p class="text-[10px] text-white/70" x-text="msg.file_info.size + ' • ' + (msg.file_info.extension || '').toUpperCase()"></p>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </template>

                                    <div class="px-4 py-2.5 rounded-2xl rounded-tr-xs bg-[#720c16] text-white text-xs sm:text-sm font-medium leading-relaxed shadow-xs break-words">
                                        <p class="whitespace-pre-wrap select-text" x-text="msg.content"></p>
                                        <div x-show="msg.fileName" class="mt-2 flex items-center gap-2 rounded-lg bg-white/10 px-2.5 py-2 text-[11px]">
                                            <i :class="msg.fileType === 'image' ? 'bi bi-image' : 'bi bi-file-earmark-text'"></i>
                                            <span class="break-all" x-text="msg.fileName"></span>
                                        </div>
                                        <p x-show="msg.fileName && !msg.attachmentContext" class="mt-1 text-[10px] font-normal text-white/70">
                                            Unggah ulang lampiran ini untuk pertanyaan lanjutan.
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-2 mt-1 mr-1 text-[10px] text-slate-400">
                                        <span x-text="msg.time || ''"></span>
                                        <button @click="editMessage(msg.content)" title="Edit & Gunakan Lagi" class="hover:text-slate-600 dark:hover:text-slate-200">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button @click="copyToClipboard(msg.content, 'Pesan disalin!')" title="Salin Pesan" class="hover:text-slate-600 dark:hover:text-slate-200">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-slate-700 dark:text-slate-200 font-bold text-xs shrink-0 shadow-xs mt-0.5">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            </div>
                        </template>

                        {{-- ASSISTANT (AI) MESSAGE --}}
                        <template x-if="msg.role === 'assistant'">
                            <div class="flex items-start gap-3">
                                {{-- AI Avatar --}}
                                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-[#720c16] to-[#9a1622] text-white flex items-center justify-center shrink-0 shadow-xs mt-1">
                                    <i class="bi bi-stars text-sm text-amber-200"></i>
                                </div>

                                {{-- Response Bubble & Content --}}
                                <div class="flex-1 min-w-0 flex flex-col items-start max-w-[92%] sm:max-w-[85%]">
                                    
                                    {{-- AI Name Header --}}
                                    <div class="flex items-center gap-2 mb-1.5">
                                        <span class="text-xs font-bold text-slate-800 dark:text-white">Cendekia AI</span>
                                        <span class="text-[10px] text-slate-400" x-text="msg.time || ''"></span>
                                    </div>

                                    {{-- Generated Image Showcase Card if present --}}
                                    <template x-if="msg.image_url">
                                        <div class="w-full mb-3 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/90 shadow-md overflow-hidden">
                                            <div class="relative group/aigen overflow-hidden bg-slate-900 cursor-pointer"
                                                 @click="openLightbox(msg.image_url, msg.prompt || 'Gambar AI Cendekia')">
                                                <img :src="msg.image_url" :alt="msg.prompt || 'Generated AI Image'" class="w-full max-h-[460px] object-contain mx-auto transition duration-300 group-hover/aigen:scale-[1.02]">
                                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/aigen:opacity-100 transition flex items-center justify-center gap-3">
                                                    <span class="px-3.5 py-1.5 rounded-full bg-white/90 text-slate-900 text-xs font-bold shadow-lg flex items-center gap-1.5">
                                                        <i class="bi bi-arrows-fullscreen"></i> Perbesar Layar Penuh
                                                    </span>
                                                </div>
                                            </div>

                                            {{-- Action Bar under generated image --}}
                                            <div class="p-3 bg-slate-50 dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700 flex flex-wrap items-center justify-between gap-2">
                                                <div class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400 min-w-0">
                                                    <i class="bi bi-palette text-amber-500"></i>
                                                    <span class="truncate max-w-[200px] sm:max-w-xs font-medium italic" x-text="msg.prompt || 'Ilustrasi AI'"></span>
                                                </div>
                                                <div class="flex items-center gap-1.5">
                                                    <button type="button"
                                                            @click="openLightbox(msg.image_url, msg.prompt || 'Gambar AI')"
                                                            class="px-2.5 py-1 text-xs rounded-lg bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 hover:bg-slate-100 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 transition flex items-center gap-1">
                                                        <i class="bi bi-arrows-fullscreen text-xs"></i>
                                                        <span>Layar Penuh</span>
                                                    </button>
                                                    <a :href="msg.image_url" download="cendekia_ai_image.jpg" target="_blank"
                                                       class="px-2.5 py-1 text-xs rounded-lg bg-[#720c16] hover:bg-[#8a1824] text-white font-semibold transition flex items-center gap-1 shadow-xs">
                                                        <i class="bi bi-download text-xs"></i>
                                                        <span>Unduh Gambar</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                    {{-- Rich Formatted Markdown Content --}}
                                    <div class="w-full px-4 py-3.5 sm:px-5 sm:py-4 rounded-2xl rounded-tl-xs bg-slate-50 dark:bg-slate-800/80 border border-slate-200/80 dark:border-slate-700/80 text-slate-800 dark:text-slate-100 text-xs sm:text-sm leading-relaxed shadow-xs overflow-hidden">
                                        <div class="ai-markdown-content break-words select-text" x-html="renderMarkdown(msg.content)"></div>
                                    </div>

                                    {{-- Action Toolbar on every AI message --}}
                                    <div class="flex items-center gap-2 mt-2 ml-1 text-slate-400 dark:text-slate-400 text-xs">
                                        
                                        {{-- 1. Copy Response Button --}}
                                        <button @click="copyToClipboard(msg.content, 'Jawaban disalin!')"
                                                class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-700 dark:hover:text-slate-200 transition font-medium text-[11px]"
                                                title="Salin seluruh teks jawaban">
                                            <i class="bi bi-clipboard"></i>
                                            <span>Salin</span>
                                        </button>

                                        {{-- 2. Regenerate Response Button --}}
                                        <button @click="regenerateLast()"
                                                class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-700 dark:hover:text-slate-200 transition font-medium text-[11px]"
                                                title="Buat ulang jawaban ini">
                                            <i class="bi bi-arrow-clockwise"></i>
                                            <span>Buat Ulang</span>
                                        </button>

                                        {{-- 3. Read Aloud (Text to Speech) --}}
                                        <button @click="toggleSpeech(msg.content, index)"
                                                :class="speechActiveIndex === index ? 'text-[#720c16] dark:text-red-400 font-bold bg-red-50 dark:bg-red-950/40' : ''"
                                                class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-700 dark:hover:text-slate-200 transition font-medium text-[11px]"
                                                title="Dengarkan pembacaan suara">
                                            <i :class="speechActiveIndex === index ? 'bi bi-stop-circle-fill animate-pulse text-rose-600' : 'bi bi-volume-up'"></i>
                                            <span x-text="speechActiveIndex === index ? 'Berhenti' : 'Dengarkan'"></span>
                                        </button>

                                        {{-- 4. Thumbs Up / Down Feedback --}}
                                        <div class="flex items-center gap-1 ml-1 border-l border-slate-200 dark:border-slate-700 pl-2">
                                            <button @click="rateMessage(index, 'up')"
                                                    :class="msg.rating === 'up' ? 'text-emerald-600' : 'hover:text-slate-700 dark:hover:text-slate-200'"
                                                    title="Jawaban membantu" class="p-1 rounded">
                                                <i :class="msg.rating === 'up' ? 'bi bi-hand-thumbs-up-fill' : 'bi bi-hand-thumbs-up'"></i>
                                            </button>
                                            <button @click="rateMessage(index, 'down')"
                                                    :class="msg.rating === 'down' ? 'text-rose-600' : 'hover:text-slate-700 dark:hover:text-slate-200'"
                                                    title="Jawaban kurang memuaskan" class="p-1 rounded">
                                                <i :class="msg.rating === 'down' ? 'bi bi-hand-thumbs-down-fill' : 'bi bi-hand-thumbs-down'"></i>
                                            </button>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </template>

                    </div>
                </template>

                {{-- Thinking / Typing indicator --}}
                <div x-show="isLoading" class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-[#720c16] to-[#9a1622] text-white flex items-center justify-center shrink-0 shadow-xs mt-1">
                        <i class="bi bi-stars text-sm text-amber-200"></i>
                    </div>
                    <div class="px-4 py-3 rounded-2xl rounded-tl-xs bg-slate-50 dark:bg-slate-800/80 border border-slate-200/80 dark:border-slate-700/80 shadow-xs flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-[#720c16] dark:bg-red-400 animate-bounce" style="animation-delay: 0ms"></span>
                        <span class="w-2 h-2 rounded-full bg-[#720c16] dark:bg-red-400 animate-bounce" style="animation-delay: 150ms"></span>
                        <span class="w-2 h-2 rounded-full bg-[#720c16] dark:bg-red-400 animate-bounce" style="animation-delay: 300ms"></span>
                        <span class="text-xs text-slate-400 ml-1 font-medium" x-text="isImageMode ? 'Cendekia AI sedang merancang gambar...' : (attachedFile ? 'Cendekia AI sedang menganalisis berkas...' : 'Cendekia AI sedang berpikir...')"></span>
                    </div>
                </div>

            </div>

        </div>

        {{-- =========================================================================
             3. FLOATING INPUT BAR (CHATGPT STYLE WITH FILE & IMAGE SUPPORT)
             ========================================================================= --}}
        <div class="p-3 sm:p-4 bg-gradient-to-t from-white via-white/95 to-transparent dark:from-slate-900 dark:via-slate-900/95 shrink-0 z-10">
            <div class="max-w-3xl mx-auto">
                
                {{-- Quick Topic Tags & Action Modes --}}
                <div x-show="messages.length > 0" class="flex items-center gap-1.5 overflow-x-auto no-scrollbar pb-2 text-[11px] text-slate-500">
                    <span class="font-semibold text-slate-400 shrink-0">Pintasan:</span>
                    <button @click="isImageMode = !isImageMode"
                            :class="isImageMode ? 'bg-amber-500 text-white font-bold' : 'bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-amber-600 dark:text-amber-400'"
                            class="px-2.5 py-1 rounded-full shrink-0 flex items-center gap-1 transition">
                        <i class="bi bi-palette-fill text-[10px]"></i>
                        <span>Gambar AI</span>
                    </button>
                    <button @click="$refs.fileInput.click()" class="px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 shrink-0 flex items-center gap-1">
                        <i class="bi bi-paperclip text-[11px]"></i>
                        <span>Analisis File</span>
                    </button>
                    <button @click="setInputText('Rancang rubrik penilaian untuk tugas akhir kelompok mata kuliah ini.')" class="px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 shrink-0">📊 Buat Rubrik</button>
                    <button @click="setInputText('Buatkan 3 soal studi kasus analitis berdasarkan materi di atas.')" class="px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 shrink-0">📝 Soal HOTS</button>
                    <button @click="setInputText('Ringkas pembahasan di atas menjadi 5 poin penting untuk slide presentasi.')" class="px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 shrink-0">💡 Ringkas Slide</button>
                    <button @click="setInputText('Tulis pengumuman LMS untuk mahasiswa terkait tugas ini.')" class="px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 shrink-0">📢 Pengumuman</button>
                </div>

                {{-- Mode Indikator: AI Image Generation Mode --}}
                <div x-show="isImageMode"
                     x-cloak
                     x-transition
                     class="mb-2 p-2 px-3 rounded-xl bg-gradient-to-r from-amber-500/15 via-rose-500/15 to-transparent border border-amber-500/30 flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2 text-amber-700 dark:text-amber-400 font-bold">
                        <i class="bi bi-palette-fill text-amber-500"></i>
                        <span>Mode Pembuatan Gambar AI Aktif &bull; Tuliskan konsep atau deskripsi visual</span>
                    </div>
                    <button type="button" @click="isImageMode = false" class="text-xs text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                        Matikan <i class="bi bi-x"></i>
                    </button>
                </div>

                {{-- Attached File Preview Pill (Pre-send) --}}
                <div x-show="attachedFile"
                     x-cloak
                     x-transition
                     class="mb-2 p-2 px-3 rounded-xl bg-slate-100 dark:bg-slate-800/90 border border-slate-200 dark:border-slate-700 flex items-center justify-between gap-3 shadow-xs">
                    <div class="flex items-center gap-2.5 min-w-0">
                        {{-- Thumbnail if image --}}
                        <template x-if="attachedFile && attachedFile.is_image">
                            <div class="relative w-10 h-10 rounded-lg overflow-hidden shrink-0 border border-slate-300 dark:border-slate-600 bg-slate-200 cursor-pointer"
                                 @click="openLightbox(attachedFile.previewUrl, attachedFile.name)">
                                <img :src="attachedFile.previewUrl" class="w-full h-full object-cover">
                            </div>
                        </template>

                        {{-- Icon if doc --}}
                        <template x-if="attachedFile && !attachedFile.is_image">
                            <div class="w-10 h-10 rounded-lg shrink-0 flex items-center justify-center text-lg bg-slate-200 dark:bg-slate-700">
                                <i :class="getFileIconClass(attachedFile.type, attachedFile.extension)"></i>
                            </div>
                        </template>

                        <div class="min-w-0">
                            <div class="flex items-center gap-1.5">
                                <p class="text-xs font-bold text-slate-800 dark:text-white truncate max-w-[200px] sm:max-w-md" x-text="attachedFile ? attachedFile.name : ''"></p>
                                <span class="px-1.5 py-0.2 rounded text-[9px] font-bold uppercase tracking-wider bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300" x-text="attachedFile ? attachedFile.extension : ''"></span>
                            </div>
                            <div class="flex items-center gap-2 text-[10px] text-slate-500 dark:text-slate-400">
                                <span x-text="attachedFile ? attachedFile.sizeFormatted : ''"></span>
                                <span>&bull;</span>
                                <span class="text-emerald-600 dark:text-emerald-400 font-semibold flex items-center gap-1">
                                    <i class="bi bi-shield-check"></i> Siap dianalisis AI
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Remove Attachment Button --}}
                    <button type="button"
                            @click="removeAttachedFile()"
                            title="Batalkan lampiran"
                            class="w-7 h-7 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 flex items-center justify-center transition">
                        <i class="bi bi-x-lg text-xs"></i>
                    </button>
                </div>

                {{-- Input Box --}}
                <div class="flex flex-col bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-md focus-within:ring-2 focus-within:ring-[#720c16]/20 dark:focus-within:ring-red-500/20 focus-within:border-[#720c16] dark:focus-within:border-red-500/70 transition-all overflow-hidden">
                    <textarea x-ref="chatInput"
                              x-model="inputMessage"
                              @input="autoResizeInput()"
                              @keydown.enter="handleEnterKey($event)"
                              @paste="handlePaste($event)"
                              rows="1"
                              :placeholder="isImageMode ? 'Deskripsikan gambar perkuliahan yang ingin dibuat AI (mis: diagram arsitektur jaringan komputer modern)...' : (attachedFile ? 'Tulis instruksi khusus untuk analisis berkas di atas (atau tekan Kirim untuk analisis lengkap)...' : 'Tanyakan materi, draf RPS, soal ujian, atau lampirkan berkas...')"
                              class="w-full bg-transparent border-0 focus:ring-0 focus:outline-none resize-none px-4 py-3 text-xs sm:text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 max-h-[160px] min-h-[46px]"></textarea>

                    {{-- Bottom Controls inside input --}}
                    <div class="flex items-center justify-between px-3 pb-2.5 pt-1 text-slate-400">
                        <div class="flex items-center gap-1.5">
                            {{-- Voice Input (Speech Recognition) --}}
                            <button type="button"
                                    x-show="speechRecognitionSupported"
                                    @click="toggleVoiceInput()"
                                    :class="isListening ? 'bg-red-500 text-white animate-pulse' : 'hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-500 dark:text-slate-400'"
                                    title="Dikte Suara (Bicara langsung)"
                                    class="p-1.5 rounded-lg text-sm transition">
                                <i :class="isListening ? 'bi bi-mic-fill' : 'bi bi-mic'"></i>
                            </button>

                            {{-- Clear input --}}
                            <button type="button"
                                    x-show="inputMessage.trim().length > 0"
                                    @click="inputMessage = ''; autoResizeInput()"
                                    title="Bersihkan teks"
                                    class="p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-xs transition">
                                <i class="bi bi-x-circle"></i>
                            </button>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="text-[10px] text-slate-400 hidden sm:inline" x-show="inputMessage.length > 0" x-text="inputMessage.length + ' karakter'"></span>

                            {{-- Send / Stop Button --}}
                            <template x-if="isLoading">
                                <button type="button"
                                        @click="stopGeneration()"
                                        title="Hentikan pembuatan"
                                        class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold transition flex items-center gap-1.5">
                                    <i class="bi bi-stop-fill text-red-400"></i>
                                    <span>Hentikan</span>
                                </button>
                            </template>

                            <template x-if="!isLoading">
                                <button type="button"
                                        @click="sendMessage()"
                                        :disabled="(inputMessage.trim() === '' && !attachedFile) || (isImageMode && inputMessage.trim() === '')"
                                        title="Kirim (Enter)"
                                        class="p-2 sm:px-3.5 sm:py-1.5 rounded-xl bg-[#720c16] hover:bg-[#8a1824] active:scale-95 disabled:opacity-30 disabled:cursor-not-allowed text-white text-xs font-bold transition shadow-xs flex items-center gap-1.5">
                                    <span class="hidden sm:inline" x-text="isImageMode ? 'Buat Gambar' : (attachedFile ? 'Analisis Berkas' : 'Kirim')"></span>
                                    <i :class="isImageMode ? 'bi bi-palette' : (attachedFile ? 'bi bi-file-earmark-arrow-up' : 'bi bi-arrow-up')"></i>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                <p class="text-[10px] text-center text-slate-400 dark:text-slate-500 mt-2">
                    Lampirkan PDF, DOCX, TXT, MD, CSV, atau gambar (maks. 10 MB). PDF hasil pindai perlu di-OCR; analisis gambar memerlukan model vision. Periksa kembali informasi penting.
                </p>

            </div>
        </div>

    </main>

    {{-- Fullscreen Image Lightbox Modal --}}
    <div x-show="lightbox.show"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @keydown.escape.window="closeLightbox()"
         class="fixed inset-0 z-50 bg-black/90 backdrop-blur-md flex flex-col items-center justify-between p-3 sm:p-6 select-none"
         @click.self="closeLightbox()">

        {{-- Lightbox Header --}}
        <div class="w-full max-w-5xl flex items-center justify-between text-white pb-3 border-b border-white/10 z-10">
            <div class="flex items-center gap-2 min-w-0">
                <i class="bi bi-image text-amber-400"></i>
                <h3 class="text-xs sm:text-sm font-bold truncate max-w-md" x-text="lightbox.title || 'Tampilan Gambar Penuh'"></h3>
            </div>
            <div class="flex items-center gap-2">
                <button @click="zoomOut()" title="Perkecil" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition">
                    <i class="bi bi-zoom-out text-sm"></i>
                </button>
                <button @click="resetZoom()" title="Reset Ukuran (100%)" class="px-2.5 h-8 rounded-lg bg-white/10 hover:bg-white/20 text-white text-xs font-semibold flex items-center justify-center transition">
                    <span x-text="Math.round(lightbox.zoom * 100) + '%'"></span>
                </button>
                <button @click="zoomIn()" title="Perbesar" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition">
                    <i class="bi bi-zoom-in text-sm"></i>
                </button>
                <a :href="lightbox.url" download="cendekia_preview.jpg" target="_blank"
                   title="Unduh Gambar Resolusi Penuh"
                   class="px-3 h-8 rounded-lg bg-[#720c16] hover:bg-[#8a1824] text-white text-xs font-semibold flex items-center gap-1.5 transition ml-2 shadow-sm">
                    <i class="bi bi-download"></i>
                    <span class="hidden sm:inline">Unduh</span>
                </a>
                <button @click="closeLightbox()" title="Tutup (ESC)" class="w-8 h-8 rounded-lg bg-white/15 hover:bg-white/30 text-white flex items-center justify-center transition ml-1">
                    <i class="bi bi-x-lg text-sm"></i>
                </button>
            </div>
        </div>

        {{-- Lightbox Image Stage --}}
        <div class="flex-1 w-full max-w-5xl flex items-center justify-center overflow-auto p-2" @click.self="closeLightbox()">
            <img :src="lightbox.url"
                 :style="'transform: scale(' + lightbox.zoom + '); transition: transform 0.15s ease-out;'"
                 class="max-h-[82vh] max-w-full object-contain rounded-xl shadow-2xl"
                 @click.stop>
        </div>

        {{-- Lightbox Footer Info --}}
        <div class="text-[11px] text-white/50 pt-2 text-center">
            Tekan <kbd class="px-1.5 py-0.5 rounded bg-white/20 text-white font-mono">ESC</kbd> atau klik area luar untuk menutup
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function cendekiaGptApp() {
    return {
        // App State
        sidebarOpen: window.innerWidth >= 1024,
        searchQuery: '',
        inputMessage: '',
        isLoading: false,
        abortController: null,
        activeChatId: null,
        conversations: [],
        messages: [],
        speechActiveIndex: null,
        speechSynthesisUtterance: null,
        speechRecognition: null,
        speechRecognitionSupported: false,
        isListening: false,
        toast: {
            show: false,
            message: '',
            timer: null
        },

        // File Attachment & Image Generation State
        attachedFile: null,
        isDragging: false,
        isImageMode: false,
        lightbox: {
            show: false,
            url: '',
            title: '',
            zoom: 1
        },

        // Storage Key per user
        storageKey: 'cendekia_ai_chats_{{ auth()->id() }}',

        initApp() {
            this.loadConversations();
            this.initSpeechRecognition();

            // Auto-resize on window resize
            window.addEventListener('resize', () => {
                if (window.innerWidth < 1024 && this.sidebarOpen) {
                    this.sidebarOpen = false;
                }
            });

            // Global click handler for copy code buttons inside rendered markdown
            document.addEventListener('click', (e) => {
                const btn = e.target.closest('.ai-code-copy-btn');
                if (btn) {
                    const code = btn.dataset.code ? decodeURIComponent(btn.dataset.code) : '';
                    if (code) {
                        navigator.clipboard.writeText(code).then(() => {
                            const originalHTML = btn.innerHTML;
                            btn.innerHTML = '<i class="bi bi-check2 text-emerald-400"></i> Tersalin! ✓';
                            btn.classList.add('text-emerald-400');
                            setTimeout(() => {
                                btn.innerHTML = originalHTML;
                                btn.classList.remove('text-emerald-400');
                            }, 2000);
                        });
                    }
                }
            });
        },

        // ----------------------------------------------------
        // CONVERSATIONS MANAGEMENT (LOCALSTORAGE)
        // ----------------------------------------------------
        loadConversations() {
            try {
                const raw = localStorage.getItem(this.storageKey);
                if (raw) {
                    this.conversations = JSON.parse(raw);
                }
            } catch (e) {
                console.error('Failed to parse chat history from localStorage', e);
                this.conversations = [];
            }

            if (this.conversations.length > 0) {
                this.selectChat(this.conversations[0].id);
            } else {
                this.createNewChat();
            }
        },

        saveConversations() {
            try {
                localStorage.setItem(this.storageKey, JSON.stringify(this.conversations, (key, value) => {
                    return key === 'attachmentContext' ? undefined : value;
                }));
            } catch (e) {
                console.error('Failed to save chat history to localStorage', e);
            }
        },

        createNewChat() {
            const newChat = {
                id: 'chat_' + Date.now(),
                title: 'Obrolan Baru',
                createdAt: Date.now(),
                updatedAt: Date.now(),
                messages: []
            };

            this.conversations.unshift(newChat);
            this.saveConversations();
            this.selectChat(newChat.id);

            if (window.innerWidth < 1024) {
                this.sidebarOpen = false;
            }

            this.$nextTick(() => {
                if (this.$refs.chatInput) this.$refs.chatInput.focus();
            });
        },

        selectChat(chatId) {
            this.stopSpeech();
            const chat = this.conversations.find(c => c.id === chatId);
            if (chat) {
                this.activeChatId = chat.id;
                this.messages = chat.messages || [];
                this.scrollToBottom();
            }
        },

        deleteChat(chatId) {
            if (!confirm('Apakah Anda yakin ingin menghapus riwayat obrolan ini?')) return;

            this.conversations = this.conversations.filter(c => c.id !== chatId);
            this.saveConversations();
            this.showToast('Obrolan berhasil dihapus');

            if (this.activeChatId === chatId) {
                if (this.conversations.length > 0) {
                    this.selectChat(this.conversations[0].id);
                } else {
                    this.createNewChat();
                }
            }
        },

        renameChat(chatId) {
            const chat = this.conversations.find(c => c.id === chatId);
            if (!chat) return;

            const newTitle = prompt('Ubah judul obrolan:', chat.title);
            if (newTitle && newTitle.trim()) {
                chat.title = newTitle.trim();
                chat.updatedAt = Date.now();
                this.saveConversations();
                this.showToast('Judul obrolan diperbarui');
            }
        },

        clearAllChats() {
            if (!confirm('Hapus seluruh riwayat percakapan AI Anda? Tindakan ini tidak dapat dibatalkan.')) return;

            this.stopSpeech();
            this.conversations = [];
            this.saveConversations();
            this.createNewChat();
            this.showToast('Semua riwayat obrolan telah dibersihkan');
        },

        clearCurrentChat() {
            if (!confirm('Kosongkan semua pesan di obrolan saat ini?')) return;

            this.stopSpeech();
            this.messages = [];
            const chat = this.conversations.find(c => c.id === this.activeChatId);
            if (chat) {
                chat.messages = [];
                chat.title = 'Obrolan Baru';
                chat.updatedAt = Date.now();
                this.saveConversations();
            }
            this.showToast('Pesan obrolan dikosongkan');
        },

        get currentChatTitle() {
            const chat = this.conversations.find(c => c.id === this.activeChatId);
            return chat ? chat.title : 'Obrolan Baru';
        },

        get filteredConversations() {
            if (!this.searchQuery.trim()) return this.conversations;
            const q = this.searchQuery.toLowerCase();
            return this.conversations.filter(c => {
                if (c.title.toLowerCase().includes(q)) return true;
                return (c.messages || []).some(m => m.content.toLowerCase().includes(q));
            });
        },

        get groupedConversations() {
            const now = Date.now();
            const oneDay = 24 * 60 * 60 * 1000;
            const todayStart = new Date().setHours(0,0,0,0);
            const yesterdayStart = todayStart - oneDay;
            const sevenDaysStart = todayStart - (7 * oneDay);

            const groups = {
                today: [],
                yesterday: [],
                previous7Days: [],
                older: []
            };

            this.filteredConversations.forEach(chat => {
                const time = chat.updatedAt || chat.createdAt || now;
                if (time >= todayStart) {
                    groups.today.push(chat);
                } else if (time >= yesterdayStart) {
                    groups.yesterday.push(chat);
                } else if (time >= sevenDaysStart) {
                    groups.previous7Days.push(chat);
                } else {
                    groups.older.push(chat);
                }
            });

            return groups;
        },

        // ----------------------------------------------------
        // MESSAGING & CHAT ACTIONS
        // ----------------------------------------------------
        setInputText(text) {
            this.inputMessage = text;
            this.autoResizeInput();
            this.$nextTick(() => {
                if (this.$refs.chatInput) this.$refs.chatInput.focus();
            });
        },

        formatFileSize(bytes) {
            return bytes < 1024 * 1024
                ? `${Math.max(1, Math.round(bytes / 1024))} KB`
                : `${(bytes / (1024 * 1024)).toFixed(1)} MB`;
        },

        sendSuggestion(promptText) {
            this.inputMessage = promptText;
            this.sendMessage();
        },

        handleEnterKey(e) {
            if (!e.shiftKey) {
                e.preventDefault();
                this.sendMessage();
            }
        },

        autoResizeInput() {
            if (this.$refs.chatInput) {
                this.$refs.chatInput.style.height = 'auto';
                this.$refs.chatInput.style.height = Math.min(this.$refs.chatInput.scrollHeight, 160) + 'px';
            }
        },

        getCurrentTime() {
            const now = new Date();
            return now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
        },

        // ----------------------------------------------------
        // FILE & ATTACHMENT HANDLERS
        // ----------------------------------------------------
        getFileIconClass(type, ext) {
            const e = (ext || '').toLowerCase();
            if (type === 'pdf' || e === 'pdf') return 'bi bi-file-earmark-pdf-fill text-red-500';
            if (type === 'word' || e === 'doc' || e === 'docx') return 'bi bi-file-earmark-word-fill text-blue-500';
            if (type === 'excel' || e === 'xls' || e === 'xlsx' || e === 'csv') return 'bi bi-file-earmark-excel-fill text-emerald-500';
            if (type === 'code' || ['py', 'php', 'js', 'ts', 'html', 'css', 'sql', 'json'].includes(e)) return 'bi bi-file-earmark-code-fill text-purple-500';
            if (type === 'image' || ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'].includes(e)) return 'bi bi-file-earmark-image-fill text-pink-500';
            return 'bi bi-file-earmark-text-fill text-amber-500';
        },

        handleFileSelect(e) {
            const files = e.target.files;
            e.target.value = '';
            if (files && files.length > 0) {
                this.processFile(files[0]);
            }
        },

        handleFileDrop(e) {
            this.isDragging = false;
            if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length > 0) {
                this.processFile(e.dataTransfer.files[0]);
            }
        },

        handlePaste(e) {
            if (e.clipboardData && e.clipboardData.items) {
                for (let i = 0; i < e.clipboardData.items.length; i++) {
                    const item = e.clipboardData.items[i];
                    if (item.kind === 'file') {
                        const file = item.getAsFile();
                        if (file) {
                            this.processFile(file);
                            e.preventDefault();
                            this.showToast('Berkas dari clipboard berhasil dilampirkan!');
                            return;
                        }
                    }
                }
            }
        },

        processFile(file) {
            const allowedExtensions = ['pdf', 'docx', 'txt', 'md', 'csv', 'png', 'jpg', 'jpeg', 'webp'];
            const ext = file.name.split('.').pop().toLowerCase();
            if (!allowedExtensions.includes(ext)) {
                this.showToast('Format tidak didukung. Pilih PDF, DOCX, TXT, MD, CSV, PNG, JPG, atau WEBP.');
                return;
            }
            if (file.size > 10 * 1024 * 1024) {
                this.showToast('Ukuran berkas maksimal 10 MB.');
                return;
            }

            const isImage = ['jpg', 'jpeg', 'png', 'webp'].includes(ext);

            let type = 'document';
            if (isImage) type = 'image';
            else if (ext === 'pdf') type = 'pdf';
            else if (['doc', 'docx'].includes(ext)) type = 'word';
            else if (['xls', 'xlsx', 'csv'].includes(ext)) type = 'excel';
            else if (['py', 'php', 'js', 'ts', 'html', 'css', 'sql', 'json'].includes(ext)) type = 'code';
            else if (['txt', 'md'].includes(ext)) type = 'text';

            let previewUrl = '';
            if (isImage) {
                previewUrl = URL.createObjectURL(file);
            }

            // Format file size
            let sizeFormatted = '';
            if (file.size >= 1048576) {
                sizeFormatted = (file.size / 1048576).toFixed(1) + ' MB';
            } else if (file.size >= 1024) {
                sizeFormatted = Math.round(file.size / 1024) + ' KB';
            } else {
                sizeFormatted = file.size + ' B';
            }

            this.attachedFile = {
                file: file,
                name: file.name,
                size: file.size,
                sizeFormatted: sizeFormatted,
                extension: ext,
                type: type,
                is_image: isImage,
                previewUrl: previewUrl
            };

            this.showToast(`Berkas "${file.name}" siap dianalisis!`);
            this.$nextTick(() => {
                if (this.$refs.chatInput) this.$refs.chatInput.focus();
            });
        },

        removeAttachedFile() {
            if (this.attachedFile && this.attachedFile.previewUrl && this.attachedFile.is_image) {
                URL.revokeObjectURL(this.attachedFile.previewUrl);
            }
            this.attachedFile = null;
            if (this.$refs.fileInput) {
                this.$refs.fileInput.value = '';
            }
        },

        // ----------------------------------------------------
        // LIGHTBOX VIEWER HANDLERS
        // ----------------------------------------------------
        openLightbox(url, title) {
            this.lightbox.url = url;
            this.lightbox.title = title || 'Tampilan Gambar AI';
            this.lightbox.zoom = 1;
            this.lightbox.show = true;
        },

        closeLightbox() {
            this.lightbox.show = false;
            this.lightbox.zoom = 1;
        },

        zoomIn() {
            this.lightbox.zoom = Math.min(this.lightbox.zoom + 0.25, 3.0);
        },

        zoomOut() {
            this.lightbox.zoom = Math.max(this.lightbox.zoom - 0.25, 0.5);
        },

        resetZoom() {
            this.lightbox.zoom = 1;
        },

        async sendMessage() {
            const text = this.inputMessage.trim();
            if ((!text && !this.attachedFile) || this.isLoading) return;
            if (this.isImageMode && !text) {
                this.showToast('Tuliskan deskripsi gambar yang ingin dibuat.');
                return;
            }
            if (this.isImageMode && this.attachedFile) {
                this.showToast('Lepaskan lampiran sebelum membuat gambar AI.');
                return;
            }

            // Stop speech if currently speaking
            this.stopSpeech();

            // Copy attached file info for user bubble
            let fileInfo = null;
            let fileToUpload = null;
            if (this.attachedFile) {
                fileToUpload = this.attachedFile.file;
                fileInfo = {
                    name: this.attachedFile.name,
                    size: this.attachedFile.sizeFormatted,
                    extension: this.attachedFile.extension,
                    type: this.attachedFile.type,
                    is_image: this.attachedFile.is_image
                };
            }

            const currentMode = this.isImageMode ? 'image' : null;

            // Append User Message
            const userMsg = {
                role: 'user',
                content: text || 'Analisis dokumen yang saya lampirkan.',
                file_info: fileInfo,
                time: this.getCurrentTime()
            };
            this.messages.push(userMsg);

            this.inputMessage = '';
            this.removeAttachedFile();
            this.isImageMode = false;
            this.autoResizeInput();
            this.isLoading = true;
            this.scrollToBottom();

            // Update conversation title if this is the first message
            const currentChat = this.conversations.find(c => c.id === this.activeChatId);
            if (currentChat) {
                if (currentChat.messages.length === 0 || currentChat.title === 'Obrolan Baru') {
                    const promptTitle = text || (fileInfo ? `Analisis ${fileInfo.name}` : 'Obrolan AI');
                    currentChat.title = promptTitle.length > 36 ? promptTitle.substring(0, 36) + '...' : promptTitle;
                }
                currentChat.messages = this.messages;
                currentChat.updatedAt = Date.now();
                this.saveConversations();
            }

            try {
                const historyPayload = this.messages.slice(0, -1).map(m => ({
                    role: m.role,
                    content: m.content,
                    attachment_context: m.attachment_context || null
                }));
                const headers = { 'X-CSRF-TOKEN': '{{ csrf_token() }}' };
                let response;

                if (currentMode === 'image') {
                    response = await fetch('{{ route('dosen.ai-assistant.generate-image') }}', {
                        method: 'POST',
                        headers: { ...headers, 'Content-Type': 'application/json' },
                        body: JSON.stringify({ prompt: text })
                    });
                } else {
                    const formData = new FormData();
                    formData.append('message', text);
                    formData.append('history', JSON.stringify(historyPayload));
                    if (fileToUpload) formData.append('file', fileToUpload);

                    response = await fetch('{{ route('dosen.ai-assistant.chat') }}', {
                        method: 'POST',
                        headers,
                        body: formData
                    });
                }

                const data = await response.json();

                if (data.success && data.message) {
                    userMsg.attachment_context = data.attachment_context || null;
                    this.messages.push({
                        role: 'assistant',
                        content: data.message,
                        type: data.type || 'text',
                        image_url: data.image_url || null,
                        prompt: data.prompt || null,
                        time: this.getCurrentTime(),
                        rating: null
                    });
                } else {
                    this.messages.push({
                        role: 'assistant',
                        content: '⚠️ **Terjadi Kendala:** ' + (data.error || 'Layanan AI sedang tidak dapat dihubungi. Silakan coba lagi.'),
                        time: this.getCurrentTime()
                    });
                }
            } catch (error) {
                if (error.name === 'AbortError') return;
                console.error('Chat error:', error);
                this.messages.push({
                    role: 'assistant',
                    type: 'text',
                    content: '⚠️ **Koneksi Terputus:** Tidak dapat menghubungi server. Periksa jaringan internet atau konfigurasi sistem.',
                    time: this.getCurrentTime()
                });
            } finally {
                this.isLoading = false;
                this.abortController = null;
                if (currentChat) {
                    currentChat.messages = this.messages;
                    currentChat.updatedAt = Date.now();
                    this.saveConversations();
                }
                this.scrollToBottom();
            }
        },

        regenerateLast() {
            if (this.isLoading) return;
            // Find last user message
            let lastUserMsg = null;
            for (let i = this.messages.length - 1; i >= 0; i--) {
                if (this.messages[i].role === 'user') {
                    lastUserMsg = this.messages[i].content;
                    break;
                }
            }

            if (lastUserMsg) {
                this.inputMessage = lastUserMsg;
                this.sendMessage();
            }
        },

        stopGeneration() {
            if (this.abortController) this.abortController.abort();
            this.showToast('Pembuatan dihentikan');
        },

        editMessage(content) {
            this.inputMessage = content;
            this.autoResizeInput();
            this.$nextTick(() => {
                if (this.$refs.chatInput) {
                    this.$refs.chatInput.focus();
                    this.$refs.chatInput.setSelectionRange(content.length, content.length);
                }
            });
        },

        rateMessage(index, type) {
            if (this.messages[index]) {
                this.messages[index].rating = this.messages[index].rating === type ? null : type;
                const currentChat = this.conversations.find(c => c.id === this.activeChatId);
                if (currentChat) {
                    currentChat.messages = this.messages;
                    this.saveConversations();
                }
                this.showToast(type === 'up' ? 'Terima kasih atas tanggapan positif Anda!' : 'Terima kasih atas umpan balik Anda.');
            }
        },

        // ----------------------------------------------------
        // UTILITIES: COPY, EXPORT, SPEECH, TOAST
        // ----------------------------------------------------
        copyToClipboard(text, customMessage) {
            if (!navigator.clipboard) {
                const ta = document.createElement('textarea');
                ta.value = text;
                document.body.appendChild(ta);
                ta.select();
                document.execCommand('copy');
                document.body.removeChild(ta);
                this.showToast(customMessage || 'Tersalin ke clipboard!');
                return;
            }

            navigator.clipboard.writeText(text).then(() => {
                this.showToast(customMessage || 'Tersalin ke clipboard!');
            }).catch(err => {
                console.error('Clipboard error', err);
            });
        },

        showToast(msg) {
            this.toast.message = msg;
            this.toast.show = true;
            if (this.toast.timer) clearTimeout(this.toast.timer);
            this.toast.timer = setTimeout(() => {
                this.toast.show = false;
            }, 2500);
        },

        exportChat() {
            if (this.messages.length === 0) return;

            let output = `# ${this.currentChatTitle}\nTanggal: ${new Date().toLocaleString('id-ID')}\nUniversitas Cendekia - Asisten AI Dosen\n\n---\n\n`;
            this.messages.forEach(m => {
                const sender = m.role === 'user' ? 'DOSEN' : 'ASISTEN AI CENDEKIA';
                output += `### ${sender} (${m.time || ''})\n\n${m.content}\n\n---\n\n`;
            });

            const blob = new Blob([output], { type: 'text/markdown;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `${this.currentChatTitle.replace(/[^a-zA-Z0-9_-]/g, '_')}.md`;
            a.click();
            URL.revokeObjectURL(url);
            this.showToast('Obrolan berhasil diekspor sebagai Markdown (.md)');
        },

        toggleSpeech(text, index) {
            if (!('speechSynthesis' in window)) {
                this.showToast('Fitur suara tidak didukung pada peramban ini.');
                return;
            }

            if (this.speechActiveIndex === index) {
                this.stopSpeech();
                return;
            }

            this.stopSpeech();
            this.speechActiveIndex = index;

            // Strip markdown tags for natural speech
            const cleanText = text
                .replace(/```[\s\S]*?```/g, 'Blok kode dilewati.')
                .replace(/[#*`_~>[\]]/g, '')
                .replace(/\|.*?\|/g, '');

            const utterance = new SpeechSynthesisUtterance(cleanText);
            utterance.lang = 'id-ID';
            utterance.rate = 1.0;

            utterance.onend = () => {
                this.speechActiveIndex = null;
            };
            utterance.onerror = () => {
                this.speechActiveIndex = null;
            };

            this.speechSynthesisUtterance = utterance;
            window.speechSynthesis.speak(utterance);
        },

        stopSpeech() {
            if ('speechSynthesis' in window && window.speechSynthesis.speaking) {
                window.speechSynthesis.cancel();
            }
            this.speechActiveIndex = null;
        },

        initSpeechRecognition() {
            const SpeechRec = window.SpeechRecognition || window.webkitSpeechRecognition;
            if (SpeechRec) {
                this.speechRecognitionSupported = true;
                const rec = new SpeechRec();
                rec.lang = 'id-ID';
                rec.continuous = false;
                rec.interimResults = false;

                rec.onresult = (event) => {
                    const transcript = event.results[0][0].transcript;
                    this.inputMessage = (this.inputMessage ? this.inputMessage + ' ' : '') + transcript;
                    this.autoResizeInput();
                    this.isListening = false;
                    this.showToast('Suara berhasil diubah ke teks');
                };

                rec.onerror = () => {
                    this.isListening = false;
                };

                rec.onend = () => {
                    this.isListening = false;
                };

                this.speechRecognition = rec;
            }
        },

        toggleVoiceInput() {
            if (!this.speechRecognition) return;

            if (this.isListening) {
                this.speechRecognition.stop();
                this.isListening = false;
            } else {
                try {
                    this.speechRecognition.start();
                    this.isListening = true;
                    this.showToast('Mendengarkan suara Anda...');
                } catch (e) {
                    this.isListening = false;
                }
            }
        },

        scrollToBottom() {
            this.$nextTick(() => {
                setTimeout(() => {
                    const container = document.getElementById('chat-messages-container');
                    if (container) {
                        container.scrollTop = container.scrollHeight;
                    }
                }, 80);
            });
        },

        // ----------------------------------------------------
        // BULLETPROOF MARKDOWN & CODE PARSER
        // ----------------------------------------------------
        renderMarkdown(rawText) {
            if (!rawText) return '';

            // Escape HTML entities to prevent XSS
            let text = rawText
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;');

            // 1. Code blocks: ```lang ... ```
            text = text.replace(/```([a-zA-Z0-9_-]*)\n([\s\S]*?)```/g, (match, lang, code) => {
                const language = (lang || 'TEXT').toUpperCase();
                const rawCodeEscaped = encodeURIComponent(code.replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&amp;/g, '&'));
                return `<div class="ai-code-block">
                    <div class="ai-code-header">
                        <span><i class="bi bi-file-earmark-code me-1"></i> ${language}</span>
                        <button type="button" class="ai-code-copy-btn" data-code="${rawCodeEscaped}">
                            <i class="bi bi-clipboard"></i> Salin Kode
                        </button>
                    </div>
                    <pre class="ai-code-pre"><code>${code}</code></pre>
                </div>`;
            });

            // 2. Tables: | col1 | col2 |
            text = text.replace(/((?:\|[^\n]+\|\r?\n)+)/g, (tableBlock) => {
                const lines = tableBlock.trim().split('\n').filter(l => l.trim().startsWith('|'));
                if (lines.length < 2) return tableBlock;

                let html = '<div class="table-wrap"><table>';
                let isHeader = true;

                lines.forEach((line, idx) => {
                    // Check if separator line (|---|---|)
                    if (line.includes('---')) {
                        isHeader = false;
                        return;
                    }

                    const cells = line.split('|').slice(1, -1).map(c => c.trim());
                    if (isHeader && idx === 0) {
                        html += '<thead><tr>' + cells.map(c => `<th>${c}</th>`).join('') + '</tr></thead><tbody>';
                    } else {
                        html += '<tr>' + cells.map(c => `<td>${c}</td>`).join('') + '</tr>';
                    }
                });

                html += '</tbody></table></div>';
                return html;
            });

            // 3. Headers
            text = text.replace(/^#### (.*$)/gim, '<h4>$1</h4>');
            text = text.replace(/^### (.*$)/gim, '<h3>$1</h3>');
            text = text.replace(/^## (.*$)/gim, '<h2>$1</h2>');
            text = text.replace(/^# (.*$)/gim, '<h1>$1</h1>');

            // 4. Blockquotes
            text = text.replace(/^\> (.*$)/gim, '<blockquote>$1</blockquote>');

            // 5. Bold & Italic
            text = text.replace(/\*\*\*(.*?)\*\*\*/gim, '<strong><em>$1</em></strong>');
            text = text.replace(/\*\*(.*?)\*\*/gim, '<strong>$1</strong>');
            text = text.replace(/\*(.*?)\*/gim, '<em>$1</em>');
            text = text.replace(/~~(.*?)~~/gim, '<del>$1</del>');

            // 6. Inline code
            text = text.replace(/`([^`]+)`/g, '<code class="ai-inline-code">$1</code>');

            // 7. Lists
            text = text.replace(/^\s*[-*]\s+(.*$)/gim, '<li>$1</li>');
            text = text.replace(/(<li>[\s\S]*?<\/li>)/gim, '<ul>$1</ul>');

            text = text.replace(/^\s*(\d+)\.\s+(.*$)/gim, '<li>$2</li>');
            // Fix double nested list tags
            text = text.replace(/<\/ul>\s*<ul>/g, '');

            // 8. Paragraphs / Line Breaks
            text = text.replace(/\n\n+/g, '</p><p>');
            text = text.replace(/\n/g, '<br>');

            return '<p>' + text + '</p>';
        }
    };
}
</script>
@endpush