<?php

use App\Models\v1\Team;
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
        Schema::create('mangas', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Team::class, 'team_id')->nullable()
                ->constrained('teams')
                ->onDelete('set null')
                ->onUpdate('set null');
            $table->string('title', 255);
            $table->string('slug', 255);
            $table->string('title_en', 255)->nullable();
            $table->string('title_ar', 255)->nullable();
            $table->string('description', 755);
            $table->string('manga_status', 50);
            $table->string('translation_status', 50)->default('ongoing');
            $table->string('author', 255);
            $table->string('cover');
            $table->tinyInteger('is_approved')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mangas');
    }
};
