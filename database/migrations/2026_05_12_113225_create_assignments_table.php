<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $row) {
            $row->id();
            $row->string('title');
            $row->text('description')->nullable();
            $row->foreignId('subject_id')->constrained()->onDelete('cascade');
            $row->string('grade_level');
            $row->string('section');
            $row->dateTime('due_date');
            $row->integer('max_score')->default(100);
            $row->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $row->string('status')->default('active'); // active, draft, archived
            $row->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
