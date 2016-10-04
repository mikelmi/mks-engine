<?php

namespace App\Providers;

use App\Models\Page;
use App\Policies\PagePolicy;
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

        Gate::before(function(Authenticatable $user, $ability) {
            if ($user->isAdmin()) {
                return true;
            }

            return null;
        });

        Gate::define('upload', function (Authenticatable $user) {
            $mode = settings('files.upload');

            if ($mode == '1') {
                return !is_null($user->getAuthIdentifier());
            }

            return $user->can('files.upload');
        });
    }
}
