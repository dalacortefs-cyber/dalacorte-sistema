<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vaga extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'titulo', 'descricao', 'departamento', 'regime', 'local',
        'remoto', 'salario_min', 'salario_max', 'status', 'data_limite',
    ];

    protected $casts = [
        'remoto'      => 'boolean',
        'salario_min' => 'decimal:2',
        'salario_max' => 'decimal:2',
        'data_limite' => 'date',
    ];

    public function candidaturas()
    {
        return $this->hasMany(Candidatura::class);
    }

    public function scopeAbertas($query)
    {
        return $query->where('status', 'aberta');
    }
}
