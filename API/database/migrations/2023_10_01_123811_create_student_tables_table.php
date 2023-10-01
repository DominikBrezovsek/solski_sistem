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
        Schema::create('StudentTable', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->string('email');
            $table->bigInteger('loginId')->unsigned()->index();
            $table->bigInteger('classId')->unsigned()->index();
            $table->foreign('loginId')->references('id')->on('UserLoginTable')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('classId')->references('id')->on('ClassTable')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('StudentTable');
    }
};
