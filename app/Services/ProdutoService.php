<?php

namespace App\Services;

use App\Models\Produto;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

/**
 * Serviço de Produtos - Responsável pela lógica de negócio relacionada a produtos
 * 
 * Principios SOLID aplicados:
 * - Single Responsibility Principle (SRP): Classe tem uma única razão para mudar
 * - Open/Closed Principle (OCP): Aberto para extensão, fechado para modificação
 * - Dependency Inversion Principle (DIP): Depende de abstrações
 * 
 * @package App\Services
 */
class ProdutoService
{
    /**
     * Cache TTL em segundos
     */
    private const CACHE_TTL = 3600; // 1 hora
    
    /**
     * Cache key para produtos ativos
     */
    private const CACHE_KEY_ATIVOS = 'produtos_ativos';
    
    /**
     * Cache key para produtos em destaque
     */
    private const CACHE_KEY_DESTAQUES = 'produtos_destaque';

    /**
     * Obtém produtos ativos com cache
     *
     * @param array $filtros
     * @return Collection
     */
    public function obterProdutosAtivos(array $filtros = []): Collection
    {
        $cacheKey = $this->gerarCacheKey(self::CACHE_KEY_ATIVOS, $filtros);
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($filtros) {
            $query = Produto::ativos();
            
            // Aplicar filtros
            if (!empty($filtros['categoria'])) {
                $query->where('categoria', $filtros['categoria']);
            }
            
            if (!empty($filtros['search'])) {
                $search = $filtros['search'];
                $query->where(function ($q) use ($search) {
                    $q->where('nome', 'like', "%{$search}%")
                      ->orWhere('descricao', 'like', "%{$search}%");
                });
            }
            
            if (!empty($filtros['ordenar_por'])) {
                $direcao = $filtros['direcao'] ?? 'asc';
                $query->orderBy($filtros['ordenar_por'], $direcao);
            } else {
                $query->orderBy('nome', 'asc');
            }
            
            return $query->get();
        });
    }

    /**
     * Obtém produtos em destaque
     *
     * @param int $limite
     * @return Collection
     */
    public function obterProdutosDestaque(int $limite = 6): Collection
    {
        return Cache::remember(self::CACHE_KEY_DESTAQUES, self::CACHE_TTL, function () use ($limite) {
            return Produto::ativos()
                ->where('em_destaque', true)
                ->orderBy('created_at', 'desc')
                ->limit($limite)
                ->get();
        });
    }

    /**
     * Busca produto por ID com validações
     *
     * @param int $id
     * @return Produto
     * @throws ValidationException
     */
    public function buscarProduto(int $id): Produto
    {
        $produto = Produto::find($id);
        
        if (!$produto) {
            throw ValidationException::withMessages([
                'produto' => 'Produto não encontrado.'
            ]);
        }
        
        return $produto;
    }

    /**
     * Busca produto ativo por ID
     *
     * @param int $id
     * @return Produto
     * @throws ValidationException
     */
    public function buscarProdutoAtivo(int $id): Produto
    {
        $produto = Produto::ativos()->find($id);
        
        if (!$produto) {
            throw ValidationException::withMessages([
                'produto' => 'Produto não encontrado ou indisponível.'
            ]);
        }
        
        return $produto;
    }

    /**
     * Cria um novo produto
     *
     * @param array $dados
     * @return Produto
     */
    public function criarProduto(array $dados): Produto
    {
        // Processar upload de imagem se existir
        if (!empty($dados['imagem'])) {
            $dados['imagem_url'] = $this->processarImagemProduto($dados['imagem']);
        }
        
        $produto = Produto::create($dados);
        
        // Limpar cache
        $this->limparCacheProdutos();
        
        // Registrar log
        Log::info('Produto criado', [
            'produto_id' => $produto->id,
            'nome' => $produto->nome,
            'usuario' => auth()->user()->name ?? 'sistema'
        ]);
        
        return $produto;
    }

    /**
     * Atualiza um produto existente
     *
     * @param Produto $produto
     * @param array $dados
     * @return Produto
     */
    public function atualizarProduto(Produto $produto, array $dados): Produto
    {
        // Processar nova imagem se existir
        if (!empty($dados['imagem'])) {
            // Remover imagem antiga se existir
            if ($produto->imagem_url) {
                $this->removerImagemProduto($produto->imagem_url);
            }
            
            $dados['imagem_url'] = $this->processarImagemProduto($dados['imagem']);
        }
        
        $produto->update($dados);
        
        // Limpar cache
        $this->limparCacheProdutos();
        
        // Registrar log
        Log::info('Produto atualizado', [
            'produto_id' => $produto->id,
            'nome' => $produto->nome,
            'usuario' => auth()->user()->name ?? 'sistema'
        ]);
        
        return $produto;
    }

    /**
     * Remove um produto
     *
     * @param Produto $produto
     * @return bool
     * @throws ValidationException
     */
    public function removerProduto(Produto $produto): bool
    {
        // Verificar se existe vendas associadas
        if ($produto->vendas()->exists()) {
            throw ValidationException::withMessages([
                'produto' => 'Não é possível remover produto com vendas associadas.'
            ]);
        }
        
        // Remover imagem se existir
        if ($produto->imagem_url) {
            $this->removerImagemProduto($produto->imagem_url);
        }
        
        $produtoId = $produto->id;
        $produtoNome = $produto->nome;
        
        $resultado = $produto->delete();
        
        // Limpar cache
        $this->limparCacheProdutos();
        
        // Registrar log
        Log::info('Produto removido', [
            'produto_id' => $produtoId,
            'nome' => $produtoNome,
            'usuario' => auth()->user()->name ?? 'sistema'
        ]);
        
        return $resultado;
    }

    /**
     * Atualiza estoque do produto
     *
     * @param Produto $produto
     * @param int $quantidade
     * @param string $tipo ('entrada' ou 'saida')
     * @param string|null $motivo
     * @return Produto
     * @throws ValidationException
     */
    public function atualizarEstoque(Produto $produto, int $quantidade, string $tipo = 'entrada', ?string $motivo = null): Produto
    {
        if ($quantidade <= 0) {
            throw ValidationException::withMessages([
                'quantidade' => 'Quantidade deve ser maior que zero.'
            ]);
        }
        
        $estoqueAnterior = $produto->estoque;
        
        if ($tipo === 'saida') {
            if ($produto->estoque < $quantidade) {
                throw ValidationException::withMessages([
                    'quantidade' => 'Estoque insuficiente.'
                ]);
            }
            $produto->decrement('estoque', $quantidade);
        } else {
            $produto->increment('estoque', $quantidade);
        }
        
        // Registrar movimentação de estoque
        Log::info('Movimentação de estoque', [
            'produto_id' => $produto->id,
            'produto_nome' => $produto->nome,
            'tipo' => $tipo,
            'quantidade' => $quantidade,
            'estoque_anterior' => $estoqueAnterior,
            'estoque_atual' => $produto->estoque,
            'motivo' => $motivo,
            'usuario' => auth()->user()->name ?? 'sistema'
        ]);
        
        // Verificar estoque baixo
        if ($produto->estoque <= ($produto->estoque_minimo ?? 10)) {
            $this->notificarEstoqueBaixo($produto);
        }
        
        return $produto->fresh();
    }

    /**
     * Processa upload de imagem do produto
     *
     * @param mixed $imagem
     * @return string
     * @throws ValidationException
     */
    private function processarImagemProduto($imagem): string
    {
        try {
            $path = $imagem->store('produtos', 'public');
            return Storage::url($path);
        } catch (\Exception $e) {
            Log::error('Erro ao processar imagem do produto', [
                'erro' => $e->getMessage()
            ]);
            
            throw ValidationException::withMessages([
                'imagem' => 'Erro ao fazer upload da imagem.'
            ]);
        }
    }

    /**
     * Remove imagem do produto
     *
     * @param string $imagemUrl
     * @return void
     */
    private function removerImagemProduto(string $imagemUrl): void
    {
        try {
            // Extrair path da URL
            $path = str_replace('/storage/', '', $imagemUrl);
            Storage::disk('public')->delete($path);
        } catch (\Exception $e) {
            Log::warning('Erro ao remover imagem do produto', [
                'imagem_url' => $imagemUrl,
                'erro' => $e->getMessage()
            ]);
        }
    }

    /**
     * Notifica sobre estoque baixo
     *
     * @param Produto $produto
     * @return void
     */
    private function notificarEstoqueBaixo(Produto $produto): void
    {
        Log::warning('Estoque baixo detectado', [
            'produto_id' => $produto->id,
            'produto_nome' => $produto->nome,
            'estoque_atual' => $produto->estoque,
            'estoque_minimo' => $produto->estoque_minimo ?? 10,
        ]);
        
        // TODO: Implementar notificação por email/SMS para administradores
    }

    /**
     * Gera cache key única baseada nos filtros
     *
     * @param string $baseKey
     * @param array $filtros
     * @return string
     */
    private function gerarCacheKey(string $baseKey, array $filtros): string
    {
        if (empty($filtros)) {
            return $baseKey;
        }
        
        $filtrosHash = md5(serialize($filtros));
        return "{$baseKey}_{$filtrosHash}";
    }

    /**
     * Limpa cache de produtos
     *
     * @return void
     */
    public function limparCacheProdutos(): void
    {
        Cache::forget(self::CACHE_KEY_ATIVOS);
        Cache::forget(self::CACHE_KEY_DESTAQUES);
        
        // Limpar cache com filtros também
        // TODO: Implementar limpeza mais inteligente com tags de cache
    }

    /**
     * Obtém produtos com estoque baixo
     *
     * @return Collection
     */
    public function obterProdutosEstoqueBaixo(): Collection
    {
        return Produto::ativos()
            ->whereRaw('estoque <= COALESCE(estoque_minimo, 10)')
            ->orderBy('estoque', 'asc')
            ->get();
    }

    /**
     * Obtém estatísticas de produtos
     *
     * @return array
     */
    public function obterEstatisticas(): array
    {
        return [
            'total' => Produto::count(),
            'ativos' => Produto::ativos()->count(),
            'inativos' => Produto::where('status', 'inativo')->count(),
            'estoque_baixo' => $this->obterProdutosEstoqueBaixo()->count(),
            'valor_total_estoque' => Produto::ativos()->sum(DB::raw('preco * estoque')),
        ];
    }

    /**
     * Obtém os produtos mais caros
     *
     * @param int $limite
     * @return \Illuminate\Support\Collection
     */
    public function obterProdutosMaisCaros(int $limite = 5)
    {
        return Produto::ativos()
            ->select(['nome', 'preco'])
            ->orderBy('preco', 'desc')
            ->limit($limite)
            ->get()
            ->map(function ($p) {
                return [
                    'nome' => $p->nome,
                    'quantidade' => 0,
                    'valor_total' => (float) $p->preco,
                ];
            });
    }
}

