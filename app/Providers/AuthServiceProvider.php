<?php

namespace App\Providers;

use App\Models\Page;
use App\Policies\PagePolicy;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Support\Facades\Gate;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Page::class => PagePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('files.upload', function (Authenticatable $user) {
            $mode = settings('files.upload');

            if ($mode == '1') {
                return !is_null($user->getAuthIdentifier());
            }

            return $user->can('files.upload');
        });

        Gate::define('files.view', function (Authenticatable $user) {
            return Gate::allows('admin.access') || Gate::allows('files.upload');
        });

        Gate::define('admin.access', function (Authenticatable $user) {
            return $user->can('admin.*');
        });

        Gate::before(function($user, $ability) {
            if ($user instanceof Authenticatable && $user->can($ability)) {
                return true;
            }
        });
    }
}
