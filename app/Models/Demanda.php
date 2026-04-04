<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Demanda extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'escritorio_id', 'titulo', 'descricao', 'tipo', 'natureza', 'status', 'prioridade',
        'empresa_id', 'empresa_nome', 'projeto_id', 'projeto_nome',
        'responsavel_id', 'responsavel_nome', 'criado_por_id', 'criado_por_nome',
        'data_abertura', 'data_previsao', 'data_conclusao', 'data_real_conclusao',
        'concluida_no_prazo', 'numero_os', 'periodicidade', 'dia_recorrencia',
        'origem_recorrente_id', 'tags',
    ];

    protected $casts = [
        'data_abertura'       => 'date',
        'data_previsao'       => 'date',
        'data_conclusao'      => 'date',
        'data_real_conclusao' => 'date',
        'concluida_no_prazo'  => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Demanda $demanda) {
            if (empty($demanda->numero_os)) {
                $ano = now()->year;
                $seq = static::whereYear('created_at', $ano)->count() + 1;
                $demanda->numero_os = sprintf('OS-%d-%04d', $ano, $seq);
            }
        });
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function projeto()
    {
        return $this->belongsTo(ProjetoInterno::class, 'projeto_id');
    }

    public function responsavel()
    {
        return $this->belongsTo(User::class, 'responsavel_id');
    }

    public function criadoPor()
    {
        return $this->belongsTo(User::class, 'criado_por_id');
    }

    public function checklistItems()
    {
        return $this->hasMany(ChecklistItem::class)->orderBy('ordem');
    }

    public function comentarios()
    {
        return $this->hasMany(ComentarioDemanda::class)->orderBy('created_at');
    }
}
