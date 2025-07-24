@extends("admin.layout")

@section("page-title", config("site-staff.siteEmployeeName")." - ".config("site-staff.sitePackageName")." - ")

@section('header-title')
        {{ config("site-staff.siteEmployeeName").' - '.$employee->title  }}
@endsection
@section('admin')
    @include("site-staff::admin.employees.pills")
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <staff-params
                        csrf-token="{{ csrf_token() }}"
                        post-url="{{ route('admin.vue.staff-params.post', ['id' => $employee->id, 'model' => 'employee']) }}"
                        get-url="{{ route('admin.vue.staff-params.get', ['id' => $employee->id, 'model' => 'employee']) }}"
                        get-available-url="{{ route('admin.vue.staff-params.available', ['id' => $employee->id, 'model' => 'employee']) }}">
                </staff-params>
            </div>
        </div>
    </div>
@endsection