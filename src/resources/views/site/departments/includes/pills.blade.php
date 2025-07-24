<div class="card-text staff-employee__departments">
    @foreach($pills as $department)
        @if ($department->published_at)
            <a href="{{ route("site.departments.show", ['department' => $department]) }}"
               class="btn btn-outline-secondary staff-employee__btn">
                {{ $department->title }}
            </a>
        @endif
    @endforeach
</div>