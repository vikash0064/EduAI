<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $row) {
            $row->id();
            $row->foreignId('assignment_id')->constrained()->onDelete('cascade');
            $row->foreignId('student_profile_id')->constrained()->onDelete('cascade');
            $row->text('content')->nullable();
            $row->string('file_path')->nullable();
            $row->string('status')->default('pending'); // pending, graded
            $row->integer('score')->nullable();
            $row->text('feedback')->nullable();
            $row->timestamp('submitted_at')->nullable();
            $row->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
