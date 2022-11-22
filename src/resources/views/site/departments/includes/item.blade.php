<div class="staff-department__item staff-department__item_level-{{ $level }}"  id="{{ $item["slug"]}}StaffDepartment">

   @if (config("site-staff.siteDepartmentsTree",false) || (! config("site-staff.siteDepartmentsTree",false) && $level <2))
        <h2 class="h{{$level+1}} staff-department__title staff-department__title_level-{{ $level }}">
            {{ $item["title"] }}
        </h2>

        @isset($item['short'])
            <div class="staff-department__short">
                {!! $item['short']  !!}
            </div>
        @endisset

        @isset($item['description'])
            <div class="staff-department__description">
                {!! $item['description']  !!}
            </div>
        @endisset
   @endif

    @foreach(\Notabenedev\SiteStaff\Facades\StaffDepartmentActions::getDepartmentEmployeesIds($item["id"]) as $id => $employee)
           {!! $employee->getTeaser() !!}
    @endforeach

    @if (! empty($item["children"]))
        @foreach ($item["children"] as $child)
            @if (isset($child["published_at"]))
                @include("site-staff::site.departments.includes.item", ["item" => $child, "first" => false, "level" => $level + 1])
            @endif
        @endforeach
    @endif
</div>



