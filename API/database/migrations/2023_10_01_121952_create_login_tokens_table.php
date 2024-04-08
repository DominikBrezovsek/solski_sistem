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
        Schema::create('LoginToken', function (Blueprint $table) {
            $table->id();
            $table->string('tokenId')->index();
            $table->string('tokenKey');
            $table->bigInteger('loginId')->unsigned()->index();
            $table->string('expiresAt');
            $table->foreign('loginId')->references('id')->on('UserLoginTable')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('LoginToken');
    }
};
