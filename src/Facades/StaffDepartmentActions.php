<?php

namespace Notabenedev\SiteStaff\Facades;

use App\StaffDepartment;
use Illuminate\Support\Facades\Facade;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Notabenedev\SiteStaff\Helpers\StaffDepartmentActionsManager;

/**
 *
 * Class StaffDepartmentActions
 * @package Notabenedev\SiteStaff\Facades
 * @method static array static getAllList()
 * @method static array getTree()
 * @method static bool saveOrder(array $data)
 * @method static array getDepartmentChildren(StaffDepartment $department, $includeSelf = false)
 * @method static forgetDepartmentChildrenIdsCache(StaffDepartment $department)
 * @method static forgetCategoryChildrenIdsCache(StaffDepartment $department)
 * @method static array getAdminBreadcrumb(StaffDepartment $department, $isEmployeePage = false)
 * @method static Collection|Builder[] getRootDepartments()
 * @method static array  getChildrenTree(StaffDepartment $department)
 * @method static array  getDepartmentParents(StaffDepartment $department)
 * @method static array  forgetDepartmentParentsCache(StaffDepartment $department)

 */
class StaffDepartmentActions extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "staff-department-actions";
    }
}