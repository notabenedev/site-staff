@if (! empty($department))
    @include("site-staff::admin.departments.includes.breadcrumb")
@endif
<div class="col-12 mb-2">
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-pills">
                @can("viewAny", \App\StaffDepartment::class)
                    <li class="nav-item">
                        <a href="{{ route("admin.departments.index") }}"
                           class="nav-link{{ isset($isTree) && !$isTree ? " active" : "" }}">
                            {{ config("site-staff.sitePackageName") }}
                        </a>
                    </li>

{{--                    <li class="nav-item">--}}
{{--                        <a href="{{ route('admin.departments.index') }}?view=tree"--}}
{{--                           class="nav-link{{ isset($isTree) && $isTree ? " active" : "" }}">--}}
{{--                            Приоритет--}}
{{--                        </a>--}}
{{--                    </li>--}}
                @endcan

                @empty($department)
                    @can("create", \App\StaffDepartment::class)
                        <li class="nav-item">
                            <a href="{{ route("admin.departments.create") }}"
                               class="nav-link{{ $currentRoute === "admin.departments.create" ? " active" : "" }}">
                                Добавить
                            </a>
                        </li>
                    @endcan
                @else
                    @can("create", \App\StaffDepartment::class)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ $currentRoute == 'admin.departments.create-child' ? " active" : "" }}"
                               data-toggle="dropdown"
                               href="#"
                               role="button"
                               aria-haspopup="true"
                               aria-expanded="false">
                                Добавить
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item"
                                   href="{{ route('admin.departments.create') }}">
                                    Основную
                                </a>
                                @if ($department->nesting < config("site-staff.departmentNest"))
                                    <a class="dropdown-item"
                                       href="{{ route('admin.departments.create-child', ['department' => $department]) }}">
                                        Вложенный
                                    </a>
                                @endif
                            </div>
                        </li>
                    @endcan

                    @can("view", $department)
                        <li class="nav-item">
                            <a href="{{ route("admin.departments.show", ["department" => $department]) }}"
                               class="nav-link{{ $currentRoute === "admin.departments.show" ? " active" : "" }}">
                                Просмотр
                            </a>
                        </li>
                    @endcan

                    @can("update", $department)
                        <li class="nav-item">
                            <a href="{{ route("admin.departments.edit", ["department" => $department]) }}"
                               class="nav-link{{ $currentRoute === "admin.departments.edit" ? " active" : "" }}">
                                Редактировать
                            </a>
                        </li>
                    @endcan

                    @can("viewAny", \App\Meta::class)
                        <li class="nav-item">
                            <a href="{{ route("admin.departments.metas", ["department" => $department]) }}"
                               class="nav-link{{ $currentRoute === "admin.departments.metas" ? " active" : "" }}">
                                Метатеги
                            </a>
                        </li>
                    @endcan

{{--                    @can("viewAny", \App\Employee::class)--}}
{{--                        <li class="nav-item">--}}
{{--                            <a href="{{ route("admin.departments.employees.index", ["department" => $department]) }}"--}}
{{--                               class="nav-link{{ strstr($currentRoute, "employees.") !== false ? " active" : "" }}">--}}
{{--                                {{ config("site-staff.siteEmployeeName") }}--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                    @endcan--}}

                    @can("delete", $department)
                        <li class="nav-item">
                            <button type="button" class="btn btn-link nav-link"
                                    data-confirm="{{ "delete-form-department-{$department->id}" }}">
                                <i class="fas fa-trash-alt text-danger"></i>
                            </button>
                            <confirm-form :id="'{{ "delete-form-department-{$department->id}" }}'">
                                <template>
                                    <form action="{{ route('admin.departments.destroy', ['department' => $department]) }}"
                                          id="delete-form-department-{{ $department->id }}"
                                          class="btn-group"
                                          method="post">
                                        @csrf
                                        @method("delete")
                                    </form>
                                </template>
                            </confirm-form>
                        </li>
                    @endcan
                @endif
            </ul>
        </div>
    </div>
</div>