import fs from 'fs';
import path from 'path';

const root = process.cwd();
const guiaPath = path.join(root, 'guia', 'Guia Yuri Garcia Pardinho-Metódo Amarelo.txt');

const readText = (p) => {
  try {
    return fs.readFileSync(p, 'utf8');
  } catch {
    return '';
  }
};

const fileExists = (p) => {
  try {
    fs.accessSync(p, fs.constants.F_OK);
    return true;
  } catch {
    return false;
  }
};

const findInFile = (p, patterns) => {
  const txt = readText(p);
  return patterns.every((rgx) => new RegExp(rgx, 'm').test(txt));
};

const summarizeGuia = () => {
  const txt = readText(guiaPath);
  const out = {
    presente: fileExists(guiaPath),
    principios: ['KISS', 'YAGNI', 'DRY', 'SoC', 'SLAP', 'SOLID', 'GRASP'],
    seguranca: ['OWASP Top 10', 'CSP', 'HSTS', 'X-Frame-Options', 'X-Content-Type-Options', 'Referrer-Policy', 'TLS'],
    acessibilidade: ['WCAG 2.2 AA', 'ARIA', 'Foco visível', 'Navegação por teclado'],
    responsividade: ['Mobile-First', 'Breakpoints semânticos', 'Grids fluidos'],
    legal: ['LGPD', 'Marco Civil', 'LBI'],
    textoAmostra: txt.slice(0, 600)
  };
  return out;
};

const checkAccessibility = () => {
  const layout = path.join(root, 'resources', 'views', 'layouts', 'app.blade.php');
  const hasSkipLink = findInFile(layout, ['class="skip-link"']);
  const hasAriaMenu = findInFile(layout, ['aria-label="Menu principal"']);
  const hasHamburguerA11y = findInFile(layout, ['aria-controls="menu-principal"', 'aria-expanded']);
  const js = path.join(root, 'resources', 'js', 'app.js');
  const hasToggleLogic = findInFile(js, ['data-navbar', 'data-navbar-toggle', 'aria-expanded']);
  const cssBase = path.join(root, 'resources', 'css', 'base.css');
  const focusVisible = findInFile(cssBase, [':focus-visible']);
  return {
    skipLink: hasSkipLink,
    ariaMenu: hasAriaMenu,
    hamburguer: hasHamburguerA11y,
    toggleLogic: hasToggleLogic,
    focusVisible
  };
};

const checkResponsiveness = () => {
  const cssBase = path.join(root, 'resources', 'css', 'base.css');
  const hasBreakpoint = findInFile(cssBase, ['@media \(min-width: 768px\)']);
  const menuFlex = findInFile(cssBase, ['\.navbar__menu', 'flex']);
  const actionsFlex = findInFile(cssBase, ['\.navbar__actions']);
  return {
    breakpoint768: hasBreakpoint,
    menuFlex,
    actionsFlex
  };
};

const checkSecurity = () => {
  const envPath = path.join(root, '.env');
  const hasEnv = fileExists(envPath);
  const envContent = readText(envPath);
  const hasDbConn = /DB_CONNECTION\s*=\s*\w+/m.test(envContent);
  const provider = path.join(root, 'app', 'Providers', 'AppServiceProvider.php');
  const hasHeaders = findInFile(provider, ['header', 'X-Content-Type-Options']);
  return {
    envPresente: hasEnv,
    envDbConnection: hasDbConn,
    securityHeadersProvider: hasHeaders
  };
};

const checkLegal = () => {
  const hasPrivacy = false;
  return { privacyPolicy: hasPrivacy };
};

const checkAll = () => {
  return {
    guia: summarizeGuia(),
    acessibilidade: checkAccessibility(),
    responsividade: checkResponsiveness(),
    seguranca: checkSecurity(),
    legal: checkLegal()
  };
};

const suggestNextActions = (report) => {
  const actions = [];
  if (!report.acessibilidade.skipLink) actions.push('Adicionar link de pular para conteúdo com .skip-link');
  if (!report.acessibilidade.ariaMenu) actions.push('Definir aria-label="Menu principal" no nav da navbar');
  if (!report.acessibilidade.hamburguer) actions.push('Garantir aria-controls e aria-expanded no botão hamburguer');
  if (!report.acessibilidade.focusVisible) actions.push('Aplicar estilos de :focus-visible para navegação por teclado');
  if (!report.responsividade.breakpoint768) actions.push('Incluir @media (min-width: 768px) para layout desktop da navbar');
  if (!report.seguranca.securityHeadersProvider) actions.push('Adicionar middleware ou headers de segurança (CSP, HSTS, X-Frame-Options, nosniff)');
  if (!report.legal.privacyPolicy) actions.push('Publicar termos de uso e política de privacidade');
  return actions;
};

const main = async () => {
  const arg = process.argv[2] || 'check';
  if (arg === 'summarize') {
    const out = summarizeGuia();
    if (process.argv.includes('--json')) console.log(JSON.stringify(out, null, 2));
    else console.log(out);
    return;
  }
  if (arg === 'check') {
    const report = checkAll();
    if (process.argv.includes('--json')) console.log(JSON.stringify(report, null, 2));
    else console.log(report);
    return;
  }
  if (arg === 'suggest') {
    const report = checkAll();
    const actions = suggestNextActions(report);
    if (process.argv.includes('--json')) console.log(JSON.stringify({ actions }, null, 2));
    else console.log(actions);
    return;
  }
  console.log('Comandos: summarize | check | suggest [--json]');
};

main();