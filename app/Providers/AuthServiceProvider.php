<?php

namespace App\Providers;

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
        // Đăng ký các policies tại đây nếu có
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Định nghĩa khả năng 'admin'
        Gate::define('admin', function ($user) {
            // Điều kiện để xác định người dùng là admin
            return $user->role === 'admin';
        });
    }
}
