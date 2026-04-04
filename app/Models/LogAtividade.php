<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogAtividade extends Model
{
    use HasFactory;

    protected $table = 'log_atividades';

    protected $fillable = [
        'escritorio_id', 'user_id', 'usuario_nome', 'modulo', 'acao',
        'registro_id', 'descricao', 'dados_anteriores', 'dados_novos', 'ip',
    ];

    protected $casts = [
        'dados_anteriores' => 'array',
        'dados_novos'      => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function registrar(string $modulo, string $acao, string $descricao, $registro = null, array $anterior = [], array $novo = []): void
    {
        static::create([
            'escritorio_id'   => auth()->user()?->escritorio_id,
            'user_id'         => auth()->id(),
            'usuario_nome'    => auth()->user()?->name ?? 'Sistema',
            'modulo'          => $modulo,
            'acao'            => $acao,
            'registro_id'     => $registro?->id ?? $registro,
            'descricao'       => $descricao,
            'dados_anteriores'=> $anterior ?: null,
            'dados_novos'     => $novo ?: null,
            'ip'              => request()->ip(),
        ]);
    }
}
