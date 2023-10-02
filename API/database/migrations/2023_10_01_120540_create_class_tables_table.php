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
        Schema::create('ClassTable', function (Blueprint $table) {
            $table->id();
            $table->string('class');
            $table->bigInteger('teacherId')->unsigned()->index();
            $table->bigInteger('schoolId')->unsigned()->index();
            $table->foreign('teacherId')->references('id')->on('TeacherTable')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('schoolId')->references('id')->on('SchoolTable')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ClassTable');
    }
};
