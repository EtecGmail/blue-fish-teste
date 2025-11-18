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
    {{-- Navegação principal como componente reutilizável --}}
    <x-common.navbar />

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
    {{-- Rodapé institucional como componente reutilizável --}}
    <x-common.footer />

    @include('cookie-consent::index')

    @yield('scripts')
</body>
</html>
