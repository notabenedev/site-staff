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

        return 0;
    }
}
