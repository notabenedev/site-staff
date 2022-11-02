@extends("admin.layout")

@section("page-title", "{$department->title} - ")

@section('header-title', "{$department->title}")

@section('admin')
    @include("site-staff::admin.departments.includes.pills")
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Добавить тег</h5>
            </div>
            <div class="card-body">
                @include("seo-integration::admin.meta.create", ['model' => 'departments', 'id' => $department->id])
            </div>
        </div>
    </div>
    <div class="col-12 mt-2">
        <div class="card">
            <div class="card-body">
                @include("seo-integration::admin.meta.table-models", ['metas' => $department->metas])
            </div>
        </div>
    </div>
@endsection