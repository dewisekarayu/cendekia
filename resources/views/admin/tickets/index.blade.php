@extends('layouts.admin')

@section('title', 'Pusat Bantuan (Ticketing) - Admin')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto px-4 sm:px-0">
    
    {{-- ===== HEADER ===== --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-800 dark:text-white">Pusat Bantuan (Helpdesk)</h1>
            <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">
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
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700/80 p-4 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-slate-400">Total Tiket</p>
            <p class="mt-1 text-2xl font-bold text-gray-850 dark:text-white">{{ $total }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700/80 p-4 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-orange-500 dark:text-orange-400">Terbuka (Open)</p>
            <p class="mt-1 text-2xl font-bold text-orange-600 dark:text-orange-400">{{ $open }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700/80 p-4 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-blue-900 dark:text-blue-400">Dijawab (Responded)</p>
            <p class="mt-1 text-2xl font-bold text-blue-900 dark:text-blue-400">{{ $responded }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700/80 p-4 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Selesai (Closed)</p>
            <p class="mt-1 text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $closed }}</p>
        </div>
    </div>

    {{-- ===== FILTERS & SEARCH ===== --}}
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700/80 p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.help-center.tickets') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 sm:gap-4 items-end">
            <input type="hidden" name="per_page" value="{{ $perPage ?? 10 }}">
            <div class="sm:col-span-5">
                <label class="block text-xs font-bold text-gray-400 dark:text-slate-400 uppercase mb-2">Pencarian</label>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Subjek, nama, email..."
                    class="w-full h-10 px-3 rounded-lg border border-gray-200 dark:border-slate-700 bg-transparent dark:bg-slate-900/60 text-gray-800 dark:text-slate-100 placeholder:text-gray-400 dark:placeholder:text-slate-500 text-xs focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition-all"
                >
            </div>
            <div class="sm:col-span-3">
                <label class="block text-xs font-bold text-gray-400 dark:text-slate-400 uppercase mb-2">Status</label>
                <select name="status" class="w-full h-10 px-3 rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900/60 text-gray-800 dark:text-slate-100 text-xs focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition-all">
                    <option value="">Semua Status</option>
                    @foreach($statuses as $key => $label)
                        <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-gray-400 dark:text-slate-400 uppercase mb-2">Kategori</label>
                <select name="category" class="w-full h-10 px-3 rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900/60 text-gray-800 dark:text-slate-100 text-xs focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition-all">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $key => $label)
                        <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="sm:col-span-2 flex gap-2">
                <button type="submit" class="flex-1 h-10 rounded-lg bg-blue-900 dark:bg-blue-600 text-white text-xs font-medium transition hover:bg-blue-800 dark:hover:bg-blue-500">
                    Filter
                </button>
                <a href="{{ route('admin.help-center.tickets') }}" class="h-10 px-3 rounded-lg border border-gray-250 dark:border-slate-700 text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-700 flex items-center justify-center text-xs font-medium transition" title="Reset">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
        </form>
    </div>

    {{-- ===== TABLE LIST ===== --}}
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-left text-gray-400 dark:text-slate-400 text-xs border-b border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-900/60">
                        <th class="px-5 py-3 font-medium text-center w-16">NO</th>
                        <th class="px-5 py-3 font-medium">Pengirim</th>
                        <th class="px-5 py-3 font-medium">Subjek / Masalah</th>
                        <th class="px-5 py-3 font-medium text-center">Kategori</th>
                        <th class="px-5 py-3 font-medium text-center">Status</th>
                        <th class="px-5 py-3 font-medium text-center">Dibuat</th>
                        <th class="px-5 py-3 font-medium text-right w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-slate-700/60 text-gray-700 dark:text-slate-300">
                    @forelse ($tickets as $index => $ticket)
                        @php
                            $statusColors = [
                                'open' => 'bg-orange-50 dark:bg-orange-950/40 text-orange-700 dark:text-orange-400 border-orange-200 dark:border-orange-800/50',
                                'responded' => 'bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 border-blue-250 dark:border-blue-800/50',
                                'closed' => 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 border-emerald-250 dark:border-emerald-800/50',
                            ];
                            $statusLabels = [
                                'open' => 'Terbuka',
                                'responded' => 'Dijawab',
                                'closed' => 'Tertutup',
                            ];
                        @endphp
                        <tr class="hover:bg-gray-50/40 dark:hover:bg-slate-700/40 transition-colors">
                            <td class="px-5 py-3.5 text-center font-bold text-gray-500 dark:text-slate-400 font-mono">
                                {{ ($tickets->currentPage() - 1) * $tickets->perPage() + $index + 1 }}
                            </td>
                            <td class="px-5 py-3.5">
                                <p class="font-semibold text-gray-800 dark:text-slate-100 mb-0.5">{{ $ticket->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-slate-400">{{ $ticket->email }}</p>
                            </td>
                            <td class="px-5 py-3.5 max-w-[280px]">
                                <p class="font-medium text-gray-800 dark:text-slate-100 truncate" title="{{ $ticket->subject }}">
                                    {{ $ticket->subject }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-slate-400 truncate mt-0.5">
                                    {{ $ticket->message }}
                                </p>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-gray-100 dark:bg-slate-700 text-[10px] font-bold text-gray-600 dark:text-slate-300">
                                    {{ $categories[$ticket->category] ?? ucfirst($ticket->category) }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 border rounded-lg text-[10px] font-semibold tracking-wide uppercase {{ $statusColors[$ticket->status] ?? 'bg-gray-50 dark:bg-slate-700 text-gray-700 dark:text-slate-300 border-gray-200 dark:border-slate-600' }}">
                                    {{ $statusLabels[$ticket->status] ?? $ticket->status }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-center text-xs text-gray-500 dark:text-slate-400">
                                {{ $ticket->created_at->format('d M Y H:i') }}
                            </td>
                            <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                <a href="{{ route('admin.help-center.ticket-detail', $ticket->id) }}" class="text-blue-900 dark:text-blue-400 text-xs font-semibold hover:underline">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-gray-400 dark:text-slate-400">
                                Belum ada tiket bantuan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-5 py-3 border-t border-gray-100 dark:border-slate-700/80 flex items-center justify-between flex-wrap gap-3">
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
                    <span class="text-xs font-bold text-gray-400 dark:text-slate-400 uppercase tracking-wider text-nowrap">Show:</span>
                    <select name="per_page" class="h-8 px-2 rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-gray-800 dark:text-slate-100 text-xs font-semibold focus:outline-none" onchange="this.form.submit()">
                        <option value="10" @selected(($perPage ?? 10) == 10)>10</option>
                        <option value="25" @selected(($perPage ?? 10) == 25)>25</option>
                        <option value="50" @selected(($perPage ?? 10) == 50)>50</option>
                        <option value="100" @selected(($perPage ?? 10) == 100)>100</option>
                    </select>
                </form>
                <span class="text-xs text-gray-500 dark:text-slate-400">Menampilkan {{ $tickets->firstItem() ?? 0 }}-{{ $tickets->lastItem() ?? 0 }} dari {{ $tickets->total() }} tiket</span>
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
