{{--
  Navbar principal - alinhada ao design system Bluefish
  - Acessibilidade WCAG 2.2 AA
  - Mobile-first com hamburguer em 768px
  - Semântica HTML5 com landmarks e ARIA
--}}
<header class="navbar" id="navbar" role="banner" data-navbar data-open="false">
  <div class="navbar__inner">
    {{-- Logo e assinatura da marca --}}
    <a href="{{ url('/') }}" class="navbar__brand" aria-label="Bluefish - Página inicial">
      <img src="{{ asset('img/pexe.png') }}"
           alt="Bluefish"
           width="120"
           height="40"
           loading="eager">
      <div class="navbar__brand-copy">
        <span class="navbar__brand-name">Bluefish</span>
        <span class="navbar__brand-tagline">Frescor e logística inteligente para o seu negócio</span>
      </div>
    </a>

    {{-- Botão mobile hamburger --}}
    <button type="button"
            class="navbar__toggle"
            data-navbar-toggle
            aria-expanded="false"
            aria-controls="menu-principal"
            aria-label="Alternar navegação">
      <span class="navbar__toggle-icon" aria-hidden="true"></span>
    </button>

    {{-- Menu de navegação principal --}}
    <nav class="navbar__menu" id="menu-principal" aria-label="Menu principal">
      <div class="navbar__menu-content">
        <ul class="navbar__list" role="menubar">
          {{-- Link: Início --}}
          <li role="none">
            <a href="{{ route('home') }}"
               class="navbar__link {{ request()->routeIs('home') ? 'is-active' : '' }}"
               role="menuitem"
               @if(request()->routeIs('home')) aria-current="page" @endif>
              Início
            </a>
          </li>

          {{-- Link: Produtos --}}
          <li role="none">
            <a href="{{ route('produtos.index') }}"
               class="navbar__link {{ request()->routeIs('produtos.*') ? 'is-active' : '' }}"
               role="menuitem"
               @if(request()->routeIs('produtos.*')) aria-current="page" @endif>
              Nossa Seleção
            </a>
          </li>

          {{-- Link: Contato --}}
          <li role="none">
            <a href="{{ route('contato.form') }}"
               class="navbar__link {{ request()->routeIs('contato.*') ? 'is-active' : '' }}"
               role="menuitem"
               @if(request()->routeIs('contato.*')) aria-current="page" @endif>
              Fale Conosco
            </a>
          </li>

          {{-- Links estáticos --}}
          <li role="none">
            <a href="#receitas" class="navbar__link" role="menuitem">Receitas</a>
          </li>
          <li role="none">
            <a href="#sobre" class="navbar__link" role="menuitem">Sobre</a>
          </li>

          {{-- Links autenticados --}}
          @auth
            <li role="none">
              <a href="{{ route('vendas.index') }}"
                 class="navbar__link {{ request()->routeIs('vendas.*') ? 'is-active' : '' }}"
                 role="menuitem"
                 @if(request()->routeIs('vendas.*')) aria-current="page" @endif>
                Minhas Compras
              </a>
            </li>

            <li role="none">
              <a href="{{ route('admin.dashboard') }}"
                 class="navbar__link {{ request()->routeIs('admin.*') ? 'is-active' : '' }}"
                 role="menuitem"
                 @if(request()->routeIs('admin.*')) aria-current="page" @endif>
                Painel
              </a>
            </li>
          @endauth
        </ul>

        <div class="navbar__meta" role="presentation">
          <p class="navbar__status">
            <span class="navbar__pill">
              <i class="fas fa-temperature-low" aria-hidden="true"></i>
              Cadeia do frio ativa
            </span>
            <span>Equipe dedicada para pedidos, rastreamento e suporte em todo o Brasil.</span>
          </p>

          {{-- Área de ações do usuário --}}
          <div class="navbar__actions" role="region" aria-label="Ações do usuário">
            @auth
              {{-- Usuário autenticado --}}
              <div class="user-welcome">
                <span class="welcome-message" role="status" aria-live="polite">
                  Olá, <strong>{{ auth()->user()->name }}</strong>! Aproveite o melhor do mar.
                </span>

                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                  @csrf
                  <button type="submit" class="logout-btn" aria-label="Sair da conta">
                    <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
                    <span>Sair</span>
                  </button>
                </form>
              </div>
            @else
              {{-- Visitante - Botões de autenticação --}}
              <div class="auth-buttons">
                <x-ui.button href="{{ route('login.form') }}"
                            variant="outline"
                            size="small"
                            icon="arrow-right"
                            iconPosition="right">
                  Entrar
                </x-ui.button>

                <x-ui.button href="{{ route('register.form') }}"
                            variant="primary"
                            size="small"
                            icon="plus"
                            iconPosition="right">
                  Criar conta
                </x-ui.button>
              </div>
            @endauth

            <a href="{{ route('contato.form') }}" class="navbar__cta-link">
              <span>Solicitar cotação</span>
              <i class="fas fa-arrow-right" aria-hidden="true"></i>
            </a>
          </div>
        </div>
      </div>
    </nav>
  </div>
</header>
