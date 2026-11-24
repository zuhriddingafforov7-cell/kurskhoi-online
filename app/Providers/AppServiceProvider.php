<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Core\Database;
use Src\Repositories\CategoryRepository;
use Src\Repositories\CourseReportRepository;
use Src\Repositories\CourseRepository;
use Src\Repositories\UserRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CourseRepository::class, fn (): CourseRepository => new CourseRepository(Database::connection()));
        $this->app->bind(CategoryRepository::class, fn (): CategoryRepository => new CategoryRepository(Database::connection()));
        $this->app->bind(UserRepository::class, fn (): UserRepository => new UserRepository(Database::connection()));
        $this->app->bind(CourseReportRepository::class, fn (): CourseReportRepository => new CourseReportRepository(Database::connection()));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $host = request()->getHost();
        $isLocalHost = in_array($host, ['127.0.0.1', 'localhost', '::1'], true);

        if (
            request()->header('X-Forwarded-Proto') === 'https'
            || request()->isSecure()
            || (!$isLocalHost && str_starts_with((string) config('app.url'), 'https://'))
        ) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        } else {
            \Illuminate\Support\Facades\URL::forceScheme('http');
        }
    }
}
