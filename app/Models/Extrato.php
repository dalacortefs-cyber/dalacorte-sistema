<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Extrato extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'cliente_id', 'user_id', 'nome_arquivo', 'caminho_arquivo',
        'tipo_arquivo', 'banco', 'agencia', 'conta',
        'data_inicio', 'data_fim', 'saldo_inicial', 'saldo_final',
        'total_transacoes', 'total_entradas', 'total_saidas',
        'status', 'dados_processados', 'analise_ia', 'observacoes',
    ];

    protected $casts = [
        'data_inicio'        => 'date',
        'data_fim'           => 'date',
        'saldo_inicial'      => 'decimal:2',
        'saldo_final'        => 'decimal:2',
        'total_entradas'     => 'decimal:2',
        'total_saidas'       => 'decimal:2',
        'dados_processados'  => 'array',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePorStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function getSaldoLiquidoAttribute(): float
    {
        return (float) $this->total_entradas - (float) $this->total_saidas;
    }
}
