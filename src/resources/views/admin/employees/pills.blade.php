<div class="col-12 mb-2">
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-pills">
                @can("viewAny", \App\StaffEmployee::class)
                    <li class="nav-item">
                        <a href="{{ route("admin.employees.index") }}"
                           class="nav-link{{ $currentRoute === "admin.employees.index" ? " active" : "" }}">
                            Список
                        </a>
                    </li>
                @endcan
                @can("create", \App\StaffEmployee::class)
                    <li class="nav-item">
                        <a href="{{ route("admin.employees.create") }}"
                           class="nav-link{{ $currentRoute === "admin.employees.create" ? " active" : "" }}">
                            Добавить
                        </a>
                    </li>
                @endcan
                @if (! empty($employee))
                    @can("view", \App\StaffEmployee::class)
                        <li class="nav-item">
                            <a href="{{ route("admin.employees.show", ["employee" => $employee]) }}"
                               class="nav-link{{ $currentRoute === "admin.employees.show" ? " active" : "" }}">
                                Просмотр
                            </a>
                        </li>
                    @endcan

                    @can("update", \App\StaffEmployee::class)
                        <li class="nav-item">
                            <a class="nav-link{{ $currentRoute == 'admin.employees.edit' ? ' active' : '' }}"
                               href="{{ route('admin.employees.edit', ['employee' => $employee]) }}">
                                Редактировать
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link{{ $currentRoute == 'admin.employees.show.gallery' ? ' active' : '' }}"
                               href="{{ route('admin.employees.show.gallery', ['employee' => $employee]) }}">
                                Галерея
                            </a>
                        </li>
                        @can("viewAny", \App\Meta::class)
                            <li class="nav-item">
                                <a class="nav-link{{ $currentRoute == 'admin.employees.show.metas' ? ' active' : '' }}"
                                   href="{{ route('admin.employees.show.metas', ['employee' => $employee]) }}">
                                    Метатеги
                                </a>
                            </li>
                        @endcan
                    @endcan

                    @can("delete", \App\StaffEmployee::class)
                        <li class="nav-item">
                            <button type="button" class="btn btn-link nav-link"
                                    data-confirm="{{ "delete-form-employee-{$employee->id}" }}">
                                <i class="fas fa-trash-alt text-danger"></i>
                            </button>
                            <confirm-form :id="'{{ "delete-form-employee-{$employee->id}" }}'">
                                <template>
                                    <form action="{{ route('admin.employees.destroy', ['employee' => $employee]) }}"
                                          id="delete-form-employee-{{ $employee->id }}"
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