{{--
  Componente Navbar - Baseado no Guia Yuri Garcia Pardinho
  
  Principios aplicados:
  - Acessibilidade WCAG 2.2 AA
  - Mobile-first design
  - Semantic HTML5
  - ARIA labels apropriados
  - Foco visível para navegação por teclado
--}}

<nav class="navbar" role="navigation" aria-label="Navegação principal">
  <div class="navbar__container">
    {{-- Logo com link para home --}}
    <div class="navbar__brand">
      <a href="{{ route('home') }}" class="navbar__logo" aria-label="Página inicial">
        <img src="{{ asset('img/logo.svg') }}" alt="Logotipo" width="120" height="40">
      </a>
    </div>

    {{-- Menu principal desktop --}}
    <div class="navbar__menu" id="navbar-menu">
      <ul class="navbar__nav" role="menubar">
        <li class="navbar__item" role="none">
          <a href="{{ route('produtos.index') }}" 
             class="navbar__link {{ request()->routeIs('produtos.*') ? 'navbar__link--active' : '' }}"
             role="menuitem"
             aria-current="{{ request()->routeIs('produtos.*') ? 'page' : 'false' }}">
            Produtos
          </a>
        </li>
        <li class="navbar__item" role="none">
          <a href="{{ route('vendas.index') }}" 
             class="navbar__link {{ request()->routeIs('vendas.*') ? 'navbar__link--active' : '' }}"
             role="menuitem"
             aria-current="{{ request()->routeIs('vendas.*') ? 'page' : 'false' }}">
            Vendas
          </a>
        </li>
        <li class="navbar__item" role="none">
          <a href="{{ route('contato.index') }}" 
             class="navbar__link {{ request()->routeIs('contato.*') ? 'navbar__link--active' : '' }}"
             role="menuitem"
             aria-current="{{ request()->routeIs('contato.*') ? 'page' : 'false' }}">
            Contato
          </a>
        </li>
        
        {{-- Links adicionais para usuários autenticados --}}
        @auth
          <li class="navbar__item" role="none">
            <a href="{{ route('admin.dashboard') }}" 
               class="navbar__link {{ request()->routeIs('admin.*') ? 'navbar__link--active' : '' }}"
               role="menuitem"
               aria-current="{{ request()->routeIs('admin.*') ? 'page' : 'false' }}">
              Admin
            </a>
          </li>
        @endauth
      </ul>
    </div>

    {{-- Area do usuário --}}
    <div class="navbar__user-area">
      @auth
        {{-- Usuário autenticado --}}
        <div class="navbar__user-menu">
          <button class="navbar__user-toggle" 
                  id="user-menu-toggle"
                  aria-expanded="false"
                  aria-haspopup="true"
                  aria-label="Menu do usuário">
            <span class="navbar__user-name">{{ auth()->user()->name }}</span>
            <svg class="navbar__user-icon" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </button>
          
          <div class="navbar__user-dropdown" id="user-dropdown" hidden>
            <a href="{{ route('logout') }}" 
               class="navbar__user-link"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              Sair
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
              @csrf
            </form>
          </div>
        </div>
      @else
        {{-- Visitante --}}
        <div class="navbar__auth">
          <a href="{{ route('login') }}" class="navbar__link navbar__link--outline">
            Entrar
          </a>
          <a href="{{ route('register') }}" class="navbar__btn navbar__btn--primary">
            Cadastrar
          </a>
        </div>
      @endauth
    </div>

    {{-- Botão mobile hamburger --}}
    <button class="navbar__toggle" 
            id="navbar-toggle"
            aria-expanded="false"
            aria-controls="navbar-menu"
            aria-label="Alternar menu de navegação">
      <span class="navbar__toggle-line"></span>
      <span class="navbar__toggle-line"></span>
      <span class="navbar__toggle-line"></span>
    </button>
  </div>
</nav>

{{-- Skip link para conteúdo principal --}}
<a href="#main-content" class="skip-link">Ir para o conteúdo principal</a>