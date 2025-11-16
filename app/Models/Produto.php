<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Produto extends Model
{
    use HasFactory;

    protected $table = 'produtos';

    protected $fillable = [
        'nome', 'descricao', 'preco', 'imagem', 'categoria',
        'estoque', 'status',
    ];

    public $timestamps = true;

    protected $casts = [
        'preco' => 'decimal:2',
        'estoque' => 'integer',
    ];

    public function vendas(): HasMany
    {
        return $this->hasMany(Venda::class);
    }

    public function scopeAtivos(Builder $query): Builder
    {
        return $query->where('status', 'ativo');
    }

    public function getImagemUrlAttribute(): string
    {
        $imagem = $this->imagem;

        if (empty($imagem)) {
            return asset('img/placeholder-product.svg');
        }

        if (Str::startsWith($imagem, ['http://', 'https://'])) {
            return $imagem;
        }

        $imagemNormalizada = ltrim($imagem, '/');

        if (Str::startsWith($imagemNormalizada, 'storage/')) {
            $imagemNormalizada = Str::after($imagemNormalizada, 'storage/');
        }

        if (Str::startsWith($imagemNormalizada, 'public/')) {
            $imagemNormalizada = Str::after($imagemNormalizada, 'public/');
        }

        if (Storage::disk('public')->exists($imagemNormalizada)) {
            return Storage::url($imagemNormalizada);
        }

        if (Str::startsWith($imagem, ['storage/', 'public/'])) {
            return asset($imagem);
        }

        return asset('storage/' . ltrim($imagem, '/'));
    }
}
