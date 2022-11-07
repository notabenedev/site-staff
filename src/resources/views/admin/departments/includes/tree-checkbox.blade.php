<ul class="list-unstyled">
    @foreach ($departments as $department)
        <li>
            <div class="custom-control custom-checkbox">
                <input class="custom-control-input"
                       type="checkbox"
                       {{ (! count($errors->all()) ) && (isset($employee) && $employee->hasDepartment($department->id)) || old('check-' . $department->id) ? "checked" : "" }}
                       value="{{ $department->id }}"
                       id="check-{{ $department->id }}"
                       name="check-{{ $department->id }}">
                <label class="custom-control-label" for="check-{{ $department->id }}">
                    {{ $department->title }}{{ count($department["children"]) ? ":" : "" }}
                </label>
                @if (count($department["children"]))
                    @include("site-staff::admin.departments.includes.tree-checkbox", ['departments' => $department["children"]])
                @endif
            </div>
        </li>
    @endforeach
</ul>

