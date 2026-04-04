@extends('layouts.painel')
@section('title', 'Certidões')
@section('breadcrumb', 'Certidões')

@section('content')
<div class="page-header" style="display:flex; justify-content:space-between; align-items:center">
    <h1 class="page-title">Certidões</h1>
    <a href="{{ route('painel.certidoes.create') }}" class="btn btn-gold">+ Nova Certidão</a>
</div>

<div class="grid-4" style="margin-bottom:1.25rem">
    <div class="card">
        <div class="card-title">Total</div>
        <div class="kpi-value" style="font-size:1.4rem">{{ $kpis['total'] }}</div>
    </div>
    <div class="card">
        <div class="card-title">Válidas</div>
        <div class="kpi-value kpi-up" style="font-size:1.4rem">{{ $kpis['validas'] }}</div>
    </div>
    <div class="card">
        <div class="card-title">Vencendo em 30d</div>
        <div class="kpi-value kpi-warn" style="font-size:1.4rem">{{ $kpis['vencendo'] }}</div>
    </div>
    <div class="card">
        <div class="card-title">Vencidas</div>
        <div class="kpi-value kpi-down" style="font-size:1.4rem">{{ $kpis['vencidas'] }}</div>
    </div>
</div>

<div class="card" style="margin-bottom:1.25rem; padding:1rem">
    <form method="GET" style="display:flex; gap:0.75rem; flex-wrap:wrap; align-items:flex-end">
        <div>
            <label class="form-label">Empresa</label>
            <select name="empresa_id" class="form-control">
                <option value="">Todas</option>
                @foreach($empresas as $e)
                    <option value="{{ $e->id }}" {{ request('empresa_id') == $e->id ? 'selected' : '' }}>{{ $e->razao_social }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Tipo</label>
            <select name="tipo" class="form-control">
                <option value="">Todos</option>
                @foreach(['CND Federal','CND Estadual','CND Municipal','FGTS','Trabalhista','Outros'] as $t)
                    <option value="{{ $t }}" {{ request('tipo') === $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Status</label>
            <select name="status" class="form-control">
                <option value="">Todos</option>
                @foreach(['Válida','Vencida','Pendente'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ $s }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Filtrar</button>
        @if(request()->hasAny(['empresa_id','tipo','status']))
            <a href="{{ route('painel.certidoes.index') }}" class="btn btn-ghost">Limpar</a>
        @endif
    </form>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Empresa</th><th>Tipo</th><th>Emissão</th><th>Validade</th>
                    <th>Status</th><th>Responsável</th><th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($certidoes as $c)
                @php
                    $diasRestantes = now()->diffInDays($c->data_validade, false);
                    $alerta = $diasRestantes >= 0 && $diasRestantes <= 30;
                @endphp
                <tr>
                    <td style="font-weight:500">{{ $c->empresa->razao_social }}</td>
                    <td>{{ $c->tipo }}</td>
                    <td style="font-size:0.85rem; color:var(--muted)">
                        {{ $c->data_emissao?->format('d/m/Y') ?? '—' }}
                    </td>
                    <td style="{{ $c->status === 'Vencida' ? 'color:#f87171' : ($alerta ? 'color:#fbbf24' : '') }}">
                        {{ $c->data_validade->format('d/m/Y') }}
                        @if($alerta && $c->status !== 'Vencida')
                            <span style="font-size:0.75rem; display:block; color:#fbbf24">{{ $diasRestantes }}d restantes</span>
                        @endif
                    </td>
                    <td>
                        @php $sc = match($c->status) { 'Válida' => 'badge-green', 'Vencida' => 'badge-red', default => 'badge-yellow' }; @endphp
                        <span class="badge {{ $sc }}">{{ $c->status }}</span>
                    </td>
                    <td style="font-size:0.85rem; color:var(--muted)">{{ $c->responsavel ?? '—' }}</td>
                    <td>
                        <div style="display:flex; gap:0.4rem">
                            @if($c->arquivo_url)
                            <a href="{{ $c->arquivo_url }}" target="_blank" class="btn btn-ghost btn-sm">PDF</a>
                            @endif
                            <a href="{{ route('painel.certidoes.edit', $c) }}" class="btn btn-ghost btn-sm">Editar</a>
                            <form method="POST" action="{{ route('painel.certidoes.destroy', $c) }}" onsubmit="return confirm('Remover certidão?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">✕</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center; padding:2rem; color:var(--muted)">Nenhuma certidão encontrada.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($certidoes->hasPages())
        <div style="padding:1rem; border-top:1px solid var(--border)">{{ $certidoes->links() }}</div>
    @endif
</div>
@endsection
