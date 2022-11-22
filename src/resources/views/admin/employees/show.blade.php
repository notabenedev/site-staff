@extends('admin.layout')

@section('page-title', config("site-staff.siteEmployeeName").' - ')
@section('header-title', config("site-staff.siteEmployeeName")." {$employee->title}")

@section('admin')
    @include("site-staff::admin.employees.pills")

    <div class="col-12">
        @if($employee->image)
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Изображение</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-inline-block">
                            @img([
                            "image" => $employee->image,
                            "template" => "medium",
                            "lightbox" => "lightGroup" . $employee->id,
                            "imgClass" => "rounded mb-2",
                            "grid" => [],
                            ])
                            @can("update",\App\Employee::class)
                                <button type="button" class="close ml-1" data-confirm="{{ "delete-form-{$employee->id}" }}">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            @endcan
                        </div>
                        @can("update",\App\Employee::class)
                            <confirm-form :id="'{{ "delete-form-{$employee->id}" }}'">
                                <template>
                                    <form action="{{ route('admin.employees.show.delete-image', ['employee' => $employee]) }}"
                                          id="delete-form-{{ $employee->id }}"
                                          class="btn-group"
                                          method="post">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE">
                                    </form>
                                </template>
                            </confirm-form>
                        @endcan
                    </div>
                </div>
        @endif
        <div class="card mb-2">
            <div class="card-header">
                <h5 class="card-title">{{ config("site-staff.employeeShortName") }}</h5>
            </div>
            <div class="card-body">
                {{ $employee->short }}
            </div>
        </div>
        <div class="card mb-2">
            <div class="card-header">
                <h5 class="card-title">{{ config("site-staff.employeeDescriptionName") }}</h5>
            </div>
            <div class="card-body">
                {!! $employee->description !!}
            </div>
        </div>
        <div class="card mb-2">
            <div class="card-header">
                <h5 class="card-title">{{ config("site-staff.employeeCommentName") }}</h5>
            </div>
            <div class="card-body">
                {!! $employee->comment !!}
            </div>
        </div>
        <div class="card mb-2">
            <div class="card-header">
                <h5 class="card-title">{{ config("site-staff.siteDepartmentName") }}</h5>
            </div>
            <div class="card-body">
                @foreach ($employee->departments as $department)
                    <a href="{{ route("admin.departments.show", ["department" => $department]) }}" class="badge badge-pill badge-secondary">{{ $department->title }}</a>
                @endforeach
            </div>
        </div>
    </div>
@endsection