@extends('admin.layout')

@section('page-title', 'Галлерея - ')
@section('header-title', "Галлерея {$employee->title}")

@section('admin')
    @include("site-staff::admin.employees.pills")
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <gallery csrf-token="{{ csrf_token() }}"
                         upload-url="{{ route('admin.vue.gallery.post', ['id' => $employee->id, 'model' => 'employee']) }}"
                         get-url="{{ route('admin.vue.gallery.get', ['id' => $employee->id, 'model' => 'employee']) }}">
                </gallery>
            </div>
        </div>
    </div>
@endsection