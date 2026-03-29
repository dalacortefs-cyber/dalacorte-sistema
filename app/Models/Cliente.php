<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'nome', 'email', 'cpf_cnpj', 'tipo_pessoa',
        'telefone', 'celular', 'cep', 'logradouro', 'numero',
        'complemento', 'bairro', 'cidade', 'estado', 'status',
        'observacoes', 'responsavel_onvio', 'codigo_onvio', 'receita_mensal',
    ];

    protected $casts = [
        'receita_mensal' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function extratos()
    {
        return $this->hasMany(Extrato::class);
    }

    public function tarefas()
    {
        return $this->hasMany(Tarefa::class);
    }

    public function scopeAtivos($query)
    {
        return $query->where('status', 'ativo');
    }

    public function scopePorTipo($query, string $tipo)
    {
        return $query->where('tipo_pessoa', $tipo);
    }

    public function getNomeFormatadoAttribute(): string
    {
        return $this->tipo_pessoa === 'juridica'
            ? strtoupper($this->nome)
            : $this->nome;
    }
}
