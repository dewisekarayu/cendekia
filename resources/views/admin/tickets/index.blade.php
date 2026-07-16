@extends('layouts.admin')

@section('title', 'Pusat Bantuan (Ticketing) - Admin')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto px-4 sm:px-0">
    
    {{-- ===== HEADER ===== --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Pusat Bantuan (Helpdesk)</h1>
            <p class="text-xs text-gray-500 mt-1">
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
        <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Total Tiket</p>
            <p class="mt-1 text-2xl font-bold text-gray-850">{{ $total }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-orange-500">Terbuka (Open)</p>
            <p class="mt-1 text-2xl font-bold text-orange-600">{{ $open }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-blue-900">Dijawab (Responded)</p>
            <p class="mt-1 text-2xl font-bold text-blue-900">{{ $responded }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-600">Selesai (Closed)</p>
            <p class="mt-1 text-2xl font-bold text-emerald-600">{{ $closed }}</p>
        </div>
    </div>

    {{-- ===== FILTERS & SEARCH ===== --}}
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.help-center.tickets') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Pencarian</label>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Subjek, nama, email..."
                    class="w-full h-10 px-3 rounded-lg border border-gray-200 bg-transparent text-gray-800 placeholder:text-gray-400 text-xs focus:ring-2 focus:ring-blue-900 focus:border-transparent outline-none transition-all"
                >
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Status</label>
                <select name="status" class="w-full h-10 px-3 rounded-lg border border-gray-200 bg-white text-gray-800 text-xs focus:ring-2 focus:ring-blue-900 focus:border-transparent outline-none transition-all">
                    <option value="">Semua Status</option>
                    @foreach($statuses as $key => $label)
                        <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Kategori</label>
                <select name="category" class="w-full h-10 px-3 rounded-lg border border-gray-200 bg-white text-gray-800 text-xs focus:ring-2 focus:ring-blue-900 focus:border-transparent outline-none transition-all">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $key => $label)
                        <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 h-10 rounded-lg bg-blue-900 text-white text-xs font-medium transition hover:bg-opacity-90">
                    Filter
                </button>
                <a href="{{ route('admin.help-center.tickets') }}" class="h-10 px-4 rounded-lg border border-gray-250 text-gray-700 hover:bg-gray-50 flex items-center justify-center text-xs font-medium transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- ===== TABLE LIST ===== --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-left text-gray-400 text-xs border-b border-gray-100 bg-gray-50/50">
                        <th class="px-5 py-3 font-medium text-center w-16">No</th>
                        <th class="px-5 py-3 font-medium">Pengirim</th>
                        <th class="px-5 py-3 font-medium">Subjek / Masalah</th>
                        <th class="px-5 py-3 font-medium text-center">Kategori</th>
                        <th class="px-5 py-3 font-medium text-center">Status</th>
                        <th class="px-5 py-3 font-medium text-center">Dibuat</th>
                        <th class="px-5 py-3 font-medium text-right w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-gray-700">
                    @forelse ($tickets as $index => $ticket)
                        @php
                            $statusColors = [
                                'open' => 'bg-orange-50 text-orange-700 border-orange-200',
                                'responded' => 'bg-blue-50 text-blue-700 border-blue-250',
                                'closed' => 'bg-emerald-50 text-emerald-700 border-emerald-250',
                            ];
                            $statusLabels = [
                                'open' => 'Terbuka',
                                'responded' => 'Dijawab',
                                'closed' => 'Tertutup',
                            ];
                        @endphp
                        <tr class="hover:bg-gray-50/40 transition-colors">
                            <td class="px-5 py-3.5 text-center font-medium text-gray-400">
                                {{ ($tickets->currentPage() - 1) * $tickets->perPage() + $index + 1 }}
                            </td>
                            <td class="px-5 py-3.5">
                                <p class="font-semibold text-gray-800 mb-0.5">{{ $ticket->name }}</p>
                                <p class="text-xs text-gray-500">{{ $ticket->email }}</p>
                            </td>
                            <td class="px-5 py-3.5 max-w-[280px]">
                                <p class="font-medium text-gray-800 truncate" title="{{ $ticket->subject }}">
                                    {{ $ticket->subject }}
                                </p>
                                <p class="text-xs text-gray-500 truncate mt-0.5">
                                    {{ $ticket->message }}
                                </p>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-gray-100 text-[10px] font-bold text-gray-600">
                                    {{ $categories[$ticket->category] ?? ucfirst($ticket->category) }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 border rounded-lg text-[10px] font-semibold tracking-wide uppercase {{ $statusColors[$ticket->status] ?? 'bg-gray-50 text-gray-700 border-gray-200' }}">
                                    {{ $statusLabels[$ticket->status] ?? $ticket->status }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-center text-xs text-gray-500">
                                {{ $ticket->created_at->format('d M Y H:i') }}
                            </td>
                            <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                <a href="{{ route('admin.help-center.ticket-detail', $ticket->id) }}" class="text-blue-900 text-xs font-semibold hover:underline">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-gray-400">
                                Belum ada tiket bantuan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($tickets->hasPages())
            <div class="px-5 py-3 border-t border-gray-100">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
