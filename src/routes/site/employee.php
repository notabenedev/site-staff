<?php

use Illuminate\Support\Facades\Route;

Route::group([
"namespace" => "App\Http\Controllers\Vendor\SiteStaff\Site",
"middleware" => ["web"],
"as" => "site.employees.",
"prefix" => config("site-staff.employeeUrlName"),
], function () {
Route::get("/{employee}", "StaffEmployeeController@show")->name("show");
});