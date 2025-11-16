@php($isEdit = $produto->exists)
<form action="{{ $isEdit ? route('admin.products.update', $produto) : route('admin.products.store') }}" method="POST" class="admin-form">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="form-grid">
        <div class="form-field">
            <label for="nome">Nome<span aria-hidden="true">*</span></label>
            <input type="text" id="nome" name="nome" value="{{ old('nome', $produto->nome ?? '') }}" required maxlength="255">
        </div>

        <div class="form-field">
            <label for="preco">Preço (R$)<span aria-hidden="true">*</span></label>
            <input type="number" step="0.01" min="0" id="preco" name="preco" value="{{ old('preco', $produto->preco !== null ? number_format($produto->preco, 2, '.', '') : '') }}" required>
        </div>

        <div class="form-field">
            <label for="estoque">Estoque</label>
            <input type="number" min="0" id="estoque" name="estoque" value="{{ old('estoque', $produto->estoque ?? '') }}">
        </div>

        <div class="form-field">
            <label for="status">Status<span aria-hidden="true">*</span></label>
            <select id="status" name="status" required>
                <option value="ativo" @selected(old('status', $produto->status) === 'ativo')>Ativo</option>
                <option value="inativo" @selected(old('status', $produto->status) === 'inativo')>Inativo</option>
            </select>
        </div>
    </div>

    <div class="form-field">
        <label for="categoria">Categoria</label>
        <input type="text" id="categoria" name="categoria" value="{{ old('categoria', $produto->categoria ?? '') }}" maxlength="255">
    </div>

    <div class="form-field">
        <label for="imagem">URL da imagem</label>
        <input type="url" id="imagem" name="imagem" value="{{ old('imagem', $produto->imagem ?? '') }}" placeholder="https://...">
        <small class="form-help">Informe uma URL acessível. Imagens locais podem ser configuradas em storage/app/public.</small>
    </div>

    <div class="form-field">
        <label for="descricao">Descrição<span aria-hidden="true">*</span></label>
        <textarea id="descricao" name="descricao" rows="5" required>{{ old('descricao', $produto->descricao ?? '') }}</textarea>
    </div>

    <div class="form-actions">
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancelar</a>
        <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Atualizar produto' : 'Cadastrar produto' }}</button>
    </div>
</form>
