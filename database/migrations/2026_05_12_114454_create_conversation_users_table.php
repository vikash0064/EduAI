<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversation_users', function (Blueprint $row) {
            $row->id();
            $row->foreignId('conversation_id')->constrained()->onDelete('cascade');
            $row->foreignId('user_id')->constrained()->onDelete('cascade');
            $row->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversation_users');
    }
};
