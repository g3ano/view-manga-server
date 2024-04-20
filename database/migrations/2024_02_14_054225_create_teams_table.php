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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name', 75);
            $table->string('slug', 255);
            $table->string('description', 255);
            $table->string('email', 255);
            $table->string('website', 75)->nullable();
            $table->string('twitter', 75)->nullable();
            $table->string('facebook', 75)->nullable();
            $table->string('discord', 75)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
