<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Certidao extends Model
{
    use HasFactory;

    protected $table = 'certidoes';

    protected $fillable = [
        'escritorio_id', 'empresa_id', 'empresa_nome', 'tipo',
        'data_emissao', 'data_validade', 'status', 'arquivo_url', 'observacoes',
    ];

    protected $casts = [
        'data_emissao'  => 'date',
        'data_validade' => 'date',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function scopeVencidas($query)
    {
        return $query->where('status', 'Vencida');
    }

    public function scopeAVencer($query, int $dias = 30)
    {
        return $query->where('data_validade', '<=', now()->addDays($dias))
                     ->where('data_validade', '>=', now())
                     ->where('status', '!=', 'Vencida');
    }
}
