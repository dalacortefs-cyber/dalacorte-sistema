<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ControleFaturamento extends Model
{
    use HasFactory;

    protected $table = 'controle_faturamento';

    protected $fillable = [
        'empresa_id', 'empresa_nome', 'mes_referencia',
        'receita_bruta', 'acumulado_12_meses', 'ultrapassou_sublimite',
    ];

    protected $casts = [
        'receita_bruta'        => 'decimal:2',
        'acumulado_12_meses'   => 'decimal:2',
        'ultrapassou_sublimite'=> 'boolean',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
