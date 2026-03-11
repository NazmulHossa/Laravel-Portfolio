<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Skill extends Model
{
    use HasFactory;

   
    protected $table = 'skills';

   
    protected $fillable = [
        'name',
        'percentage',
    ];

    
    protected $casts = [
        'percentage' => 'integer',
    ];

   
    public function getLevelAttribute(): string
    {
        return match (true) {
            $this->percentage >= 90 => 'Expert',
            $this->percentage >= 70 => 'Advanced',
            $this->percentage >= 50 => 'Intermediate',
            default                 => 'Beginner',
        };
    }

   
    public function getBadgeColorAttribute(): string
    {
        return match (true) {
            $this->percentage >= 90 => 'success',   // green  — Expert
            $this->percentage >= 70 => 'primary',   // blue   — Advanced
            $this->percentage >= 50 => 'warning',   // yellow — Intermediate
            default                 => 'secondary', // grey   — Beginner
        };
    }

    
    public function getBarColorAttribute(): string
    {
        return match (true) {
            $this->percentage >= 90 => 'bg-success',
            $this->percentage >= 70 => 'bg-primary',
            $this->percentage >= 50 => 'bg-warning',
            default                 => 'bg-secondary',
        };
    }

   
    public function scopeOrdered($query)
    {
        return $query->orderByDesc('percentage');
    }

    
    public function scopeTopSkills($query)
    {
        return $query->where('percentage', '>=', 70)->orderByDesc('percentage');
    }

 
    public function scopeSearch($query, ?string $term)
    {
        if ($term) {
            $query->where('name', 'like', "%{$term}%");
        }
        return $query;
    }
}
