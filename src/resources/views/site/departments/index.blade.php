@extends('layouts.boot')

@section('page-title', config("site-staff.sitePackageName","Сотрудники").' - ')

@section('header-title')
       {{ config("site-staff.sitePackageName","Сотрудники ") }}
@endsection

@section('content')
    <div class="col-12 content-section">
        @isset($rootDepartments)
            @foreach($rootDepartments as $item)
                <div class="row staff-department">
                        @if (isset($item["published_at"]))
                            <div class="col-12">
                                @include("site-staff::site.departments.includes.item", ["item" => $item, "first" => true, "level" => 1])
                            </div>
                        @endif
                </div>
            @endforeach
        @else
            <div class="staff-epmloyees">
                @foreach($employees as $id => $employee)
                    {!! $employee->getTeaser() !!}
                @endforeach
            </div>
        @endisset
    </div>

@endsection

