<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>MVNU</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    MVNU
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
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @else
                            
                            @if (env('TEMP_ENABLE_TESTING'))
                            <li class="nav-item">
                                <a href="{{ route('home') }}" class="nav-link">
                                Daily Screening
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('testing.optin') }}" class="nav-link">
                                Testing Opt-In
                                </a>
                            </li>
                            @endif

                            @if (Auth::user()->hasRole("SCREENINGS_VIEW") || Auth::user()->hasRole("TESTS_VIEW") || Auth::user()->hasRole("TESTS_IMPORT"))
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        Manage <span class="caret"></span>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        @if (auth::user()->hasRole("SCREENINGS_VIEW"))
                                        <a class="dropdown-item" href="{{ route('screenings.index') }}">Screenings</a>
                                        @endif

                                        @if (auth::user()->hasRole("TESTS_VIEW") || auth::user()->hasRole("TESTS_RESULTS"))
                                        <a class="dropdown-item" href="{{ route('tests.index') }}">Tests</a>
                                        @endif

                                        @if (auth::user()->hasRole("TESTS_IMPORT"))
                                        <a class="dropdown-item" href="{{ route('tests.upload') }}">Import Tests</a>
                                        @endif
                                    </div>
                                </li>
                            @endif

                            @if (Auth::user()->hasRole("ADMIN"))
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        Admin <span class="caret"></span>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('questions.index') }}">Questions</a>
                                        <a class="dropdown-item" href="{{ route('users.index') }}">Users</a>
                                    </div>
                                </li>
                            @endif

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
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script>
        window.Auth = {!! json_encode([
            'signedIn' => Auth::check(),
            'user' => Auth::user()
        ]) !!}
    </script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
