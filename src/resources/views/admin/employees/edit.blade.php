@extends('admin.layout')

@section('page-title', $employee->title . ' - Редактировать - ')
@section('header-title', "Редактировать {$employee->title}")

@section('admin')
    @include("site-staff::admin.employees.pills")
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="post"
                      class="col-12 needs-validation"
                      enctype="multipart/form-data"
                      action="{{ route('admin.employees.update', ['employee' => $employee]) }}">
                    @csrf
                    @method('put')

                    <div class="form-group">
                        <label for="title">{{ config("site-staff.employeeTitleName") }} </label>
                        <input type="text"
                               id="title"
                               name="title"
                               value="{{ old('title') ? old('title') : $employee->title }}"
                               required
                               class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}">
                        @if ($errors->has('title'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('title') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="slug">Slug</label>
                        <input type="text"
                               id="slug"
                               name="slug"
                               value="{{ old('slug') ? old('slug') : $employee->slug }}"
                               class="form-control{{ $errors->has('slug') ? ' is-invalid' : '' }}">
                        @if ($errors->has('slug'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('slug') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="short">{{ config("site-staff.employeeShortName") }}</label>
                        <input type="text"
                               id="short"
                               name="short"
                               value="{{ old('short') ? old('short') : $employee->short }}"
                               class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="description">{{ config("site-staff.employeeDescriptionName") }}</label>
                        <textarea class="form-control tiny"
                                  name="description"
                                  id="description"
                                  rows="3">{{ old('description') ? old('description') : $employee->description }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="comment">{{ config("site-staff.employeeCommentName") }}</label>
                        <textarea class="form-control tiny"
                                  name="comment"
                                  id="comment"
                                  rows="3">
                            {{ old('comment') ? old('comment') : $employee->comment }}
                        </textarea>
                    </div>

                    <div class="form-group">
                        @if($employee->image)
                            <div class="d-inline-block">
                                @pic([
                                "image" => $employee->image,
                                "template" => "small",
                                "imgClass" => "rounded mb-2",
                                "grid" => [],
                                ])
                                <button type="button" class="close ml-1" data-confirm="{{ "delete-form-{$employee->id}" }}">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        <div class="custom-file">
                            <input type="file"
                                   class="custom-file-input{{ $errors->has('image') ? ' is-invalid' : '' }}"
                                   id="custom-file-input"
                                   lang="ru"
                                   name="image"
                                   aria-describedby="inputGroupMain">
                            <label class="custom-file-label"
                                   for="custom-file-input">
                                Выберите файл главного изображения
                            </label>
                            @if ($errors->has('image'))
                                <div class="invalid-feedback">
                                    <strong>{{ $errors->first('image') }}</strong>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        @isset($departments)
                            <label>{{ config("site-staff.siteDepartmentName") }}:</label>
                            @include("site-staff::admin.departments.includes.tree-checkbox", ['departments' => $departments])
                        @endisset
                    </div>

                    <div class="btn-group mt-2"
                         role="group">
                        <button type="submit" class="btn btn-success">Обновить</button>
                    </div>
                </form>

                @if($employee->image)
                    <confirm-form :id="'{{ "delete-form-{$employee->id}" }}'">
                        <template>
                            <form action="{{ route('admin.employees.show.delete-image', ['employee' => $employee]) }}"
                                  id="delete-form-{{ $employee->id }}"
                                  class="btn-group"
                                  method="post">
                                @csrf
                                <input type="hidden" name="_method" value="DELETE">
                            </form>
                        </template>
                    </confirm-form>
                @endif
            </div>
        </div>
    </div>
@endsection
