<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';

    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        // Note: is_read is NOT in fillable because we never want users to set it.
        // Only the admin can mark a message as read via $message->markAsRead()
    ];

    protected $casts = [
        'is_read'    => 'boolean',  // DB stores 0/1 → PHP gets true/false
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ════════════════════════════════════════════════════════
    // ACCESSORS
    // ════════════════════════════════════════════════════════

    // $message->received_at  →  "15 Jan 2026, 02:30 PM"
    // A nicely formatted date string for displaying in the admin panel
    public function getReceivedAtAttribute(): string
    {
        return $this->created_at->format('d M Y, h:i A');
    }

    // $message->time_ago  →  "2 hours ago", "3 days ago"
    // Human-readable relative time — great for the inbox table
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    // $message->reply_link  →  "mailto:user@email.com?subject=Re: Hello"
    // Clicking this opens the user's email client pre-filled with recipient + subject
    public function getReplyLinkAttribute(): string
    {
        $subject = urlencode('Re: ' . $this->subject);
        return "mailto:{$this->email}?subject={$subject}";
    }

    // ════════════════════════════════════════════════════════
    // CUSTOM METHODS
    // ════════════════════════════════════════════════════════

    // $message->markAsRead()
    // Marks the message as read in the database.
    // Called from MessageController::show() when admin views a message.
    public function markAsRead(): void
    {
        if (!$this->is_read) {
            // update() only runs a query if the value actually changes
            $this->update(['is_read' => true]);
        }
    }

    // ════════════════════════════════════════════════════════
    // LOCAL SCOPES
    // ════════════════════════════════════════════════════════

    // Message::unread()->count()  →  number of unread messages (for navbar badge)
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    // Message::search('john')->paginate(10)
    // Searches name, email, and subject columns
    public function scopeSearch($query, ?string $term)
    {
        if ($term) {
            $query->where(function ($q) use ($term) {
                $q->where('name',    'like', "%{$term}%")
                  ->orWhere('email',   'like', "%{$term}%")
                  ->orWhere('subject', 'like', "%{$term}%");
            });
        }
        return $query;
    }
}