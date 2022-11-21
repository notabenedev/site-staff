<div class="card card-base h-100 employee-teaser" id="{{ $employee->slug }}StaffEmployee">
    <div class="row no-gutters">
        <div class="col-md-4 col-lg-3">
            <div class="sticky-top employee-teaser__sticky">
                @isset ($employee->image)
                    @picture([
                    'image' => $employee->image,
                    'template' => "sm-grid-12",
                    'grid' => [
                    "lg-grid-4" => 992,
                    'md-grid-6' => 768,
                    ],
                    'imgClass' => 'card-img-top',
                    ])@endpicture
                @endisset
                @empty ($employee->image)
                        <div class="employee-image__empty">
                            <svg class="employee-image__empty-ico">
                                <use xlink:href="#employee-empty-image"></use>
                            </svg>
                        </div>
                    @endempty
                <div class="card-footer">
                    @isset($employee->comment)
                        <p class="card-text text-secondary">{!! $employee->comment !!}</p>
                    @endisset
                    <div class="line mb-4"></div>
                    @foreach($employee->departments as $dep)
                        @if ($dep->published_at)
                            <a href="{{ route("site.departments.show", ['department' => $dep]) }}"
                               class="btn btn-primary my-2">
                                {{ $dep->title }}
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-8 col-lg-9">
            <div class="card-body">
                <h4 class="card-title">{{ $employee->title }}</h4>
                <p class="card-text text-secondary">{{ $employee->short }}</p>
                {!! $employee->description !!}

                @foreach($employee->images as $image)
                    @if ($loop->first)
                        <a href="#"
                           data-toggle="collapse"
                           data-target="#employee{{ $employee->slug }}Collapse"
                           class="employee-teaser__collapse-btn">
                            Смотреть {{ config("site-staff.employeeGalleryName", "Сертификаты") }}
                        </a>
                        <div class="collapse employee-teaser__collapse" id="employee{{ $employee->slug }}Collapse">
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