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
        Schema::create('Atendees', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('predmet')->unsigned()->index();
            $table->bigInteger('dijak')->unsigned()->index();
            $table->dateTime('enrolled_at');
            $table->foreign('predmet')->references('id')->on('Subject')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('dijak')->references('id')->on('Students')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Atendees');
    }
};
