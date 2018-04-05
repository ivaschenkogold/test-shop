<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/category.css') }}" rel="stylesheet">
    @if(file_exists(public_path('css/'.$request_module.'.css')))
        <link href="{{ asset('css/'.$request_module.'.css') }}" rel="stylesheet">
    @endif
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">
                    @if(isset($accesses->role->index))
                        <li><a class="nav-link" href="{{ route('role.index') }}">Роли</a></li>
                    @endif
                    @if(isset($accesses->user->index))
                        <li><a class="nav-link" href="{{ route('user.index') }}">Пользователи</a></li>
                    @endif
                    @if(isset($accesses->category->index))
                        <li><a class="nav-link" href="{{ route('category.index') }}">Категории</a></li>
                    @endif
                    @if(isset($accesses->good->index))
                        <li><a class="nav-link" href="{{ route('good.index') }}">Товары</a></li>
                    @endif
                    @if(isset($accesses->filter->index))
                        <li><a class="nav-link" href="{{ route('filter.index') }}">Фильтры</a></li>
                    @endif
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                    <li><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>
</div>

<!-- Scripts -->
{{--<script src="{{ asset('js/libs/bootstrap/bootstrap.min.js') }}"></script>--}}
<script src="{{ asset('js/app.js') }}"></script>
{{--<script src="{{ asset('js/libs/jquery-3.3.1.min.js') }}"></script>--}}
<script src="{{ asset('js/libs/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/libs/jquery.ui.nestedSortable-1.3.4.min.js') }}"></script>


@if(file_exists(public_path('js/'.$request_module.'.js')))
    <script src="{{ asset('js/'.$request_module.'.js') }}"></script>
@endif
</body>
</html>
