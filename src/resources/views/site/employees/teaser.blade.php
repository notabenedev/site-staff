<div class="card card-base staff-employee__teaser" id="{{ $employee->slug }}StaffEmployee">
    <div class="row no-gutters">
        <div class="col-md-4 col-lg-3">
            <div class="sticky-top staff-employee__sticky">
                @isset ($employee->image)
                    @picture([
                    'image' => $employee->image,
                    'template' => "sm-grid-12",
                    'grid' => [
                    "lg-grid-4" => 992,
                    'md-grid-6' => 768,
                    ],
                    'imgClass' => 'card-img-top staff-employee__image',
                    ])@endpicture
                @endisset
                @empty ($employee->image)
                        <div class="staff-employee__image-empty">
                            <svg class="staff-employee__image-empty_ico">
                                <use xlink:href="#employee-empty-image"></use>
                            </svg>
                        </div>
                    @endempty
                <div class="card-footer staff-employee__footer">
                    @isset($employee->comment)
                        <div class="card-text staff-employee__comment-title">
                            {{ config("site-staff.employeeCommentName") }}:
                        </div>
                        <div class="card-text staff-employee__comment">
                            {!! $employee->comment !!}
                        </div>
                    @endisset
                    <div class="line mb-4"></div>
                    @foreach($employee->departments as $dep)
                        @if ($dep->published_at)
                            <a href="{{ route("site.departments.show", ['department' => $dep]) }}"
                               class="btn btn-outline-secondary staff-employee__btn">
                                {{ $dep->title }}
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-8 col-lg-9">
            <div class="card-body staff-employee__body">
                <h3 class="card-title">{{ $employee->title }}</h3>
                <p class="card-text staff-employee__short">{{ $employee->short }}</p>
                {!! $employee->description !!}

                @foreach($employee->images as $image)
                    @if ($loop->first)
                        <a href="#"
                           data-toggle="collapse"
                           data-target="#employee{{ $employee->slug }}Collapse"
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
            </div>
        </div>
    </div>

</div>