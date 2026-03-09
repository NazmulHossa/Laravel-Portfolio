<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    // ═══════════════════════════════════════════════════════════
    // INDEX  —  Route: GET /admin/messages
    // Shows the inbox with search + pagination + unread count
    // ═══════════════════════════════════════════════════════════
    public function index(Request $request)
    {
        // Use the search() scope defined in Message model.
        // If ?search=john is in the URL, filters by name/email/subject.
        // If no search, returns all messages.
        $messages = Message::search($request->input('search'))
                           ->latest()           // newest first
                           ->paginate(10)       // 10 per page
                           ->withQueryString(); // keeps ?search= in pagination links

        // Count all messages for the header badge
        $totalMessages = Message::count();

        // Count UNREAD messages — uses the unread() scope in Message model
        // Used to show a red badge: "📨 Inbox (3 unread)"
        $unreadCount = Message::unread()->count();

        return view('admin.messages.index', compact('messages', 'totalMessages', 'unreadCount'));
    }

    // ═══════════════════════════════════════════════════════════
    // SHOW  —  Route: GET /admin/messages/{message}
    // Displays the full message and marks it as read
    // ═══════════════════════════════════════════════════════════
    public function show(Message $message)
    {
        // markAsRead() is the custom method defined in the Message model.
        // It sets is_read = true if it was false.
        // This reduces the "unread" count in the navbar automatically.
        $message->markAsRead();

        return view('admin.messages.show', compact('message'));
    }

    // ═══════════════════════════════════════════════════════════
    // DESTROY  —  Route: DELETE /admin/messages/{message}
    // Deletes a single message
    // ═══════════════════════════════════════════════════════════
    public function destroy(Message $message)
    {
        $senderName = $message->name; // Store name before delete for flash message

        $message->delete();

        return redirect()
            ->route('admin.messages.index')
            ->with('success', "Message from \"{$senderName}\" has been deleted.");
    }

    // ═══════════════════════════════════════════════════════════
    // DESTROY ALL  —  Route: DELETE /admin/messages
    // Deletes ALL messages (bulk clear inbox)
    // ═══════════════════════════════════════════════════════════
    public function destroyAll()
    {
        $count = Message::count(); // How many we're deleting

        // truncate() deletes all rows AND resets the auto-increment ID counter back to 1
        // It's faster than delete() for clearing entire tables
        Message::truncate();

        return redirect()
            ->route('admin.messages.index')
            ->with('success', "All {$count} messages have been deleted.");
    }
}