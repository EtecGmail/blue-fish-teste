@extends('layouts.admin')

@section('title', 'Cadastrar produto - Bluefish')

@section('content')
    <nav class="breadcrumb" aria-label="Você está em">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span aria-hidden="true">/</span>
        <a href="{{ route('admin.products.index') }}">Produtos</a>
        <span aria-hidden="true">/</span>
        <span aria-current="page">Novo produto</span>
    </nav>

    <header class="page-header">
        <h1>Novo produto</h1>
        <p>Preencha os campos obrigatórios marcados com *. Todas as informações serão exibidas imediatamente após salvar.</p>
    </header>

    @include('admin.products._form')
@endsection
