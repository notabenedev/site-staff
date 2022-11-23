@extends('layouts.boot')

@section('page-title', $rootDepartment["title"]." -".config("site-staff.sitePackageName","Сотрудники").' - ')

@section('header-title')
    {{ config("site-staff.siteEmployeeName") }} - {{ $rootDepartment["title"] }}
@endsection

@section('content')
  <div class="col-12 content-section">
      @isset($employees)
          <div class="staff-epmloyees">
              <div class="row staff-department">
                  <div class="col-12">
                      <div class="staff-department__item staff-department__item_level-1"  id="{{ $rootDepartment["slug"]}}StaffDepartment">
                          <h2 class="h2 staff-department__title staff-department__title_level-1">
                              {{ $rootDepartment["title"] }}
                          </h2>
                      </div>
                      @isset($rootDepartment['short'])
                          <div class="staff-department__short">
                              {!! $rootDepartment['short']  !!}
                          </div>
                      @endisset

                      @isset($rootDepartment['description'])
                          <div class="staff-department__description">
                              {!! $rootDepartment['description']  !!}
                          </div>
                      @endisset
                  </div>
              </div>
              @foreach($employees as $id => $employee)
                  @if ($employee->published_at)
                  {!! $employee->getTeaser( (config("site-staff.employeeGrid") ? config("site-staff.employeeGrid") : 3)) !!}
                  @endif
              @endforeach
          </div>

          @else
              @if ($rootDepartment["published_at"])
                  <div class="row staff-department">
                      <div class="col-12">
                          @include("site-staff::site.departments.includes.item", ["item" => $rootDepartment, "first" => true, "level" => 1])
                      </div>
                  </div>
              @endif
      @endisset
      @if (config("site-staff.employeeBntName"))
          @include("site-staff::site.employees.includes.modal")
      @endif
  </div>

@endsection

