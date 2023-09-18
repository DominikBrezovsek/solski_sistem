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
        Schema::create('AuthToken', function (Blueprint $table) {
            $table->id();
            $table->string('token_id');
            $table->string('token_key');
            $table->bigInteger('user_id');
            $table->bigInteger('issued_at');
            $table->bigInteger('expires_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auth_tokens');
    }
};
