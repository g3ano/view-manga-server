<?php

namespace Database\Factories\v1;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Team>
 */
class TeamFactory extends Factory
{
    public function definition(): array
    {
        $date = fake()->dateTimeThisYear();
        $name = fake()->sentence(2);
        $slug = Str::slug($name);
        $userName = Str::slug($name);
        $description = 'هو ببساطة نص شكلي (بمعنى أن الغاية هي الشكل وليس المحتوى) ويُستخدم في صناعات المطابع ودور النشر. كان لوريم إيبسوم ولايزال المعيار للنص الشكلي منذ القرن الخامس عشر عندما قامت مطبعة مجهولة برص مجموعة من الأحرف بشكل عشوائي أخذتها من نص';

        return [
            'name' => $name,
            'slug' => $slug,
            'name' => fake()->text(50),
            'description' => $description,
            'email' => fake()->safeEmail(),
            'created_at' => $date,
            'updated_at' => $date,
            'twitter' => $userName,
            'facebook' => $userName,
        ];
    }
}
