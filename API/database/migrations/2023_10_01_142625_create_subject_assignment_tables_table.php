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
        Schema::create('SubjectAssignmentTable', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tsId')->unsigned()->index();
            $table->bigInteger('amId')->unsigned()->index();
            $table->longText('description');
            $table->dateTime('givenAt');
            $table->dateTime('deadline');
            $table->foreign('tsId')->references('id')->on('TeacherSubjectTable')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('amId')->references('id')->on('AssignmentMaterialTable')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('SubjectAssignmentTable');
    }
};
