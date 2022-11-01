<?php

namespace Notabenedev\SiteStaff;

use Illuminate\Support\ServiceProvider;
use Notabenedev\SiteStaff\Console\Commands\StaffMakeCommand;

class StaffServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/site-staff.php', 'site-staff'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Публикация конфигурации
        $this->publishes([
            __DIR__.'/config/site-staff.php' => config_path('site-staff.php')
        ], 'config');

        // Подключение миграции
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        // Console
        if ($this->app->runningInConsole()){
            $this->commands([
                StaffMakeCommand::class,
            ]);
        }

        //Подключаем роуты
        if (config("site-staff.staffAdminRoutes")) {
            $this->loadRoutesFrom(__DIR__."/routes/admin/department.php");
        }
    }
}
