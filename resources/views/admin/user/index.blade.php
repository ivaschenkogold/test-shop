@extends('layouts.admin_app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Пользователи
                        @if(isset($accesses->user->create))
                            <a href="{{route('user.create')}}" class="btn btn-info">Создать</a>
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

                        @forelse($users as $user)
                            <div class="row">
                                <div class="col-md-8">
                                    <p>{{$user->name}}</p>
                                </div>
                                <div class="col-md-2">
                                    @if(isset($accesses->user->show))
                                        <a href="{{route('user.show', ['id' => $user->id])}}" class="btn btn-primary">Просмотр</a>
                                    @endif
                                </div>
                                <div class="col-md-2">
                                    @if(isset($accesses->user->delete))
                                        <button type="button" class="btn btn-danger show_delete_user_modal"
                                                data-id="{{$user->id}}">Удалить
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
                            <input type="hidden" name="delete_user_id" id="delete_user_id" value="">
                            <button type="submit" class="btn btn-danger" id="delete_user">Удалить</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    @endif
@endsection
