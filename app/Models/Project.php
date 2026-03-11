<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';

    protected $fillable = [
        'title',
        'description',
        'image',
        'url',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

 

    protected static function boot(): void
    {
        parent::boot(); 
        static::deleting(function (Project $project) {
            if ($project->image && Storage::disk('public')->exists($project->image)) {
                Storage::disk('public')->delete($project->image);
               
            }
        });
    }

    
    public function getImageUrlAttribute(): string
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return asset('storage/' . $this->image);
           
        }

       
        return 'https://placehold.co/600x400/e9ecef/6c757d?text=No+Image';
    }


    public function getShortDescriptionAttribute(): string
    {
        return mb_strlen($this->description) > 120
            ? mb_substr($this->description, 0, 120) . '...'
            : $this->description;
       
    }

 
    public function getHasLiveUrlAttribute(): bool
    {
        return !empty($this->url);
    }


    public function scopeSearch($query, ?string $term)
    {
        if ($term) {
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                  ->orWhere('description', 'like', "%{$term}%");
            });
        }
        return $query;
    }

   
    public function scopeRecent($query, int $limit = 3)
    {
        return $query->latest()->limit($limit);
    }
}
