<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HelpTicket;
use Illuminate\Http\Request;

class HelpCenterController extends Controller
{
    /**
     * Display help center dashboard
     */
    public function dashboard()
    {
        $totalTickets = HelpTicket::count();
        $openTickets = HelpTicket::where('status', 'open')->count();
        $closedTickets = HelpTicket::where('status', 'closed')->count();
        $recentTickets = HelpTicket::latest()->take(5)->get();

        return view('admin.help-center.dashboard', compact(
            'totalTickets',
            'openTickets',
            'closedTickets',
            'recentTickets'
        ));
    }

    /**
     * Display all support tickets
     */
    public function tickets(Request $request)
    {
        $query = HelpTicket::query();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%$search%")
                  ->orWhere('message', 'like', "%$search%")
                  ->orWhere('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        $tickets = $query->latest()->paginate(15);
        $statuses = ['open', 'in_progress', 'closed'];
        $categories = ['akun', 'absensi', 'nilai', 'tugas', 'kelas', 'teknis', 'pembayaran', 'lainnya'];

        return view('admin.help-center.tickets', compact('tickets', 'statuses', 'categories'));
    }

    /**
     * Show ticket detail
     */
    public function ticketDetail($id)
    {
        $ticket = HelpTicket::findOrFail($id);
        return view('admin.help-center.ticket-detail', compact('ticket'));
    }

    /**
     * Update ticket status
     */
    public function updateTicketStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:open,in_progress,closed'],
            'response' => ['nullable', 'string', 'max:5000'],
        ]);

        $ticket = HelpTicket::findOrFail($id);
        $ticket->update([
            'status' => $validated['status'],
            'admin_response' => $validated['response'] ?? $ticket->admin_response,
            'responded_at' => now(),
        ]);

        return redirect()->route('admin.help-center.ticket-detail', $id)
            ->with('success', 'Tiket berhasil diperbarui');
    }

    /**
     * Close ticket
     */
    public function closeTicket($id)
    {
        $ticket = HelpTicket::findOrFail($id);
        $ticket->update(['status' => 'closed']);

        return redirect()->route('admin.help-center.tickets')
            ->with('success', 'Tiket berhasil ditutup');
    }

    /**
     * Delete ticket
     */
    public function deleteTicket($id)
    {
        $ticket = HelpTicket::findOrFail($id);
        $ticket->delete();

        return redirect()->route('admin.help-center.tickets')
            ->with('success', 'Tiket berhasil dihapus');
    }
}
