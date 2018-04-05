@extends('layouts.admin_app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Товары категории
                        @if(isset($accesses->good->create))
                            <a href="{{route('good.create', ['cat' => $category->id])}}" class="btn btn-info">Создать</a>
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

                        @forelse($category->goods()->orderBy('weight')->get() as $good)
                            <div class="row">
                                <div class="col-md-9">
                                    <p>{{$good->name}}</p>
                                </div>
                                <div class="col-md-3">
                                    @if(isset($accesses->good->show))
                                        <a href="{{route('good.show', ['id' => $good->id, 'cat' => $category->id])}}" class="btn btn-primary">Просмотр</a>
                                    @endif
                                    @if(isset($accesses->good->delete))
                                        <button type="button" class="btn btn-danger show_delete_good_modal"
                                                data-id="{{$good->id}}">Удалить
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
    @if(isset($accesses->good->delete))
        <!-- Modal -->
        <div id="delete_good_modal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Удалить товар?</h4>
                    </div>
                    <form action="{{route('good.delete', ['cat' => $category->id])}}" method="post">
                        @csrf
                        <input type="hidden" name="delete_good_id" id="delete_good_id" value="">
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
