@extends('layouts.painel')
@section('title', 'Sócios — '.$empresa->razao_social)
@section('breadcrumb', 'Empresas / Sócios')

@section('content')
<div class="page-header" style="display:flex; justify-content:space-between; align-items:center">
    <div>
        <h1 class="page-title">Sócios</h1>
        <p class="page-subtitle">{{ $empresa->razao_social }}</p>
    </div>
    <a href="{{ route('painel.empresas.show', $empresa) }}" class="btn btn-ghost">← Voltar</a>
</div>

<div class="grid-2" style="gap:1.25rem; align-items:start">

    {{-- Lista de sócios --}}
    <div class="card">
        <div class="card-title" style="margin-bottom:1rem">Sócios Cadastrados</div>
        @forelse($empresa->socios as $socio)
        <div style="padding:1rem 0; border-bottom:1px solid var(--border)">
            <div style="display:flex; justify-content:space-between; align-items:flex-start">
                <div>
                    <div style="font-weight:500">{{ $socio->nome }}</div>
                    <div style="font-size:0.8rem; color:var(--muted); margin-top:0.2rem">
                        CPF: {{ $socio->cpf }}
                        @if($socio->tipo) · {{ $socio->tipo }} @endif
                        @if($socio->participacao) · {{ number_format($socio->participacao,1) }}% @endif
                    </div>
                    @if($socio->email || $socio->telefone)
                    <div style="font-size:0.8rem; color:var(--muted); margin-top:0.15rem">
                        {{ $socio->email }} {{ $socio->telefone ? '· '.$socio->telefone : '' }}
                    </div>
                    @endif
                </div>
                <form method="POST" action="{{ route('painel.empresas.socios.destroy', [$empresa, $socio]) }}"
                      onsubmit="return confirm('Remover {{ $socio->nome }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Remover</button>
                </form>
            </div>
        </div>
        @empty
        <p class="text-muted" style="text-align:center; padding:1.5rem 0">Nenhum sócio cadastrado.</p>
        @endforelse
    </div>

    {{-- Form novo sócio --}}
    <div class="card">
        <div class="card-title" style="margin-bottom:1rem">Adicionar Sócio</div>
        <form method="POST" action="{{ route('painel.empresas.socios.store', $empresa) }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Nome Completo *</label>
                <input type="text" name="nome" class="form-control" value="{{ old('nome') }}" required>
                @error('nome')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">CPF *</label>
                <input type="text" name="cpf" class="form-control" value="{{ old('cpf') }}" placeholder="000.000.000-00" required>
                @error('cpf')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="grid-2" style="gap:0.75rem">
                <div class="form-group">
                    <label class="form-label">Tipo</label>
                    <select name="tipo" class="form-control">
                        <option value="">—</option>
                        @foreach(['Administrador','Sócio','Sócio-Administrador'] as $t)
                            <option value="{{ $t }}" {{ old('tipo') === $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Participação (%)</label>
                    <input type="number" name="participacao" class="form-control" step="0.01" min="0" max="100" value="{{ old('participacao') }}">
                </div>
            </div>
            <div class="grid-2" style="gap:0.75rem">
                <div class="form-group">
                    <label class="form-label">E-mail</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Telefone</label>
                    <input type="text" name="telefone" class="form-control" value="{{ old('telefone') }}">
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%">Adicionar Sócio</button>
        </form>
    </div>

</div>
@endsection
