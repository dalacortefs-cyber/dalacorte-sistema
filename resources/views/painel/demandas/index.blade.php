@extends('layouts.painel')
@section('title', 'Demandas / OS')
@section('breadcrumb', 'Demandas')

@section('content')
<div class="page-header" style="display:flex; justify-content:space-between; align-items:center">
    <h1 class="page-title">Demandas / Ordens de Serviço</h1>
    <a href="{{ route('painel.demandas.create') }}" class="btn btn-gold">+ Nova Demanda</a>
</div>

{{-- Kanban counts --}}
<div class="grid-4" style="margin-bottom:1.25rem">
    @php
        $kanban = [
            'Aberta'      => ['count' => $kpis['aberta']      ?? 0, 'color' => '#fbbf24'],
            'Em andamento'=> ['count' => $kpis['em_andamento'] ?? 0, 'color' => '#60a5fa'],
            'Aguardando'  => ['count' => $kpis['aguardando']   ?? 0, 'color' => '#a78bfa'],
            'Concluída'   => ['count' => $kpis['concluida']    ?? 0, 'color' => '#34d399'],
        ];
    @endphp
    @foreach($kanban as $label => $info)
    <div class="card" style="border-left:3px solid {{ $info['color'] }}">
        <div class="card-title">{{ $label }}</div>
        <div class="kpi-value" style="font-size:1.6rem; color:{{ $info['color'] }}">{{ $info['count'] }}</div>
    </div>
    @endforeach
</div>

<div class="card" style="margin-bottom:1.25rem; padding:1rem">
    <form method="GET" style="display:flex; gap:0.75rem; flex-wrap:wrap; align-items:flex-end">
        <div>
            <label class="form-label">Buscar</label>
            <input type="text" name="busca" class="form-control" placeholder="OS, título..."
                value="{{ request('busca') }}" style="min-width:200px">
        </div>
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
            <label class="form-label">Status</label>
            <select name="status" class="form-control">
                <option value="">Todos</option>
                @foreach(['Aberta','Em andamento','Aguardando cliente','Aguardando terceiros','Concluída','Cancelada'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ $s }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Prioridade</label>
            <select name="prioridade" class="form-control">
                <option value="">Todas</option>
                @foreach(['Baixa','Normal','Alta','Urgente'] as $p)
                    <option value="{{ $p }}" {{ request('prioridade') === $p ? 'selected' : '' }}>{{ $p }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Filtrar</button>
        @if(request()->hasAny(['busca','empresa_id','status','prioridade']))
            <a href="{{ route('painel.demandas.index') }}" class="btn btn-ghost">Limpar</a>
        @endif
    </form>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>OS</th><th>Título</th><th>Empresa</th><th>Prioridade</th>
                    <th>Status</th><th>Prazo</th><th>Responsável</th><th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($demandas as $d)
                @php
                    $prazoCor = '';
                    if ($d->data_prazo && $d->status !== 'Concluída') {
                        $dias = now()->diffInDays($d->data_prazo, false);
                        if ($dias < 0) $prazoCor = '#f87171';
                        elseif ($dias <= 2) $prazoCor = '#fbbf24';
                    }
                @endphp
                <tr>
                    <td style="font-family:monospace; font-size:0.8rem; color:var(--muted)">{{ $d->numero_os }}</td>
                    <td>
                        <a href="{{ route('painel.demandas.show', $d) }}" style="color:var(--text); font-weight:500">
                            {{ Str::limit($d->titulo, 45) }}
                        </a>
                        @if($d->checklist_items_count ?? 0)
                            <span style="font-size:0.75rem; color:var(--muted); margin-left:0.3rem">
                                ({{ $d->checklist_concluidos ?? 0 }}/{{ $d->checklist_items_count }})
                            </span>
                        @endif
                    </td>
                    <td style="font-size:0.85rem">{{ $d->empresa->razao_social }}</td>
                    <td>
                        @php $pc = match($d->prioridade) { 'Urgente' => 'badge-red', 'Alta' => 'badge-yellow', 'Normal' => 'badge-blue', default => 'badge-gray' }; @endphp
                        <span class="badge {{ $pc }}">{{ $d->prioridade }}</span>
                    </td>
                    <td>
                        @php
                            $sc = match($d->status) {
                                'Concluída'  => 'badge-green',
                                'Em andamento' => 'badge-blue',
                                'Cancelada'  => 'badge-gray',
                                'Aguardando cliente', 'Aguardando terceiros' => 'badge-yellow',
                                default => 'badge-yellow'
                            };
                        @endphp
                        <span class="badge {{ $sc }}">{{ $d->status }}</span>
                    </td>
                    <td style="font-size:0.85rem; color:{{ $prazoCor ?: 'var(--text)' }}">
                        {{ $d->data_prazo?->format('d/m/Y') ?? '—' }}
                    </td>
                    <td style="font-size:0.85rem; color:var(--muted)">{{ $d->responsavel ?? '—' }}</td>
                    <td>
                        <div style="display:flex; gap:0.4rem">
                            <a href="{{ route('painel.demandas.show', $d) }}" class="btn btn-ghost btn-sm">Ver</a>
                            <a href="{{ route('painel.demandas.edit', $d) }}" class="btn btn-ghost btn-sm">Editar</a>
                            <form method="POST" action="{{ route('painel.demandas.destroy', $d) }}" onsubmit="return confirm('Remover demanda?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">✕</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center; padding:2rem; color:var(--muted)">Nenhuma demanda encontrada.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($demandas->hasPages())
        <div style="padding:1rem; border-top:1px solid var(--border)">{{ $demandas->links() }}</div>
    @endif
</div>
@endsection
