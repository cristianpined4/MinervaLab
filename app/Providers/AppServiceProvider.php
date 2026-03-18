<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Blade;

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
        // Directiva para formatear fecha a DD/MM/YYYY
        Blade::directive('dateFormat', function ($date) {
            return "<?php echo formatDate({$date}); ?>";
        });

        // Directiva para formatear hora a HH:MM AM/PM
        Blade::directive('timeFormat', function ($time) {
            return "<?php echo formatTime({$time}); ?>";
        });

        // Directiva para formatear fecha y hora
        Blade::directive('dateTimeFormat', function ($datetime) {
            return "<?php echo formatDateTime({$datetime}); ?>";
        });
    }
}

