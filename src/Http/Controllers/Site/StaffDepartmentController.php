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
        $siteBreadcrumb = null;

        if (config("site-staff.siteBreadcrumb")){
            $siteBreadcrumb = [
                (object) [
                    'active' => true,
                    'url' => route("site.departments.index"),
                    'title' => config("site-staff.siteDepartmentName"),
                ]
            ];
        }

        if (config("site-staff.siteDepartmentsTree", true)) {

            $departments = StaffDepartmentActions::getTree();
            return view("site-staff::site.departments.index", [
                "rootDepartments" => $departments,
                "siteBreadcrumb" => $siteBreadcrumb,
            ]);

        }
        else {
            return  view("site-staff::site.departments.index", [
                "employees" => array_unique(StaffEmployee::getAllPublished()),
                "siteBreadcrumb" => $siteBreadcrumb,
            ]);
        }
    }

    public function show(StaffDepartment  $department){

        $siteBreadcrumb = null;

        if (config("site-staff.siteBreadcrumb")){
            $siteBreadcrumb =  $siteBreadcrumb = StaffDepartmentActions::getSiteBreadcrumb($department);
        }

        if (config("site-staff.siteDepartmentsTree", true)) {

            return view("site-staff::site.departments.show", [
                "rootDepartment" => [
                    "title" => $department->title,
                    'slug' => $department->slug,
                    'short' => $department->short,
                    'description' => $department->description,
                    'parent' => $department->parent_id,
                    "priority" => $department->priority,
                    "published_at" => $department->published_at,
                    "id" => $department->id,
                    'children' => StaffDepartmentActions::getChildrenTree($department),
                    "siteUrl" => route("site.departments.show", ["department" => $department->slug]),
                ],
                "siteBreadcrumb" => $siteBreadcrumb,
            ]);

        }
        else {
            // Вывести также сотрудников вложенных отделов
            // $ids = StaffDepartmentActions::getDepartmentChildren($department);
            // foreach ($ids as $id){
            //     $employees[$id]  = StaffDepartmentActions::getDepartmentEmployeesIds($id);
            // }
            $employees[$department->id] = StaffDepartmentActions::getDepartmentEmployeesIds($department->id);
            foreach ($employees as $key => $items){
                foreach ($items as $id => $item){
                    if ($item->published_at)
                        $emp[$id] = $item;
                }
            }

            return  view("site-staff::site.departments.show", [
                "employees" => isset($emp) ? array_unique($emp): [],
                "rootDepartment" => [
                    "title" => $department->title,
                    'slug' => $department->slug,
                    'short' => $department->short,
                    'description' => $department->description,
                    'parent' => $department->parent_id,
                    "priority" => $department->priority,
                    "published_at" => $department->published_at,
                    "id" => $department->id,
                    'children' => [],
                    "siteUrl" => route("site.departments.show", ["department" => $department->slug]),
                ],
                "siteBreadcrumb" => $siteBreadcrumb,
            ]);
        }
    }
}
