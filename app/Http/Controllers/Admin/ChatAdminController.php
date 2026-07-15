<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatSession;
use App\Models\ChatSetting;
use Illuminate\Http\Request;

class ChatAdminController extends Controller
{
    public function index()
    {
        $sessions = ChatSession::query()
            ->with(['user', 'messages' => fn ($q) => $q->latest()->limit(1)])
            ->orderByDesc('last_message_at')
            ->paginate(20);

        $adminStatus = ChatSetting::current();

        return view('admin.help-center.chat', compact('sessions', 'adminStatus'));
    }

    public function show(ChatSession $session)
    {
        $session->load(['user', 'messages']);
        $session->messages()->where('sender_type', 'user')->update(['is_read' => true]);

        if (request()->wantsJson()) {
            return response()->json([
                'messages' => $session->messages->map(fn ($m) => [
                    'id' => $m->id,
                    'sender_type' => $m->sender_type,
                    'message' => $m->message,
                    'attachment_url' => $m->attachment_url,
                    'attachment_name' => $m->attachment_name,
                    'created_at' => $m->created_at->format('H:i'),
                ]),
            ]);
        }

        return view('admin.help-center.chat-show', compact('session'));
    }

    public function reply(Request $request, ChatSession $session)
    {
        $validated = $request->validate([
            'message' => ['nullable', 'string', 'max:2000'],
            'attachment' => ['nullable', 'file', 'max:5120'],
        ]);

        if (blank($validated['message'] ?? null) && ! $request->hasFile('attachment')) {
            return response()->json(['message' => 'Pesan atau lampiran wajib diisi.'], 422);
        }

        $attachmentPath = null;
        $attachmentName = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentPath = $file->store('chat-attachments', 'public');
            $attachmentName = $file->getClientOriginalName();
        }

        $message = $session->messages()->create([
            'sender_type' => 'admin',
            'sender_id' => $request->user()->id,
            'message' => $validated['message'] ?? null,
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
            'is_read' => false,
        ]);

        $session->update(['last_message_at' => now()]);

        return response()->json([
            'message_data' => [
                'id' => $message->id,
                'sender_type' => 'admin',
                'message' => $message->message,
                'attachment_url' => $message->attachment_url,
                'attachment_name' => $message->attachment_name,
                'created_at' => $message->created_at->format('H:i'),
            ],
        ]);
    }

    public function toggleStatus(Request $request)
    {
        $setting = ChatSetting::current();
        $setting->update([
            'admin_status' => $setting->isOnline() ? 'offline' : 'online',
        ]);

        return back()->with('success', 'Status admin diperbarui menjadi '.($setting->isOnline() ? 'Online' : 'Offline').'.');
    }

    public function closeSession(ChatSession $session)
    {
        $session->update(['status' => 'closed']);

        return back()->with('success', 'Percakapan ditutup.');
    }
}
