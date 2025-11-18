{{--
  Rodapé institucional - componente reutilizável alinhado ao design system
  - Estrutura semântica e responsiva
  - Links claros e acessíveis
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
