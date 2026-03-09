<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        // truncate() deletes all existing rows first, then resets ID back to 1
        // So running db:seed twice won't create duplicates
        Skill::truncate();

        $skills = [
            // ── Backend ──────────────────────────────────────
            ['name' => 'Laravel',    'percentage' => 90],
            ['name' => 'PHP',        'percentage' => 88],
            ['name' => 'MySQL',      'percentage' => 82],
            ['name' => 'REST API',   'percentage' => 80],

            // ── Frontend ─────────────────────────────────────
            ['name' => 'HTML & CSS', 'percentage' => 95],
            ['name' => 'JavaScript', 'percentage' => 78],
            ['name' => 'Vue.js',     'percentage' => 72],
            ['name' => 'Bootstrap',  'percentage' => 88],

            // ── Tools ────────────────────────────────────────
            ['name' => 'Git & GitHub', 'percentage' => 85],
            ['name' => 'Linux / CLI',  'percentage' => 70],
        ];

        foreach ($skills as $skill) {
            Skill::create($skill);
        }
    }
}