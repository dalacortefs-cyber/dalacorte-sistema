<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ControleCnd extends Model
{
    use HasFactory;

    protected $table = 'controle_cnd';

    protected $fillable = [
        'empresa_id', 'empresa_nome', 'esfera',
        'data_consulta', 'status', 'data_validade', 'arquivo_url', 'observacoes',
    ];

    protected $casts = [
        'data_consulta' => 'date',
        'data_validade' => 'date',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
