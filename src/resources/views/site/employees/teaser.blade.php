
<div class="card{{ (config("site-staff.employeeCardBase") ? " card-base" : " bg-transparent border-0") }} staff-employee__teaser" id="{{ $employee->slug }}">
    <div class="row">
        <div class="col-md-4 col-lg-{{ $grid }}">
            <div class="sticky-top staff-employee__sticky">
                @if (config("site-staff.employeePage", false))
                <a href="{{ route("site.employees.show",["employee" => $employee]) }}">
                @endif
                @isset ($employee->image)
                    @picture([
                    'image' => $employee->image,
                    'template' => "employees",
                    'grid' => [
                    "employees-grid-xxl-".$grid => 1400,
                    "employees-grid-xl-".$grid => 1200,
                    "employees-grid-lg-".$grid => 992,
                    'employees-grid-md-4' => 768,
                    ],
                    'imgClass' => 'img-fluid rounded staff-employee__image',
                    ])@endpicture
                @endisset
                @empty ($employee->image)
                        <div class="staff-employee__image-empty">
                            <svg class="staff-employee__image-empty_ico">
                                <use xlink:href="#employee-empty-image"></use>
                            </svg>
                        </div>
                    @endempty
                @if (config("site-staff.employeePage", false))
                </a>
                @endif
                <div class="{{ (config("site-staff.employeeCardBase") ? "card-footer " : "") }}staff-employee__footer">
                    @isset($employee->comment)
                        <div class="card-text staff-employee__comment-title">
                            {{ config("site-staff.employeeCommentName") }}:
                        </div>
                        <div class="card-text staff-employee__comment">
                            {!! $employee->comment !!}
                        </div>
                    @endisset
                    @if(config("site-staff.employeeBtnName") && $employee->btn_enabled)
                            <a href="#" class="btn btn-outline-primary staff-employee__modal-btn"
                               data-bs-toggle="modal"
                               data-bs-target="#staffEmployeeModal"
                               data-bs-whatever="{{ $employee->title }}"
                            >
                                {{ config("site-staff.employeeBtnName") }}
                            </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-8 col-lg-{{ 12 - $grid }}">
            <div class="{{ (config("site-staff.employeeCardBase") ? "card-body " : "") }}staff-employee__body">
                <h3 class="card-title">{{ $employee->title }}</h3>
                <div class="card-text staff-employee__short">{{ $employee->short }}</div>
                @include("site-staff::site.departments.includes.pills",["pills" => $employee->departments])
                <div class="card-text staff-employee__description">
                    {!! $employee->description !!}
                </div>
                @foreach($employee->images as $image)
                    @if ($loop->first)
                        <a href="#"
                           data-bs-toggle="collapse"
                           data-bs-target="#employee{{ $employee->slug }}Collapse"
                           class="btn btn-outline-primary staff-employee__collapse-btn">
                            <i class="fas fa-eye staff-employee__collapse-eye"></i>
                            {{ config("site-staff.employeeGalleryName", "Сертификаты") }}
                        </a>
                        <div class="collapse staff-employee__collapse" id="employee{{ $employee->slug }}Collapse">
                    @endif
                        @img([
                        "image" => $image,
                        "template" => "certificate",
                        "lightbox" => "employeeGallery".$employee->slug,
                        "imgClass" => "",
                        "grid" => [
                        ],
                        ])
                    @if ($loop->last)
                        </div>
                    @endif
                @endforeach
                <div class="card-text">
                    @if (config("site-staff.employeePage", false))
                        <a href="{{ route("site.employees.show",["employee" => $employee]) }}" class="btn btn-primary">Подробнее</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
