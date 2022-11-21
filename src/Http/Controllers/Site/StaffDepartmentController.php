<?php

namespace Notabenedev\SiteStaff\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\StaffDepartment;
use Notabenedev\SiteStaff\Facades\StaffDepartmentActions;

class StaffDepartmentController extends Controller
{
    public function index()
    {
        //if (config("site-staff.siteDepartmentsTree", true)) {

            $departments = StaffDepartmentActions::getTree();
            return view("site-staff::site.departments.index", [
                "rootDepartments" => StaffDepartmentActions::getRootDepartments(),
                "departments" => $departments,
            ]);

//        }
//        else {
//
//            try {
//                $department = StaffDepartment::query()
//                    ->whereNull("parent_id")
//                    ->whereNotNull("published_at")
//                    ->orderBy("priority")
//                    ->firstOrFail();
//            }
//            catch (\Exception $exception) {
//                abort(404);
//                $department = null;
//            }
//
//            $child = $department->children()->whereNotNull("published_at")->orderBy("priority")->first();
//            if ($child) {
//                $department = $child;
//            }
//
//            return redirect()
//                ->route("site.departments.show",
//                    ["department" => $department]);
//
//        }

    }
}
