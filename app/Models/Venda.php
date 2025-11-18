<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $produto_id
 * @property int $quantidade
 * @property float $valor_total
 * @property string $status
 * @property Produto $produto
 * @property User $user
 */
class Venda extends Model
{
    protected $fillable = [
        'user_id',
        'produto_id',
        'quantidade',
        'valor_total',
        'status',
    ];

    protected $casts = [
        'quantidade' => 'integer',
        'valor_total' => 'decimal:2',
    ];

    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
