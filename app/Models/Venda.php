<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
