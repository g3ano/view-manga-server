<?php

use App\Models\v1\Manga;
use App\Models\v1\Tag;
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
        Schema::create('manga_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Manga::class, 'manga_id')
                ->constrained('mangas')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreignIdFor(Tag::class, 'tag_id')
                ->constrained('tags')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manga_tag');
    }
};
