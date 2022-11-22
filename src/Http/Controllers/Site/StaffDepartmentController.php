<?php

namespace Notabenedev\SiteStaff\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\StaffDepartment;
use App\StaffEmployee;
use Notabenedev\SiteStaff\Facades\StaffDepartmentActions;

class StaffDepartmentController extends Controller
{
    public function index()
    {
        if (config("site-staff.siteDepartmentsTree", true)) {

            $departments = StaffDepartmentActions::getTree();
            return view("site-staff::site.departments.index", [
                "rootDepartments" => StaffDepartmentActions::getRootDepartments(),
                "departments" => $departments,
            ]);

        }
        else {
            return  view("site-staff::site.departments.index", [
                "employees" => StaffEmployee::getAllPublished(),
            ]);
        }

    }
}
