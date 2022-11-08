@extends("admin.layout")

@section("page-title", config("site-staff.siteEmployeeName")." - ".config("site-staff.sitePackageName")." - ")

@section('header-title')
    @empty($department)
        {{ config("site-staff.siteEmployeeName") }}
    @else
        {{ config("site-staff.siteDepartmentName"). " - ". $department->title }}
    @endempty
@endsection
@section('admin')
    @isset($department)
        @include("site-staff::admin.departments.includes.pills")
    @endisset
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <universal-priority
                        :elements="{{ json_encode($groups) }}"
                        url="{{ route("admin.vue.priority", ["table" => "staff_employees", "field" => "priority"]) }}"
                >

                </universal-priority>
            </div>
        </div>
    </div>
@endsection