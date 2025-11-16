@extends('layouts.admin')

@section('title', 'Admin - Início')

@section('content')
    <div class="card" style="padding: 2rem;">
            <h1 style="margin-bottom: 1rem;">Área Administrativa</h1>
            <p style="margin-bottom: 1.5rem;">Acesse o painel para visualizar métricas, gráficos e gerenciar conteúdos.</p>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                Ir para o Dashboard
            </a>
    </div>
@endsection


