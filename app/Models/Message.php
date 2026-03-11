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
        
    ];

    protected $casts = [
        'is_read'    => 'boolean',  // DB stores 0/1 → PHP gets true/false
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

   
    public function getReceivedAtAttribute(): string
    {
        return $this->created_at->format('d M Y, h:i A');
    }

  
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    
    public function getReplyLinkAttribute(): string
    {
        $subject = urlencode('Re: ' . $this->subject);
        return "mailto:{$this->email}?subject={$subject}";
    }


    public function markAsRead(): void
    {
        if (!$this->is_read) {
          
            $this->update(['is_read' => true]);
        }
    }

  
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

   
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
