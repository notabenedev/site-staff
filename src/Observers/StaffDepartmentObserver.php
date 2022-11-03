<?php

namespace Notabenedev\SiteStaff\Observers;

use App\StaffDepartment;
use Notabenedev\SiteStaff\Events\StaffDepartmentChangePosition;
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
     * После создания.
     *
     * @param StaffDepartment $department
     */
    public function created(StaffDepartment $department)
    {
        event(new StaffDepartmentChangePosition($department));
    }



    /**
     * Перед обновлением.
     *
     * @param StaffDepartment $department
     */
    public function updating(StaffDepartment $department)
    {
        $original = $department->getOriginal();
        if (isset($original["parent_id"]) && $original["parent_id"] !== $department->parent_id) {

            if ((! $department->parent->published_at) && $department->published_at) {
                  $department->publishCascade();
                 // throw new PreventActionException("Невозможно изменить Отдел, родитель не опубликован");
            }
            $this->departmentChangedParent($department, $original["parent_id"]);
        }
    }

    /**
     * После обновления.
     *
     * @param StaffDepartment $department
     */
    public function updated(StaffDepartment $department)
    {
        if (isset($department->parent))
            $this->departmentChangedParent($department, $department->parent->id);
        else
            $this->departmentChangedParent($department, "");
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
        if (! empty($parent)) {
            $parent = StaffDepartment::find($parent);
            event(new StaffDepartmentChangePosition($parent));
        }
        event(new StaffDepartmentChangePosition($department));
    }

}
