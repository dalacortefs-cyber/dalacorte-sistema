@extends('layouts.painel')
@section('title', 'Obrigações')
@section('breadcrumb', 'Obrigações')

@section('content')
<div class="page-header" style="display:flex; justify-content:space-between; align-items:center">
    <h1 class="page-title">Obrigações Fiscais</h1>
    <a href="{{ route('painel.obrigacoes.create') }}" class="btn btn-gold">+ Nova Obrigação</a>
</div>

<div class="card" style="margin-bottom:1.25rem; padding:1rem">
    <form method="GET" style="display:flex; gap:0.75rem; flex-wrap:wrap; align-items:flex-end">
        <div>
            <label class="form-label">Buscar</label>
            <input type="text" name="busca" class="form-control" placeholder="Nome da obrigação..."
                value="{{ request('busca') }}" style="min-width:220px">
        </div>
        <div>
            <label class="form-label">Esfera</label>
            <select name="esfera" class="form-control">
                <option value="">Todas</option>
                @foreach(['Federal','Estadual','Municipal','Trabalhista'] as $e)
                    <option value="{{ $e }}" {{ request('esfera') === $e ? 'selected' : '' }}>{{ $e }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Regime</label>
            <select name="regime" class="form-control">
                <option value="">Todos</option>
                @foreach(['MEI','Simples Nacional','Lucro Presumido','Lucro Real','Todos'] as $r)
                    <option value="{{ $r }}" {{ request('regime') === $r ? 'selected' : '' }}>{{ $r }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Filtrar</button>
        @if(request()->hasAny(['busca','esfera','regime']))
            <a href="{{ route('painel.obrigacoes.index') }}" class="btn btn-ghost">Limpar</a>
        @endif
    </form>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nome</th><th>Esfera</th><th>Regime</th>
                    <th>Periodicidade</th><th>Vencimento</th><th>Ativo</th><th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($obrigacoes as $o)
                <tr>
                    <td style="font-weight:500">{{ $o->nome }}</td>
                    <td>
                        @php $ec = match($o->esfera) { 'Federal' => 'badge-blue', 'Estadual' => 'badge-green', 'Municipal' => 'badge-teal', default => 'badge-gray' }; @endphp
                        <span class="badge {{ $ec }}">{{ $o->esfera }}</span>
                    </td>
                    <td style="font-size:0.85rem">{{ $o->regime_tributario ?? 'Todos' }}</td>
                    <td style="font-size:0.85rem; color:var(--muted)">{{ $o->periodicidade ?? '—' }}</td>
                    <td style="font-size:0.85rem; color:var(--muted)">{{ $o->dia_vencimento ? 'Dia '.$o->dia_vencimento : '—' }}</td>
                    <td>
                        <span class="badge {{ $o->ativo ? 'badge-green' : 'badge-gray' }}">
                            {{ $o->ativo ? 'Ativo' : 'Inativo' }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex; gap:0.4rem">
                            <a href="{{ route('painel.obrigacoes.edit', $o) }}" class="btn btn-ghost btn-sm">Editar</a>
                            <form method="POST" action="{{ route('painel.obrigacoes.destroy', $o) }}" onsubmit="return confirm('Remover obrigação?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">✕</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center; padding:2rem; color:var(--muted)">Nenhuma obrigação encontrada.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($obrigacoes->hasPages())
        <div style="padding:1rem; border-top:1px solid var(--border)">{{ $obrigacoes->links() }}</div>
    @endif
</div>
@endsection
