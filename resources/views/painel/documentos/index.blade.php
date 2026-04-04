@extends('layouts.painel')
@section('title', 'Documentos')
@section('breadcrumb', 'Documentos')

@section('content')
<div class="page-header" style="display:flex; justify-content:space-between; align-items:center">
    <h1 class="page-title">Documentos</h1>
    <a href="{{ route('painel.documentos.create') }}" class="btn btn-gold">+ Novo Documento</a>
</div>

<div class="card" style="margin-bottom:1.25rem; padding:1rem">
    <form method="GET" style="display:flex; gap:0.75rem; flex-wrap:wrap; align-items:flex-end">
        <div>
            <label class="form-label">Buscar</label>
            <input type="text" name="busca" class="form-control" placeholder="Nome, descrição..."
                value="{{ request('busca') }}" style="min-width:220px">
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
            <label class="form-label">Categoria</label>
            <select name="categoria" class="form-control">
                <option value="">Todas</option>
                @foreach(['Contrato','Procuração','Declaração','Relatório','Nota Fiscal','Alvará','Outros'] as $cat)
                    <option value="{{ $cat }}" {{ request('categoria') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Filtrar</button>
        @if(request()->hasAny(['busca','empresa_id','categoria']))
            <a href="{{ route('painel.documentos.index') }}" class="btn btn-ghost">Limpar</a>
        @endif
    </form>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nome</th><th>Empresa</th><th>Categoria</th>
                    <th>Competência</th><th>Adicionado em</th><th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($documentos as $doc)
                <tr>
                    <td>
                        <div style="font-weight:500">{{ $doc->nome }}</div>
                        @if($doc->descricao)
                            <div style="font-size:0.8rem; color:var(--muted)">{{ Str::limit($doc->descricao, 60) }}</div>
                        @endif
                    </td>
                    <td style="font-size:0.85rem">{{ $doc->empresa->razao_social }}</td>
                    <td><span class="badge badge-gray">{{ $doc->categoria ?? '—' }}</span></td>
                    <td style="font-size:0.85rem; color:var(--muted)">{{ $doc->competencia ?? '—' }}</td>
                    <td style="font-size:0.85rem; color:var(--muted)">{{ $doc->created_at->format('d/m/Y') }}</td>
                    <td>
                        <div style="display:flex; gap:0.4rem">
                            @if($doc->arquivo_url)
                            <a href="{{ $doc->arquivo_url }}" target="_blank" class="btn btn-ghost btn-sm">Baixar</a>
                            @endif
                            <a href="{{ route('painel.documentos.edit', $doc) }}" class="btn btn-ghost btn-sm">Editar</a>
                            <form method="POST" action="{{ route('painel.documentos.destroy', $doc) }}" onsubmit="return confirm('Remover documento?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">✕</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center; padding:2rem; color:var(--muted)">Nenhum documento encontrado.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($documentos->hasPages())
        <div style="padding:1rem; border-top:1px solid var(--border)">{{ $documentos->links() }}</div>
    @endif
</div>
@endsection
