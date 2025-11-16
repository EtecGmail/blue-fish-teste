<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Bluefish')</title>
    @php($hasViteManifest = file_exists(public_path('build/manifest.json')))
    @if($hasViteManifest)
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    @endif
    @stack('styles')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <a href="#conteudo-admin" class="skip-link">Ir para o conteúdo principal</a>

    <header class="admin-topbar" role="banner">
        <div class="admin-topbar__inner">
            <button
                type="button"
                class="admin-topbar__toggle"
                data-admin-sidebar-toggle
                aria-controls="menu-administrativo"
                aria-expanded="false"
            >
                <span class="sr-only">Alternar menu administrativo</span>
                <span class="admin-topbar__toggle-icon" aria-hidden="true"></span>
            </button>

            <a href="{{ route('admin.index') }}" class="admin-topbar__brand">Bluefish Admin</a>

            <div class="admin-topbar__actions">
                @auth
                    <span class="admin-topbar__user" role="status">
                        <i class="fas fa-user-circle" aria-hidden="true"></i>
                        <span>{{ auth()->user()->name }}</span>
                    </span>
                    <form action="{{ route('logout') }}" method="POST" class="admin-topbar__logout">
                        @csrf
                        <button type="submit" class="logout-btn">
                            <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
                            <span>Sair</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login.form') }}" class="btn btn-secondary btn-sm">Entrar</a>
                @endauth
            </div>
        </div>
    </header>

    <div class="admin-layout">
        <aside
            class="admin-sidebar"
            aria-label="Menu administrativo"
            data-admin-sidebar
            data-open="false"
            id="menu-administrativo"
        >
            <div class="admin-sidebar__header">
                <a href="{{ route('admin.index') }}" class="admin-sidebar__brand">Bluefish Admin</a>
                <button type="button" class="admin-sidebar__close" data-admin-sidebar-close>
                    <span class="sr-only">Fechar menu administrativo</span>
                    <i class="fas fa-times" aria-hidden="true"></i>
                </button>
            </div>

            <nav class="admin-sidebar__nav" aria-label="Seções do painel">
                <ul class="admin-menu">
                    <li class="admin-menu-item">
                        <a
                            href="{{ route('admin.dashboard') }}"
                            class="admin-menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                            @if(request()->routeIs('admin.dashboard')) aria-current="page" @endif
                        >
                            <i class="fas fa-gauge" aria-hidden="true"></i> Painel
                        </a>
                    </li>
                    <li class="admin-menu-item">
                        <a
                            href="{{ Route::has('admin.products.index') ? route('admin.products.index') : '#' }}"
                            class="admin-menu-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}"
                            @if(request()->routeIs('admin.products.*')) aria-current="page" @endif
                        >
                            <i class="fas fa-fish" aria-hidden="true"></i> Produtos
                        </a>
                    </li>
                    <li class="admin-menu-item">
                        <a
                            href="{{ Route::has('admin.users.index') ? route('admin.users.index') : '#' }}"
                            class="admin-menu-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                            @if(request()->routeIs('admin.users.*')) aria-current="page" @endif
                        >
                            <i class="fas fa-users" aria-hidden="true"></i> Usuários
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <div class="admin-sidebar-backdrop" data-admin-sidebar-backdrop hidden></div>

        <main class="admin-content" id="conteudo-admin">
            @if(session('sucesso') || session('erro') || $errors->any())
                <div class="page-shell" style="margin-bottom: 1.5rem;">
                    @if(session('sucesso'))
                        <div class="alert alert-success" role="status" aria-live="polite">
                            <i class="fas fa-check-circle" aria-hidden="true"></i>
                            {{ session('sucesso') }}
                        </div>
                    @endif

                    @if(session('erro'))
                        <div class="alert alert-error" role="alert" aria-live="assertive">
                            <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                            {{ session('erro') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-error" role="alert" aria-live="assertive">
                            <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            @endif

            @yield('content')
        </main>
    </div>
    @yield('scripts')
</body>
</html>


