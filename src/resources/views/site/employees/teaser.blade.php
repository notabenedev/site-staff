<div class="card card-base employee-teaser h-100">
    @isset ($employee->image)
        @picture([
            'image' => $employee->image,
            'template' => "sm-grid-12",
            'grid' => [
                "lg-grid-{$grid}" => 992,
                'md-grid-6' => 768,
            ],
            'imgClass' => 'card-img-top',
        ])@endpicture
    @endisset
    @empty ($employee->image)
        <div class="empty-image">
            <i class="far fa-image fa-9x"></i>
        </div>
    @endempty
    <div class="card-body">
        <div class="line mb-4"></div>
        <h4 class="card-title">{{ $employee->title }}</h4>
        <p class="card-text text-secondary">{{ $employee->short }}</p>
    </div>
    <div class="card-footer">
{{--        <a href="{{ route("site.employees.show", ['employee' => $employee]) }}"--}}
{{--           class="btn btn-primary px-4 py-2">--}}
{{--            Подробнее--}}
{{--        </a>--}}
    </div>
</div>