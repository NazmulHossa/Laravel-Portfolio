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

    // ════════════════════════════════════════════════════════
    // BOOT METHOD
    // boot() runs when the Model class is first loaded.
    // Use it to register automatic event listeners.
    // ════════════════════════════════════════════════════════

    protected static function boot(): void
    {
        parent::boot(); // Always call parent::boot() first

        // Listen for the "deleting" event.
        // Every time a project is deleted (Project::destroy() or $project->delete()),
        // this closure runs FIRST — before the DB row is removed.
        // This ensures the image file is never left orphaned on disk.
        static::deleting(function (Project $project) {
            if ($project->image && Storage::disk('public')->exists($project->image)) {
                Storage::disk('public')->delete($project->image);
                // Now you can safely delete from ProjectController without worrying about cleanup:
                // $project->delete(); ← boot() handles the image automatically
            }
        });
    }

    // ════════════════════════════════════════════════════════
    // ACCESSORS
    // ════════════════════════════════════════════════════════

    // $project->image_url
    // Returns the full browser-accessible URL for the image.
    // If no image: returns a placeholder so you never need @if in Blade.
    public function getImageUrlAttribute(): string
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return asset('storage/' . $this->image);
            // asset() prepends your APP_URL so you get:
            // http://localhost:8000/storage/projects/abc.jpg
        }

        // Placeholder: grey box with "No Image" text
        return 'https://placehold.co/600x400/e9ecef/6c757d?text=No+Image';
    }

    // $project->short_description
    // Returns first 120 chars with "..." if the description is longer.
    // Used on cards — no need to call Str::limit() in Blade.
    public function getShortDescriptionAttribute(): string
    {
        return mb_strlen($this->description) > 120
            ? mb_substr($this->description, 0, 120) . '...'
            : $this->description;
        // mb_strlen / mb_substr handle multi-byte characters (e.g. Arabic, Bengali)
        // correctly — unlike strlen/substr which count bytes not characters
    }

    // $project->has_live_url
    // Returns true/false. Usage: @if($project->has_live_url)
    public function getHasLiveUrlAttribute(): bool
    {
        return !empty($this->url);
    }

    // ════════════════════════════════════════════════════════
    // LOCAL SCOPES
    // ════════════════════════════════════════════════════════

    // Project::search('laravel')->get()
    // Searches by title or description
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

    // Project::recent(3)->get()
    // Returns the N most recently added projects
    public function scopeRecent($query, int $limit = 3)
    {
        return $query->latest()->limit($limit);
    }
}