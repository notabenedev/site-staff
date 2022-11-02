<?php

namespace Notabenedev\SiteStaff\Facades;

use Illuminate\Support\Facades\Facade;
use Notabenedev\SiteStaff\Helpers\StaffDepartmentActionsManager;

/**
 *
 * Class StaffDepartmentActions
 * @package Notabenedev\SiteStaff\Facades
 *
 *
 */
class StaffDepartmentActions extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "staff-department-actions";
    }
}