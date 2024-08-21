@extends('admin.layout')

@section('page-title', config("site-staff.siteEmployeeName"). ' - ')
@section('header-title', config("site-staff.siteEmployeeName"))

@section('admin')
    @include("site-staff::admin.employees.pills")
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <form action="{{ route($currentRoute) }}"
                      class="d-lg-flex"
                      method="get">
                    <label class="sr-only" for="title">{{ config("site-staff.employeeTitleName") }} </label>
                    <input type="text"
                           class="form-control mb-2 me-sm-2"
                           id="title"
                           name="title"
                           value="{{ $query->get('title') }}"
                           placeholder="{{ config("site-staff.employeeTitleName") }} ">

                    <button type="submit" class="btn btn-primary mb-2 me-sm-1">Применить</button>
                    <a href="{{ route($currentRoute) }}" class="btn btn-secondary mb-2">Сбросить</a>
                </form>
            </div>
           @include("site-staff::admin.employees.includes.table-list", ["employeesList" => $employeesList,"page" => $page,"per" => $per])
        </div>
    </div>

    @if ($employeesList->lastPage() > 1)
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{ $employeesList->links() }}
                </div>
            </div>
        </div>
    @endif
@endsection
