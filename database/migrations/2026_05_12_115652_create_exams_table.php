<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $row) {
            $row->id();
            $row->string('title');
            $row->foreignId('subject_id')->constrained()->onDelete('cascade');
            $row->string('grade_level');
            $row->string('section')->nullable();
            $row->dateTime('date');
            $row->string('room')->nullable();
            $row->string('status')->default('upcoming'); // upcoming, scheduled, completed
            $row->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
