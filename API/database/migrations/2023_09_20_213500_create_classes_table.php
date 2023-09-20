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
        Schema::create('Classes', function (Blueprint $table) {
            $table->id();
            $table->string('naziv')->index();
            $table->string('razrednik');
            $table->string('naziv_sole');
            $table->foreign('naziv_sole')->references('naziv')->on('Schools')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('razrednik')->references('email')->on('Teachers')->onDelete('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Classes');
    }
};
