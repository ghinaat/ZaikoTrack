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
        try {
            if (\Schema::hasTable('email_configuration')) {
                $mailsetting = EmailConfiguration::first();
                if ($mailsetting) {

                    config(['mail.default' => $mailsetting->protocol]);
                    config(['mail.mailers.smtp.host' => $mailsetting->host]);
                    config(['mail.mailers.smtp.port' => $mailsetting->port]);
                    config(['mail.mailers.smtp.encryption' => $mailsetting->tls]);
                    config(['mail.mailers.smtp.username' => $mailsetting->username]);
                    config(['mail.mailers.smtp.password' => $mailsetting->password]);
                    config(['mail.mailers.form.address' => $mailsetting->email]);
                    config(['mail.mailers.form.name' => 'si-mase']);
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
        }


        
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