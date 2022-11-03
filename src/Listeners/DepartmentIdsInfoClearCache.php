<?php

namespace Notabenedev\SiteStaff\Listeners;

use Notabenedev\SiteStaff\Events\StaffDepartmentChangePosition;
use Notabenedev\SiteStaff\Facades\StaffDepartmentActions;

class DepartmentIdsInfoClearCache
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
    public function handle(StaffDepartmentChangePosition $event)
    {
        $department = $event->department;
        // Очистить список id категорий.
        StaffDepartmentActions::forgetDepartmentChildrenIdsCache($department);
        StaffDepartmentActions::forgetDepartmentParentsCache($department);
    }
}
