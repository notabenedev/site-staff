<?php

namespace Notabenedev\SiteStaff\Observers;

use App\StaffDepartment;
use PortedCheese\BaseSettings\Exceptions\PreventActionException;
use PortedCheese\BaseSettings\Exceptions\PreventDeleteException;

class StaffDepartmentObserver
{

    /**
     * Перед сохранением
     *
     * @param StaffDepartment $department
     */
    public function creating(StaffDepartment $department){
        if (isset($department->parent_id)) {
            $max = StaffDepartment::query()
                ->where("parent_id", $department->parent_id)
                ->max("priority");
        }
        else
            $max = StaffDepartment::query()
                ->whereNull("parent_id")
                ->max("priority");

        $department->priority = $max +1;
        if ($department->isParentPublished())  $department->published_at = now();

    }



    /**
     * Перед обновлением.
     *
     * @param StaffDepartment $department
     */
    public function updating(StaffDepartment $department)
    {


    }

    /**
     * После создания.
     *
     * @param StaffDepartment $department
     */
    public function updated(StaffDepartment $department)
    {

    }

    /**
     * Перед удалением
     *
     * @param StaffDepartment $department
     * @throws PreventDeleteException
     */
    public function deleting(StaffDepartment $department){
        if ($department->children->count()){
            throw new PreventDeleteException("Невозможно удалить отдел, есть вложенные");
        }
//        if ($department->employees->count()){
//            throw new PreventDeleteException("Невозможно удалить отдел, есть сотрудники");
//        }
    }

    /**
     * Очистить список id дочерних категорий.
     *
     * @param StaffDepartment $department
     * @param $parent
     */
    protected function departmentChangedParent(StaffDepartment $department, $parent)
    {

    }

}
