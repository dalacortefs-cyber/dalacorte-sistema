<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'responsavel_id', 'nome', 'email', 'telefone', 'empresa',
        'cargo', 'origem', 'status', 'servico_interesse',
        'valor_estimado', 'observacoes', 'data_proximo_contato',
    ];

    protected $casts = [
        'valor_estimado'       => 'decimal:2',
        'data_proximo_contato' => 'datetime',
    ];

    public function responsavel()
    {
        return $this->belongsTo(User::class, 'responsavel_id');
    }

    public function scopePorStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeAtivos($query)
    {
        return $query->whereNotIn('status', ['ganho', 'perdido']);
    }
}
