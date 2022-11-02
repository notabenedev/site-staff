@extends("admin.layout")

@section("page-title", "{$department->title} - ")

@section('header-title', "{$department->title}")

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
            </div>
        </div>
    </div>
    @if ($childrenCount)
        <div class="col-12 mt-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Вложенные</h5>
                </div>
                <div class="card-body">
                    @include("site-staff::admin.departments.includes.table-list", ["departments" => $children])
                </div>
            </div>
        </div>
    @endif
@endsection