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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('author');
            $table->string('category');
            $table->string('subject_FK');
            $table->index('subject_FK');
            $table->foreign('subject_FK')->references('ime_predmeta')->on('Subject')->onDelete('cascade')->onUpdate('cascade');
            $table->string('document');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists('materials');
    }
};
