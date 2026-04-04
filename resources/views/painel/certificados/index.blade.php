@extends('layouts.painel')
@section('title', 'Certificados Digitais')
@section('breadcrumb', 'Certificados Digitais')

@section('content')
<div class="page-header" style="display:flex; justify-content:space-between; align-items:center">
    <h1 class="page-title">Certificados Digitais</h1>
    <a href="{{ route('painel.certificados.create') }}" class="btn btn-gold">+ Novo Certificado</a>
</div>

<div class="grid-4" style="margin-bottom:1.25rem">
    <div class="card">
        <div class="card-title">Total</div>
        <div class="kpi-value" style="font-size:1.4rem">{{ $kpis['total'] }}</div>
    </div>
    <div class="card">
        <div class="card-title">Válidos</div>
        <div class="kpi-value kpi-up" style="font-size:1.4rem">{{ $kpis['validos'] }}</div>
    </div>
    <div class="card">
        <div class="card-title">Vencendo em 30d</div>
        <div class="kpi-value kpi-warn" style="font-size:1.4rem">{{ $kpis['vencendo'] }}</div>
    </div>
    <div class="card">
        <div class="card-title">Vencidos</div>
        <div class="kpi-value kpi-down" style="font-size:1.4rem">{{ $kpis['vencidos'] }}</div>
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
                @foreach(['e-CNPJ A1','e-CNPJ A3','e-CPF A1','e-CPF A3','NF-e','CT-e'] as $t)
                    <option value="{{ $t }}" {{ request('tipo') === $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Status</label>
            <select name="status" class="form-control">
                <option value="">Todos</option>
                @foreach(['Válido','Vencido','Revogado'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ $s }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Filtrar</button>
        @if(request()->hasAny(['empresa_id','tipo','status']))
            <a href="{{ route('painel.certificados.index') }}" class="btn btn-ghost">Limpar</a>
        @endif
    </form>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Empresa</th><th>Tipo</th><th>Titular</th>
                    <th>Emissão</th><th>Vencimento</th><th>Status</th><th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($certificados as $cert)
                @php
                    $diasRestantes = now()->diffInDays($cert->data_validade, false);
                    $alerta = $diasRestantes >= 0 && $diasRestantes <= 30;
                @endphp
                <tr>
                    <td style="font-weight:500">{{ $cert->empresa->razao_social }}</td>
                    <td><span class="badge badge-teal">{{ $cert->tipo }}</span></td>
                    <td style="font-size:0.85rem">{{ $cert->titular ?? '—' }}</td>
                    <td style="font-size:0.85rem; color:var(--muted)">
                        {{ $cert->data_emissao?->format('d/m/Y') ?? '—' }}
                    </td>
                    <td style="{{ $cert->status === 'Vencido' ? 'color:#f87171' : ($alerta ? 'color:#fbbf24' : '') }}">
                        {{ $cert->data_validade->format('d/m/Y') }}
                        @if($alerta && $cert->status !== 'Vencido')
                            <span style="font-size:0.75rem; display:block; color:#fbbf24">{{ $diasRestantes }}d</span>
                        @endif
                    </td>
                    <td>
                        @php $sc = match($cert->status) { 'Válido' => 'badge-green', 'Vencido' => 'badge-red', default => 'badge-gray' }; @endphp
                        <span class="badge {{ $sc }}">{{ $cert->status }}</span>
                    </td>
                    <td>
                        <div style="display:flex; gap:0.4rem">
                            <a href="{{ route('painel.certificados.edit', $cert) }}" class="btn btn-ghost btn-sm">Editar</a>
                            <form method="POST" action="{{ route('painel.certificados.destroy', $cert) }}" onsubmit="return confirm('Remover certificado?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">✕</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center; padding:2rem; color:var(--muted)">Nenhum certificado encontrado.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($certificados->hasPages())
        <div style="padding:1rem; border-top:1px solid var(--border)">{{ $certificados->links() }}</div>
    @endif
</div>
@endsection
