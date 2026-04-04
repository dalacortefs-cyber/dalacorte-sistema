<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContaReceber extends Model
{
    use HasFactory;

    protected $table = 'contas_receber';

    protected $fillable = [
        'escritorio_id', 'empresa_id', 'empresa_nome', 'descricao',
        'valor', 'data_vencimento', 'data_pagamento', 'forma_pagamento',
        'status', 'dias_atraso', 'valor_juros', 'valor_multa',
        'competencia', 'tipo_origem',
    ];

    protected $casts = [
        'data_vencimento' => 'date',
        'data_pagamento'  => 'date',
        'valor'           => 'decimal:2',
        'valor_juros'     => 'decimal:2',
        'valor_multa'     => 'decimal:2',
    ];

    public function escritorio()
    {
        return $this->belongsTo(Escritorio::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function scopeAtrasadas($query)
    {
        return $query->where('status', 'Atrasado');
    }

    public function scopePendentes($query)
    {
        return $query->whereIn('status', ['Pendente', 'Atrasado']);
    }
}
