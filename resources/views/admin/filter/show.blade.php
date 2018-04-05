@extends('layouts.admin_app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Редактирование фильтра</div>

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
                        @if(isset($accesses->filter->edit))
                            <form action="{{route('filter.edit', ['cat' => request()->cat])}}" method="post">
                                @csrf
                                @endif

                                <input type="hidden" name="filter_id" value="{{$filter->id}}">

                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="">Название</label>
                                    </div>
                                    <div class="col-md-10 {{$errors->has('name')?"has-error":""}}">
                                        <input type="text" class="form-control" name="name"
                                               value="{{old('name')?old('name'):$filter->name}}">
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
                                                <option value="{{$category->id}}" {{$filter->category_id == $category->id?'selected':""}}>
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
                                        <label for="">Параметры</label>
                                    </div>
                                    <div class="col-md-10">
                                        @if(\App\Models\Admin\Role::hasAccess('parameter', 'create'))
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
                                        @endif
                                        @if(\App\Models\Admin\Role::hasAccess('parameter', 'edit') || \App\Models\Admin\Role::hasAccess('parameter', 'create'))
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
                                                                        @if(\App\Models\Admin\Role::hasAccess('filter', 'delete'))
                                                                            <a class="btn btn-danger"
                                                                               data-id="{{$key}}"
                                                                               onclick="deleteParameter($(this))">Удалить</a>
                                                                        @endif
                                                                    </span>
                                                                    @if($errors->has('parameter'.$key))
                                                                        <span class="help-block">
                                                                            <strong>{{$errors->first('parameter'.$key)}}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    @else
                                                        @forelse($filter->parameters()->orderBy('weight')->get() as $parameter)
                                                            <li id="list_{{$parameter->id}}" class="parameter"
                                                                data-id="{{$parameter->id}}">
                                                                <div class="row">
                                                                    <p class="col-md-10">
                                                                        <input type="text" class="form-control"
                                                                               name="parameter[{{$parameter->id}}]"
                                                                               value="{{$parameter->name}}">
                                                                    </p>
                                                                    <p class="col-md-2">
                                                                        @if(\App\Models\Admin\Role::hasAccess('parameter', 'delete'))
                                                                            <a class="btn btn-danger"
                                                                               data-id="{{$parameter->id}}"
                                                                               onclick="deleteParameter($(this))">Удалить</a>
                                                                        @endif
                                                                    </p>
                                                                    @if($errors->has('parameter'.$parameter->id))
                                                                        <span class="help-block">
                                                                            <strong>{{$errors->first('parameter'.$parameter->id)}}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </li>
                                                        @empty

                                                        @endforelse
                                                    @endif
                                                </ol>
                                                <input type="hidden" name="parameters_order" id="parameters_order"
                                                       value="">
                                            </div>
                                        @else
                                            @forelse($filter->parameters()->orderBy('weight')->get() as $parameter)
                                                <div class="row parameter" data-id="{{$parameter->id}}">
                                                    <div class="col-md-10">
                                                        <p>{{$parameter->name}}</p>
                                                    </div>
                                                    <div class="col-md-2">
                                                        @if(\App\Models\Admin\Role::hasAccess('parameter', 'delete'))
                                                            <a class="btn btn-danger"
                                                               data-id="{{$parameter->id}}"
                                                               onclick="deleteParameter($(this))">Удалить</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            @empty

                                            @endforelse
                                        @endif
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 col-md-offset-4">
                                        @if(isset($accesses->filter->edit))
                                            <input type="submit" class="btn btn-success" value="Сохранить">
                                        @endif
                                        @if(isset($accesses->filter->delete))
                                            <a class="btn btn-danger" data-toggle="modal"
                                               data-target="#delete_filter_modal">Удалить</a>
                                        @endif
                                    </div>
                                </div>
                                @if(isset($accesses->filter->edit))
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(isset($accesses->filter->delete))
        <!-- Modal -->
        <div id="delete_filter_modal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Удалить фильтр?</h4>
                    </div>
                    <form action="{{route('filter.delete', ['cat' => request()->cat])}}" method="post">
                        @csrf
                        <input type="hidden" name="delete_filter_id" id="delete_filter_id" value="{{$filter->id}}">
                        <div class="modal-body">
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
