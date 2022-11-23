<?php
return [
    "sitePackageName" => "Отделы и специалисты",
    "siteDepartmentName" => "Отделы",
    "siteEmployeeName" => "Сотрудники",

    "siteDepartmentsTree" => false,//  php artisan cache:clear needed
    "certificateBgColor" => 'fafafa',
    "siteBreadcrumb" => true,

    "staffAdminRoutes" => true,
    "staffSiteRoutes" => true,

    "departmentNest" => 4,
    "staffUrlName" => "staff",
    "departmentUrlName" => "department",
    "employeeUrlName" => "employee",

    "employeeCardBase" => false,
    "employeeGrid" => 3,
    "employeeTitleName" => "ФИО",
    "employeeShortName" => "Специализация",
    "employeeDescriptionName" => "Описание",
    "employeeCommentName" => "График работы",
    "employeeGalleryName" => "Дипломы и сертификаты",
    "employeeBntName" => "Записаться на прием",
    "employeeTitleInputName" => "Специалист",
    "employeeSubmitName" => "Отправить заявку",
    "employeeModalAbout" => "Оставьте заявку и мы Вам перезвоним",

    "departmentFacade" => \Notabenedev\SiteStaff\Helpers\StaffDepartmentActionsManager::class,

];