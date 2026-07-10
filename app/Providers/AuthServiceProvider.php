<?php

namespace App\Providers;

use App\Models\ForumDiskusi;
use App\Models\Absensi;
use App\Policies\ForumPolicy;
use App\Policies\AbsensiPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        ForumDiskusi::class => ForumPolicy::class,
        Absensi::class => AbsensiPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Gate: Check if user can send forum message
        Gate::define('send-forum-message', function ($user, ForumDiskusi $forum) {
            return (new ForumPolicy())->sendMessage($user, $forum);
        });

        // Gate: Check if user can view forum
        Gate::define('view-forum', function ($user, ForumDiskusi $forum) {
            return (new ForumPolicy())->view($user, $forum);
        });

        // Gate: Check if user can delete forum message
        Gate::define('delete-forum-message', function ($user, ForumDiskusi $forum) {
            return (new ForumPolicy())->deleteMessage($user, $forum);
        });

        // Gate: Check if user can check in to attendance
        Gate::define('check-in-attendance', function ($user, Absensi $absensi) {
            return (new AbsensiPolicy())->checkIn($user, $absensi);
        });

        // Gate: Check if user can manage attendance
        Gate::define('manage-attendance', function ($user, Absensi $absensi) {
            return (new AbsensiPolicy())->manageAttendance($user, $absensi);
        });

        // Gate: Check if user can view attendance history
        Gate::define('view-attendance-history', function ($user, Absensi $absensi) {
            return (new AbsensiPolicy())->viewHistory($user, $absensi);
        });
    }
}