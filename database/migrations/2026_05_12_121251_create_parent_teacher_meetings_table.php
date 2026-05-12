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
        Schema::create('parent_teacher_meetings', function (Blueprint $row) {
            $row->id();
            $row->foreignId('teacher_id')->constrained('users');
            $row->foreignId('parent_id')->constrained('users');
            $row->dateTime('meeting_date');
            $row->string('status')->default('scheduled'); // scheduled, completed, cancelled
            $row->text('notes')->nullable();
            $row->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parent_teacher_meetings');
    }
};
