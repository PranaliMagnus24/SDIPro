<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="manifest" href="manifest.json">
    <meta name="viewport" content="width=device-width; initial-scale=1; viewport-fit=cover">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SDI(Sunni Dawate Islami)</title>
    @php
    $favicon = DB::table('general')->where('ID', 1)->value('favicon') ?? 'logo.png';
    @endphp
    <link rel="icon" href="{{ asset('general/' . $favicon) }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <!-----------font awesome---------->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- Vendor CSS Files -->
    <link href="{{ asset('admin/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <!-- Template Main CSS File -->
    <link href="{{ asset('admin/assets/css/style.css') }}" rel="stylesheet">
    <script src="{{ asset('admin/assets/vendor/sweet-alert/sweetalert2@11.js')}}"></script>
    <link href="{{ asset('admin/assets/vendor/select2/select2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <style>
        html, body {
        height: 100%;
        margin: 0;
        display: flex;
        flex-direction: column;
    }

    #app {
        flex: 1;
    }
    /* .dropdown-submenu .dropdown-menu {
        top: 0;
        left: 100%;
        margin-top: -1px;
        display: none;
        position: absolute;
        z-index: 1050;
    } */
    .dropdown-submenu:hover .dropdown-menu {
        display: block;
    }
    .dropdown-submenu {
    position: relative;
}

.dropdown-submenu .dropdown-menu {
    top: 0;
    left: 100%;
    margin-left: 0.1rem;
    margin-right: 0.1rem;
}


    </style>
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/serviceworker.js')
        .then((reg) => console.log('Service Worker Registered', reg))
        .catch((err) => console.log('Service Worker Not Registered', err));
    }
    </script>
</head>
<body>
    <!-------Falsh success message--------->
    <div>
        @if(session('success'))
        <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-right',
            iconColor: 'white',
            customClass: {
                popup: 'colored-toast',
            },
            showConfirmButton: false,
            timer: 1900,
            timerProgressBar: true,
        });
        (async () => {
            await Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}',
            });
        })();
       </script>
       @endif
    </div>
    <div id="app">
        @if(Auth::check())<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">@endif
            <div class="container">
                @if(Auth::check())
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('general/' . (DB::table('general')->where('ID', 1)->value('logo') ?? 'logourdu.png')) }}" alt="Logo" style="height: 50px;">
                </a>
                @endif
                @if(Auth::check())
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                @endif
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        <!-- @guest
                            @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @endif
                            @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                            @endif
                            @else -->
                            @if (!empty(Auth::user()->roles) && isset(Auth::user()->roles[0]) && in_array(Auth::user()->roles[0]->name, ['Admin']))
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="javascript:void(0)" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Ramadan
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('collectionlist') }}">Ramadan List
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('collection.create') }}">Ramadan create
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Qurbani
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('qurbanis.index') }}">Qurbani List
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('qurbanis.create') }}">Qurbani Create</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ url('/qurbani/guest-submissions') }}">Guest List</a>
                                    </li>
                                    <li class="dropdown-submenu">
                                        <a class="dropdown-item dropdown-toggle" href="#">Final List</a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="/finalList/1">Day 1</a></li>
                                            <li><a class="dropdown-item" href="/finalList/2">Day 2</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="javascript:void(0)" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Causes
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <li>
                                    <a class="dropdown-item" href="{{ route('causes.causeslist') }}">Causes List</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('causes.create') }}">Causes create
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="javascript:void(0)" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                   Faqs
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('faqlist') }}">Faq List</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('faq.create') }}">Faq create
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a class="nav-link" href="{{ route('formlist') }}">Ijtema</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('master.settings') }}">
                                        General Setting
                                    </a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="javascript:void(0)" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Master Setting
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('users.index') }}">Users</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('roles.index') }}">Roles</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('qurbani.days')}}">Qurbani Days</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('categorylist') }}">Donation Category
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                @else
                                {{--<li><a class="nav-link" href="{{ route('qurbanis.index') }}">Manage Qurbani</a></li>--}}
                                <li>
                                    <a class="nav-link" href="{{ route('collection.create') }}">Create Receipt
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link" href="{{ route('collectionlist') }}">Ramadan</a>
                                </li>
                                <!-- <li><a class="nav-link" href="{{ route('qurbanis.index') }}">Qurbani List</a></li>
                                    <li><a class="nav-link" href="{{ route('qurbanis.create') }}">Qurbani Create</a>
                                    </li>
                                    <li><a class="nav-link" href="{{ url('/qurbani/guest-submissions') }}">Guest List</a> -->
                                @endif
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                                <!-- @endguest -->
                            </ul>
                        </div>
                    </div>
                </nav>
                <main class="py-4">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                @auth
                                <div class="card" style="background-color: #fff; border: 1px solid #dee2e6; box-shadow: 0 2px 6px rgba(0,0,0,0.05);">
                                    <div class="card-body">
                                        @yield('content')
                                    </div>
                                </div>
                                @endauth
                                @guest
                                <div>
                                    @yield('content')
                                </div>
                                @endguest
                            </div>
                        </div>
                    </div>
                </main>
            </div>
<footer class="mt-2 pt-2 pb-2" style="color: black; text-align: center;">
    <p class="mt-2">
        &#xA9; <?=date("Y") ?> All Rights Reserved by Sunni Dawate Islami (SDI).
        <br>
        Developed By
        <a href="https://magnusideas.com" target="_blank" style="color:rgb(8, 58, 122); text-decoration: none;">Magnus Ideas Pvt. Ltd.</a>
    </p>
</footer>
{{-- <script src="{{ asset('admin/assets/js/jquery-3.6.0.min.js')}}"></script> --}}
<script src="{{ asset('admin/assets/js/jquery.min.js')}}"></script>
<script src="{{ asset('admin/assets/vendor/select2/select2.min.js') }}"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

<script src="{{ asset('admin/assets/js/main.js')}}"></script>
</body>
</html>
