@extends("admin.layout")

@section("page-title", "{$department->title} - ". config("site-staff.siteDepartmentName"))

@section('header-title',  config("site-staff.siteDepartmentName")." - {$department->title}")

@section('admin')
    @include("site-staff::admin.departments.includes.pills")
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Заголовок</dt>
                    <dd class="col-sm-9">{{ $department->title }}</dd>
                    @if ($department->short)
                        <dt class="col-sm-3">Краткое описание</dt>
                        <dd class="col-sm-9">{{ $department->short }}</dd>
                    @endif
                    @if ($department->slug)
                        <dt class="col-sm-3">Адрес</dt>
                        <dd class="col-sm-9">{{ $department->slug }}</dd>
                    @endif
                    @if ($department->description)
                        <dt class="col-sm-3">Описание</dt>
                        <dd class="col-sm-9">{!! $department->description !!}</dd>
                    @endif

                    @if ($department->parent)
                        <dt class="col-sm-3">Родитель</dt>
                        <dd class="col-sm-9">
                            <a href="{{ route("admin.departments.show", ["department" => $department->parent]) }}">
                                {{ $department->parent->title }}
                            </a>
                        </dd>
                    @endif
                    @if ($childrenCount)
                        <dt class="col-sm-3">Дочерние</dt>
                        <dd class="col-sm-9">{{ $childrenCount }}</dd>
                    @endif
                </dl>
                @if ($childrenCount)
                    <h6>Дочерние:</h6>
                    @include("site-staff::admin.departments.includes.table-list", ["departments" => $children])
                @endif
            </div>
        </div>
    </div>

    @if (count($department->employees))
        <div class="col-12 mt-3">
            <div class="card">
                <div class="card-header">
                    <h5>{{ config("site-staff.siteEmployeeName") }}</h5>
                    <a href="{{ route("admin.departments.employees-tree", ["department" => $department]) }}">{{ config("site-staff.siteEmployeeName") }} - Приоритет</a>
                </div>
                @include("site-staff::admin.employees.includes.table-list", ["employeesList" => $department->employees])
            </div>
        </div>
    @endif



@endsection