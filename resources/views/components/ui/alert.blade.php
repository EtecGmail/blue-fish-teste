{{--
  Componente Alert - Baseado no Guia Yuri Garcia Pardinho
  
  Principios aplicados:
  - Acessibilidade WCAG 2.2 AA
  - Semântica HTML5 apropriada
  - ARIA labels para leitores de tela
  - Estados visuais claros
  
  @props [
    'type' => 'info', // success, warning, error, info
    'title' => null,
    'dismissible' => false,
    'icon' => true
  ]
--}}

@php
  $alertTypes = [
    'success' => [
      'class' => 'alert--success',
      'icon' => 'check-circle',
      'role' => 'status'
    ],
    'warning' => [
      'class' => 'alert--warning', 
      'icon' => 'exclamation-triangle',
      'role' => 'alert'
    ],
    'error' => [
      'class' => 'alert--error',
      'icon' => 'x-circle',
      'role' => 'alert'
    ],
    'info' => [
      'class' => 'alert--info',
      'icon' => 'info-circle',
      'role' => 'status'
    ]
  ];
  
  $alertConfig = $alertTypes[$type] ?? $alertTypes['info'];
@endphp

<div class="alert {{ $alertConfig['class'] }}" 
     role="{{ $alertConfig['role'] }}"
     aria-live="polite"
     @if($dismissible) id="alert-{{ uniqid() }}" @endif>
     
  <div class="alert__content">
    @if($icon)
      <svg class="alert__icon" width="20" height="20" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
        @switch($alertConfig['icon'])
          @case('check-circle')
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            @break
          @case('exclamation-triangle')
            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            @break
          @case('x-circle')
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            @break
          @default
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
        @endswitch
      </svg>
    @endif
    
    <div class="alert__text">
      @if($title)
        <h3 class="alert__title">{{ $title }}</h3>
      @endif
      
      <div class="alert__message">
        {{ $slot }}
      </div>
    </div>
  </div>
  
  @if($dismissible)
    <button type="button" 
            class="alert__close" 
            aria-label="Fechar alerta"
            data-dismiss="alert">
      <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
        <path d="M4.646 4.646a.5.5 0 01.708 0L8 7.293l2.646-2.647a.5.5 0 01.708.708L8.707 8l2.647 2.646a.5.5 0 01-.708.708L8 8.707l-2.646 2.647a.5.5 0 01-.708-.708L7.293 8 4.646 5.354a.5.5 0 010-.708z"/>
      </svg>
    </button>
  @endif
</div>

@push('scripts')
  @if($dismissible)
    <script>
      // Funcionalidade de fechar alerta - seguindo principios de acessibilidade
      document.addEventListener('DOMContentLoaded', function() {
        const alert = document.getElementById('alert-{{ uniqid() }}');
        const closeBtn = alert?.querySelector('[data-dismiss="alert"]');
        
        if (closeBtn) {
          closeBtn.addEventListener('click', function() {
            // Animacao de saida suave
            alert.style.opacity = '0';
            setTimeout(() => {
              alert.remove();
            }, 300);
          });
          
          // Suporte para teclado (Enter e Space)
          closeBtn.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
              e.preventDefault();
              closeBtn.click();
            }
          });
        }
      });
    </script>
  @endif
@endpush