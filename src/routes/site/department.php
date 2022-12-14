<?php

use Illuminate\Support\Facades\Route;

Route::group([
"namespace" => "App\Http\Controllers\Vendor\SiteStaff\Site",
"middleware" => ["web"],
"as" => "site.departments.",
"prefix" => config("site-staff.departmentUrlName"),
], function () {
Route::get("/", "StaffDepartmentController@index")->name("index");
Route::get("/{department}", "StaffDepartmentController@show")->name("show");
});