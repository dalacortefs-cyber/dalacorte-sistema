<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ComentarioDemanda extends Model
{
    use HasFactory;

    protected $table = 'comentarios_demandas';

    protected $fillable = [
        'demanda_id', 'user_id', 'usuario_nome', 'mensagem', 'tipo',
        'arquivo_url', 'nome_arquivo', 'editado', 'data_edicao',
    ];

    protected $casts = [
        'editado'      => 'boolean',
        'data_edicao'  => 'date',
    ];

    public function demanda()
    {
        return $this->belongsTo(Demanda::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
