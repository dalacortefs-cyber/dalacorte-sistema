@extends('layouts.painel')
@section('title', 'Projetos Internos')
@section('breadcrumb', 'Projetos Internos')

@section('content')
<div class="page-header" style="display:flex; justify-content:space-between; align-items:center">
    <h1 class="page-title">Projetos Internos</h1>
    <a href="{{ route('painel.projetos.create') }}" class="btn btn-gold">+ Novo Projeto</a>
</div>

<div class="card" style="margin-bottom:1.25rem; padding:1rem">
    <form method="GET" style="display:flex; gap:0.75rem; flex-wrap:wrap; align-items:flex-end">
        <div>
            <label class="form-label">Status</label>
            <select name="status" class="form-control">
                <option value="">Todos</option>
                @foreach(['Planejamento','Em andamento','Pausado','Concluído','Cancelado'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ $s }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Prioridade</label>
            <select name="prioridade" class="form-control">
                <option value="">Todas</option>
                @foreach(['Baixa','Normal','Alta','Crítica'] as $p)
                    <option value="{{ $p }}" {{ request('prioridade') === $p ? 'selected' : '' }}>{{ $p }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Filtrar</button>
        @if(request()->hasAny(['status','prioridade']))
            <a href="{{ route('painel.projetos.index') }}" class="btn btn-ghost">Limpar</a>
        @endif
    </form>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Projeto</th><th>Responsável</th><th>Prioridade</th>
                    <th>Status</th><th>Prazo</th><th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($projetos as $p)
                @php
                    $prazoCor = '';
                    if ($p->data_fim_prevista && $p->status !== 'Concluído' && $p->status !== 'Cancelado') {
                        $dias = now()->diffInDays($p->data_fim_prevista, false);
                        if ($dias < 0) $prazoCor = '#f87171';
                        elseif ($dias <= 7) $prazoCor = '#fbbf24';
                    }
                @endphp
                <tr>
                    <td>
                        <div style="font-weight:500">{{ $p->nome }}</div>
                        @if($p->descricao)
                            <div style="font-size:0.8rem; color:var(--muted)">{{ Str::limit($p->descricao, 60) }}</div>
                        @endif
                    </td>
                    <td style="font-size:0.85rem; color:var(--muted)">{{ $p->responsavel ?? '—' }}</td>
                    <td>
                        @php $pc = match($p->prioridade) { 'Crítica' => 'badge-red', 'Alta' => 'badge-yellow', 'Normal' => 'badge-blue', default => 'badge-gray' }; @endphp
                        <span class="badge {{ $pc }}">{{ $p->prioridade }}</span>
                    </td>
                    <td>
                        @php $sc = match($p->status) { 'Concluído' => 'badge-green', 'Em andamento' => 'badge-blue', 'Cancelado' => 'badge-gray', 'Pausado' => 'badge-yellow', default => 'badge-gray' }; @endphp
                        <span class="badge {{ $sc }}">{{ $p->status }}</span>
                    </td>
                    <td style="font-size:0.85rem; color:{{ $prazoCor ?: 'var(--text)' }}">
                        {{ $p->data_fim_prevista?->format('d/m/Y') ?? '—' }}
                    </td>
                    <td>
                        <div style="display:flex; gap:0.4rem">
                            <a href="{{ route('painel.projetos.edit', $p) }}" class="btn btn-ghost btn-sm">Editar</a>
                            <form method="POST" action="{{ route('painel.projetos.destroy', $p) }}" onsubmit="return confirm('Remover projeto?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">✕</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center; padding:2rem; color:var(--muted)">Nenhum projeto encontrado.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($projetos->hasPages())
        <div style="padding:1rem; border-top:1px solid var(--border)">{{ $projetos->links() }}</div>
    @endif
</div>
@endsection
