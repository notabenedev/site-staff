<?php

namespace Notabenedev\SiteStaff\Facades;

use App\StaffEmployee;
use Illuminate\Support\Facades\Facade;

/**
 *
 * Class StaffDepartmentActions
 * @package Notabenedev\SiteStaff\Facades
 * @method static array getAllList()
 * @method static array getSiteBreadcrumb(StaffEmployee $employee)

 */
class StaffEmployeeActions extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "staff-employee-actions";
    }
}