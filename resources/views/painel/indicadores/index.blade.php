@extends('layouts.painel')
@section('title', 'Indicadores')
@section('breadcrumb', 'Indicadores')

@section('content')
<div class="page-header" style="display:flex; justify-content:space-between; align-items:center">
    <h1 class="page-title">Indicadores & KPIs</h1>
    <form method="GET" style="display:flex; gap:0.5rem; align-items:center">
        <select name="ano" class="form-control" onchange="this.form.submit()">
            @for($y = now()->year; $y >= now()->year - 2; $y--)
                <option value="{{ $y }}" {{ request('ano', now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>
    </form>
</div>

{{-- KPI cards row --}}
<div class="grid-4" style="margin-bottom:1.5rem">
    <div class="card">
        <div class="card-title">MRR Atual</div>
        <div class="kpi-value" style="font-size:1.5rem">R$ {{ number_format($kpis['mrr'], 2, ',', '.') }}</div>
        <div style="font-size:0.8rem; color:var(--muted); margin-top:0.25rem">Receita Recorrente Mensal</div>
    </div>
    <div class="card">
        <div class="card-title">Adimplência</div>
        <div class="kpi-value kpi-up" style="font-size:1.5rem">{{ $kpis['adimplencia'] }}%</div>
        <div style="font-size:0.8rem; color:var(--muted); margin-top:0.25rem">Clientes em dia</div>
    </div>
    <div class="card">
        <div class="card-title">Entregas no Prazo</div>
        <div class="kpi-value kpi-up" style="font-size:1.5rem">{{ $kpis['entrega_prazo'] }}%</div>
        <div style="font-size:0.8rem; color:var(--muted); margin-top:0.25rem">Tarefas concluídas a tempo</div>
    </div>
    <div class="card">
        <div class="card-title">Empresas Ativas</div>
        <div class="kpi-value" style="font-size:1.5rem">{{ $kpis['empresas_ativas'] }}</div>
        <div style="font-size:0.8rem; color:var(--muted); margin-top:0.25rem">Clientes ativos</div>
    </div>
</div>

{{-- Charts row 1 --}}
<div class="grid-2" style="gap:1.25rem; margin-bottom:1.25rem">
    <div class="card">
        <div class="card-title" style="margin-bottom:1rem">MRR — Últimos 6 Meses</div>
        <canvas id="chartMrr" height="200"></canvas>
    </div>
    <div class="card">
        <div class="card-title" style="margin-bottom:1rem">Tarefas por Status</div>
        <canvas id="chartTarefas" height="200"></canvas>
    </div>
</div>

{{-- Charts row 2 --}}
<div class="grid-2" style="gap:1.25rem; margin-bottom:1.25rem">
    <div class="card">
        <div class="card-title" style="margin-bottom:1rem">Empresas por Regime</div>
        <canvas id="chartRegimes" height="200"></canvas>
    </div>
    <div class="card">
        <div class="card-title" style="margin-bottom:1rem">Taxa de Entrega no Prazo (meses)</div>
        <canvas id="chartEntrega" height="200"></canvas>
    </div>
</div>

{{-- Indicadores estratégicos --}}
@if(isset($indicadores) && $indicadores->count())
<div class="card">
    <div class="card-title" style="margin-bottom:1rem">Indicadores Estratégicos</div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Indicador</th><th>Período</th><th>Meta</th><th>Realizado</th><th>Atingimento</th></tr>
            </thead>
            <tbody>
                @foreach($indicadores as $ind)
                @php
                    $pct = $ind->meta > 0 ? round(($ind->realizado / $ind->meta) * 100) : 0;
                    $cor = $pct >= 100 ? '#34d399' : ($pct >= 70 ? '#fbbf24' : '#f87171');
                @endphp
                <tr>
                    <td style="font-weight:500">{{ $ind->nome }}</td>
                    <td style="color:var(--muted)">{{ $ind->periodo }}</td>
                    <td>{{ $ind->meta }}</td>
                    <td>{{ $ind->realizado }}</td>
                    <td>
                        <div style="display:flex; align-items:center; gap:0.5rem">
                            <div style="flex:1; height:6px; background:var(--border); border-radius:3px">
                                <div style="width:{{ min($pct, 100) }}%; height:100%; background:{{ $cor }}; border-radius:3px"></div>
                            </div>
                            <span style="font-size:0.8rem; color:{{ $cor }}; min-width:2.5rem">{{ $pct }}%</span>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@push('scripts')
<script>
const chartDefaults = {
    plugins: { legend: { labels: { color: '#b0bec5' } } },
    scales: {
        x: { ticks: { color: '#b0bec5' }, grid: { color: 'rgba(255,255,255,0.05)' } },
        y: { ticks: { color: '#b0bec5' }, grid: { color: 'rgba(255,255,255,0.05)' } }
    }
};

// MRR chart
fetch('{{ route("painel.indicadores.dados") }}?tipo=mrr&ano={{ request("ano", now()->year) }}')
    .then(r => r.json())
    .then(data => {
        new Chart(document.getElementById('chartMrr'), {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'MRR (R$)',
                    data: data.values,
                    borderColor: '#1B4A52',
                    backgroundColor: 'rgba(27,74,82,0.2)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#8B6914',
                }]
            },
            options: { ...chartDefaults, responsive: true }
        });
    });

// Tarefas por status
fetch('{{ route("painel.indicadores.dados") }}?tipo=tarefas_status')
    .then(r => r.json())
    .then(data => {
        new Chart(document.getElementById('chartTarefas'), {
            type: 'doughnut',
            data: {
                labels: data.labels,
                datasets: [{
                    data: data.values,
                    backgroundColor: ['#fbbf24','#60a5fa','#34d399','#9ca3af'],
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { labels: { color: '#b0bec5' } } }
            }
        });
    });

// Regimes tributários
fetch('{{ route("painel.indicadores.dados") }}?tipo=regimes')
    .then(r => r.json())
    .then(data => {
        new Chart(document.getElementById('chartRegimes'), {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Empresas',
                    data: data.values,
                    backgroundColor: ['#1B4A52','#8B6914','#60a5fa','#34d399'],
                }]
            },
            options: { ...chartDefaults, responsive: true, plugins: { legend: { display: false } } }
        });
    });

// Entrega no prazo
fetch('{{ route("painel.indicadores.dados") }}?tipo=entrega_prazo&ano={{ request("ano", now()->year) }}')
    .then(r => r.json())
    .then(data => {
        new Chart(document.getElementById('chartEntrega'), {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: '% no prazo',
                    data: data.values,
                    backgroundColor: 'rgba(52,211,153,0.6)',
                    borderColor: '#34d399',
                    borderWidth: 1,
                }]
            },
            options: {
                ...chartDefaults,
                responsive: true,
                scales: {
                    ...chartDefaults.scales,
                    y: { ...chartDefaults.scales.y, min: 0, max: 100 }
                }
            }
        });
    });
</script>
@endpush
@endsection
