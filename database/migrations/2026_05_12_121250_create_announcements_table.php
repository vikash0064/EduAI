<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $row) {
            $row->id();
            $row->string('title');
            $row->text('message');
            $row->string('type')->default('general'); // general, emergency, holiday
            $row->string('target_role')->default('all'); // all, students, teachers, parents
            $row->foreignId('created_by')->constrained('users');
            $row->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
