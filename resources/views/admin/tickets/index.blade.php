@extends('layouts.portal')

@section('title', 'Pusat Bantuan (Ticketing) - Admin')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    {{-- ===== HEADER ===== --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                Pusat Bantuan (Helpdesk)
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                Kelola dan tanggapi tiket kendala teknis serta administrasi dari mahasiswa & dosen.
            </p>
        </div>
    </div>

    {{-- ===== STAT CARDS ===== --}}
    @php
        $total = \App\Models\Ticket::count();
        $open = \App\Models\Ticket::where('status', 'open')->count();
        $responded = \App\Models\Ticket::where('status', 'responded')->count();
        $closed = \App\Models\Ticket::where('status', 'closed')->count();
    @endphp
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 shadow-sm">
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Tiket</p>
            <p class="mt-2 text-3xl font-extrabold text-slate-800 dark:text-white">{{ $total }}</p>
        </div>
        <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 shadow-sm">
            <p class="text-[11px] font-bold uppercase tracking-wider text-orange-500">Terbuka (Open)</p>
            <p class="mt-2 text-3xl font-extrabold text-orange-600 dark:text-orange-500">{{ $open }}</p>
        </div>
        <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 shadow-sm">
            <p class="text-[11px] font-bold uppercase tracking-wider text-blue-500">Dijawab (Responded)</p>
            <p class="mt-2 text-3xl font-extrabold text-blue-600 dark:text-blue-500">{{ $responded }}</p>
        </div>
        <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 shadow-sm">
            <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-500">Selesai (Closed)</p>
            <p class="mt-2 text-3xl font-extrabold text-emerald-600 dark:text-emerald-500">{{ $closed }}</p>
        </div>
    </div>

    {{-- ===== FILTERS & SEARCH ===== --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.help-center.tickets') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Pencarian</label>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Subjek, nama, email..."
                    class="w-full h-10 px-3.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-transparent text-slate-800 dark:text-white placeholder:text-slate-400 text-xs focus:ring-2 focus:ring-[#002B6B] dark:focus:ring-blue-600 outline-none transition-all"
                >
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Status</label>
                <select name="status" class="w-full h-10 px-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-300 text-xs focus:ring-2 focus:ring-[#002B6B] dark:focus:ring-blue-600 outline-none transition-all">
                    <option value="">Semua Status</option>
                    @foreach($statuses as $key => $label)
                        <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Kategori</label>
                <select name="category" class="w-full h-10 px-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-300 text-xs focus:ring-2 focus:ring-[#002B6B] dark:focus:ring-blue-600 outline-none transition-all">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $key => $label)
                        <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 h-10 rounded-xl bg-[#002B6B] dark:bg-blue-600 hover:bg-blue-800 dark:hover:bg-blue-700 text-white text-xs font-bold transition shadow-md shadow-blue-700/10">
                    Filter
                </button>
                <a href="{{ route('admin.help-center.tickets') }}" class="h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 flex items-center justify-center text-xs font-bold transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- ===== TABLE LIST ===== --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-950 border-b border-slate-200 dark:border-slate-800 text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-wider">
                        <th class="px-6 py-4 text-center w-16">No</th>
                        <th class="px-6 py-4">Pengirim</th>
                        <th class="px-6 py-4">Subjek / Masalah</th>
                        <th class="px-6 py-4 text-center">Kategori</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Dibuat</th>
                        <th class="px-6 py-4 text-center w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs text-slate-700 dark:text-slate-300">
                    @forelse ($tickets as $index => $ticket)
                        @php
                            $statusColors = [
                                'open' => 'bg-orange-50 text-orange-700 border-orange-200 dark:bg-orange-500/10 dark:text-orange-400 dark:border-orange-500/20',
                                'responded' => 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-500/10 dark:text-blue-400 dark:border-blue-500/20',
                                'closed' => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20',
                            ];
                            $statusLabels = [
                                'open' => 'Terbuka',
                                'responded' => 'Dijawab',
                                'closed' => 'Tertutup',
                            ];
                        @endphp
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4 text-center font-semibold text-slate-400">
                                {{ ($tickets->currentPage() - 1) * $tickets->perPage() + $index + 1 }}
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-800 dark:text-white text-sm mb-0.5">{{ $ticket->name }}</p>
                                <p class="text-[11px] text-slate-400">{{ $ticket->email }}</p>
                            </td>
                            <td class="px-6 py-4 max-w-[280px]">
                                <p class="font-bold text-slate-800 dark:text-white truncate" title="{{ $ticket->subject }}">
                                    {{ $ticket->subject }}
                                </p>
                                <p class="text-[11px] text-slate-400 truncate mt-0.5">
                                    {{ $ticket->message }}
                                </p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-[10px] font-bold text-slate-600 dark:text-slate-400">
                                    {{ $categories[$ticket->category] ?? ucfirst($ticket->category) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 border rounded-lg text-[10px] font-extrabold tracking-wide uppercase {{ $statusColors[$ticket->status] ?? 'bg-slate-50 text-slate-700 border-slate-200' }}">
                                    {{ $statusLabels[$ticket->status] ?? $ticket->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-slate-400">
                                {{ $ticket->created_at->format('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('admin.help-center.ticket-detail', $ticket->id) }}" class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg bg-slate-50 dark:bg-slate-800 hover:bg-[#002B6B] dark:hover:bg-blue-600 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:text-white dark:hover:text-white font-bold text-[11px] transition shadow-sm">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center text-slate-400">
                                <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-50 dark:bg-slate-800/50 flex items-center justify-center mb-4 border border-slate-100 dark:border-slate-800">
                                    <svg class="w-8 h-8 text-slate-300 dark:text-slate-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <h3 class="font-bold text-slate-700 dark:text-white text-base">Tidak Ada Tiket</h3>
                                <p class="text-xs text-slate-400 mt-1">Belum ada tiket bantuan yang masuk atau hasil filter kosong.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($tickets->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
