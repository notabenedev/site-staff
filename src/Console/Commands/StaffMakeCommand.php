<?php

namespace Notabenedev\SiteStaff\Console\Commands;

use App\Menu;
use App\MenuItem;
use PortedCheese\BaseSettings\Console\Commands\BaseConfigModelCommand;


class StaffMakeCommand extends BaseConfigModelCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:staff
    {--all : Run all}
    {--models : Export models}
    {--observers : Export observers}
    {--controllers : Export controllers}
    {--policies : Export and create rules} 
    {--only-default : Create only default rules}
    {--menu : Create admin menu}
    {--vue : Export vue}
    {--scss : Export scss}
    ';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Settings for site-staff package';

    /**
     * Vendor Name
     * @var string
     *
     */
    protected $vendorName = 'Notabenedev';

    /**
     * Package Name
     * @var string
     *
     */
    protected $packageName = 'SiteStaff';

    /**
     * The models to  be exported
     * @var array
     */
    protected $models = ["StaffDepartment", "StaffEmployee"];

    /**
     * Создание наблюдателей
     *
     * @var array
     */
    protected $observers = ["StaffDepartmentObserver"];

    /**
     * Make Controllers
     */
    protected $controllers = [
        "Admin" => ["StaffDepartmentController", "StaffEmployeeController"],
        "Site" => ["StaffDepartmentController"],
    ];

    /**
     * Policies
     * @var array
     *
     */
    protected $ruleRules = [
        [
            "title" => "Отделы",
            "slug" => "departments",
            "policy" => "StaffDepartmentPolicy",
        ],
        [
            "title" => "Сотрудники",
            "slug" => "employees",
            "policy" => "StaffEmployeePolicy",
        ],
    ];

    /**
     * Vue files folder
     *
     * @var string
     */
    protected $vueFolder = "site-staff";

    /**
     * Vue files list
     *
     * @var array
     */
    protected $vueIncludes = [
        'admin' => [ 'admin-department-list' => "DepartmentListComponent",
        ],
        'app' => [],
    ];

    /**
     * Стили.
     *
     * @var array
     */
    protected $scssIncludes = [
        "app" => [
            "site-staff/staff",
        ],
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $all = $this->option("all");

        if ($this->option("models") || $all) {
            $this->exportModels();
        }

        if ($this->option("observers") || $all) {
            $this->exportObservers();
        }

        if ($this->option("controllers") || $all) {
            $this->exportControllers("Admin");
            $this->exportControllers("Site");
        }

        if ($this->option("policies") || $all) {
            $this->makeRules();
        }

        if ($this->option("menu") || $all) {
            $this->makeMenu();
        }

        if ($this->option("vue") || $all) {
            $this->makeVueIncludes("admin");
        }

        if ($this->option("scss") || $all) {
            $this->makeScssIncludes("app");
        }

        return 0;
    }

    protected function makeMenu()
    {
        try {
            $menu = Menu::query()
                ->where('key', 'admin')
                ->firstOrFail();
        }
        catch (\Exception $e) {
            return;
        }

        $title = config("site-staff.sitePackageName");
        $itemData = [
            'title' => $title,
            'template' => "site-staff::admin.employees.menu",
            'url' => "#",
            'ico' => 'far fa-newspaper',
            'menu_id' => $menu->id,
        ];

        try {
            $menuItem = MenuItem::query()
                ->where("menu_id", $menu->id)
                ->where('title', $title)
                ->firstOrFail();
            $menuItem->update($itemData);
            $this->info("Элемент меню '$title' обновлен");
        }
        catch (\Exception $e) {
            MenuItem::create($itemData);
            $this->info("Элемент меню '$title' создан");
        }
    }
}
