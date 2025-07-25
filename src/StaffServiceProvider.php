<?php

namespace Notabenedev\SiteStaff;

use App\StaffEmployee;
use Illuminate\Contracts\View\View;
use Illuminate\Support\ServiceProvider;
use Notabenedev\SiteStaff\Console\Commands\StaffMakeCommand;
use App\StaffDepartment;
use App\Observers\Vendor\SiteStaff\StaffDepartmentObserver;
use Notabenedev\SiteStaff\Events\StaffDepartmentChangePosition;
use Notabenedev\SiteStaff\Listeners\DepartmentEmployeesPriorityClearCache;
use Notabenedev\SiteStaff\Listeners\DepartmentIdsInfoClearCache;
use PortedCheese\BaseSettings\Events\ImageUpdate;
use Notabenedev\SiteStaff\Listeners\ClearCacheOnUpdateImage;
use PortedCheese\BaseSettings\Events\PriorityUpdate;

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
            $this->loadRoutesFrom(__DIR__."/routes/admin/employee.php");
        }
        if (config("site-staff.staffSiteRoutes")) {
            $this->loadRoutesFrom(__DIR__."/routes/site/department.php");
            $this->loadRoutesFrom(__DIR__."/routes/site/employee.php");
        }

        // Подключение шаблонов.
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'site-staff');

        view()->composer([
            "site-staff::admin.departments.includes.tree-checkbox",

        ], function ($view){
            $departments = StaffDepartment::getTree();
            $view->with("departments", $departments);
        });

        // SVG.
        $svg = app()->config["theme.configSvg"];
        $svg[] = "site-staff::layouts.svg";
        app()->config["theme.configSvg"] = $svg;

        // Подключаем изображения.
        $imagecache = app()->config['imagecache.paths'];
        $imagecache[] = 'storage/employees/main';
        $imagecache[] = 'storage/gallery/employee';
        app()->config['imagecache.paths'] = $imagecache;

        // Подключаем галерею.
        $gallery = app()->config["gallery.models"];
        $gallery["employee"] = StaffEmployee::class;
        app()->config["gallery.models"] = $gallery;

        // Фильтры
        $this->extendImages();

        // Подключение метатегов.
        $seo = app()->config["seo-integration.models"];
        $seo["departments"] = StaffDepartment::class;
        $seo["employees"] = StaffEmployee::class;
        app()->config["seo-integration.models"] = $seo;

        // Events
        $this->addEvents();

        // Наблюдатели.
        $this->addObservers();

        // Assets.
        $this->publishes([
            __DIR__ . '/resources/js/components' => resource_path('js/components/vendor/site-staff'),
            __DIR__ . "/resources/sass" => resource_path("sass/vendor/site-staff"),
            __DIR__ . '/resources/js/scripts' => resource_path('js/vendor/site-staff'),
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
        $this->app->singleton("staff-employee-actions", function () {
            $class = config("site-staff.employeeFacade");
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
        // Подписаться на обновление изображений.
        $this->app['events']->listen(ImageUpdate::class, ClearCacheOnUpdateImage::class);
        // Подписаться на изменения приоритета сотрудника
        $this->app['events']->listen(PriorityUpdate::class, DepartmentEmployeesPriorityClearCache::class);
    }

    /**
     * Стили для изображений.
     */
    private function extendImages()
    {
        $imagecache = app()->config['imagecache.templates'];

        $imagecache['certificate'] = \Notabenedev\SiteStaff\Filters\Certificate::class;
        $imagecache['employees'] = \Notabenedev\SiteStaff\Filters\Employees::class;
        $imagecache['employees-grid-xxl-4'] = \Notabenedev\SiteStaff\Filters\EmployeesGridXxl4::class;
        $imagecache['employees-grid-xl-4'] = \Notabenedev\SiteStaff\Filters\EmployeesGridXl4::class;
        $imagecache['employees-grid-xxl-3'] = \Notabenedev\SiteStaff\Filters\EmployeesGridXxl3::class;
        $imagecache['employees-grid-xl-3'] = \Notabenedev\SiteStaff\Filters\EmployeesGridXl3::class;
        $imagecache['employees-grid-lg-4'] = \Notabenedev\SiteStaff\Filters\EmployeesGridLg4::class;
        $imagecache['employees-grid-lg-3'] = \Notabenedev\SiteStaff\Filters\EmployeesGridLg3::class;
        $imagecache['employees-grid-md-4'] = \Notabenedev\SiteStaff\Filters\EmployeesGridMd4::class;
        app()->config['imagecache.templates'] = $imagecache;
    }
}
