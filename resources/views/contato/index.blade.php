@extends('layouts.app')

@section('title', 'Contato - Bluefish')

@section('content')
    <div class="contato-container">
            <div class="contato-header">
                <h1>Fale Conosco</h1>
                <p>Entre em contato conosco para tirar suas dúvidas ou fazer um pedido</p>
            </div>


            <div class="contato-grid">
                <div class="contato-info">
                    <h2>Informações de Contato</h2>
                    <div class="contato-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div class="contato-item-info">
                            <h3>Endereço</h3>
                            <p>Rua das Pescarias, 123<br>Centro - São Paulo, SP</p>
                        </div>
                    </div>
                    <div class="contato-item">
                        <i class="fas fa-phone"></i>
                        <div class="contato-item-info">
                            <h3>Telefone</h3>
                            <p>(11) 1234-5678</p>
                        </div>
                    </div>
                    <div class="contato-item">
                        <i class="fas fa-envelope"></i>
                        <div class="contato-item-info">
                            <h3>E-mail</h3>
                            <p>contato@bluefish.com</p>
                        </div>
                    </div>
                    <div class="contato-item">
                        <i class="fas fa-clock"></i>
                        <div class="contato-item-info">
                            <h3>Horário de Atendimento</h3>
                            <p>Segunda a Sexta: 8h às 18h<br>Sábado: 8h às 13h</p>
                        </div>
                    </div>
                </div>

                <div class="contato-form">
                    <h2>Envie sua Mensagem</h2>
                    <form action="{{ route('contato.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="nome">Nome Completo</label>
                            <input type="text" id="nome" name="nome" required>
                        </div>
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="telefone">Telefone</label>
                            <input type="tel" id="telefone" name="telefone">
                        </div>
                        <div class="form-group">
                            <label for="assunto">Assunto</label>
                            <select id="assunto" name="assunto" required>
                                <option value="">Selecione um assunto</option>
                                <option value="duvida">Dúvida</option>
                                <option value="pedido">Pedido</option>
                                <option value="reclamacao">Reclamação</option>
                                <option value="sugestao">Sugestão</option>
                                <option value="outro">Outro</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="mensagem">Mensagem</label>
                            <textarea id="mensagem" name="mensagem" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar Mensagem</button>
                    </form>
                </div>
            </div>

            <div class="contato-mapa">
                <h2>Nossa Localização</h2>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3657.197577494978!2d-46.652277!3d-23.550520!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjPCsDMzJzAxLjkiUyA0NsKwMzknMDguMiJX!5e0!3m2!1spt-BR!2sbr!4v1625761234567!5m2!1spt-BR!2sbr" 
                        width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </div>
@endsection