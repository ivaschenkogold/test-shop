@extends('layouts.admin_app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Фильры
                        @if(isset($accesses->filter->create))
                            <a href="{{route('filter.create')}}" class="btn btn-info">Создать</a>
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

                        @forelse($filters as $filter)
                            <div class="row">
                                <div class="col-md-4">
                                    <p>{{$filter->name}}</p>
                                </div>
                                <div class="col-md-5">
                                    <p>{{$filter->category->name}}</p>
                                </div>
                                <div class="col-md-3">
                                    @if(isset($accesses->filter->show))
                                        <a href="{{route('filter.show', ['id' => $filter->id])}}"
                                           class="btn btn-primary">Просмотр</a>
                                    @endif
                                    @if(isset($accesses->filter->delete))
                                            <a class="btn btn-danger show_delete_filter_modal" data-id="{{$filter->id}}">Удалить</a>
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
                    <form action="{{route('filter.delete')}}" method="post">
                        @csrf
                        <input type="hidden" name="delete_filter_id" id="delete_filter_id" value="">
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
