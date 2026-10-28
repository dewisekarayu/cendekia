@extends('layouts.admin')

@section('title', 'Pusat Bantuan (Ticketing) - Admin')

@push('styles')
<style>
    /* Dark mode styling khusus halaman daftar tiket pusat bantuan admin */
    html.dark .ticket-stat-card {
        background-color: #063c43 !important;
        border-color: rgba(94, 234, 212, 0.2) !important;
    }
    html.dark .ticket-filter-card {
        background-color: #063c43 !important;
        border-color: rgba(94, 234, 212, 0.2) !important;
    }
    html.dark .ticket-table-card {
        background-color: #063c43 !important;
        border-color: rgba(94, 234, 212, 0.2) !important;
    }
    html.dark .ticket-table-card table thead tr {
        background-color: #042f35 !important;
        border-bottom-color: rgba(94, 234, 212, 0.15) !important;
    }
    html.dark .ticket-table-card table thead th {
        color: #5eead4 !important;
    }
    html.dark .ticket-filter-input {
        background-color: #03252a !important;
        border-color: rgba(94, 234, 212, 0.25) !important;
        color: #f0fdfa !important;
    }
    html.dark .ticket-filter-input:focus {
        border-color: #2dd4bf !important;
    }
    html.dark .ticket-filter-btn {
        background-color: #0f766e !important;
        color: #ffffff !important;
    }
    html.dark .ticket-filter-btn:hover {
        background-color: #115e59 !important;
    }
    html.dark .ticket-reset-btn {
        border-color: rgba(94, 234, 212, 0.25) !important;
        color: #5eead4 !important;
    }
    html.dark .ticket-reset-btn:hover {
        background-color: #042f35 !important;
    }
    html.dark .ticket-category-pill {
        background-color: #03252a !important;
        color: #5eead4 !important;
        border: 1px solid rgba(94, 234, 212, 0.2) !important;
    }
</style>
@endpush

