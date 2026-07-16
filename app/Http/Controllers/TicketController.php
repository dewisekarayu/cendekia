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

        $tickets = $query->latest()->paginate(15)->withQueryString();
        
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

        return view('admin.tickets.index', compact('tickets', 'statuses', 'categories'));
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

        // 3. Kirim notifikasi email via Gmail SMTP
        // Kita dukung baik user terdaftar maupun tamu lewat email langsung
        try {
            Notification::route('mail', $ticket->email)
                ->notify(new TiketDibalas($ticket, $reply));
        } catch (\Exception $e) {
            // Log error jika pengiriman email gagal namun tetep biarkan flow jalan
            logger()->error("Gagal mengirim email ke {$ticket->email}: " . $e->getMessage());
            return redirect()->route('admin.help-center.ticket-detail', $ticket->id)
                ->with('success', 'Balasan berhasil dikirim, namun pengiriman notifikasi email mengalami kendala.');
        }

        return redirect()->route('admin.help-center.ticket-detail', $ticket->id)
            ->with('success', 'Balasan berhasil dikirim dan notifikasi email telah terkirim.');
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

        return redirect()->route('admin.help-center.tickets')
            ->with('success', "Tiket #{$ticket->id} berhasil ditutup.");
    }
}
