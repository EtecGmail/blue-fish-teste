# Bluefish

Soluções digitais para distribuição de pescados com foco em UX acessível, responsividade mobile-first e fluxo seguro de vendas.

## Visão geral

A Bluefish é um painel Laravel que concentra vitrine de produtos, fluxo de vendas, área de contato e uma área administrativa protegida. O projeto segue o Guia Yuri Garcia Pardinho - Método Amarelo, priorizando simplicidade, clareza e segurança. A interface foi redesenhada para melhorar contraste, navegação por teclado e comportamento em telas pequenas.

### Principais recursos

- Layout responsivo mobile-first com navegação acessível, botão de menu e skip link.
- Páginas de autenticação, vitrine de produtos, detalhes individuais, contato e histórico de compras.
- Painel administrativo com métricas de vendas e acesso protegido por middleware `admin`.
- Seeds padronizados que criam usuário administrador usando variáveis de ambiente.
- CSS modular com design tokens (`resources/css/design-tokens.css`) e componentes reutilizáveis.

## Stack técnica

- PHP 8.3 + Laravel 11
- Banco SQLite (padrão) ou qualquer banco configurado via `.env`
- Vite com CSS modular (sem Tailwind por padrão)
- Node.js 18+ para build front-end

## Requisitos

| Ferramenta            | Versão recomendada |
|-----------------------|--------------------|
| PHP                   | 8.2 ou superior    |
| Composer              | 2.x                |
| Node.js               | 18.x ou 20.x       |
| npm                   | 9.x ou 10.x        |
| SQLite (ou outro SGBD)| incluído no PHP    |

### Dicas para Windows

1. **Opção recomendada (WSL 2 + Ubuntu)**
   - Habilite o WSL2 (`wsl --install`) e reinicie.
   - Instale Ubuntu via Microsoft Store.
   - Dentro do Ubuntu, instale PHP, Composer e Node (use [`sudo apt install php8.2 php8.2-sqlite3 composer nodejs npm`] ou use um gerenciador como [asdf](https://asdf-vm.com/)).
   - Trabalhe no diretório `/mnt/c/...` para acessar os arquivos do Windows.

2. **Opção nativa**
   - Instale [Laravel Herd](https://herd.laravel.com/) ou [Laragon](https://laragon.org/) para ter PHP e serviços prontos.
   - Garanta que o PHP usado pela linha de comando esteja no `PATH`.
   - Instale Node.js via [nvm-windows](https://github.com/coreybutler/nvm-windows) ou instalador oficial.

Independentemente da opção, execute os comandos em um terminal com suporte a Git (PowerShell, Windows Terminal ou o próprio WSL).

## Como executar

```bash
# 1. Clonar o repositório
git clone https://github.com/sua-conta/BlueFish.git
cd BlueFish

# 2. Copiar variáveis de ambiente
copy .env.example .env

# 3. Instalar dependências do backend e frontend
composer install
npm install

# 4. Gerar chave da aplicação
php artisan key:generate

# 5. Executar migrações e seeds
php artisan migrate --seed

# 6. Subir back-end e front-end
php artisan serve
# Em outro terminal
npm run dev
```

A aplicação ficará disponível em `http://localhost:8000`. O Vite injeta o CSS/JS automaticamente em modo desenvolvimento.

### Usuário administrador

As seeds criam automaticamente um usuário administrador usando as variáveis abaixo (definidas em `.env`):

```
ADMIN_USER_NAME="Administrador Bluefish"
ADMIN_USER_EMAIL=admin@bluefish.com
ADMIN_USER_PASSWORD=admin123
ADMIN_USER_PHONE=11988887777
```

Após rodar `php artisan migrate --seed`, faça login com o e-mail e senha configurados para acessar `http://localhost:8000/admin/dashboard`. Edite os valores no `.env` para alterar o usuário criado nas seeds.

### Outros comandos úteis

```bash
# Rodar testes automatizados
php artisan test

# Compilar assets para produção
npm run build
```

## Padrões adotados

- **Acessibilidade**: foco visível, navegação por teclado e contraste AA.
- **UX Writing**: textos objetivos, em português brasileiro e com mensagens de erro específicas.
- **Responsividade**: breakpoints semânticos e grid fluido.
- **Segurança**: middleware `admin`, sessões seguras, rate limiting em login e uso de `.env` para segredos.

A documentação e o código seguem o Método Amarelo, privilegiando simplicidade e clareza. Para contribuições futuras, mantenha o padrão de componentes reutilizáveis, tokens centralizados e commits rastreáveis.

## Assistentes de IA e utilitários

- Arquivo de diretrizes para agentes: `AGENTS.md`
- Guia de referência: `guia/Guia Yuri Garcia Pardinho-Metódo Amarelo.txt`

### Scripts de IA

- Resumo do guia:
  - `npm run ai:summarize`
  - `npm run ai:summarize -- --json`
- Verificação do projeto (acessibilidade, responsividade e segurança básicas):
  - `npm run ai:check`
  - `npm run ai:check -- --json`
- Sugestões de próximas ações alinhadas ao guia:
  - `npm run ai:suggest`
  - `npm run ai:suggest -- --json`

### O que é verificado

- Acessibilidade: `resources/views/layouts/app.blade.php:38-97`, `resources/js/app.js:1-44`, `resources/css/base.css`
- Responsividade: `resources/css/base.css:414-446`
- Segurança: `.env` e `DB_CONNECTION`, presença de cabeçalhos de segurança em `app/Providers/AppServiceProvider.php`

### Arquivo do utilitário

- Código: `tools/ai_guia_helper.js`
- Comandos: `summarize | check | suggest` com suporte a `--json`

### Boas práticas

- Preferir editar arquivos existentes e seguir o estilo do projeto.
- Evitar adicionar dependências sem necessidade e sem alinhamento com o guia.
- Não expor segredos, usar variáveis de ambiente e validar mudanças com build/testes.
