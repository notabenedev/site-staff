@extends('admin.layout')

@section('page-title', config("site-staff.siteEmployeeName"). ' - ')
@section('header-title', config("site-staff.siteEmployeeName"))

@section('admin')
    @include("site-staff::admin.employees.pills")
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <form action="{{ route($currentRoute) }}"
                      class="form-inline"
                      method="get">
                    <label class="sr-only" for="title">{{ config("site-staff.employeeTitleName") }} </label>
                    <input type="text"
                           class="form-control mb-2 mr-sm-2"
                           id="title"
                           name="title"
                           value="{{ $query->get('title') }}"
                           placeholder="{{ config("site-staff.employeeTitleName") }} ">

                    <button type="submit" class="btn btn-primary mb-2 mr-sm-1">Применить</button>
                    <a href="{{ route($currentRoute) }}" class="btn btn-secondary mb-2">Сбросить</a>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ config("site-staff.employeeTitleName") }} </th>
                            <th>Slug</th>
                            <th>{{ config("site-staff.employeeShortName") }}</th>
                            @canany(["view", "update", "delete"], \App\StaffEmployee::class)
                                <th>Действия</th>
                            @endcanany
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($employeesList as $item)
                            <tr>
                                <td>
                                    {{ $page * $per + $loop->iteration }}
                                </td>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->slug }}</td>
                                <td>{{ $item->short }}</td>
                                @canany(["view", "update", "delete", "publish"], \App\StaffEmployee::class)
                                    <td>
                                        <div role="toolbar" class="btn-toolbar">
                                            <div class="btn-group btn-group-sm mr-1">
                                                @can("update", \App\StaffEmployee::class)
                                                    <a href="{{ route("admin.employees.edit", ["employee" => $item]) }}" class="btn btn-primary">
                                                        <i class="far fa-edit"></i>
                                                    </a>
                                                @endcan
                                                @can("view", \App\StaffEmployee::class)
                                                    <a href="{{ route('admin.employees.show', ["employee" => $item]) }}" class="btn btn-dark">
                                                        <i class="far fa-eye"></i>
                                                    </a>
                                                @endcan
                                                @can("delete", \App\StaffEmployee::class)
                                                    <button type="button" class="btn btn-danger" data-confirm="{{ "delete-form-{$item->id}" }}">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                @endcan
                                            </div>
                                            @can("publish", \App\StaffEmployee::class)
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-{{ $item->published_at ? "success" : "secondary" }}" data-confirm="{{ "publish-form-{$item->id}" }}">
                                                        <i class="fas fa-toggle-{{ $item->published_at ? "on" : "off" }}"></i>
                                                    </button>
                                                </div>
                                            @endcan
                                        </div>
                                        @can("publish", \App\StaffEmployee::class)
                                            <confirm-form :id="'{{ "publish-form-{$item->id}" }}'" text="Это изменит статус публикации" confirm-text="Да, изменить!">
                                                <template>
                                                    <form action="{{ route('admin.employees.publish', ["employee" => $item]) }}"
                                                          id="publish-form-{{ $item->id }}"
                                                          class="btn-group"
                                                          method="post">
                                                        @csrf
                                                        @method("put")
                                                    </form>
                                                </template>
                                            </confirm-form>
                                        @endcan
                                        @can("delete", \App\StaffEmployee::class)
                                            <confirm-form :id="'{{ "delete-form-{$item->id}" }}'">
                                                <template>
                                                    <form action="{{ route('admin.employees.destroy', ["employee" => $item]) }}"
                                                          id="delete-form-{{ $item->id }}"
                                                          class="btn-group"
                                                          method="post">
                                                        @csrf
                                                        <input type="hidden" name="_method" value="DELETE">
                                                    </form>
                                                </template>
                                            </confirm-form>
                                        @endcan
                                    </td>
                                @endcanany
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
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
