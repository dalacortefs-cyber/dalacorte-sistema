<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContaPagar extends Model
{
    use HasFactory;

    protected $table = 'contas_pagar';

    protected $fillable = [
        'descricao', 'fornecedor', 'categoria', 'valor',
        'data_vencimento', 'data_pagamento', 'forma_pagamento',
        'status', 'competencia', 'recorrente',
    ];

    protected $casts = [
        'data_vencimento' => 'date',
        'data_pagamento'  => 'date',
        'valor'           => 'decimal:2',
        'recorrente'      => 'boolean',
    ];
}
