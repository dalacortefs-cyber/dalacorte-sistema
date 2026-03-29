<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Candidatura extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vaga_id', 'nome', 'email', 'telefone', 'linkedin',
        'curriculo_path', 'carta_apresentacao', 'status',
        'observacoes_internas', 'pretensao_salarial',
    ];

    protected $casts = [
        'pretensao_salarial' => 'decimal:2',
    ];

    public function vaga()
    {
        return $this->belongsTo(Vaga::class);
    }

    public function scopePorStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}
