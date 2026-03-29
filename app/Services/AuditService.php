<?php

namespace App\Services;

use App\Models\LogAuditoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditService
{
    public function registrar(
        string $acao,
        string $modulo,
        ?array $dadosAntes = null,
        ?array $dadosDepois = null,
        ?string $modeloTipo = null,
        ?int $modeloId = null,
        ?string $observacao = null
    ): LogAuditoria {
        return LogAuditoria::create([
            'user_id'      => Auth::id(),
            'acao'         => $acao,
            'modulo'       => $modulo,
            'modelo_tipo'  => $modeloTipo,
            'modelo_id'    => $modeloId,
            'dados_antes'  => $dadosAntes,
            'dados_depois' => $dadosDepois,
            'ip'           => request()->ip(),
            'user_agent'   => request()->userAgent(),
            'observacao'   => $observacao,
        ]);
    }

    public function registrarCriacao(string $modulo, object $modelo, ?string $observacao = null): void
    {
        $this->registrar(
            acao: 'criacao',
            modulo: $modulo,
            dadosDepois: $modelo->toArray(),
            modeloTipo: get_class($modelo),
            modeloId: $modelo->id,
            observacao: $observacao
        );
    }

    public function registrarAtualizacao(string $modulo, object $modelo, array $dadosAntes, ?string $observacao = null): void
    {
        $this->registrar(
            acao: 'atualizacao',
            modulo: $modulo,
            dadosAntes: $dadosAntes,
            dadosDepois: $modelo->toArray(),
            modeloTipo: get_class($modelo),
            modeloId: $modelo->id,
            observacao: $observacao
        );
    }

    public function registrarExclusao(string $modulo, object $modelo, ?string $observacao = null): void
    {
        $this->registrar(
            acao: 'exclusao',
            modulo: $modulo,
            dadosAntes: $modelo->toArray(),
            modeloTipo: get_class($modelo),
            modeloId: $modelo->id,
            observacao: $observacao
        );
    }

    public function registrarLogin(int $userId, bool $sucesso, ?string $email = null): void
    {
        LogAuditoria::create([
            'user_id'     => $sucesso ? $userId : null,
            'acao'        => $sucesso ? 'login_sucesso' : 'login_falha',
            'modulo'      => 'auth',
            'dados_depois' => $email ? ['email' => $email] : null,
            'ip'          => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);
    }

    public function registrarLogout(int $userId): void
    {
        $this->registrar('logout', 'auth');
    }
}
