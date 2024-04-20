<?php

namespace Database\Factories\v1;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Manga>
 */
class MangaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $mangaStatus = Arr::random([
            'completed',
            'hiatus',
            'ongoing',
        ]);
        $translationStatus = Arr::random([
            'completed',
            'hiatus',
            'ongoing',
        ]);
        $description = 'هو ببساطة نص شكلي (بمعنى أن الغاية هي الشكل وليس المحتوى) ويُستخدم في صناعات المطابع ودور النشر. كان لوريم إيبسوم ولايزال المعيار للنص الشكلي منذ القرن الخامس عشر عندما قامت مطبعة مجهولة برص مجموعة من الأحرف بشكل عشوائي أخذتها من نص';
        $date = fake()->dateTimeThisYear();
        $title = fake()->realTextBetween(30, 60);
        $slug = Str::slug($title);

        $dir = 'public/manga_covers/';
        $files = Storage::files($dir);
        $cover = $files[rand(0, count($files) - 1)];

        return [
            'team_id' => fake()->numberBetween(1, 50),
            'title' => $title,
            'slug' => $slug,
            'title_en' => Str::reverse($title),
            'description' => $description,
            'manga_status' => $mangaStatus,
            'translation_status' => $translationStatus,
            'author' => fake()->firstName . ' ' . fake()->lastName,
            'cover' => substr($cover, strpos($cover, '/') + 1),
            'is_approved' => fake()->numberBetween(0, 1),
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
