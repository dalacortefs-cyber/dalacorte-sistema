@extends('layouts.painel')
@section('title', 'Empresas')
@section('breadcrumb', 'Empresas')

@section('content')
<div class="page-header" style="display:flex; justify-content:space-between; align-items:flex-start">
    <div>
        <h1 class="page-title">Empresas</h1>
        <p class="page-subtitle">{{ $empresas->total() }} empresa(s) cadastrada(s)</p>
    </div>
    <a href="{{ route('painel.empresas.create') }}" class="btn btn-primary">+ Nova Empresa</a>
</div>

{{-- Filtros --}}
<div class="card" style="margin-bottom:1.25rem; padding:1rem">
    <form method="GET" style="display:flex; gap:0.75rem; flex-wrap:wrap; align-items:flex-end">
        <div style="flex:1; min-width:200px">
            <label class="form-label">Buscar</label>
            <input type="text" name="busca" class="form-control" value="{{ request('busca') }}" placeholder="Razão social, CNPJ...">
        </div>
        <div>
            <label class="form-label">Regime</label>
            <select name="regime" class="form-control">
                <option value="">Todos</option>
                @foreach(['MEI','Simples Nacional','Lucro Presumido','Lucro Real'] as $r)
                    <option value="{{ $r }}" {{ request('regime') === $r ? 'selected' : '' }}>{{ $r }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Status</label>
            <select name="status" class="form-control">
                <option value="">Todos</option>
                @foreach(['Ativa','Inativa','Suspensa'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ $s }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Filtrar</button>
        <a href="{{ route('painel.empresas.index') }}" class="btn btn-ghost">Limpar</a>
    </form>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Razão Social</th>
                    <th>CNPJ</th>
                    <th>Regime</th>
                    <th>Honorário</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($empresas as $empresa)
                <tr>
                    <td>
                        <a href="{{ route('painel.empresas.show', $empresa) }}" style="color:var(--text); text-decoration:none; font-weight:500">
                            {{ $empresa->razao_social }}
                        </a>
                        @if($empresa->nome_fantasia)
                            <div style="font-size:0.75rem; color:var(--muted)">{{ $empresa->nome_fantasia }}</div>
                        @endif
                    </td>
                    <td style="font-family:monospace; font-size:0.85rem">{{ $empresa->cnpj }}</td>
                    <td>
                        @if($empresa->regime_tributario)
                            <span class="badge badge-teal">{{ $empresa->regime_tributario }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @if($empresa->valor_honorario_mensal)
                            R$ {{ number_format($empresa->valor_honorario_mensal, 2, ',', '.') }}
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @php $sc = match($empresa->status) { 'Ativa' => 'badge-green', 'Inativa' => 'badge-gray', default => 'badge-yellow' }; @endphp
                        <span class="badge {{ $sc }}">{{ $empresa->status }}</span>
                    </td>
                    <td>
                        <div style="display:flex; gap:0.4rem">
                            <a href="{{ route('painel.empresas.show', $empresa) }}" class="btn btn-ghost btn-sm">Ver</a>
                            <a href="{{ route('painel.empresas.edit', $empresa) }}" class="btn btn-ghost btn-sm">Editar</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:2rem; color:var(--muted)">
                        Nenhuma empresa encontrada.
                        <a href="{{ route('painel.empresas.create') }}" style="color:var(--gold-light)">Cadastrar primeira empresa</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($empresas->hasPages())
        <div style="padding:1rem; border-top: 1px solid var(--border)">
            {{ $empresas->links() }}
        </div>
    @endif
</div>
@endsection
