<?php

namespace App\Providers;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Gate::define('isTeknisi', function (User $user) {
            return $user->level === 'teknisi';
        });

        Gate::define('isKaprog', function (User $user) {
            return $user->level === 'kaprog';
        });

        Gate::define('isKabeng', function (User $user) {
            return $user->level === 'kabeng';
        });

        Gate::define('isSiswa', function (User $user) {
            return $user->level === 'siswa';
        });


    }
}