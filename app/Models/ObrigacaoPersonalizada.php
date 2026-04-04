<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ObrigacaoPersonalizada extends Model
{
    use HasFactory;

    protected $table = 'obrigacoes_personalizadas';

    protected $fillable = [
        'empresa_id', 'empresa_nome', 'obrigacao_id', 'nome_obrigacao',
        'esfera', 'periodicidade', 'dia_vencimento', 'ativa',
        'motivo_excecao', 'data_inicio', 'data_fim',
    ];

    protected $casts = [
        'ativa'       => 'boolean',
        'data_inicio' => 'date',
        'data_fim'    => 'date',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function obrigacao()
    {
        return $this->belongsTo(Obrigacao::class);
    }
}
