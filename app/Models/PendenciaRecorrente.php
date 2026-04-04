<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PendenciaRecorrente extends Model
{
    use HasFactory;

    protected $table = 'pendencias_recorrentes';

    protected $fillable = [
        'escritorio_id', 'titulo', 'descricao', 'periodicidade', 'dia_vencimento',
        'tipo', 'prioridade', 'empresa_id', 'empresa_nome',
        'responsavel_id', 'responsavel_nome', 'ativa',
        'proxima_geracao', 'ultima_geracao', 'total_ciclos_gerados',
    ];

    protected $casts = [
        'ativa'           => 'boolean',
        'proxima_geracao' => 'date',
        'ultima_geracao'  => 'date',
    ];

    public function demandas()
    {
        return $this->hasMany(Demanda::class, 'origem_recorrente_id');
    }
}
