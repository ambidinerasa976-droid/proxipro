<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
        VerifyEmail::toMailUsing(function ($notifiable, string $url) {
            return (new MailMessage)
                ->subject('Verification de votre adresse e-mail')
                ->greeting('Bonjour')
                ->line('Merci pour votre inscription sur ProxiPro.')
                ->line('Cliquez sur le bouton ci-dessous pour verifier votre adresse e-mail.')
                ->action('Verifier mon adresse e-mail', $url)
                ->line('Si vous n\'avez pas cree de compte, ignorez cet email.');
        });

        // Configurer Carbon en français pour les dates
        Carbon::setLocale('fr');
        setlocale(LC_TIME, 'fr_FR.UTF-8', 'fra_fra', 'fra');

        // Enregistrer les fonctions mathématiques manquantes pour SQLite (Haversine)
        if (DB::connection()->getDriverName() === 'sqlite') {
            $pdo = DB::connection()->getPdo();
            $pdo->sqliteCreateFunction('acos', 'acos', 1);
            $pdo->sqliteCreateFunction('cos', 'cos', 1);
            $pdo->sqliteCreateFunction('sin', 'sin', 1);
            $pdo->sqliteCreateFunction('radians', 'deg2rad', 1);
        }
    }
}
