@extends('layouts.painel')
@section('title', $usuario ? 'Editar Usuário' : 'Novo Usuário')
@section('breadcrumb', 'Usuários / '.($usuario ? 'Editar' : 'Novo'))

@section('content')
<div class="page-header" style="display:flex; justify-content:space-between; align-items:center">
    <h1 class="page-title">{{ $usuario ? 'Editar Usuário' : 'Novo Usuário' }}</h1>
    <a href="{{ route('painel.usuarios.index') }}" class="btn btn-ghost">← Voltar</a>
</div>

<div style="max-width:620px">
<form method="POST" action="{{ $usuario ? route('painel.usuarios.update', $usuario) : route('painel.usuarios.store') }}">
    @csrf
    @if($usuario) @method('PUT') @endif

    <div class="card" style="margin-bottom:1.25rem">
        <div class="card-title" style="margin-bottom:1rem">Dados do Usuário</div>

        <div class="form-group">
            <label class="form-label">Nome Completo *</label>
            <input type="text" name="name" class="form-control" required
                value="{{ old('name', $usuario?->name) }}" placeholder="Nome do usuário">
            @error('name')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">E-mail *</label>
            <input type="email" name="email" class="form-control" required
                value="{{ old('email', $usuario?->email) }}" placeholder="email@exemplo.com">
            @error('email')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="grid-2" style="gap:0.75rem">
            <div class="form-group">
                <label class="form-label">Perfil de Acesso *</label>
                <select name="role" class="form-control" required>
                    @foreach(['admin' => 'Administrador', 'gestor' => 'Gestor', 'operacional' => 'Operacional', 'cliente' => 'Cliente'] as $value => $label)
                        <option value="{{ $value }}" {{ old('role', $usuario?->role ?? 'operacional') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('role')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Cargo / Função</label>
                <input type="text" name="cargo" class="form-control"
                    value="{{ old('cargo', $usuario?->cargo) }}" placeholder="Ex: Contador, Auxiliar">
            </div>
        </div>

        @if(!$usuario)
        <div class="form-group">
            <label class="form-label">Senha *</label>
            <input type="password" name="password" class="form-control" required
                placeholder="Mínimo 8 caracteres" autocomplete="new-password">
            @error('password')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label class="form-label">Confirmar Senha *</label>
            <input type="password" name="password_confirmation" class="form-control" required
                placeholder="Repita a senha" autocomplete="new-password">
        </div>
        @else
        <div style="background:rgba(139,105,20,0.1); border:1px solid rgba(139,105,20,0.3); border-radius:6px; padding:0.75rem; font-size:0.85rem; color:var(--muted)">
            Para alterar a senha, use a função "Redefinir Senha" na listagem de usuários.
        </div>
        @endif
    </div>

    @if($usuario)
    <div class="card" style="margin-bottom:1.25rem">
        <div class="card-title" style="margin-bottom:1rem">Vínculo com Empresa Cliente</div>
        <div class="form-group">
            <label class="form-label">Empresa (apenas para perfil Cliente)</label>
            <select name="empresa_id" class="form-control">
                <option value="">— Sem empresa vinculada —</option>
                @foreach($empresas as $e)
                    <option value="{{ $e->id }}" {{ old('empresa_id', $usuario?->empresa_id) == $e->id ? 'selected' : '' }}>
                        {{ $e->razao_social }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    @endif

    <div style="display:flex; gap:0.75rem; justify-content:flex-end">
        <a href="{{ route('painel.usuarios.index') }}" class="btn btn-ghost">Cancelar</a>
        <button type="submit" class="btn btn-primary">
            {{ $usuario ? 'Salvar Alterações' : 'Criar Usuário' }}
        </button>
    </div>
</form>
</div>
@endsection
