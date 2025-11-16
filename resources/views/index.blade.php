@extends('layouts.app')

@section('title', 'Bluefish - Home')

@section('body-class', 'home-page')

@section('full-width')
    <section class="home-hero">
        <div class="home-hero__inner">
            <div class="home-hero__content">
                <div class="home-hero__headline">
                    <h1>Frescor do mar direto para o seu negócio</h1>
                    <p>Distribuímos pescados selecionados com qualidade certificada para mercados, empórios e restaurantes em todo o Brasil.</p>
                </div>
                <div class="home-hero__actions">
                    @auth
                        <a href="{{ route('produtos.index') }}" class="btn btn-primary">
                            <i class="fas fa-fish" aria-hidden="true"></i>
                            <span>Explorar produtos</span>
                        </a>
                    @else
                        <a href="{{ route('login.form') }}" class="btn btn-primary">
                            <i class="fas fa-user" aria-hidden="true"></i>
                            <span>Fazer login</span>
                        </a>
                    @endauth
                    <a href="{{ route('contato.form') }}" class="btn btn-secondary">
                        <i class="fas fa-envelope" aria-hidden="true"></i>
                        <span>Fale conosco</span>
                    </a>
                </div>
            </div>
            <figure class="home-hero__figure">
                <img class="home-hero__image" src="{{ asset('img/pescador.jpg') }}" alt="Pescador Bluefish selecionando peixes frescos">
            </figure>
        </div>
    </section>

    <section class="section home-about">
        <div class="section__inner home-about__grid">
            <div class="home-about__text">
                <div>
                    <h2>Tradição em frutos do mar premium</h2>
                    <p class="home-about__description">
                        Somos especialistas em conectar pesca responsável a varejistas que valorizam qualidade, segurança e regularidade de entrega.
                        A Bluefish nasceu no litoral brasileiro e leva esse frescor para dentro do seu estabelecimento.
                    </p>
                </div>
                <div class="home-feature-grid">
                    <article class="home-feature-card">
                        <i class="fas fa-fish" aria-hidden="true"></i>
                        <h3>Seleção diária</h3>
                        <p>Equipe dedicada à curadoria dos melhores pescados de cada safra.</p>
                    </article>
                    <article class="home-feature-card">
                        <i class="fas fa-truck" aria-hidden="true"></i>
                        <h3>Logística refrigerada</h3>
                        <p>Rede de distribuição com controle de temperatura do embarque à entrega.</p>
                    </article>
                    <article class="home-feature-card">
                        <i class="fas fa-shield-alt" aria-hidden="true"></i>
                        <h3>Rastreabilidade</h3>
                        <p>Documentação sanitária e fiscalização em cada lote comercializado.</p>
                    </article>
                    <article class="home-feature-card">
                        <i class="fas fa-store" aria-hidden="true"></i>
                        <h3>Parcerias flexíveis</h3>
                        <p>Planos sob medida para varejistas, empórios e redes gastronômicas.</p>
                    </article>
                </div>
            </div>
            <div class="home-about__figure">
                <img class="home-about__image" src="{{ asset('img/peixe.jpg') }}" alt="Peixes frescos prontos para entrega">
            </div>
        </div>
    </section>

    <section class="section home-products">
        <div class="section__inner">
            <div class="home-products__header">
                <h2>Nossa curadoria de pescados</h2>
                <p>Seleção com controle de origem, certificações ambientais e preço competitivo para o atacado.</p>
            </div>
            <div class="home-products__grid">
                <article class="home-product-card">
                    <img src="{{ asset('img/salmao.jpg') }}" alt="Postas de salmão fresco">
                    <h3>Salmão Atlântico</h3>
                    <p>Cortes nobres com certificação de sustentabilidade e padrão para sashimis.</p>
                </article>
                <article class="home-product-card">
                    <img src="{{ asset('img/atum.jpg') }}" alt="Lombo de atum fresco">
                    <h3>Atum Yellowfin</h3>
                    <p>Opção versátil para grelhados, pokes e gastronomia japonesa.</p>
                </article>
                <article class="home-product-card">
                    <img src="{{ asset('img/camarao.jpg') }}" alt="Camarões frescos Bluefish">
                    <h3>Camarão Cinza</h3>
                    <p>Congelamento rápido que mantém textura e sabor para pratos premium.</p>
                </article>
            </div>
            <div class="home-products__cta">
                @auth
                    <a href="{{ route('produtos.index') }}" class="btn btn-primary">Ver catálogo completo</a>
                @else
                    <a href="{{ route('login.form') }}" class="btn btn-primary">Faça login para acessar a vitrine</a>
                @endauth
            </div>
        </div>
    </section>

    <section class="home-cta">
        <div class="home-cta__inner">
            <h2>Quer o frescor do mar no seu estoque?</h2>
            <p>Converse com nossa equipe comercial e monte um portfólio alinhado ao perfil dos seus clientes.</p>
            @auth
                <a href="{{ route('contato.form') }}" class="btn btn-primary">
                    <i class="fas fa-comments" aria-hidden="true"></i>
                    <span>Agendar conversa</span>
                </a>
            @else
                <a href="{{ route('login.form') }}" class="btn btn-primary">
                    <i class="fas fa-lock" aria-hidden="true"></i>
                    <span>Entrar para falar com o time</span>
                </a>
            @endauth
        </div>
    </section>
@endsection