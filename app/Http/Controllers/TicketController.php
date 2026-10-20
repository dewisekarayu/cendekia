<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketReply;
use App\Notifications\TiketDibalas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class TicketController extends Controller
{
    /**
     * Dashboard List Tiket (Admin Panel)
     */
    public function index(Request $request)
    {
        $query = Ticket::query();

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter Kategori
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Pencarian (Subjek, Pesan, Nama, Email)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $perPage = (int) $request->input('per_page', $request->input('show', 10));
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        $tickets = $query->latest()->paginate($perPage)->withQueryString();
        
        $statuses = [
            'open' => 'Terbuka (Open)',
            'responded' => 'Dijawab (Responded)',
            'closed' => 'Tertutup (Closed)'
        ];

        $categories = [
            'akun' => 'Akun',
            'absensi' => 'Absensi',
            'nilai' => 'Nilai',
            'tugas' => 'Tugas',
            'kelas' => 'Kelas',
            'teknis' => 'Teknis',
            'lainnya' => 'Lainnya'
        ];

        return view('admin.tickets.index', compact('tickets', 'statuses', 'categories', 'perPage'));
    }

    /**
     * Detail Tiket & Form Balas
     */
    public function show($id)
    {
        $ticket = Ticket::with(['replies.user', 'user'])->findOrFail($id);

        $categories = [
            'akun' => 'Akun',
            'absensi' => 'Absensi',
            'nilai' => 'Nilai',
            'tugas' => 'Tugas',
            'kelas' => 'Kelas',
            'teknis' => 'Teknis',
            'lainnya' => 'Lainnya'
        ];

        return view('admin.tickets.show', compact('ticket', 'categories'));
    }

    /**
     * Balas Tiket & Ubah Status ke 'responded' & Kirim Notifikasi Gmail
     */
    public function reply(Request $request, $id)
    {
        $request->validate([
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $ticket = Ticket::findOrFail($id);

        // Tiket yang sudah closed tidak bisa dibalas lagi
        if ($ticket->status === 'closed') {
            return redirect()->back()->with('error', 'Tiket ini sudah ditutup dan tidak dapat dibalas.');
        }

        // 1. Simpan reply
        $reply = TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id'    => Auth::id(),
            'message'   => $request->message,
        ]);

        // 2. Perbarui status tiket secara otomatis menjadi 'responded'
        $ticket->update([
            'status' => 'responded'
        ]);

        // 3. Kirim notifikasi in-app ke pengguna (Dosen / Mahasiswa) pembuat tiket
        try {
            $recipientId = $ticket->user_id ?? \App\Models\User::where('email', $ticket->email)->value('id');
            if ($recipientId) {
                \App\Models\Notifikasi::create([
                    'user_id' => $recipientId,
                    'kelas_perkuliahan_id' => null,
                    'judul' => 'Balasan Tiket Bantuan #' . $ticket->id,
                    'pesan' => 'Admin telah membalas tiket "' . \Illuminate\Support\Str::limit($ticket->subject, 30) . '": ' . \Illuminate\Support\Str::limit($request->message, 50),
                    'tipe' => 'informasi',
                    'url' => route('help-center.my-tickets.show', $ticket->id),
                ]);
            }
        } catch (\Throwable $e) {
            logger()->error("Gagal membuat notifikasi in-app tiket dibalas: " . $e->getMessage());
        }

        // 4. Kirim notifikasi email via Gmail SMTP
        // Kita dukung baik user terdaftar maupun tamu lewat email langsung
        try {
            Notification::route('mail', $ticket->email)
                ->notify(new TiketDibalas($ticket, $reply));
        } catch (\Exception $e) {
            // Log error jika pengiriman email gagal namun tetep biarkan flow jalan
            logger()->error("Gagal mengirim email ke {$ticket->email}: " . $e->getMessage());
            return redirect()->route('admin.help-center.ticket-detail', $ticket->id)
                ->with('success', 'Balasan berhasil dikirim dan notifikasi in-app telah diteruskan ke pengguna.');
        }

        return redirect()->route('admin.help-center.ticket-detail', $ticket->id)
            ->with('success', 'Balasan berhasil dikirim dan notifikasi telah dikirim ke pengguna.');
    }

    /**
     * Tutup Tiket ('closed')
     */
    public function close($id)
    {
        $ticket = Ticket::findOrFail($id);
        
        $ticket->update([
            'status' => 'closed'
        ]);

        // Kirim notifikasi in-app ke pengguna bahwa tiket telah diselesaikan
        try {
            $recipientId = $ticket->user_id ?? \App\Models\User::where('email', $ticket->email)->value('id');
            if ($recipientId) {
                \App\Models\Notifikasi::create([
                    'user_id' => $recipientId,
                    'kelas_perkuliahan_id' => null,
                    'judul' => 'Tiket Bantuan Ditutup: #' . $ticket->id,
                    'pesan' => 'Tiket bantuan "' . \Illuminate\Support\Str::limit($ticket->subject, 30) . '" telah selesai ditangani dan ditutup oleh Admin.',
                    'tipe' => 'informasi',
                    'url' => route('help-center.my-tickets.show', $ticket->id),
                ]);
            }
        } catch (\Throwable $e) {
            logger()->error("Gagal membuat notifikasi in-app tiket ditutup: " . $e->getMessage());
        }

        return redirect()->route('admin.help-center.tickets')
            ->with('success', "Tiket #{$ticket->id} berhasil ditutup.");
    }
}
