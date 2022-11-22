<?php
return [
    "sitePackageName" => "Отделы и специалисты",
    "siteDepartmentName" => "Отделы",
    "siteEmployeeName" => "Сотрудники",

    "siteDepartmentsTree" => true,
    "certificateBgColor" => 'fafafa',
    "siteBreadcrumb" => true,

    "staffAdminRoutes" => true,
    "staffSiteRoutes" => true,

    "departmentNest" => 4,
    "staffUrlName" => "staff",
    "departmentUrlName" => "department",
    "employeeUrlName" => "employee",

    "employeeTitleName" => "ФИО",
    "employeeShortName" => "Специализация",
    "employeeDescriptionName" => "Описание",
    "employeeCommentName" => "График работы",
    "employeeGalleryName" => "Дипломы и сертификаты",

    "departmentFacade" => \Notabenedev\SiteStaff\Helpers\StaffDepartmentActionsManager::class,

];