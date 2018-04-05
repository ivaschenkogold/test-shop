@extends('layouts.admin_app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Создание категории</div>

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

                        <form action="{{route('category.store')}}" method="post">
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
                                    <label for="">Родительская категория</label>
                                </div>
                                <div class="col-md-10 {{$errors->has('parent')?"has-error":""}}">
                                    <select name="parent" id="" class="form-control">
                                        <option value="1">Корневая категория</option>
                                        @forelse($categories as $category)
                                            <option value="{{$category->id}}" {{old('parent') && old('parent') == $category->id?'selected':""}}>
                                                @for($i = 0; $i < $category->depth; $i++)
                                                    -
                                                @endfor
                                                {{$category->name}}
                                            </option>
                                        @empty

                                        @endforelse
                                    </select>
                                    @if($errors->has('parent'))
                                        <span class="help-block">
                                            <strong>{{$errors->first('parent')}}</strong>
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
                                <div class="col-md-12">
                                    <div class="col-md-2 col-md-offset-5">
                                        <input type="submit" class="btn btn-success" value="Создать">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
