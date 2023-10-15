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
        Schema::create('AssignmentMaterialTable', function (Blueprint $table) {
            $table->id();
            $table->string('material');
            $table->dateTime('addedAt');
            $table->bigInteger('author')->unsigned()->index();
            $table->foreign('author')->references('id')->on('TeacherTable')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('AssignmentMaterialTable');
    }
};
