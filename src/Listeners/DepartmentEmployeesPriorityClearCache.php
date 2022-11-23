<?php

namespace Notabenedev\SiteStaff\Listeners;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Notabenedev\SiteStaff\Events\StaffDepartmentChangePosition;
use Notabenedev\SiteStaff\Facades\StaffDepartmentActions;
use Notabenedev\SiteStaff\Models\StaffEmployee;
use PortedCheese\BaseSettings\Events\PriorityUpdate;

class DepartmentEmployeesPriorityClearCache
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(PriorityUpdate $event)
    {

        if ($event->table == "staff_employees") {
            $ids = $event->ids;
            if (! empty($ids)) {
                $employees = StaffEmployee::query()->whereIn("id", $ids)->get();
                foreach ($employees as $employee){
                    $employee->forgetCache();
                    $departments = $employee->departments;
                    foreach ($departments as $department){
                        // Очистить id сотрудников
                        StaffDepartmentActions::forgetDepartmentEmployeesIds($department);
                    }
                }
            }
        }
        Cache::forget("staff-employees-getAllPublished");
    }
}
