<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScoreColaborador extends Model
{
    use HasFactory;

    protected $table = 'scores_colaboradores';

    protected $fillable = [
        'escritorio_id', 'user_id', 'usuario_nome', 'mes_referencia',
        'score', 'total_tarefas', 'tarefas_concluidas', 'tarefas_no_prazo',
        'tarefas_atrasadas', 'taxa_retrabalho', 'sla_medio_dias',
    ];

    protected $casts = [
        'score'         => 'decimal:2',
        'taxa_retrabalho' => 'decimal:2',
        'sla_medio_dias'  => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
