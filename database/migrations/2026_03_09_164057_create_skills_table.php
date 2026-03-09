<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // ═══════════════════════════════════════════════════════════
    // Run with: php artisan migrate
    // Rollback with: php artisan migrate:rollback
    // ═══════════════════════════════════════════════════════════
    public function up(): void
    {
        Schema::create('skills', function (Blueprint $table) {
            $table->id();                               // BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY

            $table->string('name');                     // Skill name e.g. "Laravel", "Vue.js"
            //   string() = VARCHAR(255) by default

            $table->unsignedTinyInteger('percentage');  // Skill level: 1–100
            //   unsignedTinyInteger = 0 to 255, perfect for a percentage (saves space vs integer)

            $table->timestamps();                       // Adds created_at and updated_at columns
            //   Laravel auto-manages these when you use Eloquent create/update
        });
    }

    public function down(): void
    {
        // This runs when you rollback — it drops the table entirely
        Schema::dropIfExists('skills');
    }
};