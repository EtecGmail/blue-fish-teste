# AGENTS

Guia prático para agentes de IA trabalharem com o projeto BlueFish, alinhado ao “Método Amarelo” de Yuri Garcia Pardinho.

## Propósito
- Acelerar mudanças com simplicidade, clareza, segurança, acessibilidade e pragmatismo.
- Garantir consistência técnica e responsabilidade em todo o ciclo de desenvolvimento.

## Princípios
- KISS, YAGNI, DRY.
- Separation of Concerns e SLAP.
- SOLID pragmático; PoLS; GRASP como referência.
- Security & Privacy by Design; Defense in Depth.

## Regras Operacionais
- Preferir editar arquivos existentes; evitar criar arquivos novos sem necessidade explícita.
- Seguir padrões do projeto: estilos CSS, semântica HTML, convenções de Blade/Laravel.
- Não introduzir bibliotecas sem confirmar que o projeto já utiliza ou que há justificativa clara.
- Não expor segredos; nunca logar credenciais; usar variáveis em `.env`.
- Referenciar trechos com `file_path:line_number` ao instruir humanos.

## Acessibilidade
- Alinhar a WCAG 2.2 AA.
- Incluir landmarks semânticos e ARIA adequados (ex.: `aria-label` em navegação, `aria-controls`/`aria-expanded` em toggles).
- Foco visível, navegação por teclado completa e `:focus-visible` aplicados.
- Skip link presente: `resources/views/layouts/app.blade.php`.

## Responsividade
- Mobile-first; breakpoints semânticos.
- Layouts em Flexbox/Grid; imagens e hit targets adequados (≥ 44px).
- Navbar com hamburguer em 768px e menu desktop centralizado.

## Segurança
- Seguir OWASP Top 10 e OWASP API Security Top 10.
- CSRF em rotas com estado; validação e encode de saída para prevenir XSS.
- Cabeçalhos de segurança recomendados: CSP sem `unsafe-inline` (nonce/hash), `X-Frame-Options: DENY`, `X-Content-Type-Options: nosniff`, `Referrer-Policy: no-referrer`, HSTS.
- TLS forte; proteção de sessões; rate limiting em endpoints sensíveis.

## LGPD e Conformidade
- Minimização de dados; base legal por tratamento; direitos dos titulares.
- Ambientes separados; dados mascarados fora de produção.
- Políticas de privacidade e termos de uso publicados.

## Fluxo de Mudança
- Planejar com tarefas atômicas; executar; validar; entregar.
- Validar com testes disponíveis, build, e verificação visual.
- Rodar build de assets quando necessário (`vite`).

## Checklists
- Pull Request: código claro e coeso; testes passando; impactos de segurança e acessibilidade considerados.
- Pré-produção: fluxos críticos testados; rollback; conformidade LGPD; segurança validada.
- Segurança Web/Mobile: 2FA onde aplicável; TLS; CSP; rotação de chaves; backups.
- Responsividade e Acessibilidade: diferentes tamanhos de tela; teclado; contraste; prefers-reduced-motion.

## Padrões de UI (Navbar)
- Fixa no topo com z-index adequado; estados ativos visíveis; transições suaves.
- Logotipo à esquerda, menu principal centralizado, área de usuário à direita.
- Breakpoint móvel em 768px com botão hamburguer acessível.
- Arquivos principais: `resources/views/layouts/app.blade.php`, `resources/css/base.css`, `public/css/styles.css`, `resources/js/app.js`.

## Scripts de Apoio
- `npm run ai:summarize` — resume o guia e confirma presença.
- `npm run ai:check` — checa acessibilidade, responsividade e segurança básicas no projeto.
- `npm run ai:suggest` — sugere próximas ações alinhadas ao guia.

## Estrutura e Convenções
- Laravel com Blade e Vite; CSS em `resources/css` (build) e fallback em `public/css/styles.css`.
- Evitar inline scripts; preferir `resources/js/app.js`.
- Reutilizar classes e tokens de design existentes.

## Commits e Rastreabilidade
- Usar mensagens claras (ex.: Conventional Commits).
- Manter histórico compreensível e vincular mudanças relevantes a issues/PRs.

## ADRs
- Registrar decisões arquiteturais e de segurança relevantes com contexto, decisão, consequências e testes de validação.