<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contrato extends Model
{
    use HasFactory;

    protected $fillable = [
        'escritorio_id', 'empresa_id', 'empresa_nome',
        'valor_mensal', 'dia_vencimento', 'indice_reajuste',
        'periodicidade_reajuste', 'data_inicio', 'data_ultimo_reajuste', 'ativo',
    ];

    protected $casts = [
        'data_inicio'         => 'date',
        'data_ultimo_reajuste'=> 'date',
        'valor_mensal'        => 'decimal:2',
        'ativo'               => 'boolean',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
