@extends('layouts.admin_app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Просмотр роли</div>

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


                        @if(isset($accesses->role->edit))
                            <form action="{{route('role.edit')}}" method="post">
                                <input type="hidden" name="role_id" value="{{$role->id}}">
                                @endif
                                {{csrf_field()}}
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="">Название</label>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="name"
                                               value="{{old('name')?old('name'):$role->name}}">
                                    </div>
                                </div>
                                <div class="row">
                                    @forelse($modules as $module)
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="">{{$module->name}}</label>
                                                </div>
                                            </div>
                                            @forelse($module->funcs as $func)
                                                <div class="row">
                                                    <div class="col-md-10">
                                                        <p>{{$func->name}}</p>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="checkbox" name="func[{{$func->id}}]"
                                                               value="1" {{$role->funcs->contains($func->id)?"checked":""}}>
                                                    </div>
                                                </div>
                                            @empty

                                            @endforelse
                                        </div>
                                    @empty

                                    @endforelse
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-4 col-md-offset-4">
                                            @if(isset($accesses->role->edit))
                                                <input type="submit" class="btn btn-success" value="Сохранить">
                                            @endif
                                                @if(isset($accesses->role->delete))
                                                    <button type="button" class="btn btn-danger show_delete_role_modal"
                                                            data-id="{{$role->id}}" data-users="{{$role->users->count()}}">Удалить
                                                    </button>
                                                @endif
                                        </div>
                                    </div>
                                </div>
                                @if(isset($accesses->role->edit))
                            </form>
                        @endif
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
