<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Steven van Rosendaal">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
            <div class="container d-flex align-items-end">
                <a class="navbar-brand d-flex align-items-end" href="{{ url('/') }}">
                    <img class="my-2" src="{{ asset('img/curio_small.png') }}">
                    <div class="ml-1">Uitleensysteem</div>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        @guest
                        @else
                        <li class="nav-item">
                            <a class="nav-link" href="#" onclick="$('#searchProductsModal').modal()">Producten Uitlenen</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Beheren
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('manageProducts') }}">Producten</a>
                                <a class="dropdown-item" href="{{ route('manageProductTypes') }}">Producttypes</a>
                                <a class="dropdown-item" href="{{ route('listReservations') }}">Reserveringen</a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('import') }}">Importeren</a>
                        </li>
                        @endguest
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('searchProducts') }}">
                                        {{ __('Logout') }}
                                    </a>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @if(session('success'))
                @include('components.success-alert', ['success' => session('success')])
            @endif
            @if(session('warning'))
                @include('components.warning-alert', ['warning' => session('warning')])
            @endif
            @if(session('error'))
                @include('components.error-alert', ['error' => session('error')])
            @endif
            @yield('content')
        </main>
    </div>
    @guest
    @else
        @include('components.search-products-modal')
    @endguest
</body>
</html>
