<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'produtos';

    protected $fillable = [
        'nome',
        'descricao',
        'preco',
        'imagem',
    ];

    protected $appends = [
        'name',
        'description',
        'price',
        'image',
        'image_url',
    ];

    public function getNameAttribute(): ?string
    {
        return $this->attributes['nome'] ?? null;
    }

    public function setNameAttribute(?string $value): void
    {
        $this->attributes['nome'] = $value;
    }

    public function getDescriptionAttribute(): ?string
    {
        return $this->attributes['descricao'] ?? null;
    }

    public function setDescriptionAttribute(?string $value): void
    {
        $this->attributes['descricao'] = $value;
    }

    public function getPriceAttribute(): ?float
    {
        $value = $this->attributes['preco'] ?? null;

        return $value === null ? null : (float) $value;
    }

    public function setPriceAttribute($value): void
    {
        $this->attributes['preco'] = $value;
    }

    public function getImageAttribute(): ?string
    {
        return $this->attributes['imagem'] ?? null;
    }

    public function setImageAttribute(?string $value): void
    {
        $this->attributes['imagem'] = $value;
    }

    public function getImageUrlAttribute(): string
    {
        $img = $this->imagem ?? null;

        return $img ? asset($img) : asset('img/pexe.png');
    }
}
