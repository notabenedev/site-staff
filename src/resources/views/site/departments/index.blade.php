@extends('layouts.boot')

@section('page-title', config("site-staff.sitePackageName","Сотрудники").' - ')

@section('header-title')
       {{ config("site-staff.sitePackageName","Сотрудники ") }}
@endsection

@section('content')
    <div class="col-12">
        @foreach($rootDepartments as $item)
            <div class="row staff__department">
                @if (isset($item["published_at"]))
                    <div class="col-12">
                        @include("site-staff::site.departments.includes.item", ["item" => $item, "first" => true, "level" => 1])
                    </div>
                @endif
            </div>
        @endforeach
    </div>

@endsection

