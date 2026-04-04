<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Documento extends Model
{
    use HasFactory;

    protected $fillable = [
        'escritorio_id', 'empresa_id', 'empresa_nome', 'tipo_documento',
        'descricao', 'data_validade', 'arquivo_url', 'categoria', 'status',
    ];

    protected $casts = [
        'data_validade' => 'date',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
