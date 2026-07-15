@extends('layouts.portal')

@section('title', 'Support Tickets - Admin')

@section('content')
<div class="space-y-6">
    <!-- HEADER -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900">Support Tickets</h1>
            <p class="text-gray-600 mt-1">Kelola semua support tickets dari mahasiswa</p>
        </div>
        <a href="{{ route('admin.help-center.dashboard') }}" class="text-sm font-semibold text-[#002B6B] hover:text-blue-800">
            ← Kembali
        </a>
    </div>

    <!-- FILTERS -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <form method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                    <input
                        type="text"
                        name="search"
                        placeholder="Nama, email, subject..."
                        value="{{ request('search') }}"
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-[#002B6B] focus:border-transparent outline-none"
                    />
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-[#002B6B] focus:border-transparent outline-none">
                        <option value="">Semua Status</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                                @php
                                    $labels = [
                                        'open' => 'Terbuka',
                                        'in_progress' => 'Diproses',
                                        'closed' => 'Tertutup',
                                    ];
                                @endphp
                                {{ $labels[$status] ?? $status }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Category Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="category" class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-[#002B6B] focus:border-transparent outline-none">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>
                                {{ ucfirst($cat) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-[#002B6B] hover:bg-blue-800 text-white rounded-lg font-medium text-sm transition">
                        Filter
                    </button>
                    @if(request()->filled('search') || request()->filled('status') || request()->filled('category'))
                        <a href="{{ route('admin.help-center.tickets') }}" class="px-4 py-2 border border-gray-200 text-gray-700 rounded-lg font-medium text-sm hover:bg-gray-50 transition">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- TICKETS TABLE -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Nama & Email</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Subject</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($tickets as $ticket)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $ticket->name }}</div>
                                <div class="text-xs text-gray-500">{{ $ticket->email }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">
                                {{ $ticket->subject }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-semibold">
                                    {{ ucfirst($ticket->category) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @php
                                    $statusColors = [
                                        'open' => 'bg-orange-50 text-orange-700',
                                        'in_progress' => 'bg-blue-50 text-blue-700',
                                        'closed' => 'bg-green-50 text-green-700',
                                    ];
                                    $statusLabels = [
                                        'open' => 'Terbuka',
                                        'in_progress' => 'Diproses',
                                        'closed' => 'Tertutup',
                                    ];
                                @endphp
                                <span class="px-3 py-1 {{ $statusColors[$ticket->status] ?? 'bg-gray-50 text-gray-700' }} rounded-full text-xs font-semibold">
                                    {{ $statusLabels[$ticket->status] ?? $ticket->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $ticket->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm space-x-2">
                                <a href="{{ route('admin.help-center.ticket-detail', $ticket->id) }}" class="text-[#002B6B] hover:text-blue-800 font-semibold">
                                    Lihat
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="font-medium">Tidak ada tiket</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- PAGINATION -->
    @if($tickets->hasPages())
        <div class="flex justify-center">
            {{ $tickets->links() }}
        </div>
    @endif
</div>
@endsection
