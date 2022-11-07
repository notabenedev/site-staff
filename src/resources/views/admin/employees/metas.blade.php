@extends('admin.layout')

@section('page-title', 'Meta - ')
@section('header-title', 'Meta')

@section('admin')
    @include("site-staff::admin.employees.pills")
    <div class="col-12 mt-2">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Добавить тег</h5>
            </div>
            <div class="card-body">
                @include("seo-integration::admin.meta.create", ['model' => 'employees', 'id' => $employee->id])
            </div>
        </div>
    </div>
    <div class="col-12 mt-2">
        <div class="card">
            <div class="card-body">
                @include("seo-integration::admin.meta.table-models", ['metas' => $employee->metas])
            </div>
        </div>
    </div>
@endsection
