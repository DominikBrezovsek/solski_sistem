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
        Schema::create('SubmissionTable', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('assignmentId')->unsigned()->index();
            $table->bigInteger('studSubjectId')->unsigned()->index();
            $table->dateTime('handedInAt');
            $table->string('file');
            $table->foreign('assignmentId')->references('id')->on('SubjectAssignmentTable')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('studSubjectId')->references('id')->on('StudentSubjectTable')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('SubmissionTable');
    }
};
