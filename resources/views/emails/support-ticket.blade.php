<x-mail::message>
# Tiket Bantuan Baru

Ada tiket bantuan baru masuk melalui Help Center Cendekia LMS.

**Nama:** {{ $ticket->name }}
**Email:** {{ $ticket->email }}
**Kategori:** {{ \App\Models\Faq::CATEGORIES[$ticket->category] ?? ucfirst($ticket->category) }}
**Subjek:** {{ $ticket->subject }}

**Pesan:**

{{ $ticket->message }}

@if($ticket->attachment_path)
Lampiran disertakan pada email ini.
@endif

<x-mail::button :url="route('admin.help-center.tickets.show', $ticket->id)">
Lihat Tiket
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
