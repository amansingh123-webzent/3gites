<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Photo;
use App\Models\Poll;
use App\Models\Post;
use App\Models\User;
use App\Policies\CommentPolicy;
use App\Policies\PhotoPolicy;
use App\Policies\PollPolicy;
use App\Policies\PostPolicy;
use App\Policies\ProfilePolicy;
use App\Services\ImageUploadService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ImageUploadService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Define admin gate for @can('admin') checks
        Gate::define('admin', function (User $user) {
            return $user->hasRole('admin');
        });
        
        // Register ProfilePolicy
        Gate::policy(User::class, ProfilePolicy::class);
        
        // Register Post and Comment policies
        Gate::policy(Post::class, PostPolicy::class);
        Gate::policy(Comment::class, CommentPolicy::class);
        
        // Register PhotoPolicy
        Gate::policy(Photo::class, PhotoPolicy::class);
        
        // Register PollPolicy
        Gate::policy(Poll::class, PollPolicy::class);

        // Use Tailwind CSS for pagination
        \Illuminate\Pagination\Paginator::useTailwind();

        // Login: 5 attempts per minute per email+IP combo
        // (Already handled in LoginRequest — this is a belt-and-suspenders fallback)
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by(
                $request->string('email')->lower() . '|' . $request->ip()
            );
        });

        // Forgot password: 3 attempts per minute per IP
        RateLimiter::for('forgot-password', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip());
        });

        // Contact form: 5 per hour per IP (used in Module: Contact)
        RateLimiter::for('contact', function (Request $request) {
            return Limit::perHour(5)->by($request->ip());
        });
    }
}
