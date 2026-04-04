@extends('layouts.painel')
@section('title', 'Usuários')
@section('breadcrumb', 'Usuários')

@section('content')
<div class="page-header" style="display:flex; justify-content:space-between; align-items:flex-start">
    <div>
        <h1 class="page-title">Gestão de Usuários</h1>
        <p class="page-subtitle">{{ $usuarios->total() }} usuário(s)</p>
    </div>
    <a href="{{ route('painel.usuarios.create') }}" class="btn btn-primary">+ Novo Usuário</a>
</div>

@if(session('nova_senha'))
    <div class="alert alert-warn" style="font-family:monospace; font-size:1rem">
        🔑 Nova senha gerada: <strong>{{ session('nova_senha') }}</strong> — copie agora, ela não será exibida novamente.
    </div>
@endif

<div class="card" style="margin-bottom:1.25rem; padding:1rem">
    <form method="GET" style="display:flex; gap:0.75rem; flex-wrap:wrap; align-items:flex-end">
        <div>
            <label class="form-label">Perfil</label>
            <select name="role" class="form-control">
                <option value="">Todos</option>
                @foreach(['admin','gestor','operacional','cliente'] as $r)
                    <option value="{{ $r }}" {{ request('role') === $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Status</label>
            <select name="ativo" class="form-control">
                <option value="">Todos</option>
                <option value="1" {{ request('ativo') === '1' ? 'selected' : '' }}>Ativos</option>
                <option value="0" {{ request('ativo') === '0' ? 'selected' : '' }}>Inativos</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Filtrar</button>
        <a href="{{ route('painel.usuarios.index') }}" class="btn btn-ghost">Limpar</a>
    </form>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Perfil</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usuarios as $usuario)
                <tr>
                    <td>
                        <div style="display:flex; align-items:center; gap:0.6rem">
                            <div style="width:32px; height:32px; background:var(--teal); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:bold; flex-shrink:0">
                                {{ strtoupper(substr($usuario->name, 0, 2)) }}
                            </div>
                            <div>
                                <div style="font-weight:500">{{ $usuario->name }}</div>
                                @if($usuario->cargo)
                                    <div style="font-size:0.75rem; color:var(--muted)">{{ $usuario->cargo }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td style="color:var(--muted); font-size:0.875rem">{{ $usuario->email }}</td>
                    <td>
                        @php
                            $roleClass = match($usuario->role) {
                                'admin'      => 'badge-teal',
                                'gestor'     => 'badge-gold',
                                'operacional'=> 'badge-blue',
                                default      => 'badge-gray',
                            };
                        @endphp
                        <span class="badge {{ $roleClass }}">{{ ucfirst($usuario->role) }}</span>
                    </td>
                    <td>
                        <span class="badge {{ $usuario->active ? 'badge-green' : 'badge-red' }}">
                            {{ $usuario->active ? 'Ativo' : 'Inativo' }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex; gap:0.4rem; flex-wrap:wrap">
                            <a href="{{ route('painel.usuarios.edit', $usuario) }}" class="btn btn-ghost btn-sm">Editar</a>

                            <form method="POST" action="{{ route('painel.usuarios.toggle-ativo', $usuario) }}" style="display:inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-ghost btn-sm">
                                    {{ $usuario->active ? 'Desativar' : 'Ativar' }}
                                </button>
                            </form>

                            <form method="POST" action="{{ route('painel.usuarios.reset-senha', $usuario) }}" style="display:inline"
                                  onsubmit="return confirm('Resetar a senha de {{ $usuario->name }}?')">
                                @csrf
                                <button type="submit" class="btn btn-ghost btn-sm">Reset Senha</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center; padding:2rem; color:var(--muted)">Nenhum usuário encontrado.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($usuarios->hasPages())
        <div style="padding:1rem; border-top:1px solid var(--border)">{{ $usuarios->links() }}</div>
    @endif
</div>
@endsection
