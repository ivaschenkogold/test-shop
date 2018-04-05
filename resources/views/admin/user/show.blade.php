@extends('layouts.admin_app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Просмотр пользователя
                    </div>

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

                        @if(isset($accesses->user->edit))
                            <form action="{{route('user.edit')}}" method="post">
                                @endif
                                @csrf
                                <input type="hidden" name="id" value="{{$user->id}}">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="">Имя</label>
                                    </div>
                                    <div class="col-md-10 {{$errors->has('name')?"has-error":""}}">
                                        <input type="text" class="form-control" name="name"
                                               value="{{old('name')?old('name'):$user->name}}">
                                        @if($errors->has('name'))
                                            <span class="help-block">
                                            <strong>{{$errors->first('name')}}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="">Email</label>
                                    </div>
                                    <div class="col-md-10 {{$errors->has('email')?"has-error":""}}">
                                        <input type="email" class="form-control" name="email"
                                               value="{{old('email')?old('email'):$user->email}}">
                                        @if($errors->has('email'))
                                            <span class="help-block">
                                            <strong>{{$errors->first('email')}}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="">Пароль</label>
                                    </div>
                                    <div class="col-md-10 {{$errors->has('password')?"has-error":""}}">
                                        <input type="password" class="form-control" name="password"
                                               value="{{old('password')?old('password'):''}}">
                                        @if($errors->has('password'))
                                            <span class="help-block">
                                            <strong>{{$errors->first('password')}}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="">Подтверждение пароля</label>
                                    </div>
                                    <div class="col-md-10 {{$errors->has('password_confirmation')?"has-error":""}}">
                                        <input type="password" class="form-control" name="password_confirmation"
                                               value="">
                                        @if($errors->has('password_confirmation'))
                                            <span class="help-block">
                                            <strong>{{$errors->first('password_confirmation')}}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="">Роль</label>
                                    </div>
                                    <div class="col-md-10 {{$errors->has('role')?"has-error":""}}">
                                        <select name="role" id="" class="form-control">
                                            @forelse($roles as $role)
                                                @if($user->role_id == $role->id)
                                                    <option value="{{$role->id}}" selected>{{$role->name}}</option>
                                                @else
                                                    <option value="{{$role->id}}">{{$role->name}}</option>
                                                @endif
                                            @empty

                                            @endforelse
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 col-md-offset-4">
                                        @if(isset($accesses->user->edit))
                                            <input type="submit" class="btn btn-success" value="Сохранить">
                                        @endif
                                        @if(isset($accesses->user->delete))
                                            <button type="button" class="btn btn-danger" data-toggle="modal"
                                                    data-target="#delete_user_modal"
                                                    data-id="{{$user->id}}">Удалить
                                            </button>
                                        @endif
                                    </div>
                                </div>
                                @if(isset($accesses->user->edit))
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(isset($accesses->user->delete))
        <!-- Modal -->
        <div id="delete_user_modal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Удалить пользователя?</h4>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <form action="{{route('user.delete')}}" method="post">
                            @csrf
                            <input type="hidden" name="delete_user_id" id="delete_user_id" value="{{$user->id}}">
                            <button type="submit" class="btn btn-danger" id="delete_user">Удалить</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    @endif
@endsection
