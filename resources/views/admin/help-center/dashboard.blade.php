@extends('layouts.portal')

@section('title', 'Help Center - Admin')

@section('content')
<div class="space-y-6">
    <!-- HEADER -->
    <div class="bg-gradient-to-br from-[#002B6B] to-[#0044a8] text-white p-6 sm:p-8 rounded-2xl shadow-lg">
        <h1 class="text-3xl font-extrabold mb-2">Help Center Management</h1>
        <p class="text-blue-100">Kelola support tickets dan bantuan mahasiswa</p>
    </div>

    <!-- STATS CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Tickets -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Total Tiket</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalTickets }}</p>
                </div>
                <div class="p-4 bg-blue-50 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Open Tickets -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Tiket Terbuka</p>
                    <p class="text-3xl font-bold text-orange-600 mt-1">{{ $openTickets }}</p>
                </div>
                <div class="p-4 bg-orange-50 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Closed Tickets -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Tiket Tertutup</p>
                    <p class="text-3xl font-bold text-green-600 mt-1">{{ $closedTickets }}</p>
                </div>
                <div class="p-4 bg-green-50 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- RECENT TICKETS -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-900">Tiket Terbaru</h2>
            <a href="{{ route('admin.help-center.tickets') }}" class="text-sm font-semibold text-[#002B6B] hover:text-blue-800">
                Lihat Semua →
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Subject</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($recentTickets as $ticket)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="font-medium">{{ $ticket->name }}</div>
                                <div class="text-xs text-gray-500">{{ $ticket->email }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $ticket->subject }}</td>
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
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('admin.help-center.ticket-detail', $ticket->id) }}" class="text-[#002B6B] hover:text-blue-800 font-semibold">
                                    Lihat
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                Belum ada tiket support
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- QUICK ACTIONS -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <a href="{{ route('admin.help-center.tickets') }}" class="p-6 bg-white rounded-2xl border border-gray-200 hover:shadow-lg transition group">
            <div class="flex items-start gap-4">
                <div class="p-3 bg-blue-50 rounded-lg group-hover:bg-blue-100 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-gray-900">Kelola Semua Tiket</h3>
                    <p class="text-sm text-gray-600 mt-1">Lihat, filter, dan respons semua support tickets</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-hover:text-[#002B6B] transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </div>
        </a>

        <a href="{{ route('help-center.faq') }}" class="p-6 bg-white rounded-2xl border border-gray-200 hover:shadow-lg transition group">
            <div class="flex items-start gap-4">
                <div class="p-3 bg-purple-50 rounded-lg group-hover:bg-purple-100 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-gray-900">Lihat Help Center</h3>
                    <p class="text-sm text-gray-600 mt-1">Lihat halaman FAQ dan panduan untuk mahasiswa</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-hover:text-[#002B6B] transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </div>
        </a>
    </div>
</div>
@endsection
