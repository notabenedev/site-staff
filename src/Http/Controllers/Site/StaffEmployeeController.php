<?php

namespace Notabenedev\SiteStaff\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Meta;
use App\StaffEmployee;
use Notabenedev\SiteStaff\Facades\StaffEmployeeActions;

class StaffEmployeeController extends Controller
{
    public function show(StaffEmployee $employee){

        if (! config("site-staff.employeePage", false) || !$employee->published_at)
            return redirect()->route("site.departments.index",[],301);

        $siteBreadcrumb = null;

        if (config("site-staff.siteBreadcrumb")){
            $siteBreadcrumb =  StaffEmployeeActions::getSiteBreadcrumb($employee);
        }

        $pageMetas = Meta::getByModelKey($employee);

        return view("site-staff::site.employees.show", [
            "employee" => $employee,
            "siteBreadcrumb" => $siteBreadcrumb,
            "pageMetas" => $pageMetas,
        ]);
    }
}
