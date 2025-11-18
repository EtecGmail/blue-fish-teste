<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('description', 'Bluefish - Produtos do mar frescos e de alta qualidade para seu negócio')">
    <meta name="theme-color" content="#0ea5e9">
    
    {{-- Segurança e compatibilidade --}}
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="index,follow">
    
    <title>@yield('title', 'Bluefish - O frescor do mar para seu negócio')</title>
    
    {{-- Preload de recursos críticos --}}
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" as="style">
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    
    @php($hasViteManifest = file_exists(public_path('build/manifest.json')))
    @if($hasViteManifest)
        @vite(['resources/css/app.css', 'resources/js/app.js'], 'build', ['nonce' => app('csp-nonce')])
    @else
        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    @endif
    @stack('styles')
    
    {{-- Fontes e ícones - carregamento otimizado --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" media="print" onload="this.media='all'">
    
    {{-- Ícone da aplicação --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon.svg') }}">
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
</head>
<body class="@yield('body-class')">
    <a href="#conteudo-principal" class="skip-link">Ir para o conteúdo principal</a>

    {{-- 
      Header com navegação principal
      Implementado seguindo princípios de acessibilidade WCAG 2.2 AA
      e design mobile-first do Guia Yuri Garcia Pardinho
    --}}
    <header class="navbar" id="navbar" data-navbar data-open="false">
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

                @if(auth()->user()->is_admin ?? false)
                  <li role="none">
                    <a href="{{ route('admin.dashboard') }}"
                       class="navbar__link {{ request()->routeIs('admin.*') ? 'is-active' : '' }}"
                       role="menuitem"
                       @if(request()->routeIs('admin.*')) aria-current="page" @endif>
                      Painel Admin
                    </a>
                  </li>
                @endif
              @endauth
            </ul>

            <div class="navbar__meta">
              <p class="navbar__status">
                <span class="navbar__pill">Atendimento 24/7</span>
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

    {{-- 
      Conteúdo principal da aplicação
      Implementado com foco em acessibilidade e semântica HTML5
    --}}
    <main id="conteudo-principal" class="page-content @yield('main-class')">
      {{-- 
        Sistema de notificações e alertas
        Usa componente moderno com acessibilidade e animações suaves
      --}}
      @if(session('sucesso') || session('erro') || $errors->any())
        <div class="page-shell">
          @if(session('sucesso'))
            <x-ui.alert type="success" 
                       icon="true" 
                       dismissible="true"
                       aria-live="polite">
              {{ session('sucesso') }}
            </x-ui.alert>
          @endif

          @if(session('erro'))
            <x-ui.alert type="error" 
                       icon="true" 
                       dismissible="true"
                       aria-live="assertive">
              {{ session('erro') }}
            </x-ui.alert>
          @endif

          @if($errors->any())
            <x-ui.alert type="error" 
                       icon="true" 
                       dismissible="true"
                       title="Erro de validação"
                       aria-live="assertive">
              <ul class="error-list">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </x-ui.alert>
          @endif
        </div>
      @endif

        @hasSection('full-width')
            @yield('full-width')
        @endif

        @hasSection('content')
            <div class="page-shell">
                @yield('content')
            </div>
        @endif
    </main>

    {{-- 
      Rodapé com informações institucionais
      Estruturado semanticamente com HTML5 e acessibilidade
    --}}
    <footer class="footer" role="contentinfo">
      <div class="footer__container">
        <div class="footer__grid">
          {{-- Seção sobre a empresa --}}
          <section class="footer__section footer__section--brand" aria-labelledby="footer-about">
            <div class="footer__brand" id="footer-about">
              <img src="{{ asset('img/pexe.png') }}" alt="Logo Bluefish" width="72" height="72" loading="lazy">
              <div>
                <p class="footer__brand-name">Bluefish</p>
                <p class="footer__brand-tagline">Frescor, rastreabilidade e logística sustentável para o seu negócio.</p>
              </div>
            </div>
            <p class="footer__description">
              Operamos com cadeias frias certificadas e parceiros auditados, garantindo produtos premium do mar com impacto ambiental reduzido.
            </p>
          </section>

          {{-- Seção de links úteis --}}
          <section class="footer__section" aria-labelledby="footer-links">
            <h3 id="footer-links" class="footer__title">Links úteis</h3>
            <ul class="footer__links">
              <li><a href="{{ route('home') }}" class="footer__link">Página inicial</a></li>
              <li><a href="{{ route('produtos.index') }}" class="footer__link">Catálogo completo</a></li>
              <li><a href="{{ route('contato.form') }}" class="footer__link">Contato e cotações</a></li>
              <li><a href="{{ route('vendas.index') }}" class="footer__link">Pedidos recentes</a></li>
              <li><a href="{{ route('legal.privacidade') }}" class="footer__link">Privacidade</a></li>
            </ul>
          </section>

          {{-- Seção de contato e dados da empresa --}}
          <section class="footer__section" aria-labelledby="footer-contact">
            <h3 id="footer-contact" class="footer__title">Contato</h3>
            <address class="footer__contact">
              <p class="footer__contact-item">
                <i class="fas fa-phone" aria-hidden="true"></i>
                <a href="tel:+551112345678" class="footer__link">(11) 1234-5678</a>
              </p>
              <p class="footer__contact-item">
                <i class="fas fa-envelope" aria-hidden="true"></i>
                <a href="mailto:contato@bluefish.com" class="footer__link">contato@bluefish.com</a>
              </p>
              <p class="footer__contact-item">
                <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                Rua Exemplo, 123 - São Paulo/SP, CEP 00000-000
              </p>
              <p class="footer__contact-item">
                <i class="fas fa-id-card" aria-hidden="true"></i>
                CNPJ: 00.000.000/0001-00
              </p>
            </address>
          </section>

          {{-- Seção de redes sociais --}}
          <section class="footer__section" aria-labelledby="footer-social">
            <h3 id="footer-social" class="footer__title">Redes sociais</h3>
            <p class="footer__social-text">Conteúdos diários, bastidores da pesca e alertas de disponibilidade.</p>
            <nav class="footer__social-nav" aria-label="Redes sociais da Bluefish">
              <ul class="footer__social-list">
                <li>
                  <a href="#" class="footer__social-link" aria-label="Facebook da Bluefish">
                    <i class="fab fa-facebook-f" aria-hidden="true"></i>
                  </a>
                </li>
                <li>
                  <a href="#" class="footer__social-link" aria-label="Instagram da Bluefish">
                    <i class="fab fa-instagram" aria-hidden="true"></i>
                  </a>
                </li>
                <li>
                  <a href="#" class="footer__social-link" aria-label="Twitter da Bluefish">
                    <i class="fab fa-twitter" aria-hidden="true"></i>
                  </a>
                </li>
                <li>
                  <a href="#" class="footer__social-link" aria-label="LinkedIn da Bluefish">
                    <i class="fab fa-linkedin-in" aria-hidden="true"></i>
                  </a>
                </li>
              </ul>
            </nav>
          </section>
        </div>

        <div class="footer__divider" aria-hidden="true"></div>

        {{-- Informações legais --}}
        <div class="footer__bottom">
          <p class="footer__copyright">
            &copy; {{ date('Y') }} Bluefish. Operação licenciada. Todos os direitos reservados.
          </p>
          <p class="footer__compliance">Cumprimos LGPD, rastreabilidade e controles sanitários brasileiros.</p>
          <nav class="footer__legal-nav" aria-label="Links legais">
            <ul class="footer__legal-list">
              <li><a href="{{ route('legal.privacidade') }}" class="footer__legal-link">Política de Privacidade</a></li>
              <li><a href="{{ route('legal.termos') }}" class="footer__legal-link">Termos de Uso</a></li>
              <li><a href="{{ route('home') }}#lgpd" class="footer__legal-link">LGPD</a></li>
            </ul>
          </nav>
        </div>
      </div>
    </footer>

    @yield('scripts')
</body>
</html>
