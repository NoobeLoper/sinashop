<?php

namespace App\Providers;

use App\Permission;
use App\Policies\UserPolicy;
use App\Policies\OrderPolicy;
use App\Order;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        //For Testing Policy - For Editing Users:
        // User::class => UserPolicy::class,
        Order::class => OrderPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //Baraye nemoone, az raahe Policy anjam shod (Code ha montaqel shode.)
        // Gate::define('edit-user', function ($user, $toBeEditedUser) {
        //     return $user->id == $toBeEditedUser->id;
        // });

        // Code zir ro ezafe kardam, Chon migrate DONE nemishod!
        // if (App::runningInConsole() && !App::environment('testing')) {
        //     return;
        // }

        Gate::before(function ($user){
            if ($user->isSuperUser()) {
                return true;
            }
        });

        foreach (Permission::all() as $permission) {
            Gate::define($permission->name, function ($user) use ($permission) {
                return $user->hasPermission($permission);
            });
        }

    }
}
