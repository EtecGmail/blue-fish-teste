@extends('layouts.admin')

@section('title', 'Editar produto - Bluefish')

@section('content')
    <nav class="breadcrumb" aria-label="Você está em">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span aria-hidden="true">/</span>
        <a href="{{ route('admin.products.index') }}">Produtos</a>
        <span aria-hidden="true">/</span>
        <span aria-current="page">Editar {{ $produto->nome }}</span>
    </nav>

    <header class="page-header">
        <h1>Editar produto</h1>
        <p>Atualize dados de preço, estoque e visibilidade. As alterações entram em vigor imediatamente após salvar.</p>
    </header>

    @include('admin.products._form')
@endsection
