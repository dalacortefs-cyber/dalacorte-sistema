<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Configuracao extends Model
{
    use HasFactory;

    protected $table = 'configuracoes';

    protected $fillable = ['chave', 'valor', 'descricao', 'tipo'];

    public static function get(string $chave, mixed $default = null): mixed
    {
        $config = static::where('chave', $chave)->first();
        if (!$config) return $default;

        return match($config->tipo) {
            'boolean' => (bool) $config->valor,
            'number'  => (float) $config->valor,
            'json'    => json_decode($config->valor, true),
            default   => $config->valor,
        };
    }

    public static function set(string $chave, mixed $valor, string $tipo = 'string'): void
    {
        static::updateOrCreate(
            ['chave' => $chave],
            ['valor' => is_array($valor) ? json_encode($valor) : (string) $valor, 'tipo' => $tipo]
        );
    }
}
