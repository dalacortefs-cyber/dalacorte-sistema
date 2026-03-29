<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tarefa extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'responsavel_id', 'cliente_id', 'titulo',
        'descricao', 'prioridade', 'status', 'categoria',
        'data_vencimento', 'data_conclusao',
    ];

    protected $casts = [
        'data_vencimento' => 'datetime',
        'data_conclusao'  => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function responsavel()
    {
        return $this->belongsTo(User::class, 'responsavel_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function scopePendentes($query)
    {
        return $query->whereIn('status', ['pendente', 'em_andamento']);
    }

    public function scopeVencidas($query)
    {
        return $query->where('data_vencimento', '<', now())
                     ->whereNotIn('status', ['concluida', 'cancelada']);
    }

    public function getAtrasadaAttribute(): bool
    {
        return $this->data_vencimento
            && $this->data_vencimento->isPast()
            && !in_array($this->status, ['concluida', 'cancelada']);
    }
}
