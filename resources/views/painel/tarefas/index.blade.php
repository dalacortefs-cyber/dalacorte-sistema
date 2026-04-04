@extends('layouts.painel')
@section('title', 'Tarefas')
@section('breadcrumb', 'Tarefas e Obrigações')

@section('content')
<div class="page-header" style="display:flex; justify-content:space-between; align-items:flex-start">
    <div>
        <h1 class="page-title">Tarefas e Obrigações</h1>
        <p class="page-subtitle">{{ $tarefas->total() }} tarefa(s)</p>
    </div>
    <a href="{{ route('painel.tarefas.create') }}" class="btn btn-primary">+ Nova Tarefa</a>
</div>

{{-- KPIs Kanban --}}
<div class="grid-3" style="margin-bottom:1.25rem">
    @foreach($kanban as $status => $count)
    <div class="card" style="text-align:center">
        <div class="card-title">{{ $status }}</div>
        <div class="kpi-value">{{ $count }}</div>
    </div>
    @endforeach
</div>

{{-- Filtros --}}
<div class="card" style="margin-bottom:1.25rem; padding:1rem">
    <form method="GET" style="display:flex; gap:0.75rem; flex-wrap:wrap; align-items:flex-end">
        <div style="flex:1; min-width:180px">
            <label class="form-label">Empresa</label>
            <select name="empresa_id" class="form-control">
                <option value="">Todas</option>
                @foreach($empresas as $e)
                    <option value="{{ $e->id }}" {{ request('empresa_id') == $e->id ? 'selected' : '' }}>{{ $e->razao_social }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Competência</label>
            <input type="text" name="competencia" class="form-control" value="{{ request('competencia') }}" placeholder="MM/YYYY" style="width:100px">
        </div>
        <div>
            <label class="form-label">Status</label>
            <select name="status" class="form-control">
                <option value="">Todos</option>
                @foreach(['Pendente','Em andamento','Concluído','Arquivado'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ $s }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Criticidade</label>
            <select name="criticidade" class="form-control">
                <option value="">Todas</option>
                @foreach(['Baixa','Média','Alta','Crítica'] as $c)
                    <option value="{{ $c }}" {{ request('criticidade') === $c ? 'selected' : '' }}>{{ $c }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Filtrar</button>
        <a href="{{ route('painel.tarefas.index') }}" class="btn btn-ghost">Limpar</a>
    </form>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Obrigação</th>
                    <th>Empresa</th>
                    <th>Competência</th>
                    <th>Vencimento</th>
                    <th>Responsável</th>
                    <th>Criticidade</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tarefas as $tarefa)
                <tr>
                    <td style="font-weight:500">{{ $tarefa->obrigacao_nome }}</td>
                    <td style="font-size:0.85rem; color:var(--muted)">{{ $tarefa->empresa_nome }}</td>
                    <td>{{ $tarefa->competencia }}</td>
                    <td style="{{ $tarefa->data_vencimento && $tarefa->data_vencimento->isPast() && $tarefa->status !== 'Concluído' ? 'color:#f87171' : '' }}">
                        {{ $tarefa->data_vencimento ? $tarefa->data_vencimento->format('d/m/Y') : '—' }}
                    </td>
                    <td style="font-size:0.85rem">{{ $tarefa->responsavel ?: '—' }}</td>
                    <td>
                        @php $cc = match($tarefa->nivel_criticidade) { 'Crítica' => 'badge-red', 'Alta' => 'badge-yellow', 'Baixa' => 'badge-blue', default => 'badge-gray' }; @endphp
                        <span class="badge {{ $cc }}">{{ $tarefa->nivel_criticidade }}</span>
                    </td>
                    <td>
                        @php $sc = match($tarefa->status) { 'Concluído' => 'badge-green', 'Em andamento' => 'badge-blue', 'Arquivado' => 'badge-gray', default => 'badge-yellow' }; @endphp
                        <span class="badge {{ $sc }}">{{ $tarefa->status }}</span>
                    </td>
                    <td>
                        <div style="display:flex; gap:0.4rem">
                            @if($tarefa->status !== 'Concluído')
                            <form method="POST" action="{{ route('painel.tarefas.concluir', $tarefa) }}" style="display:inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-ghost btn-sm" title="Marcar como concluído">✓</button>
                            </form>
                            @endif
                            <a href="{{ route('painel.tarefas.edit', $tarefa) }}" class="btn btn-ghost btn-sm">Editar</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center; padding:2rem; color:var(--muted)">
                        Nenhuma tarefa encontrada.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tarefas->hasPages())
        <div style="padding:1rem; border-top:1px solid var(--border)">{{ $tarefas->links() }}</div>
    @endif
</div>
@endsection
