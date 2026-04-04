<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IndicadorEstrategico extends Model
{
    use HasFactory;

    protected $table = 'indicadores_estrategicos';

    protected $fillable = [
        'escritorio_id', 'competencia', 'total_empresas_ativas',
        'mrr', 'receita_honorarios', 'receita_extras', 'receita_total',
        'taxa_inadimplencia', 'total_tarefas', 'tarefas_concluidas', 'tarefas_no_prazo',
        'taxa_conclusao_prazo', 'sla_medio_dias',
        'certificados_vencendo', 'certidoes_vencendo', 'obrigacoes_criticas_pendentes',
    ];

    protected $casts = [
        'mrr'                  => 'decimal:2',
        'receita_honorarios'   => 'decimal:2',
        'receita_extras'       => 'decimal:2',
        'receita_total'        => 'decimal:2',
        'taxa_inadimplencia'   => 'decimal:2',
        'taxa_conclusao_prazo' => 'decimal:2',
        'sla_medio_dias'       => 'decimal:2',
    ];

    public function escritorio()
    {
        return $this->belongsTo(Escritorio::class);
    }
}
