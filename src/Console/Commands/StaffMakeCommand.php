<?php

namespace Notabenedev\SiteStaff\Console\Commands;

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
    protected $models = ["StaffDepartment"];

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
        "Admin" => ["StaffDepartmentController"],
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
        }

        if ($this->option("policies") || $all) {
            $this->makeRules();
        }

        return 0;
    }
}
