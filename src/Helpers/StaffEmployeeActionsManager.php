<?php

namespace Notabenedev\SiteStaff\Helpers;

use App\StaffEmployee;

class StaffEmployeeActionsManager
{

    /**
     * Список всех
     *
     * @return array
     */
    public function getAllList()
    {
        $employees = [];
        foreach (StaffEmployee::all()->sortBy("title") as $item) {
            $employees[$item->id] = "$item->title ({$item->slug})";
        }
        return $employees;
    }

    /**
     * Хлебные крошки для сайта.
     *
     * @param StaffEmployee $employee
     * @return array
     */
    public function getSiteBreadcrumb(StaffEmployee $employee)
    {
        $breadcrumb = null;

        $breadcrumb[] = (object) [
            "title" => config("site-staff.siteEmployeeName"),
            "url" => route("site.departments.index"),
            "active" => false,
        ];

        $breadcrumb[] = (object) [
            "title" => $employee->title,
            "url" => route("site.employees.show", ["employee" => $employee]),
            "active" => true,
        ];

        return $breadcrumb;
    }
}