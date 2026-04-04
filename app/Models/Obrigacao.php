<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Obrigacao extends Model
{
    use HasFactory;

    protected $fillable = [
        'escritorio_id', 'nome', 'esfera', 'periodicidade', 'dia_vencimento',
        'dias_antecedencia_envio_cliente', 'sla_dias_internos', 'nivel_criticidade',
        'regimes_aplicaveis', 'ufs_aplicaveis', 'tipo_atividade_aplicavel',
        'requer_empregados', 'centralizada_matriz', 'ativa', 'eh_padrao_sistema',
    ];

    protected $casts = [
        'requer_empregados'   => 'boolean',
        'centralizada_matriz' => 'boolean',
        'ativa'               => 'boolean',
        'eh_padrao_sistema'   => 'boolean',
    ];

    public function escritorio()
    {
        return $this->belongsTo(Escritorio::class);
    }

    public function tarefas()
    {
        return $this->hasMany(TarefaDfs::class);
    }
}