@section('content')
<div class="space-y-6 max-w-7xl mx-auto px-4 sm:px-0">
    
    {{-- ===== HEADER ===== --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-800 dark:text-white">Pusat Bantuan (Helpdesk)</h1>
            <p class="text-xs text-gray-500 dark:text-slate-300 mt-1">
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
        <div class="ticket-stat-card bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700/80 p-4 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-slate-300">Total Tiket</p>
            <p class="mt-1 text-2xl font-bold text-gray-850 dark:text-white">{{ $total }}</p>
        </div>
        <div class="ticket-stat-card bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700/80 p-4 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-orange-500 dark:text-orange-400">Terbuka (Open)</p>
            <p class="mt-1 text-2xl font-bold text-orange-600 dark:text-orange-400">{{ $open }}</p>
        </div>
        <div class="ticket-stat-card bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700/80 p-4 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-teal-600 dark:text-teal-300">Dijawab (Responded)</p>
            <p class="mt-1 text-2xl font-bold text-teal-600 dark:text-teal-300">{{ $responded }}</p>
        </div>
        <div class="ticket-stat-card bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700/80 p-4 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Selesai (Closed)</p>
            <p class="mt-1 text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $closed }}</p>
        </div>
    </div>

    {{-- ===== FILTERS & SEARCH ===== --}}
    <div class="ticket-filter-card bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700/80 p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.help-center.tickets') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 sm:gap-4 items-end">
            <input type="hidden" name="per_page" value="{{ $perPage ?? 10 }}">
            <div class="sm:col-span-5">
                <label class="block text-xs font-bold text-gray-400 dark:text-slate-300 uppercase mb-2">Pencarian</label>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Subjek, nama, email..."
                    class="ticket-filter-input w-full h-10 px-3 rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900/60 text-gray-800 dark:text-slate-100 placeholder:text-gray-400 dark:placeholder:text-slate-400 text-xs focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition-all"
                >
            </div>
            <div class="sm:col-span-3">
                <label class="block text-xs font-bold text-gray-400 dark:text-slate-300 uppercase mb-2">Status</label>
                <select name="status" class="ticket-filter-input w-full h-10 px-3 rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900/60 text-gray-800 dark:text-slate-100 text-xs focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition-all">
                    <option value="">Semua Status</option>
                    @foreach($statuses as $key => $label)
                        <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-gray-400 dark:text-slate-300 uppercase mb-2">Kategori</label>
                <select name="category" class="ticket-filter-input w-full h-10 px-3 rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900/60 text-gray-800 dark:text-slate-100 text-xs focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition-all">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $key => $label)
                        <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="sm:col-span-2 flex gap-2">
                <button type="submit" class="ticket-filter-btn flex-1 h-10 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-medium transition shadow-sm">
                    Filter
                </button>
                <a href="{{ route('admin.help-center.tickets') }}" class="ticket-reset-btn h-10 px-3 rounded-lg border border-gray-250 dark:border-slate-700 text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-teal-900/40 flex items-center justify-center text-xs font-medium transition" title="Reset">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
        </form>
    </div>

    {{-- ===== TABLE LIST ===== --}}
    <div class="ticket-table-card bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-left text-gray-400 dark:text-slate-300 text-xs border-b border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-900/60">
                        <th class="px-5 py-3 font-medium text-center w-16">NO</th>
                        <th class="px-5 py-3 font-medium">Pengirim</th>
                        <th class="px-5 py-3 font-medium">Subjek / Masalah</th>
                        <th class="px-5 py-3 font-medium text-center">Kategori</th>
                        <th class="px-5 py-3 font-medium text-center">Status</th>
                        <th class="px-5 py-3 font-medium text-center">Dibuat</th>
                        <th class="px-5 py-3 font-medium text-right w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-teal-500/15 text-gray-700 dark:text-slate-200">
                    @forelse ($tickets as $index => $ticket)
                        @php
                            $statusColors = [
                                'open' => 'bg-orange-50 dark:bg-amber-950/60 text-orange-700 dark:text-amber-300 border-orange-200 dark:border-amber-700/60',
                                'responded' => 'bg-blue-50 dark:bg-teal-950/60 text-blue-700 dark:text-teal-300 border-blue-250 dark:border-teal-700/60',
                                'closed' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-250 dark:border-emerald-700/60',
                            ];
                            $statusLabels = [
                                'open' => 'Terbuka',
                                'responded' => 'Dijawab',
                                'closed' => 'Tertutup',
                            ];
                        @endphp
                        <tr class="hover:bg-gray-50/40 dark:hover:bg-teal-900/20 transition-colors">
                            <td class="px-5 py-3.5 text-center font-bold text-gray-500 dark:text-slate-300 font-mono">
                                {{ ($tickets->currentPage() - 1) * $tickets->perPage() + $index + 1 }}
                            </td>
                            <td class="px-5 py-3.5">
                                <p class="font-semibold text-gray-800 dark:text-slate-100 mb-0.5">{{ $ticket->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-slate-300">{{ $ticket->email }}</p>
                            </td>
                            <td class="px-5 py-3.5 max-w-[280px]">
                                <p class="font-medium text-gray-800 dark:text-slate-100 truncate" title="{{ $ticket->subject }}">
                                    {{ $ticket->subject }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-slate-300 truncate mt-0.5">
                                    {{ $ticket->message }}
                                </p>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="ticket-category-pill inline-flex items-center px-2.5 py-0.5 rounded-full bg-gray-100 dark:bg-teal-950/60 text-[10px] font-bold text-gray-600 dark:text-teal-300">
                                    {{ $categories[$ticket->category] ?? ucfirst($ticket->category) }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 border rounded-lg text-[10px] font-semibold tracking-wide uppercase {{ $statusColors[$ticket->status] ?? 'bg-gray-50 dark:bg-slate-700 text-gray-700 dark:text-slate-300 border-gray-200 dark:border-slate-600' }}">
                                    {{ $statusLabels[$ticket->status] ?? $ticket->status }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-center text-xs text-gray-500 dark:text-slate-300">
                                {{ $ticket->created_at->format('d M Y H:i') }}
                            </td>
                            <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                <a href="{{ route('admin.help-center.ticket-detail', $ticket->id) }}" class="text-emerald-700 dark:text-teal-300 text-xs font-semibold hover:underline">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-gray-400 dark:text-slate-300">
                                Belum ada tiket bantuan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-5 py-3 border-t border-gray-100 dark:border-teal-500/20 flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-3 flex-wrap">
                <form method="GET" action="{{ route('admin.help-center.tickets') }}" class="flex items-center gap-2">
                    @foreach(request()->except(['per_page', 'page']) as $k => $v)
                        @if(is_array($v))
                            @foreach($v as $item)
                                <input type="hidden" name="{{ $k }}[]" value="{{ $item }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                        @endif
                    @endforeach
                    <span class="text-xs font-bold text-gray-400 dark:text-slate-300 uppercase tracking-wider text-nowrap">Show:</span>
                    <select name="per_page" class="ticket-filter-input h-8 px-2 rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-gray-800 dark:text-slate-100 text-xs font-semibold focus:outline-none" onchange="this.form.submit()">
                        <option value="10" @selected(($perPage ?? 10) == 10)>10</option>
                        <option value="25" @selected(($perPage ?? 10) == 25)>25</option>
                        <option value="50" @selected(($perPage ?? 10) == 50)>50</option>
                        <option value="100" @selected(($perPage ?? 10) == 100)>100</option>
                    </select>
                </form>
                <span class="text-xs text-gray-500 dark:text-slate-300">Menampilkan {{ $tickets->firstItem() ?? 0 }}-{{ $tickets->lastItem() ?? 0 }} dari {{ $tickets->total() }} tiket</span>
            </div>
            @if ($tickets->hasPages())
                <div>
                    {{ $tickets->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
