<?php
use Illuminate\Support\Facades\Route;

use \App\Http\Controllers\Vendor\SiteStaff\Admin\StaffDepartmentController;

Route::group([
    "middleware" => ["web", "management"],
    "as" => "admin.",
    "prefix" => "admin",
], function () {
    Route::group([
        "prefix" => config("site-staff.departmentUrlName"),
        "as" => "departments.",
    ],function (){
        //employees tree
        Route::get("{department}/employees-tree", [StaffDepartmentController::class, "employeesTree"])
            ->name("employees-tree");

        Route::get("/", [StaffDepartmentController::class, "index"])->name("index");
        Route::get("/create", [StaffDepartmentController::class, "create"])->name("create");
        Route::post("", [StaffDepartmentController::class, "store"])->name("store");
        Route::get("/{department}", [StaffDepartmentController::class, "show"])->name("show");
        Route::get("/{department}/edit", [StaffDepartmentController::class, "edit"])->name("edit");
        Route::put("/{department}", [StaffDepartmentController::class, "update"])->name("update");
        Route::delete("/{department}", [StaffDepartmentController::class, "destroy"])->name("destroy");
    });

    // Изменить вес у категорий.
    Route::put(config("site-staff.departmentUrlName")."/tree/priority", [StaffDepartmentController::class,"changeItemsPriority"])
        ->name("departments.item-priority");

    Route::group([
        "prefix" => config("site-staff.departmentUrlName")."/{department}",
        "as" => "departments.",
    ], function () {
        //опубликовать
        Route::put("publish", [StaffDepartmentController::class,"publish"])
            ->name("publish");

        // Добавить подкатегорию.
        Route::get("create-child", [StaffDepartmentController::class,"create"])
            ->name("create-child");
        // Сохранить подкатегорию.
        Route::post("store-child", [StaffDepartmentController::class,"store"])
            ->name("store-child");
        // Meta.
        Route::get("metas", [StaffDepartmentController::class,"metas"])
            ->name("metas");
    });
}
);
