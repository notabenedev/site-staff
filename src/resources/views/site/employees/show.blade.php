@extends('layouts.boot')

@section('page-title', $employee->title." -".config("site-staff.sitePackageName","Сотрудники").' - ')

@section('header-title')
    {{ $employee->title }}
@endsection

@section('content')
  <div class="col-12">
      <div class="row staff-employee">
          <div class="col-12 col-md-8 col-lg-9">
              @isset($employee->short)
                  <div class="staff-employee__short">
                      {{ $employee->short }}
                  </div>
              @endisset
              @include("site-staff::site.departments.includes.pills",["pills" => $employee->departments])
              @isset($employee->description)
                  <div class="staff-employee__description">
                      {!! $employee->description  !!}
                  </div>
              @endisset
              @isset($employee->params)
                  @includeIf("staff-types::site.staff-params.includes.items",["available" => Notabenedev\StaffTypes\Facades\StaffParamActions::prepareAvailableData($employee)])
              @endisset
              @isset($employee->offers)
                  @includeIf("staff-types::site.staff-offers.includes.items",["items" => $employee->offers])
              @endisset

          </div>
          <div class="col-12 col-md-4 col-lg-3">
              <div class="sticky-top staff-employee__sticky">
                  @isset ($employee->image)
                      @picture([
                      'image' => $employee->image,
                      'template' => "employees",
                      'grid' => [
                      "employees-grid-xxl-3" => 1400,
                      "employees-grid-xl-3" => 1200,
                      "employees-grid-lg-3" => 992,
                      'employees-grid-md-4' => 768,
                      ],
                      'imgClass' => 'img-fluid rounded',
                      ])@endpicture
                  @endisset
                  @empty ($employee->image)
                      <div class="staff-employee__image-empty">
                          <svg class="staff-employee__image-empty_ico">
                              <use xlink:href="#employee-empty-image"></use>
                          </svg>
                      </div>
                  @endempty
                  <div class="staff-employee__footer">
                      @isset($employee->comment)
                          <div class="card-text staff-employee__comment-title">
                              {{ config("site-staff.employeeCommentName") }}:
                          </div>
                          <div class="card-text staff-employee__comment">
                              {!! $employee->comment !!}
                          </div>
                      @endisset

                  </div>
              </div>
          </div>
      </div>
  @if (config("site-staff.employeeBtnName"))
      @include("site-staff::site.employees.includes.modal")
  @endif
  </div>
@endsection

