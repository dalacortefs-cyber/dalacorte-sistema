<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notificacao extends Model
{
    use HasFactory;

    protected $table = 'notificacoes';

    protected $fillable = [
        'escritorio_id', 'user_id', 'tipo', 'prioridade',
        'titulo', 'mensagem', 'link_referencia', 'lida', 'data_leitura',
    ];

    protected $casts = [
        'lida'         => 'boolean',
        'data_leitura' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeNaoLidas($query)
    {
        return $query->where('lida', false);
    }
}
