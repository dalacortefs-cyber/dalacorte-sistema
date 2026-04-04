<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Escritorio extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome_escritorio', 'cnpj', 'endereco', 'telefone', 'email',
        'logo_url', 'logo_dark_url', 'cor_primaria', 'cor_secundaria', 'cor_destaque',
        'slogan', 'website', 'portal_titulo', 'portal_boas_vindas',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function empresas()
    {
        return $this->hasMany(Empresa::class);
    }

    public function obrigacoes()
    {
        return $this->hasMany(Obrigacao::class);
    }

    public function tarefas()
    {
        return $this->hasMany(TarefaDfs::class);
    }

    public function notificacoes()
    {
        return $this->hasMany(Notificacao::class);
    }
}
