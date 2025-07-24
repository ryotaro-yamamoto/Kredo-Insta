<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name')}} | @yield('title')</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap" rel="stylesheet">

    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @livewireStyles

    <!-- Bootstrap 5 JS Bundle (includes Popper) -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> --}}

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <h1 class="h2 mb-0 fw-bold fancy-font">{{ config('app.name') }}</h1>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                     <!-- [SOON] Search bar here. -->
                     @auth
                         @if(request()->is('admin/users'))
                            <ul class="navbar-nav ms-auto">
                                <form action="{{route('admin.search.users')}}" style="width: 300px">
                                    <input type="search" name="search_users" class="form-control form-control-sm" placeholder="Admin Search...">
                                </form>
                            </ul>
                            @endif
                        @endauth
                        @auth                             
                            @if(request()->is('admin/posts'))
                            <ul class="navbar-nav ms-auto">
                                <form action="{{route('admin.search.posts')}}" style="width: 300px">
                                    <input type="search" name="search_posts" class="form-control form-control-sm" placeholder="Admin Search...">
                                </form>
                            </ul>
                         @endif
                     @endauth
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (!request()->is('login') && !request()->is('register'))
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
                            @endif
                        @else
                            <!-- Search -->
                            <li class="nav-item" title="Search">
                                <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#searchModal">
                                    <i class="fa-solid fa-magnifying-glass text-dark icon-sm"></i>
                                </a>
                            </li>
                            <!-- Home -->
                            <li class="nav-item" title="Home">
                                <a href="{{ route('index') }}" class="nav-link"><i class="fa-solid fa-house text-dark icon-sm"></i></a>
                            </li>
                            <!-- Create post -->
                            <li class="nav-item" title="Create Post">
                                <a href="{{ route('post.create') }}" class="nav-link"><i class="fa-solid fa-pen-to-square text-dark icon-sm"></i></a>
                            </li>
                            <!-- Account -->
                            <li class="nav-item dropdown">
                                <button id="account-dropdown" class="btn shadow-none nav-link" data-bs-toggle="dropdown">
                                    @if(Auth::user()->avatar)
                                        <img src="{{Auth::user()->avatar}}" alt="{{ Auth::user()->name }}" class="rounded-circle avatar-sm border border-secondary border-opacity-25">
                                    @else
                                        <i class="fa-solid fa-circle-user text-dark icon-sm"></i>
                                    @endif
                                </button>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="account-dropdown">
                                    <!-- [SOON] Admin Controls -->
                                    @can('admin')

                                    <a href="{{route('admin.users')}}" class="dropdown-item">
                                        <i class="fa-solid fa-user-gear"></i> Admin
                                    </a>
                                    
                                    <hr class="dropdown-divider">
                                    @endcan

                                    {{-- Profile --}}
                                    <a href="{{ route('profile.show', Auth::user()->id) }}" class="dropdown-item">
                                        <i class="fa-solid fa-circle-user"></i> Profile
                                    </a>

                                    {{-- Logout --}}
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fa-solid fa-right-from-bracket"></i> {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-5">
            <div class="container">
                <div class="row justify-content-center">
                    {{-- [SOON]Admin Menu(col-3) --}}
                    @if (request()->is('admin/*'))
                        <div class="col-3">
                            <div class="list-group">
                                <a href="{{route('admin.users')}}" class="list-group-item {{request()->is('admin/users') ? 'active' : ''}}">
                                    <i class="fa-solid fa-users"></i> Users
                                </a>
                                <a href="{{route('admin.posts')}}" class="list-group-item {{request()->is('admin/posts') ? 'active' : ''}}">
                                    <i class="fa-solid fa-newspaper"></i> Posts
                                </a>
                                <a href="{{route('admin.categories')}}" class="list-group-item {{request()->is('admin/categories') ? 'active' : ''}}">
                                    <i class="fa-solid fa-tags"></i> Categories
                                </a>
                            </div>
                        </div>
                    @else
                        @if (isset($categories))
                            <div class="col-3">
                                <div class="position-sticky" style="top: 80px;">
                                    <div class="card shadow-sm p-3 w-75">
                                        <form method="GET" action="{{ route('index') }}">
                                            <h5 class="mb-3 fw-bold">Filter by Category</h5>
                                            @foreach ($categories as $category)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $category->id }}" id="category{{ $category->id }}" {{ in_array($category->id, $categoryIds) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="category{{ $category->id }}">
                                                        {{ $category->name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                            <button type="submit" class="btn btn-sm btn-primary mt-2 w-100 fw-bold">Filter</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif

                    <div class="col-9">
                        @yield('content')
                    </div>
                </div>
            </div>
        </main>
    </div>
    <!-- Search Modal -->
    <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
        <div class="modal-dialog custom-position">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title" id="searchModalLabel">Search Users</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('search') }}" method="GET">
                    <div class="modal-body">
                        <input type="search" name="search" class="form-control" placeholder="Search for users...">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        //Modal用のautofocus
        document.addEventListener('DOMContentLoaded', function () {
            const searchModal = document.getElementById('searchModal');
            searchModal.addEventListener('shown.bs.modal', function () {
                const input = searchModal.querySelector('input[name="search"]');
                if (input) {
                    input.focus();
                }
            });
        });
    </script>
    @livewireScripts
</body>
</html>
