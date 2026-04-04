<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TarefaDfs extends Model
{
    use HasFactory;

    protected $table = 'tarefas_dfs';

    protected $fillable = [
        'escritorio_id', 'empresa_id', 'empresa_nome',
        'obrigacao_id', 'obrigacao_personalizada_id', 'obrigacao_nome',
        'competencia', 'data_vencimento', 'data_expectativa_envio',
        'status', 'responsavel', 'data_conclusao', 'data_real_conclusao',
        'usuario_conclusao', 'comprovante_url', 'observacoes',
        'esfera', 'periodicidade', 'nivel_criticidade',
        'foi_retrabalho', 'concluida_no_prazo',
    ];

    protected $casts = [
        'data_vencimento'        => 'date',
        'data_expectativa_envio' => 'date',
        'data_conclusao'         => 'date',
        'data_real_conclusao'    => 'date',
        'foi_retrabalho'         => 'boolean',
        'concluida_no_prazo'     => 'boolean',
    ];

    public function escritorio()
    {
        return $this->belongsTo(Escritorio::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function obrigacao()
    {
        return $this->belongsTo(Obrigacao::class);
    }

    public function obrigacaoPersonalizada()
    {
        return $this->belongsTo(ObrigacaoPersonalizada::class);
    }

    public function scopePendentes($query)
    {
        return $query->whereIn('status', ['Pendente', 'Em andamento']);
    }

    public function scopeVencendoEm($query, int $dias)
    {
        return $query->where('data_vencimento', '<=', now()->addDays($dias))
                     ->where('data_vencimento', '>=', now())
                     ->where('status', '!=', 'Concluído');
    }
}
