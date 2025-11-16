<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';

    protected $fillable = [
        'nome', 'email', 'senha', 'telefone', 'status', 'reset_token', 'reset_token_expira',
        'remember_token', 'remember_token_expira', 'data_criacao', 'data_atualizacao'
    ];

    public $timestamps = false;
}