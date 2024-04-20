<?php

namespace App\Providers;

use App\Models\v1\Chapter;
use App\Models\v1\Manga;
use App\Models\v1\Tag;
use App\Models\v1\Team;
use App\Models\v1\User;
use App\Policies\v1\ChapterPolicy;
use App\Policies\v1\MangaPolicy;
use App\Policies\v1\TagPolicy;
use App\Policies\v1\TeamPolicy;
use App\Policies\v1\UserPolicy;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
        Team::class => TeamPolicy::class,
        Tag::class => TagPolicy::class,
        Manga::class => MangaPolicy::class,
        Chapter::class => ChapterPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url') . "/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
    }
}
