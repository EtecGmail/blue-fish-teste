@extends('layouts.app')

@section('title', 'Login - Bluefish')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('body-class', 'auth-page')

@section('content')
    <div class="auth-shell">
        <div class="auth-card">
            <div class="auth-card__header">
                <a href="{{ url('/') }}" class="auth-card__logo" aria-label="Voltar para a página inicial">
                    <img src="{{ asset('img/pexe.png') }}" alt="Bluefish">
                </a>
                <h1 class="auth-card__title">Login</h1>
                <p class="auth-card__subtitle">Entre com suas credenciais</p>
            </div>

            <form class="auth-form" method="POST" action="{{ route('login') }}" id="loginForm" novalidate>
                @csrf
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="email" aria-describedby="email-feedback" required>
                    <p id="email-feedback" class="form-feedback" aria-live="polite" aria-hidden="true" hidden>Por favor, insira um e-mail válido.</p>
                </div>
                <div class="form-group">
                    <label for="password">Senha</label>
                    <input type="password" id="password" name="senha" autocomplete="current-password" aria-describedby="password-feedback" required>
                    <p id="password-feedback" class="form-feedback" aria-live="assertive" aria-hidden="true" hidden>A senha é obrigatória.</p>
                </div>
                <div class="form-check">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Lembrar-me</label>
                </div>
                <button type="submit" class="btn btn-primary">Entrar</button>
            </form>
            <div class="auth-divider" role="presentation">
                <span>ou</span>
            </div>
            <div class="social-login" aria-label="Entrar com redes sociais">
                <button class="social-btn google"><i class="fab fa-google"></i> Google</button>
                <button class="social-btn facebook"><i class="fab fa-facebook-f"></i> Facebook</button>
            </div>
            <div class="auth-links">
                <p>Não tem uma conta? <a href="{{ route('register.form') }}">Registre-se</a></p>
                <p><a href="#">Esqueceu sua senha?</a></p>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const loginForm = document.getElementById('loginForm');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const emailFeedback = document.getElementById('email-feedback');
        const passwordFeedback = document.getElementById('password-feedback');

        const toggleFeedback = (input, feedbackEl, shouldShow, message) => {
            if (!feedbackEl) {
                return;
            }

            if (typeof message === 'string') {
                feedbackEl.textContent = message;
            }

            feedbackEl.hidden = !shouldShow;
            feedbackEl.setAttribute('aria-hidden', shouldShow ? 'false' : 'true');
            if (shouldShow) {
                input.setAttribute('aria-invalid', 'true');
            } else {
                input.removeAttribute('aria-invalid');
            }
        };

        const validateEmail = () => {
            const value = emailInput.value.trim();

            if (value === '') {
                toggleFeedback(emailInput, emailFeedback, true, 'O e-mail é obrigatório.');
                return false;
            }

            if (!emailInput.checkValidity()) {
                toggleFeedback(emailInput, emailFeedback, true, 'Por favor, insira um e-mail válido.');
                return false;
            }

            toggleFeedback(emailInput, emailFeedback, false);
            return true;
        };

        const validatePassword = () => {
            const value = passwordInput.value.trim();

            if (value === '') {
                toggleFeedback(passwordInput, passwordFeedback, true, 'A senha é obrigatória.');
                return false;
            }

            toggleFeedback(passwordInput, passwordFeedback, false);
            return true;
        };

        emailInput.addEventListener('input', () => {
            if (emailInput.value.trim() === '') {
                toggleFeedback(emailInput, emailFeedback, false);
                return;
            }

            if (emailInput.checkValidity()) {
                toggleFeedback(emailInput, emailFeedback, false);
            }
        });

        passwordInput.addEventListener('input', () => {
            if (passwordInput.value.trim() !== '') {
                toggleFeedback(passwordInput, passwordFeedback, false);
            }
        });

        loginForm.addEventListener('submit', (event) => {
            const isEmailValid = validateEmail();
            const isPasswordValid = validatePassword();

            if (!isEmailValid || !isPasswordValid) {
                event.preventDefault();
            }
        });
    });
</script>
@endsection
