<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('Visual aid application', 'Visual aid application') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
   



    <!-- Front awsome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css"> 



    <!-- Styles -->
    <link href="{{ asset('css/resource/css/all.css') }}" rel="stylesheet">
</head>
<body>
    <div >
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container" >
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img style="width:20%;" src="/storage/aptiv.png" />
                    {{ config('Visual aid application', 'Visual aid application') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">

                            @if (App\User::admin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('allEv') }}">{{ __('All Ev') }}</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('newType') }}">{{ __('Add a New Visuel Aid') }}</a>
                            </li>
                            @endif

                        <!-- Authentication Links 
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            <li class="nav-item">
                                @if (Route::has('register'))
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                @endif
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                        -->
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <footer class="row fixed-bottom" style="background-color: #0A79DF">
            <div class="col-xs-12 col-sm-4 col-md-4 footer-column"> </div>
        <div  class="col-xs-12 col-sm-4 col-md-4 footer-column" style="text-align: center;"     ">
             © Created by <strong> Anass LAMAALAM </strong> 2019
        </div>
        <div class="col-xs-12 col-sm-4 col-md-4 footer-column"> </div>
    </footer>
</body>
</html>
