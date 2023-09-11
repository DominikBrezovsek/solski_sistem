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
        Schema::create('Students', function (Blueprint $table) {
            $table->id();
            $table->string('ime');
            $table->string('priimek');
            $table->string('sola');
            $table->string('oddelek');
            $table->string('naslov');
            $table->string('posta');
            $table->string('postna_st');
            $table->string('telefonska');
            $table->string('email');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
