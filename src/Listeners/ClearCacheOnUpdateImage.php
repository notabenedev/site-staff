<?php

namespace Notabenedev\SiteStaff\Listeners;

use App\StaffEmployee;
use Notabenedev\SiteStaff\Facades\StaffDepartmentActions;

class ClearCacheOnUpdateImage
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
    public function handle($event)
    {
        $morph = $event->morph;
        if (!empty($morph) && get_class($morph) == StaffEmployee::class) {
            $morph->forgetCache();
            $departments = $morph->departments;
            foreach ($departments as $department){
                // Очистить id сотрудников
                StaffDepartmentActions::forgetDepartmentEmployeesIds($department);
            }
        }
    }
}
