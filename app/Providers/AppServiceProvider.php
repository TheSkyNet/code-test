<?php

namespace App\Providers;

use App\Models\Section;
use Illuminate\Support\Facades\Route;
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
        /**
         * @todo
         * This should be moved somewhere more obvious because it May cause model binding issues Elsewhere in the application
         * https://laravel.com/docs/11.x/routing#customizing-the-resolution-logic
         * It also really should be in one query
         */
        Route::bind('section', function (string $value) {
            if (is_numeric($value)) {
                return Section::findOrFail($value);
            }
            $section = Section::where('description', '=', $value);
            if ($section->exists()) {
                return $section->first();
            }

            return Section::where('description', 'like', '%' . $value . '%')
                ->firstOrFail();
        });
    }
}
