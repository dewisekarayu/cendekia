<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $tickets = SupportTicket::query()
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->get('status')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $statuses = SupportTicket::STATUSES;

        return view('admin.help-center.tickets', compact('tickets', 'statuses'));
    }

    public function show(SupportTicket $ticket)
    {
        return view('admin.help-center.ticket-show', compact('ticket'));
    }

    public function updateStatus(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:'.implode(',', array_keys(SupportTicket::STATUSES))],
        ]);

        $ticket->update($validated);

        return back()->with('success', 'Status tiket diperbarui.');
    }
}
