@extends('layouts.admin_app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Роли
                        @if(isset($accesses->role->create))
                            <a href="{{route('role.create')}}" class="btn btn-info">Создать</a>
                        @endif
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

                        @forelse($roles as $role)
                            <div class="row">
                                <div class="col-md-8">
                                    <p>{{$role->name}}</p>
                                </div>
                                <div class="col-md-2">
                                    @if(isset($accesses->role->show))
                                        <a href="{{route('role.show', ['id' => $role->id])}}" class="btn btn-primary">Просмотр</a>
                                    @endif
                                </div>
                                <div class="col-md-2">
                                    @if(isset($accesses->role->delete))
                                        <button type="button" class="btn btn-danger show_delete_role_modal"
                                                data-id="{{$role->id}}" data-users="{{$role->users->count()}}">Удалить
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @empty

                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(isset($accesses->role->delete))
        <!-- Modal -->
        <div id="delete_role_modal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Удалить роль?</h4>
                    </div>
                    <form action="{{route('role.delete')}}" method="post">
                        @csrf
                        <input type="hidden" name="delete_role_id" id="delete_role_id" value="">
                        <div class="modal-body">
                            <div class="new-user-role" hidden>
                                <div class="row">
                                    <p>В этой группе находятся <span class="users-in-role-count"></span> пользователей.
                                        Для
                                        удаления роли необходимо перенести её пользователей в другую.</p>
                                    <div class="col-md-4">
                                        <label for="">Переместить пользователей в</label>
                                    </div>
                                    <div class="col-md-8 move-users-to">
                                        <select name="move_users_to" id="move_users_to" class="form-control">
                                            <option value=""></option>
                                            @forelse($roles as $role)
                                                <option value="{{$role->id}}">{{$role->name}}</option>
                                            @empty

                                            @endforelse
                                        </select>
                                        <span class="help-block" hidden>
                                            <strong>У Вас нету ролей куда можно перенести пользователей, создайте новую роль или удалите пользователей</strong>
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
