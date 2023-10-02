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
        Schema::create('StudentSubjectTable', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('subjectId')->unsigned()->index();
            $table->bigInteger('studentId')->unsigned()->index();
            $table->dateTime('enrolledAt');
            $table->foreign('subjectId')->references('id')->on('SubjectTable')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('studentId')->references('id')->on('StudentTable')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('StudentSubjectTable');
    }
};
