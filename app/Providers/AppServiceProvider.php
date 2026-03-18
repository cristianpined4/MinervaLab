<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Blade;
use App\Helpers\DateHelper;

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
            return "<?php echo DateHelper::formatDate({$date}); ?>";
        });

        // Directiva para formatear hora a HH:MM AM/PM
        Blade::directive('timeFormat', function ($time) {
            return "<?php echo DateHelper::formatTime({$time}); ?>";
        });

        // Directiva para formatear fecha y hora
        Blade::directive('dateTimeFormat', function ($datetime) {
            return "<?php echo DateHelper::formatDateTime({$datetime}); ?>";
        });

        // Registrar helper global
        if (!function_exists('formatDate')) {
            function formatDate($date) {
                return DateHelper::formatDate($date);
            }
        }

        if (!function_exists('formatTime')) {
            function formatTime($time) {
                return DateHelper::formatTime($time);
            }
        }

        if (!function_exists('formatDateTime')) {
            function formatDateTime($datetime) {
                return DateHelper::formatDateTime($datetime);
            }
        }
    }
}

