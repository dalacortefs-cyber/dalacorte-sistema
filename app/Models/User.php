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
        'name', 'email', 'password',
        // Campos legados (API)
        'tipo', 'ativo', 'telefone', 'avatar', 'ultimo_acesso',
        // Campos novos (Painel)
        'role', 'active', 'escritorio_id', 'empresa_id', 'cargo',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'ultimo_acesso'     => 'datetime',
        'ativo'             => 'boolean',
        'active'            => 'boolean',
        'password'          => 'hashed',
    ];

    // ─── JWT (API) ────────────────────────────────────────────────────────────

    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'role'  => $this->role,
            'name'  => $this->name,
            'email' => $this->email,
        ];
    }

    // ─── Helpers de role (Painel) ─────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isGestor(): bool
    {
        return in_array($this->role, ['admin', 'gestor']);
    }

    public function isOperacional(): bool
    {
        return in_array($this->role, ['admin', 'gestor', 'operacional']);
    }

    public function isCliente(): bool
    {
        return $this->role === 'cliente';
    }

    // ─── Relacionamentos (Painel) ─────────────────────────────────────────────

    public function escritorio()
    {
        return $this->belongsTo(Escritorio::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function notificacoes()
    {
        return $this->hasMany(Notificacao::class);
    }

    public function notificacoesNaoLidas()
    {
        return $this->hasMany(Notificacao::class)->where('lida', false);
    }

    public function logAtividades()
    {
        return $this->hasMany(LogAtividade::class);
    }

    public function tarefasDfs()
    {
        return $this->hasMany(TarefaDfs::class, 'responsavel', 'name');
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeAtivos($query)
    {
        return $query->where('active', true);
    }

    public function scopePorRole($query, string $role)
    {
        return $query->where('role', $role);
    }
}
