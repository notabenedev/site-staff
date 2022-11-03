<?php
return [
    "sitePackageName" => "Отделы и специалисты",
    "siteDepartmentName" => "Отделы",
    "siteEmployeeName" => "Сотрудники",

    "siteBreadcrumb" => true,

    "staffAdminRoutes" => true,
    "staffSiteRoutes" => true,

    "departmentNest" => 4,
    "staffUrlName" => "staff",
    "departmentUrlName" => "department",
    "employeeUrlName" => "employee",

    "departmentFacade" => \Notabenedev\SiteStaff\Helpers\StaffDepartmentActionsManager::class,

];