<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CertificadoDigital extends Model
{
    use HasFactory;

    protected $table = 'certificados_digitais';

    protected $fillable = [
        'escritorio_id', 'empresa_id', 'empresa_nome', 'tipo',
        'data_validade', 'responsavel', 'arquivo_url', 'status', 'observacoes',
    ];

    protected $casts = [
        'data_validade' => 'date',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function scopeVencendoEm($query, int $dias = 30)
    {
        return $query->where('data_validade', '<=', now()->addDays($dias))
                     ->where('data_validade', '>=', now());
    }
}
