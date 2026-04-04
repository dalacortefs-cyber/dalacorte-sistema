<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjetoInterno extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'projetos_internos';

    protected $fillable = [
        'escritorio_id', 'titulo', 'descricao', 'responsavel_id', 'responsavel_nome',
        'status', 'prioridade', 'data_inicio', 'data_previsao', 'data_conclusao',
        'cor_identificacao',
    ];

    protected $casts = [
        'data_inicio'    => 'date',
        'data_previsao'  => 'date',
        'data_conclusao' => 'date',
    ];

    public function responsavel()
    {
        return $this->belongsTo(User::class, 'responsavel_id');
    }

    public function demandas()
    {
        return $this->hasMany(Demanda::class, 'projeto_id');
    }
}
