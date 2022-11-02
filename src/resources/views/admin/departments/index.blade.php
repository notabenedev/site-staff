@extends("admin.layout")

@section("page-title", config("site-staff.siteDepartmentName")." - ".config("site-staff.sitePackageName")." - ")

@section('header-title', config("site-staff.siteDepartmentName"))

@section('admin')
    @include("site-staff::admin.departments.includes.pills")
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if ($isTree)
                    @include("site-staff::admin.departments.includes.tree", ["departments" => $departments])
                @else
                    @include("site-staff::admin.departments.includes.table-list", ["departments" => $departments])
                @endif
            </div>
        </div>
    </div>
@endsection