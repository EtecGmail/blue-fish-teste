@extends('layouts.app')

@section('title', 'Login Admin - Bluefish')

@section('content')
  <div class="auth-container">
    <div class="auth-card">
      <div class="auth-header">
          <h1>Login Administrativo</h1>
          <p>Acesso restrito</p>
      </div>

      <form class="auth-form" method="POST" action="{{ route('admin.login') }}">
          @csrf
          <div class="form-group">
              <label for="email">E-mail</label>
              <input type="email" id="email" name="email" value="{{ old('email') }}" required>
          </div>
          <div class="form-group">
              <label for="password">Senha</label>
              <input type="password" id="password" name="password" required>
          </div>
          <button type="submit" class="btn btn-primary">Entrar</button>
      </form>
    </div>
  </div>
@endsection


