{{--
  Componente Button - Baseado no Guia Yuri Garcia Pardinho
  
  Principios aplicados:
  - Semântica HTML5 apropriada
  - Acessibilidade com ARIA labels
  - Estados interativos claros
  - Suporte para diferentes variações
  
  @props [
    'type' => 'button', // button, submit, reset
    'variant' => 'primary', // primary, secondary, outline, ghost, danger
    'size' => 'medium', // small, medium, large
    'href' => null, // Se definido, renderiza como link
    'disabled' => false,
    'loading' => false,
    'icon' => null, // Nome do ícone ou false
    'iconPosition' => 'left', // left, right
    'fullWidth' => false, // quando true, ocupa 100% da largura
    'ariaLabel' => null
  ]
--}}

@php
  $type = $type ?? 'button';
  $variant = $variant ?? 'primary';
  $size = $size ?? 'medium';
  $href = $href ?? null;
  $disabled = $disabled ?? false;
  $loading = $loading ?? false;
  $icon = $icon ?? null;
  $iconPosition = $iconPosition ?? 'left';
  $fullWidth = $fullWidth ?? false;
  $ariaLabel = $ariaLabel ?? null;

  $buttonVariants = [
    'primary' => 'btn--primary',
    'secondary' => 'btn--secondary', 
    'outline' => 'btn--outline',
    'ghost' => 'btn--ghost',
    'danger' => 'btn--danger'
  ];
  
  $buttonSizes = [
    'small' => 'btn--small',
    'medium' => 'btn--medium',
    'large' => 'btn--large'
  ];
  
  $baseClasses = 'btn';
  $variantClass = $buttonVariants[$variant] ?? $buttonVariants['primary'];
  $sizeClass = $buttonSizes[$size] ?? $buttonSizes['medium'];
  $widthClass = $fullWidth ? 'btn--full-width' : '';
  $disabledClass = ($disabled || $loading) ? 'btn--disabled' : '';
  
  $classes = implode(' ', array_filter([
    $baseClasses,
    $variantClass,
    $sizeClass,
    $widthClass,
    $disabledClass
  ]));
  
  // Ícones disponíveis
  $icons = [
    'arrow-right' => '<path d="M13.293 6.293L7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293-1.414-1.414z"/>',
    'arrow-left' => '<path d="M10.707 6.293L5 12l5.707 5.707 1.414-1.414L7.828 12l4.293-4.293-1.414-1.414z"/>',
    'plus' => '<path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
    'check' => '<path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>',
    'loading' => '<path d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>',
    'external' => '<path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>'
  ];
  
  $iconSvg = $icon && isset($icons[$icon]) ? $icons[$icon] : null;
  
  $ariaLabel = $ariaLabel ?? ($loading ? 'Carregando...' : $slot->toHtml());
@endphp

@if($href && !$disabled)
  {{-- Renderiza como link --}}
  <a href="{{ $href }}" 
     class="{{ $classes }}"
     @if($ariaLabel) aria-label="{{ $ariaLabel }}" @endif
     @if($loading) aria-busy="true" @endif>
    
    {{-- Ícone à esquerda --}}
    @if($iconSvg && $iconPosition === 'left')
      <svg class="btn__icon btn__icon--left" 
           width="16" 
           height="16" 
           viewBox="0 0 16 16" 
           fill="currentColor"
           @if($loading) class="btn__icon--spinning" @endif
           aria-hidden="true">
        {!! $iconSvg !!}
      </svg>
    @endif
    
    <span class="btn__text">{{ $slot }}</span>
    
    {{-- Ícone à direita --}}
    @if($iconSvg && $iconPosition === 'right')
      <svg class="btn__icon btn__icon--right" 
           width="16" 
           height="16" 
           viewBox="0 0 16 16" 
           fill="currentColor"
           @if($loading) class="btn__icon--spinning" @endif
           aria-hidden="true">
        {!! $iconSvg !!}
      </svg>
    @endif
  </a>
@else
  {{-- Renderiza como botão --}}
  <button type="{{ $type }}"
          class="{{ $classes }}"
          @if($disabled) disabled @endif
          @if($ariaLabel) aria-label="{{ $ariaLabel }}" @endif
          @if($loading) aria-busy="true" @endif>
    
    {{-- Ícone à esquerda --}}
    @if($iconSvg && $iconPosition === 'left')
      <svg class="btn__icon btn__icon--left" 
           width="16" 
           height="16" 
           viewBox="0 0 16 16" 
           fill="currentColor"
           @if($loading) class="btn__icon--spinning" @endif
           aria-hidden="true">
        {!! $iconSvg !!}
      </svg>
    @endif
    
    <span class="btn__text">{{ $slot }}</span>
    
    {{-- Ícone à direita --}}
    @if($iconSvg && $iconPosition === 'right')
      <svg class="btn__icon btn__icon--right" 
           width="16" 
           height="16" 
           viewBox="0 0 16 16" 
           fill="currentColor"
           @if($loading) class="btn__icon--spinning" @endif
           aria-hidden="true">
        {!! $iconSvg !!}
      </svg>
    @endif
  </button>
