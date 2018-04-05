@extends('layouts.admin_app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Создание роли</div>

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

                        <form action="{{route('role.store')}}" method="post">
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
                                                           value="{{$func->id}}">
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
