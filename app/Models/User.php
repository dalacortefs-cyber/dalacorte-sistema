<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $fillable = [
        'name', 'email', 'password', 'tipo',
        'ativo', 'telefone', 'avatar', 'ultimo_acesso',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'ultimo_acesso'     => 'datetime',
        'ativo'             => 'boolean',
        'password'          => 'hashed',
    ];

    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'tipo'  => $this->tipo,
            'name'  => $this->name,
            'email' => $this->email,
        ];
    }

    // Relationships
    public function clientes()
    {
        return $this->hasMany(Cliente::class);
    }

    public function tarefas()
    {
        return $this->hasMany(Tarefa::class, 'responsavel_id');
    }

    public function extratos()
    {
        return $this->hasMany(Extrato::class);
    }

    public function noticias()
    {
        return $this->hasMany(Noticia::class);
    }

    public function logsAuditoria()
    {
        return $this->hasMany(LogAuditoria::class);
    }

    // Scopes
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    public function scopePorTipo($query, string $tipo)
    {
        return $query->where('tipo', $tipo);
    }
}
