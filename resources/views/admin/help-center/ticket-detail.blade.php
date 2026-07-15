@extends('layouts.portal')

@section('title', 'Ticket Detail - Admin')

@section('content')
<div class="space-y-6 max-w-4xl">
    <!-- HEADER -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900">Ticket #{{ $ticket->id }}</h1>
            <p class="text-gray-600 mt-1">{{ $ticket->subject }}</p>
        </div>
        <a href="{{ route('admin.help-center.tickets') }}" class="text-sm font-semibold text-[#002B6B] hover:text-blue-800">
            ← Kembali
        </a>
    </div>

    <!-- TICKET INFO -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Info Card -->
        <div class="md:col-span-2 bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <div class="space-y-6">
                <!-- From -->
                <div>
                    <p class="text-sm font-semibold text-gray-700 mb-2">Dari</p>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-[#002B6B]/10 flex items-center justify-center">
                            <span class="font-bold text-[#002B6B]">{{ substr($ticket->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $ticket->name }}</p>
                            <p class="text-sm text-gray-600">{{ $ticket->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Message -->
                <div>
                    <p class="text-sm font-semibold text-gray-700 mb-2">Pesan</p>
                    <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">
                        {{ $ticket->message }}
                    </div>
                </div>

                <!-- Attachment -->
                @if($ticket->attachment_path)
                    <div>
                        <p class="text-sm font-semibold text-gray-700 mb-2">Lampiran</p>
                        <a href="{{ asset('storage/' . $ticket->attachment_path) }}" class="inline-flex items-center gap-2 text-[#002B6B] hover:text-blue-800 font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Download File
                        </a>
                    </div>
                @endif

                <!-- Admin Response -->
                @if($ticket->admin_response)
                    <div>
                        <p class="text-sm font-semibold text-gray-700 mb-2">Respons Admin</p>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">
                            {{ $ticket->admin_response }}
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            Direspons: {{ $ticket->responded_at->format('d M Y H:i') }}
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-4">
            <!-- Status Card -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <p class="text-sm font-semibold text-gray-700 mb-3">Status</p>
                @php
                    $statusColors = [
                        'open' => 'bg-orange-50 text-orange-700 border-orange-200',
                        'in_progress' => 'bg-blue-50 text-blue-700 border-blue-200',
                        'closed' => 'bg-green-50 text-green-700 border-green-200',
                    ];
                    $statusLabels = [
                        'open' => 'Terbuka',
                        'in_progress' => 'Diproses',
                        'closed' => 'Tertutup',
                    ];
                @endphp
                <div class="px-4 py-2 {{ $statusColors[$ticket->status] ?? 'bg-gray-50 text-gray-700 border-gray-200' }} border rounded-lg text-center font-semibold text-sm">
                    {{ $statusLabels[$ticket->status] ?? $ticket->status }}
                </div>
            </div>

            <!-- Category Card -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <p class="text-sm font-semibold text-gray-700 mb-3">Kategori</p>
                <div class="px-4 py-2 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg text-center font-semibold text-sm">
                    {{ ucfirst($ticket->category) }}
                </div>
            </div>

            <!-- Dates Card -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-3">
                <div>
                    <p class="text-xs font-semibold text-gray-600 uppercase">Dibuat</p>
                    <p class="text-sm text-gray-900">{{ $ticket->created_at->format('d M Y H:i') }}</p>
                </div>
                @if($ticket->responded_at)
                    <div class="pt-3 border-t border-gray-200">
                        <p class="text-xs font-semibold text-gray-600 uppercase">Direspons</p>
                        <p class="text-sm text-gray-900">{{ $ticket->responded_at->format('d M Y H:i') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- UPDATE STATUS FORM -->
    @if($ticket->status !== 'closed')
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Update Status & Respons</h2>
            <form method="POST" action="{{ route('admin.help-center.update-status', $ticket->id) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" required class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-[#002B6B] focus:border-transparent outline-none">
                        <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Terbuka</option>
                        <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>Diproses</option>
                        <option value="closed">Tertutup</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Respons untuk Mahasiswa</label>
                    <textarea name="response" rows="6" placeholder="Tulis respons Anda di sini..." class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-[#002B6B] focus:border-transparent outline-none resize-none">{{ $ticket->admin_response }}</textarea>
                    <p class="text-xs text-gray-500 mt-2">Respons ini akan dikirim via email ke mahasiswa</p>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="px-6 py-2 bg-[#002B6B] hover:bg-blue-800 text-white rounded-lg font-medium text-sm transition">
                        Simpan Perubahan
                    </button>
                    @if($ticket->status !== 'closed')
                        <form method="POST" action="{{ route('admin.help-center.close', $ticket->id) }}" class="inline" onsubmit="return confirm('Yakin ingin menutup tiket ini?');">
                            @csrf
                            <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium text-sm transition">
                                Tutup Tiket
                            </button>
                        </form>
                    @endif
                </div>
            </form>
        </div>
    @else
        <div class="bg-green-50 border border-green-200 rounded-2xl p-6 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-green-600 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-green-900 font-semibold">Tiket sudah ditutup</p>
            <p class="text-green-700 text-sm mt-1">Tiket ini sudah tertutup dan tidak dapat diubah</p>
        </div>
    @endif
</div>
@endsection
