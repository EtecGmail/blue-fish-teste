{{--
  Componente Card - Baseado no Guia Yuri Garcia Pardinho
  
  Principios aplicados:
  - Semântica HTML5 com article
  - Acessibilidade com headings hierárquicos
  - Design tokens para consistência visual
  - Estados interativos claros
  
  @props [
    'title' => null,
    'subtitle' => null,
    'image' => null,
    'imageAlt' => '',
    'href' => null,
    'tags' => [],
    'footer' => null,
    'variant' => 'default', // default, highlighted, compact
    'interactive' => false
  ]
--}}

@php
  $cardClasses = [
    'default' => 'card',
    'highlighted' => 'card card--highlighted',
    'compact' => 'card card--compact'
  ];
  
  $cardClass = $cardClasses[$variant] ?? $cardClasses['default'];
  
  // Se for interativo (clicável), adiciona classe e atributos apropriados
  if ($interactive && $href) {
    $cardClass .= ' card--interactive';
  }
@endphp

<article class="{{ $cardClass }}">
  @if($href && $interactive)
    <a href="{{ $href }}" class="card__link" aria-label="{{ $title }}">
      <span class="card__link-overlay"></span>
    </a>
  @endif

  {{-- Imagem do card --}}
  @if($image)
    <div class="card__image-container">
      <img src="{{ $image }}" 
           alt="{{ $imageAlt }}" 
           class="card__image"
           loading="lazy">
    </div>
  @endif

  {{-- Conteúdo do card --}}
  <div class="card__content">
    @if($title)
      <h3 class="card__title">
        @if($href && !$interactive)
          <a href="{{ $href }}" class="card__title-link">{{ $title }}</a>
        @else
          {{ $title }}
        @endif
      </h3>
    @endif

    @if($subtitle)
      <p class="card__subtitle">{{ $subtitle }}</p>
    @endif

    {{-- Slot para conteúdo principal --}}
    <div class="card__body">
      {{ $slot }}
    </div>

    {{-- Tags/categorias --}}
    @if(!empty($tags))
      <div class="card__tags">
        @foreach($tags as $tag)
          <span class="card__tag">{{ $tag }}</span>
        @endforeach
      </div>
    @endif
  </div>

  {{-- Rodapé do card --}}
  @if($footer)
    <div class="card__footer">
      {{ $footer }}
    </div>
  @endif
</article>

{{-- Estilos específicos do card --}}
@push('styles')
  <style>
    /*
     * Estilos do Card Component
     * Seguindo design tokens e principios de acessibilidade
     */
    
    .card {
      --card-border-radius: var(--radius-lg, 8px);
      --card-shadow: var(--shadow-sm, 0 1px 3px rgba(0, 0, 0, 0.1));
      --card-padding: var(--spacing-4, 1rem);
      --card-bg: var(--color-white, #ffffff);
      --card-border: var(--color-gray-200, #e5e7eb);
      --card-transition: var(--transition-base, 0.2s ease);
      
      background: var(--card-bg);
      border: 1px solid var(--card-border);
      border-radius: var(--card-border-radius);
      box-shadow: var(--card-shadow);
      overflow: hidden;
      transition: all var(--card-transition);
      position: relative;
    }
    
    .card:hover {
      box-shadow: var(--shadow-md, 0 4px 6px rgba(0, 0, 0, 0.1));
      transform: translateY(-2px);
    }
    
    .card--highlighted {
      --card-border: var(--color-primary-200, #bfdbfe);
      --card-shadow: var(--shadow-md, 0 4px 6px rgba(0, 0, 0, 0.1));
      
      border-width: 2px;
    }
    
    .card--compact {
      --card-padding: var(--spacing-3, 0.75rem);
    }
    
    .card--interactive {
      cursor: pointer;
    }
    
    .card--interactive:hover {
      box-shadow: var(--shadow-lg, 0 10px 15px rgba(0, 0, 0, 0.1));
    }
    
    .card__link {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      z-index: 1;
      text-decoration: none;
    }
    
    .card__link-overlay {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
    }
    
    .card__image-container {
      aspect-ratio: 16/9;
      overflow: hidden;
      background: var(--color-gray-100, #f3f4f6);
    }
    
    .card__image {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform var(--card-transition);
    }
    
    .card:hover .card__image {
      transform: scale(1.05);
    }
    
    .card__content {
      padding: var(--card-padding);
    }
    
    .card__title {
      margin: 0 0 var(--spacing-2, 0.5rem) 0;
      font-size: var(--font-size-lg, 1.125rem);
      font-weight: 600;
      line-height: 1.3;
      color: var(--color-gray-900, #111827);
    }
    
    .card__title-link {
      color: inherit;
      text-decoration: none;
      position: relative;
      z-index: 2;
    }
    
    .card__title-link:hover {
      color: var(--color-primary-600, #2563eb);
      text-decoration: underline;
    }
    
    .card__subtitle {
      margin: 0 0 var(--spacing-3, 0.75rem) 0;
      font-size: var(--font-size-sm, 0.875rem);
      color: var(--color-gray-600, #4b5563);
      line-height: 1.5;
    }
    
    .card__body {
      color: var(--color-gray-700, #374151);
      line-height: 1.6;
      margin-bottom: var(--spacing-3, 0.75rem);
    }
    
    .card__tags {
      display: flex;
      flex-wrap: wrap;
      gap: var(--spacing-1, 0.25rem);
      margin-top: var(--spacing-3, 0.75rem);
    }
    
    .card__tag {
      display: inline-block;
      padding: var(--spacing-1, 0.25rem) var(--spacing-2, 0.5rem);
      font-size: var(--font-size-xs, 0.75rem);
      font-weight: 500;
      color: var(--color-primary-700, #1d4ed8);
      background: var(--color-primary-100, #dbeafe);
      border-radius: var(--radius-sm, 4px);
      text-transform: uppercase;
      letter-spacing: 0.025em;
    }
    
    .card__footer {
      padding: var(--spacing-3, 0.75rem) var(--card-padding);
      background: var(--color-gray-50, #f9fafb);
      border-top: 1px solid var(--card-border);
      font-size: var(--font-size-sm, 0.875rem);
      color: var(--color-gray-600, #4b5563);
    }
    
    /* Acessibilidade: foco visível */
    .card__link:focus-visible {
      outline: 2px solid var(--color-primary-500, #3b82f6);
      outline-offset: 2px;
    }
    
    .card__title-link:focus-visible {
      outline: 2px solid var(--color-primary-500, #3b82f6);
      outline-offset: 2px;
      border-radius: var(--radius-sm, 4px);
    }
    
    /* Responsividade */
    @media (max-width: 768px) {
      .card__content {
        padding: var(--spacing-3, 0.75rem);
      }
      
      .card__title {
        font-size: var(--font-size-base, 1rem);
      }
      
      .card__subtitle,
      .card__body {
        font-size: var(--font-size-sm, 0.875rem);
      }
    }
  </style>
@endpush