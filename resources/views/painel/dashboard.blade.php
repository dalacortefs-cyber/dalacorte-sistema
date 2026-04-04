@extends('layouts.painel')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="page-header">
    <h1 class="page-title">Dashboard</h1>
    <p class="page-subtitle">Visão geral · Competência {{ $mesAtual }}</p>
</div>

{{-- ── KPIs Row 1 ──────────────────────────────────────────────────── --}}
<div class="grid-4" style="margin-bottom:1rem">

    <div class="card">
        <div class="card-title">Empresas Ativas</div>
        <div class="kpi-value">{{ number_format($totalEmpresas) }}</div>
        <div class="kpi-sub">clientes em carteira</div>
    </div>

    <div class="card">
        <div class="card-title">MRR (Honorários)</div>
        <div class="kpi-value" style="font-size:1.5rem">
            R$ {{ number_format($mrr, 2, ',', '.') }}
        </div>
        <div class="kpi-sub">
            Recebido: R$ {{ number_format($recebidoMes, 2, ',', '.') }}
        </div>
    </div>

    <div class="card">
        <div class="card-title">Taxa de Adimplência</div>
        <div class="kpi-value {{ $taxaAdimplencia >= 90 ? 'kpi-up' : ($taxaAdimplencia >= 70 ? 'kpi-warn' : 'kpi-down') }}">
            {{ $taxaAdimplencia }}%
        </div>
        <div class="kpi-sub">{{ $inadimplentes }} em atraso no mês</div>
    </div>

    <div class="card">
        <div class="card-title">Tarefas Pendentes</div>
        <div class="kpi-value {{ $tarefasPendentes > 10 ? 'kpi-warn' : 'kpi-up' }}">
            {{ $tarefasPendentes }}
        </div>
        <div class="kpi-sub">no mês atual</div>
    </div>

</div>

{{-- ── KPIs Row 2 ──────────────────────────────────────────────────── --}}
<div class="grid-3" style="margin-bottom:1.75rem">

    <div class="card" style="border-color: {{ $tarefasVencendo7 > 0 ? 'rgba(245,158,11,0.3)' : 'var(--border)' }}">
        <div class="card-title">Vencendo em ≤ 7 dias</div>
        <div class="kpi-value {{ $tarefasVencendo7 > 0 ? 'kpi-warn' : '' }}">
            {{ $tarefasVencendo7 }}
        </div>
        <div class="kpi-sub">obrigações/tarefas</div>
    </div>

    <div class="card" style="border-color: {{ $certifVencendo > 0 ? 'rgba(245,158,11,0.3)' : 'var(--border)' }}">
        <div class="card-title">Certificados Vencendo</div>
        <div class="kpi-value {{ $certifVencendo > 0 ? 'kpi-warn' : '' }}">
            {{ $certifVencendo }}
        </div>
        <div class="kpi-sub">em 30 dias</div>
    </div>

    <div class="card" style="border-color: {{ $certidoesAlerta > 0 ? 'rgba(239,68,68,0.3)' : 'var(--border)' }}">
        <div class="card-title">Certidões com Alerta</div>
        <div class="kpi-value {{ $certidoesAlerta > 0 ? 'kpi-down' : '' }}">
            {{ $certidoesAlerta }}
        </div>
        <div class="kpi-sub">vencidas ou a vencer</div>
    </div>

</div>

{{-- ── 2-col content ────────────────────────────────────────────────── --}}
<div class="grid-2" style="gap:1.25rem; align-items:start">

    {{-- Tarefas Recentes --}}
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem">
            <div class="card-title" style="margin:0">Tarefas Recentes</div>
            <a href="{{ route('painel.tarefas.index') }}" class="btn btn-ghost btn-sm">Ver todas</a>
        </div>

        @forelse($tarefasRecentes as $tarefa)
            <div style="padding: 0.65rem 0; border-bottom: 1px solid var(--border); display:flex; justify-content:space-between; align-items:center">
                <div>
                    <div style="font-size:0.85rem; color:var(--text)">{{ $tarefa->obrigacao_nome }}</div>
                    <div style="font-size:0.75rem; color:var(--muted)">{{ $tarefa->empresa_nome }} · {{ $tarefa->competencia }}</div>
                </div>
                @php
                    $statusClass = match($tarefa->status) {
                        'Concluído'    => 'badge-green',
                        'Em andamento' => 'badge-blue',
                        'Arquivado'    => 'badge-gray',
                        default        => 'badge-yellow',
                    };
                @endphp
                <span class="badge {{ $statusClass }}">{{ $tarefa->status }}</span>
            </div>
        @empty
            <p class="text-muted" style="text-align:center; padding:1rem 0">Nenhuma tarefa recente.</p>
        @endforelse
    </div>

    {{-- Contas a Receber do Mês --}}
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem">
            <div>
                <div class="card-title" style="margin:0">Contas a Receber</div>
                <div style="font-size:0.75rem; color:var(--gold-light)">
                    R$ {{ number_format($totalAReceber, 2, ',', '.') }} pendente
                </div>
            </div>
            <a href="{{ route('painel.financeiro.contas-receber') }}" class="btn btn-ghost btn-sm">Ver todas</a>
        </div>

        @forelse($contasReceberMes as $conta)
            <div style="padding: 0.65rem 0; border-bottom: 1px solid var(--border); display:flex; justify-content:space-between; align-items:center">
                <div>
                    <div style="font-size:0.85rem; color:var(--text)">{{ $conta->empresa_nome }}</div>
                    <div style="font-size:0.75rem; color:var(--muted)">
                        Venc. {{ \Carbon\Carbon::parse($conta->data_vencimento)->format('d/m/Y') }}
                    </div>
                </div>
                <div style="text-align:right">
                    <div style="font-size:0.85rem; color:var(--text)">R$ {{ number_format($conta->valor, 2, ',', '.') }}</div>
                    <span class="badge {{ $conta->status === 'Atrasado' ? 'badge-red' : 'badge-yellow' }}">
                        {{ $conta->status }}
                    </span>
                </div>
            </div>
        @empty
            <p class="text-muted" style="text-align:center; padding:1rem 0">Todas as contas em dia.</p>
        @endforelse
    </div>

</div>

{{-- ── Alertas Críticos ─────────────────────────────────────────────── --}}
@if($alertasCriticos->isNotEmpty())
<div class="card" style="margin-top:1.25rem; border-color: rgba(239,68,68,0.3)">
    <div class="card-title" style="color: #f87171">⚠ Obrigações Críticas Pendentes</div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Obrigação</th>
                    <th>Empresa</th>
                    <th>Competência</th>
                    <th>Vencimento</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($alertasCriticos as $t)
                <tr>
                    <td>{{ $t->obrigacao_nome }}</td>
                    <td>{{ $t->empresa_nome }}</td>
                    <td>{{ $t->competencia }}</td>
                    <td style="color: #f87171">
                        {{ $t->data_vencimento ? \Carbon\Carbon::parse($t->data_vencimento)->format('d/m/Y') : '—' }}
                    </td>
                    <td><span class="badge badge-yellow">{{ $t->status }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection
