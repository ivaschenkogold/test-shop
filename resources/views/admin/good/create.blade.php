@extends('layouts.admin_app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Создание товара</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                Упс! Что-то пошло не так<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{route('good.store', ['cat' => request()->cat])}}" enctype="multipart/form-data"
                              method="post">
                            @csrf

                            <div class="row">
                                <div class="col-md-2">
                                    <label for="">Название</label>
                                </div>
                                <div class="col-md-10 {{$errors->has('name')?"has-error":""}}">
                                    <input type="text" class="form-control" name="name"
                                           value="{{old('name')?old('name'):''}}">
                                    @if($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{$errors->first('name')}}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <label for="">Категория</label>
                                </div>
                                <div class="col-md-10 {{$errors->has('category')?"has-error":""}}">
                                    <select name="category" id="" class="form-control">
                                        @forelse($categories as $category)
                                            <option value="{{$category->id}}"
                                                    @if(old('category'))
                                                    @if(old('category') == $category->id)
                                                    selected
                                                    @endif
                                                    @elseif(request()->cat)
                                                    @if(request()->cat == $category->id)
                                                    selected
                                                    @endif
                                                    @endif
                                            >
                                                @for($i = 1; $i < $category->depth; $i++)
                                                    -
                                                @endfor
                                                {{$category->name}}
                                            </option>
                                        @empty

                                        @endforelse

                                    </select>
                                    @if($errors->has('category'))
                                        <span class="help-block">
                                            <strong>{{$errors->first('category')}}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <label for="">Slug</label>
                                </div>
                                <div class="col-md-10 {{$errors->has('slug')?"has-error":""}}">
                                    <input type="text" class="form-control" name="slug"
                                           value="{{old('slug')?old('slug'):''}}">
                                    @if($errors->has('slug'))
                                        <span class="help-block">
                                            <strong>{{$errors->first('slug')}}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="">Seo Title</label>
                                </div>
                                <div class="col-md-10 {{$errors->has('title')?"has-error":""}}">
                                    <input type="text" class="form-control" name="title"
                                           value="{{old('title')?old('title'):''}}">
                                    @if($errors->has('title'))
                                        <span class="help-block">
                                            <strong>{{$errors->first('title')}}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="">Seo Keywords</label>
                                </div>
                                <div class="col-md-10 {{$errors->has('keywords')?"has-error":""}}">
                                    <input type="text" class="form-control" name="keywords"
                                           value="{{old('keywords')?old('keywords'):''}}">
                                    @if($errors->has('keywords'))
                                        <span class="help-block">
                                            <strong>{{$errors->first('keywords')}}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="">Seo Description</label>
                                </div>
                                <div class="col-md-10 {{$errors->has('desc')?"has-error":""}}">
                                    <textarea name="desc" id="" cols="30" rows="10"
                                              class="form-control">{{old('desc')?old('desc'):''}}</textarea>
                                    @if($errors->has('desc'))
                                        <span class="help-block">
                                            <strong>{{$errors->first('desc')}}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <label for="">Изображения</label>
                                </div>
                                <div class="col-md-10">
                                    <div class="row image-upload-fields">
                                        <input type="file" class="image-upload-field" name="image[1]" data-id="1"
                                               value="" onchange="showPreview($(this))">
                                    </div>
                                    <div class="row images-preview">
                                        <ol class="sortgood sortable ui-sortable" style="width: 100%;">

                                        </ol>
                                    </div>
                                    <input type="hidden" name="images_order" id="images_order" value="">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <label for="">Текст</label>
                                </div>
                                <div class="col-md-10 {{$errors->has('text')?"has-error":""}}">
                                    <textarea name="text" id="" cols="30" rows="10"
                                              class="form-control">{{old('text')?old('text'):''}}</textarea>
                                    @if($errors->has('text'))
                                        <span class="help-block">
                                            <strong>{{$errors->first('text')}}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <p>Фильтры</p>
                            </div>
                            <div class="row filters">
                                @include('admin.good.parts.filters', ['category' => old('category')?$categories->wehre('id', old('category'))->first():$categories->first()])
                            </div>

                            <div class="row">
                                <div class="col-md-2 col-md-offset-5">
                                    <input type="submit" class="btn btn-success" value="Создать">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
