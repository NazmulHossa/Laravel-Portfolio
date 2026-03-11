<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{


    public function index(Request $request)
    {
       
        $messages = Message::search($request->input('search'))
                           ->latest()           // newest first
                           ->paginate(10)       // 10 per page
                           ->withQueryString(); // keeps ?search= in pagination links

       
        $totalMessages = Message::count();

        
        $unreadCount = Message::unread()->count();

        return view('admin.messages.index', compact('messages', 'totalMessages', 'unreadCount'));
    }

  
    public function show(Message $message)
    {
        
        $message->markAsRead();

        return view('admin.messages.show', compact('message'));
    }

  
    public function destroy(Message $message)
    {
        $senderName = $message->name; // Store name before delete for flash message

        $message->delete();

        return redirect()
            ->route('admin.messages.index')
            ->with('success', "Message from \"{$senderName}\" has been deleted.");
    }

   
    public function destroyAll()
    {
        $count = Message::count(); // How many we're deleting

       
        Message::truncate();

        return redirect()
            ->route('admin.messages.index')
            ->with('success', "All {$count} messages have been deleted.");
    }
}
