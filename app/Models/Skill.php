<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Skill extends Model
{
    use HasFactory;

    // ── TABLE NAME ───────────────────────────────────────────
    // Laravel auto-guesses "skills" from "Skill", but being explicit is good practice
    protected $table = 'skills';

    // ── MASS ASSIGNMENT ──────────────────────────────────────
    // Only these fields can be filled via Skill::create() or $skill->update()
    // Any other field sent from a form will be silently ignored (security)
    protected $fillable = [
        'name',
        'percentage',
    ];

    // ── CASTS ────────────────────────────────────────────────
    // Auto-converts DB values to PHP types when you access them
    // DB stores percentage as "90" (string) → cast makes it integer 90
    protected $casts = [
        'percentage' => 'integer',
    ];

    // ════════════════════════════════════════════════════════
    // ACCESSORS  (virtual properties computed from real data)
    // Access them like normal properties: $skill->level
    // ════════════════════════════════════════════════════════

    // $skill->level  →  "Expert" / "Advanced" / "Intermediate" / "Beginner"
    // match(true) checks each condition top-to-bottom, stops at first match
    public function getLevelAttribute(): string
    {
        return match (true) {
            $this->percentage >= 90 => 'Expert',
            $this->percentage >= 70 => 'Advanced',
            $this->percentage >= 50 => 'Intermediate',
            default                 => 'Beginner',
        };
    }

    // $skill->badge_color  →  Bootstrap color name: "success", "primary", etc.
    // Usage in Blade: <span class="badge bg-{{ $skill->badge_color }}">
    public function getBadgeColorAttribute(): string
    {
        return match (true) {
            $this->percentage >= 90 => 'success',   // green  — Expert
            $this->percentage >= 70 => 'primary',   // blue   — Advanced
            $this->percentage >= 50 => 'warning',   // yellow — Intermediate
            default                 => 'secondary', // grey   — Beginner
        };
    }

    // $skill->bar_color  →  Bootstrap progress bar color
    // Separate from badge_color so you can style them independently
    public function getBarColorAttribute(): string
    {
        return match (true) {
            $this->percentage >= 90 => 'bg-success',
            $this->percentage >= 70 => 'bg-primary',
            $this->percentage >= 50 => 'bg-warning',
            default                 => 'bg-secondary',
        };
    }

    // ════════════════════════════════════════════════════════
    // LOCAL SCOPES  (reusable query fragments)
    // Usage: Skill::ordered()->get()
    // ════════════════════════════════════════════════════════

    // Skill::ordered()->get()
    // Returns all skills sorted highest → lowest percentage
    public function scopeOrdered($query)
    {
        return $query->orderByDesc('percentage');
    }

    // Skill::topSkills()->get()
    // Returns only skills with percentage >= 70
    public function scopeTopSkills($query)
    {
        return $query->where('percentage', '>=', 70)->orderByDesc('percentage');
    }

    // Skill::search('laravel')->get()
    // Returns skills whose name contains the search term
    // Usage: Skill::search(request('q'))->ordered()->get()
    public function scopeSearch($query, ?string $term)
    {
        if ($term) {
            $query->where('name', 'like', "%{$term}%");
        }
        return $query;
    }
}