/**
 * Bluefish - Aplicação Principal
 * 
 * Implementado seguindo os princípios do Guia Yuri Garcia Pardinho:
 * - Código limpo e modular
 * - Acessibilidade WCAG 2.2 AA
 * - Performance otimizada
 * - Padrões modernos ES6+
 */

import './bootstrap';

/**
 * Classe para gerenciar a navegação principal
 * Implementa padrões de acessibilidade e mobile-first
 */
class NavigationManager {
  constructor() {
    this.navbar = document.querySelector('[data-navbar]');
    this.toggle = document.querySelector('[data-navbar-toggle]');
    this.menu = document.getElementById('menu-principal');
    this.isOpen = false;
    
    this.init();
  }

  init() {
    if (!this.navbar || !this.toggle || !this.menu) {
      console.warn('Elementos de navegação não encontrados');
      return;
    }

    this.setupEventListeners();
    this.setupScrollBehavior();
    this.setupKeyboardNavigation();
    this.closeMenu(); // Estado inicial fechado
  }

  setupEventListeners() {
    // Toggle do menu mobile
    this.toggle.addEventListener('click', () => this.toggleMenu());
    
    // Fechar menu ao clicar em links (mobile)
    this.menu.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        if (this.isMobileViewport()) {
          this.closeMenu();
        }
      });
    });

    // Fechar menu ao redimensionar para desktop
    window.addEventListener('resize', () => {
      if (!this.isMobileViewport() && this.isOpen) {
        this.closeMenu();
      }
    }, { passive: true });
  }

  setupScrollBehavior() {
    const updateNavbarState = () => {
      const isScrolled = window.scrollY > 40;
      this.navbar.classList.toggle('navbar--scrolled', isScrolled);
    };

    updateNavbarState();
    window.addEventListener('scroll', updateNavbarState, { passive: true });
  }

  setupKeyboardNavigation() {
    // Fechar menu com ESC
    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape' && this.isOpen) {
        this.closeMenu();
        this.toggle.focus();
      }
    });

    // Trap focus dentro do menu quando aberto
    this.menu.addEventListener('keydown', (event) => {
      if (!this.isOpen) return;
      
      const focusableElements = this.getFocusableElements();
      if (focusableElements.length === 0) return;

      const firstElement = focusableElements[0];
      const lastElement = focusableElements[focusableElements.length - 1];

      if (event.key === 'Tab') {
        if (event.shiftKey && document.activeElement === firstElement) {
          event.preventDefault();
          lastElement.focus();
        } else if (!event.shiftKey && document.activeElement === lastElement) {
          event.preventDefault();
          firstElement.focus();
        }
      }
    });
  }

  getFocusableElements() {
    return Array.from(
      this.menu.querySelectorAll(
        'a[href], button, input, textarea, select, details, [tabindex]:not([tabindex="-1"])'
      )
    ).filter(element => !element.hasAttribute('disabled') && !element.getAttribute('aria-hidden'));
  }

  toggleMenu() {
    this.isOpen ? this.closeMenu() : this.openMenu();
  }

  openMenu() {
    this.isOpen = true;
    this.navbar.dataset.open = 'true';
    this.toggle.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden'; // Previne scroll do body
    
    // Focus no primeiro elemento do menu
    const firstFocusable = this.getFocusableElements()[0];
    if (firstFocusable) {
      setTimeout(() => firstFocusable.focus(), 100);
    }
  }

  closeMenu() {
    this.isOpen = false;
    this.navbar.dataset.open = 'false';
    this.toggle.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = ''; // Restaura scroll
  }

  isMobileViewport() {
    return window.matchMedia('(max-width: 767px)').matches;
  }
}

/**
 * Classe para gerenciar animações de entrada suaves
 * Usa Intersection Observer para performance otimizada
 */
class FadeInAnimation {
  constructor(selector = '.fade-in') {
    this.elements = document.querySelectorAll(selector);
    this.observer = null;
    
    this.init();
  }

  init() {
    if (this.elements.length === 0) return;

    if ('IntersectionObserver' in window) {
      this.setupIntersectionObserver();
    } else {
      // Fallback para navegadores antigos
      this.elements.forEach(element => element.classList.add('is-visible'));
    }
  }

  setupIntersectionObserver() {
    this.observer = new IntersectionObserver(
      (entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            this.observer.unobserve(entry.target);
          }
        });
      },
      { 
        threshold: 0.2,
        rootMargin: '0px 0px -50px 0px' // Trigger um pouco antes de entrar na viewport
      }
    );

    this.elements.forEach(element => {
      this.observer.observe(element);
    });
  }

  destroy() {
    if (this.observer) {
      this.observer.disconnect();
    }
  }
}

/**
 * Classe para gerenciar a sidebar administrativa
 * Implementa padrões de acessibilidade para painéis administrativos
 */
