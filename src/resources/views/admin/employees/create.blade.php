@extends('admin.layout')

@section('page-title', config("site-staff.siteEmployeeName").' - Добавить - ')
@section('header-title',  config("site-staff.siteEmployeeName").' - Добавить')

@section('admin')
    @include("site-staff::admin.employees.pills")
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="post"
                      class="col-12 needs-validation"
                      enctype="multipart/form-data"
                      action="{{ route('admin.employees.store') }}">
                    @csrf

                    <div class="form-group">
                        <label for="title">{{ config("site-staff.employeeTitleName") }} <span class="text-danger">*</span></label>
                        <input type="text"
                               id="title"
                               name="title"
                               value="{{ old('title') }}"
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
                               Name="slug"
                               value="{{ old('slug') }}"
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
                               Name="short"
                               value="{{ old('short') }}"
                               class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="description">{{ config("site-staff.employeeDescriptionName") }} <span class="text-danger">*</span></label>
                        <textarea class="form-control tiny {{ $errors->has('description') ? ' is-invalid' : '' }}"
                                  name="description"
                                  id="description"
                                  rows="3">
                            {{ old('description') }}
                        </textarea>
                        @if ($errors->has('description'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('description') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="comment">{{ config("site-staff.employeeCommentName") }}</label>
                        <textarea class="form-control tiny {{ $errors->has('comment') ? ' is-invalid' : '' }}"
                                  name="comment"
                                  id="comment"
                                  rows="3">
                            {{ old('comment') }}
                        </textarea>
                        @if ($errors->has('comment'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('comment') }}</strong>
                            </span>
                        @endif
                    </div>

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

                    <div class="form-group mt-3">
                        @isset($departments)
                            <label>{{ config("site-staff.siteDepartmentName") }}:</label>
                           @include("site-staff::admin.departments.includes.tree-checkbox", ['departments' => $departments])
                        @endisset
                    </div>

                    <div class="btn-group mt-2"
                         role="group">
                        <button type="submit" class="btn btn-success">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
