<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReceitaExtra extends Model
{
    use HasFactory;

    protected $table = 'receitas_extras';

    protected $fillable = [
        'escritorio_id', 'empresa_id', 'empresa_nome', 'tipo',
        'descricao', 'valor_total', 'parcelas', 'valor_parcela',
        'data_emissao', 'arquivo_url',
    ];

    protected $casts = [
        'data_emissao' => 'date',
        'valor_total'  => 'decimal:2',
        'valor_parcela'=> 'decimal:2',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