class AdminSidebarManager {
  constructor() {
    this.sidebar = document.querySelector('[data-admin-sidebar]');
    this.toggle = document.querySelector('[data-admin-sidebar-toggle]');
    this.closeBtn = document.querySelector('[data-admin-sidebar-close]');
    this.backdrop = document.querySelector('[data-admin-sidebar-backdrop]');
    this.isOpen = false;
    
    this.init();
  }

  init() {
    if (!this.sidebar || !this.toggle) {
      console.warn('Elementos da sidebar administrativa não encontrados');
      return;
    }

    this.setupEventListeners();
    this.closeSidebar(); // Estado inicial fechado
  }

  setupEventListeners() {
    // Toggle da sidebar
    this.toggle.addEventListener('click', () => this.toggleSidebar());
    
    // Botão de fechar
    if (this.closeBtn) {
      this.closeBtn.addEventListener('click', () => this.closeSidebar());
    }
    
    // Click no backdrop
    if (this.backdrop) {
      this.backdrop.addEventListener('click', () => this.closeSidebar());
    }

    // Fechar com ESC
    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape' && this.isOpen) {
        this.closeSidebar();
      }
    });

    // Fechar ao redimensionar para desktop
    window.addEventListener('resize', () => {
      if (window.matchMedia('(min-width: 64.0625rem)').matches && this.isOpen) {
        this.closeSidebar();
      }
    }, { passive: true });
  }

  toggleSidebar() {
    this.isOpen ? this.closeSidebar() : this.openSidebar();
  }

  openSidebar() {
    this.isOpen = true;
    this.sidebar.dataset.open = 'true';
    this.toggle.setAttribute('aria-expanded', 'true');
    
    if (this.backdrop) {
      this.backdrop.hidden = false;
      document.body.style.overflow = 'hidden';
    }
  }

  closeSidebar() {
    this.isOpen = false;
    this.sidebar.dataset.open = 'false';
    this.toggle.setAttribute('aria-expanded', 'false');
    
    if (this.backdrop) {
      this.backdrop.hidden = true;
      document.body.style.overflow = '';
    }
    
    // Retornar foco para o toggle
    this.toggle.focus();
  }
}

/**
 * Classe principal da aplicação
 * Gerencia a inicialização e coordenação dos módulos
 */
class BluefishApp {
  constructor() {
    this.modules = new Map();
    this.isInitialized = false;
    
    this.init();
  }

  async init() {
    if (this.isInitialized) return;

    try {
      // Inicializar módulos principais
      this.registerModule('navigation', new NavigationManager());
      this.registerModule('animations', new FadeInAnimation());
      this.registerModule('adminSidebar', new AdminSidebarManager());
      
      // Inicializar módulos específicos de páginas
      this.initPageSpecificModules();
      
      this.isInitialized = true;
      console.log('Bluefish App inicializada com sucesso');
      
    } catch (error) {
      console.error('Erro ao inicializar Bluefish App:', error);
      this.handleInitializationError(error);
    }
  }

  registerModule(name, instance) {
    this.modules.set(name, instance);
  }

  getModule(name) {
    return this.modules.get(name);
  }

  initPageSpecificModules() {
    // Detectar página atual e inicializar módulos específicos
    const currentPage = this.detectCurrentPage();
    
    switch (currentPage) {
      case 'produtos':
        this.initProdutosModules();
        break;
      case 'admin':
        this.initAdminModules();
        break;
      default:
        // Módulos padrão já estão inicializados
        break;
    }
  }

  detectCurrentPage() {
    const path = window.location.pathname;
    if (path.includes('/admin')) return 'admin';
    if (path.includes('/produtos')) return 'produtos';
    if (path.includes('/vendas')) return 'vendas';
    return 'default';
  }

  initProdutosModules() {
    // Placeholder para futuros módulos de produtos
    console.log('Módulos de produtos inicializados');
  }

  initAdminModules() {
    // Placeholder para futuros módulos administrativos
    console.log('Módulos administrativos inicializados');
  }

  handleInitializationError(error) {
    // Fallback básico para garantir funcionalidade mínima
    console.warn('Executando fallback devido a erro de inicialização');
    
    // Garantir que o menu mobile funcione mesmo com erro
    const toggle = document.querySelector('[data-navbar-toggle]');
    const navbar = document.querySelector('[data-navbar]');
    
    if (toggle && navbar) {
      toggle.addEventListener('click', () => {
        const isOpen = navbar.dataset.open === 'true';
        navbar.dataset.open = String(!isOpen);
        toggle.setAttribute('aria-expanded', String(!isOpen));
      });
    }
  }

  destroy() {
    // Limpar módulos e listeners
    this.modules.forEach(module => {
      if (typeof module.destroy === 'function') {
        module.destroy();
      }
    });
    
    this.modules.clear();
    this.isInitialized = false;
  }
}

// Inicializar aplicação quando DOM estiver pronto
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => {
    window.bluefishApp = new BluefishApp();
  });
} else {
  // DOM já está pronto
  window.bluefishApp = new BluefishApp();
}

// Exportar para uso global se necessário
export { BluefishApp, NavigationManager, FadeInAnimation, AdminSidebarManager };
