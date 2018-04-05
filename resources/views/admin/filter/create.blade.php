@extends('layouts.admin_app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Создание фильтра</div>

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

                        <form action="{{route('filter.store', ['cat' => request()->cat])}}" method="post">
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
                                        @if(old('category'))

                                        @elseif(request()->cat)

                                        @endif
                                    </select>
                                    @if($errors->has('category'))
                                        <span class="help-block">
                                            <strong>{{$errors->first('category')}}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            @if(\App\Models\Admin\Role::hasAccess('parameter', 'create'))
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="">Параметры</label>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="row">
                                            <div class="col-md-10 add_parameter_name">
                                                <input type="text" class="form-control" id="add_parameter_name">
                                                <span class="help-block" hidden>
                                                    <strong></strong>
                                                </span>
                                            </div>
                                            <div class="col-md-2">
                                                <a class="btn btn-success" id="add_parameter">Добавить</a>
                                            </div>
                                        </div>

                                        <div class="parameters-list">
                                            <ol class="sortparameter sortable ui-sortable" style="width: 100%;">
                                                @if(old('parameter'))
                                                    @foreach(old($parameter) as $key => $parameter)
                                                        <li id="list_{{$key}}" class="parameter" data-id="{{$key}}">
                                                            <div class="row">
                                                                <span class="col-md-10">
                                                                    <input type="text" class="form-control"
                                                                           name="parameter[{{$key}}]"
                                                                           value="{{$parameter}}">
                                                                </span>
                                                                <span class="col-md-2">
                                                                    <a class="btn btn-danger"
                                                                       data-id="{{$key}}"
                                                                       onclick="deleteParameter($(this))">Удалить</a>
                                                                </span>
                                                                @if($errors->has('parameter'.$key))
                                                                    <span class="help-block">
                                                                            <strong>{{$errors->first('parameter'.$key)}}</strong>
                                                                        </span>
                                                                @endif
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                @endif
                                            </ol>
                                            <input type="hidden" name="parameters_order" id="parameters_order" value="">
                                        </div>

                                    </div>
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-2 col-md-offset-5">
                                    <input type="submit" class="btn btn-success" value="Сохранить">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
