<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Socio extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id', 'nome', 'cpf', 'participacao', 'tipo', 'email', 'telefone',
    ];

    protected $casts = [
        'participacao' => 'decimal:2',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
