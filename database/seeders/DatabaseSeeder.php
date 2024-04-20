<?php

namespace Database\Seeders;

use App\Models\v1\Manga;
use App\Models\v1\Role;
use App\Models\v1\Tag;
use App\Models\v1\Team;
use App\Models\v1\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            Role::ADMIN => 'admin',
            Role::MODERATOR =>   'moderator',
            Role::TEAM_LEADER => 'team_leader',
            Role::TEAM_MEMBER => 'team_member'
        ];

        foreach ($roles as $key => $value) {
            Role::create([
                'name' => $value
            ]);
        }

        $admin = User::create([
            'username' => 'g3ano',
            'slug' => 'g3ano',
            'email' => 'g3ano@email.com',
            'password' => Hash::make('password'),
        ]);
        $mod = User::create([
            'username' => 'mod',
            'slug' => 'mod',
            'email' => 'mod@email.com',
            'password' => Hash::make('password'),
        ]);
        $admin->roles()->attach($admin->id, [
            'user_id' => $admin->id,
            'role_id' => Role::ADMIN,
        ]);
        $mod->roles()->attach($mod->id, [
            'user_id' => $mod->id,
            'role_id' => Role::MODERATOR,
        ]);
        User::create([
            'username' => 'regular',
            'slug' => 'regular',
            'email' => 'regular@email.com',
            'password' => Hash::make('password'),
        ]);

        User::factory(500)->create();
        Team::factory(50)->create();

        Tag::factory(50)->create();
        Manga::factory(200)->create();

        for ($i = 0; $i < 500; $i++) {
            DB::table('team_user')
                ->insert([
                    'team_id' => ceil(($i + 1) / 10),
                    'user_id' => $i + 1,
                    'is_leader' => ($i + 1) % 10 === 0 ? 1 : 0,
                    'is_pending' => Team::MEMBER_ACTIVE,
                    'created_at' => fake()->dateTimeThisDecade(),
                ]);

            DB::table('role_user')
                ->insert([
                    'user_id' => $i + 1,
                    'role_id' => Role::TEAM_MEMBER,
                ]);

            if (($i + 1) % 10 === 0) {
                DB::table('role_user')
                    ->insert([
                        'user_id' => $i + 1,
                        'role_id' => Role::TEAM_LEADER,
                    ]);
            }
        }

        for ($i = 0; $i < 200; $i++) {

            DB::table('manga_tag')
                ->insert([
                    'manga_id' => $i + 1,
                    'tag_id' => random_int(1, 50),
                ]);

            if ($i % 2 === 0) {
                DB::table('manga_tag')
                    ->insert([
                        'manga_id' => $i + 1,
                        'tag_id' => random_int(1, 50),
                    ]);
            }

            if ($i % 3 === 0) {
                DB::table('manga_tag')
                    ->insert([
                        'manga_id' => $i + 1,
                        'tag_id' => random_int(1, 50),
                    ]);
            }
        }
    }
}
