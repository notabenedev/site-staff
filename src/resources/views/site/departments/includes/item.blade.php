<div class="staff__department-item staff__department-item_level-{{ $level }}"  id="{{ $item["slug"]}}StaffDepartment">

   @if (config("site-staff.siteDepartmentsTree",false) || (! config("site-staff.siteDepartmentsTree",false) && $level <2))
        <h3 class="h{{$level+1}} staff__department-title staff__department-title_level-{{ $level }}">
            {{ $item["title"] }}
        </h3>

        @isset($item['short'])
            <div class="staff__department-short">
                {!! $item['short']  !!}
            </div>
        @endisset

        @isset($item['description'])
            <div class="staff__department-description">
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