@endif

@push('styles')
  <style>
    /*
     * Estilos do Button Component
     * Seguindo design tokens e principios de acessibilidade
     */
    
    .btn {
      --btn-border-radius: var(--radius-md, 6px);
      --btn-font-weight: 500;
      --btn-transition: var(--transition-base, 0.2s ease);
      --btn-focus-ring: 0 0 0 2px var(--color-white), 0 0 0 4px var(--color-primary-500);
      
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: var(--spacing-2, 0.5rem);
      font-weight: var(--btn-font-weight);
      text-decoration: none;
      border: 1px solid transparent;
      border-radius: var(--btn-border-radius);
      cursor: pointer;
      transition: all var(--btn-transition);
      position: relative;
      user-select: none;
      white-space: nowrap;
    }
    
    /* Tamanhos */
    .btn--small {
      padding: var(--spacing-2, 0.5rem) var(--spacing-3, 0.75rem);
      font-size: var(--font-size-sm, 0.875rem);
      line-height: 1.25rem;
    }
    
    .btn--medium {
      padding: var(--spacing-3, 0.75rem) var(--spacing-4, 1rem);
      font-size: var(--font-size-base, 1rem);
      line-height: 1.5rem;
    }
    
    .btn--large {
      padding: var(--spacing-4, 1rem) var(--spacing-6, 1.5rem);
      font-size: var(--font-size-lg, 1.125rem);
      line-height: 1.75rem;
    }
    
    /* Variações de estilo */
    .btn--primary {
      background: var(--color-primary-600, #2563eb);
      color: var(--color-white, #ffffff);
    }
    
    .btn--primary:hover:not(:disabled) {
      background: var(--color-primary-700, #1d4ed8);
    }
    
    .btn--secondary {
      background: var(--color-gray-200, #e5e7eb);
      color: var(--color-gray-900, #111827);
      border-color: var(--color-gray-300, #d1d5db);
    }
    
    .btn--secondary:hover:not(:disabled) {
      background: var(--color-gray-300, #d1d5db);
    }
    
    .btn--outline {
      background: transparent;
      color: var(--color-primary-600, #2563eb);
      border-color: var(--color-primary-600, #2563eb);
    }
    
    .btn--outline:hover:not(:disabled) {
      background: var(--color-primary-50, #eff6ff);
    }
    
    .btn--ghost {
      background: transparent;
      color: var(--color-gray-700, #374151);
    }
    
    .btn--ghost:hover:not(:disabled) {
      background: var(--color-gray-100, #f3f4f6);
    }
    
    .btn--danger {
      background: var(--color-red-600, #dc2626);
      color: var(--color-white, #ffffff);
    }
    
    .btn--danger:hover:not(:disabled) {
      background: var(--color-red-700, #b91c1c);
    }
    
    /* Estados */
    .btn:focus-visible {
      outline: none;
      box-shadow: var(--btn-focus-ring);
    }
    
    .btn--disabled {
      opacity: 0.5;
      cursor: not-allowed;
      pointer-events: none;
    }
    
    .btn--full-width {
      width: 100%;
    }
    
    /* Ícones */
    .btn__icon {
      flex-shrink: 0;
    }
    
    .btn__icon--left {
      margin-right: calc(var(--spacing-2, 0.5rem) * -0.5);
    }
    
    .btn__icon--right {
      margin-left: calc(var(--spacing-2, 0.5rem) * -0.5);
    }
    
    .btn__icon--spinning {
      animation: btn-spin 1s linear infinite;
    }
    
    @keyframes btn-spin {
      from {
        transform: rotate(0deg);
      }
      to {
        transform: rotate(360deg);
      }
    }
    
    /* Acessibilidade: preferência de movimento reduzido */
    @media (prefers-reduced-motion: reduce) {
      .btn {
        transition: none;
      }
      
      .btn__icon--spinning {
        animation: none;
      }
    }
    
    /* Responsividade */
    @media (max-width: 640px) {
      .btn--large {
        padding: var(--spacing-3, 0.75rem) var(--spacing-4, 1rem);
        font-size: var(--font-size-base, 1rem);
      }
    }
  </style>
@endpush