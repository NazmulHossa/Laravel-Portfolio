<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');                      // Sender's name
            $table->string('email');                     // Sender's email
            $table->string('subject');                   // Message subject
            $table->text('message');                     // Message body
            $table->boolean('is_read')->default(false);  // ← Read/unread tracking
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};