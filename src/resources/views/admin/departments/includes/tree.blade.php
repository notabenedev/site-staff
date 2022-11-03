@can("update", \App\StaffDepartment::class)
    <admin-department-list :structure="{{ json_encode($departments) }}"
                         :nesting="{{ config("site-staff.departmentNest") }}"
                         :update-url="'{{ route("admin.departments.item-priority") }}'">
    </admin-department-list>
@else
    <ul>
        @foreach ($departments as $department)
            <li>
                @can("view", \App\StaffDepartment::class)
                    <a href="{{ route('admin.departments.show', ['department' => $department["slug"]]) }}"
                       class="btn btn-link">
                        {{ $department["title"] }}
                    </a>
                @else
                    <span>{{ $department['title'] }}</span>
                @endcan
                <span class="badge badge-secondary">{{ count($department["children"]) }}</span>
                @if (count($department["children"]))
                    @include("site-staff::admin.departments.includes.tree", ['departments' => $department["children"]])
                @endif
            </li>
        @endforeach
    </ul>
@endcan
