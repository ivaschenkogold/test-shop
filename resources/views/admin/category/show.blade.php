@extends('layouts.admin_app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Редактирование категории</div>

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

                        @if(isset($accesses->category->edit))
                            <form action="{{route('category.edit')}}" method="post">
                                @endif
                                @csrf
                                <input type="hidden" name="category_id" value="{{$category->id}}">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="">Название</label>
                                    </div>
                                    <div class="col-md-10 {{$errors->has('name')?"has-error":""}}">
                                        <input type="text" class="form-control" name="name"
                                               value="{{old('name')?old('name'):$category->name}}">
                                        @if($errors->has('name'))
                                            <span class="help-block">
                                            <strong>{{$errors->first('name')}}</strong>
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
                                               value="{{old('slug')?old('slug'):$category->slug}}">
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
                                               value="{{old('title')?old('title'):$category->title}}">
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
                                               value="{{old('keywords')?old('keywords'):$category->keywords}}">
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
                                              class="form-control">{{old('desc')?old('desc'):$category->desc}}</textarea>
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
                                              class="form-control">{{old('text')?old('text'):$category->text}}</textarea>
                                        @if($errors->has('text'))
                                            <span class="help-block">
                                            <strong>{{$errors->first('text')}}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-4 col-md-offset-4">
                                            @if(isset($accesses->category->edit))
                                                <input type="submit" class="btn btn-success" value="Сохранить">
                                            @endif
                                            @if(isset($accesses->category->delete))
                                                <a class="btn btn-danger show_delete_category_modal"
                                                   data-id="{{$category->id}}">Удалить</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @if(isset($accesses->category->edit))
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(isset($accesses->category->delete))
        <!-- Modal -->
        <div id="delete_category_modal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Удалить категорию?</h4>
                    </div>
                    <form action="{{route('category.delete')}}" method="post">
                        @csrf
                        <input type="hidden" name="delete_category_id" id="delete_category_id" value="">
                        <div class="modal-body">
                            <div class="new-good-category" hidden>
                                <div class="row">
                                    <p>В этой группе находятся <span class="goods-in-category-count"></span> товаров.
                                        Для
                                        удаления категории необходимо перенести её товары в другую.</p>
                                    <div class="col-md-4">
                                        <label for="">Переместить товары в</label>
                                    </div>
                                    <div class="col-md-8 move-goods-to">
                                        <select name="move_goods_to" id="move_goods_to" class="form-control">
                                            @forelse($categories as $category)
                                                <option value="{{$category->id}}">
                                                    @for($i = 0; $i < $category->depth; $i++)
                                                        -
                                                    @endfor
                                                    {{$category->name}}
                                                </option>
                                            @empty

                                            @endforelse
                                        </select>
                                        <span class="help-block" hidden>
                                            <strong>У Вас нету категорий куда можно перенести товары, создайте новую категорию или удалите товары</strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger">Удалить</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    @endif
@endsection
