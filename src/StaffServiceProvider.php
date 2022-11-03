<?php

namespace Notabenedev\SiteStaff;

use Illuminate\Contracts\View\View;
use Illuminate\Support\ServiceProvider;
use Notabenedev\SiteStaff\Console\Commands\StaffMakeCommand;
use App\StaffDepartment;
use App\Observers\Vendor\SiteStaff\StaffDepartmentObserver;
use Notabenedev\SiteStaff\Events\StaffDepartmentChangePosition;
use Notabenedev\SiteStaff\Listeners\DepartmentIdsInfoClearCache;

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

        $this->initFacades();

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
        if (config("site-staff.staffSiteRoutes")) {
            $this->loadRoutesFrom(__DIR__."/routes/site/department.php");
        }

        // Подключение шаблонов.
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'site-staff');

        // Подключение метатегов.
        $seo = app()->config["seo-integration.models"];
        $seo["departments"] = StaffDepartment::class;
        app()->config["seo-integration.models"] = $seo;

        // Events
        $this->addEvents();

        // Наблюдатели.
        $this->addObservers();

        // Assets.
        $this->publishes([
            __DIR__ . '/resources/js/components' => resource_path('js/components/vendor/site-staff'),
        ], 'public');
    }

    /**
     * Подключение Facade.
     */
    protected function initFacades()
    {
        $this->app->singleton("staff-department-actions", function () {
            $class = config("site-staff.departmentFacade");
            return new $class;
        });
    }

    /**
     * Добавление наблюдателей.
     */
    protected function addObservers()
    {
        if (class_exists(StaffDepartmentObserver::class) && class_exists(StaffDepartment::class)) {
            StaffDepartment::observe(StaffDepartmentObserver::class);
        }
    }

    /**
     * Подключение Events.
     */

    protected function addEvents()
    {
        // Изменение позиции группы.
        $this->app["events"]->listen(StaffDepartmentChangePosition::class, DepartmentIdsInfoClearCache::class);
    }
}
