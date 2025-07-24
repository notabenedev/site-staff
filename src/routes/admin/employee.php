<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Vendor\SiteStaff\Admin\StaffEmployeeController;
Route::group([
    'namespace' => 'App\Http\Controllers\Vendor\SiteStaff\Admin',
    'middleware' => ['web', 'management'],
    'as' => 'admin.',
    'prefix' => 'admin',
], function () {

    Route::group([
        "prefix" => config("site-staff.employeeUrlName"),
        "as" => "employees.",
    ],function (){
        Route::get("/", [StaffEmployeeController::class, "index"])->name("index");
        Route::get("/create", [StaffEmployeeController::class, "create"])->name("create");
        Route::post("", [StaffEmployeeController::class, "store"])->name("store");
        Route::get("/{employee}", [StaffEmployeeController::class, "show"])->name("show");
        Route::get("/{employee}/edit", [StaffEmployeeController::class, "edit"])->name("edit");
        Route::put("/{employee}", [StaffEmployeeController::class, "update"])->name("update");
        Route::delete("/{employee}", [StaffEmployeeController::class, "destroy"])->name("destroy");
    });

    // Публикация
    Route::put(config("site-staff.employeeUrlName")."/{employee}/publish", "StaffEmployeeController@publish")
        ->name("employees.publish");

    // Изменить вес
    Route::put(config("site-staff.employeeUrlName")."/tree/priority", [StaffEmployeeController::class,"changeItemsPriority"])
        ->name("employees.item-priority");

    Route::group([
        'prefix' => config("site-staff.employeeUrlName").'/{employee}',
        'as' => 'employees.show.',
    ], function () {
        Route::get('params', 'StaffEmployeeController@params')
            ->name('params');
        Route::get('metas', 'StaffEmployeeController@metas')
            ->name('metas');
        Route::get('gallery', 'StaffEmployeeController@gallery')
            ->name('gallery');
        Route::delete('delete-image', 'StaffEmployeeController@deleteImage')
            ->name('delete-image');
    });

});