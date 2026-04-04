@extends('layouts.painel')
@section('title', $demanda->numero_os.' — '.$demanda->titulo)
@section('breadcrumb', 'Demandas / '.$demanda->numero_os)

@section('content')
<div class="page-header" style="display:flex; justify-content:space-between; align-items:flex-start">
    <div>
        <div style="font-family:monospace; font-size:0.85rem; color:var(--muted); margin-bottom:0.25rem">{{ $demanda->numero_os }}</div>
        <h1 class="page-title">{{ $demanda->titulo }}</h1>
        <div style="display:flex; gap:0.5rem; margin-top:0.4rem; flex-wrap:wrap">
            @php
                $sc = match($demanda->status) {
                    'Concluída' => 'badge-green', 'Em andamento' => 'badge-blue',
                    'Cancelada' => 'badge-gray', default => 'badge-yellow'
                };
                $pc = match($demanda->prioridade) {
                    'Urgente' => 'badge-red', 'Alta' => 'badge-yellow',
                    'Normal' => 'badge-blue', default => 'badge-gray'
                };
            @endphp
            <span class="badge {{ $sc }}">{{ $demanda->status }}</span>
            <span class="badge {{ $pc }}">{{ $demanda->prioridade }}</span>
            @if($demanda->categoria)
                <span class="badge badge-teal">{{ $demanda->categoria }}</span>
            @endif
        </div>
    </div>
    <div style="display:flex; gap:0.5rem">
        <a href="{{ route('painel.demandas.edit', $demanda) }}" class="btn btn-ghost">Editar</a>
        <a href="{{ route('painel.demandas.index') }}" class="btn btn-ghost">← Voltar</a>
    </div>
</div>

<div class="grid-2" style="gap:1.25rem; align-items:start">

    {{-- Left: Details + Description --}}
    <div style="display:flex; flex-direction:column; gap:1.25rem">

        <div class="card">
            <div class="card-title" style="margin-bottom:1rem">Detalhes</div>
            <table style="width:100%; font-size:0.875rem">
                @foreach([
                    ['Empresa', $demanda->empresa->razao_social],
                    ['Responsável', $demanda->responsavel ?? '—'],
                    ['Data Abertura', $demanda->data_abertura?->format('d/m/Y') ?? '—'],
                    ['Prazo', $demanda->data_prazo?->format('d/m/Y') ?? '—'],
                    ['Conclusão', $demanda->data_conclusao?->format('d/m/Y') ?? '—'],
                ] as [$label, $value])
                <tr style="border-bottom:1px solid var(--border)">
                    <td style="padding:0.6rem 0; color:var(--muted); width:40%">{{ $label }}</td>
                    <td style="padding:0.6rem 0">{{ $value }}</td>
                </tr>
                @endforeach
            </table>
        </div>

        @if($demanda->descricao)
        <div class="card">
            <div class="card-title" style="margin-bottom:0.75rem">Descrição</div>
            <div style="font-size:0.875rem; line-height:1.6; white-space:pre-wrap">{{ $demanda->descricao }}</div>
        </div>
        @endif

        {{-- Checklist --}}
        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem">
                <div class="card-title" style="margin:0">Checklist</div>
                @php
                    $total = $demanda->checklistItems->count();
                    $feitos = $demanda->checklistItems->where('concluido', true)->count();
                @endphp
                @if($total > 0)
                    <span style="font-size:0.85rem; color:var(--muted)">{{ $feitos }}/{{ $total }}</span>
                @endif
            </div>

            @if($total > 0)
            @foreach($demanda->checklistItems as $item)
            <form method="POST" action="{{ route('painel.demandas.checklist.toggle', [$demanda, $item]) }}"
                  style="padding:0.5rem 0; border-bottom:1px solid var(--border); display:flex; align-items:center; gap:0.75rem">
                @csrf @method('PATCH')
                <input type="checkbox" name="concluido" value="1" {{ $item->concluido ? 'checked' : '' }}
                    onchange="this.form.submit()" style="width:1rem; height:1rem; accent-color:var(--teal)">
                <span style="font-size:0.875rem; {{ $item->concluido ? 'text-decoration:line-through; color:var(--muted)' : '' }}">
                    {{ $item->descricao }}
                </span>
            </form>
            @endforeach
            @endif

            <form method="POST" action="{{ route('painel.demandas.checklist.store', $demanda) }}"
                  style="margin-top:0.75rem; display:flex; gap:0.5rem">
                @csrf
                <input type="text" name="descricao" class="form-control" placeholder="Novo item do checklist..." required style="flex:1">
                <button type="submit" class="btn btn-primary btn-sm">+</button>
            </form>
        </div>
    </div>

    {{-- Right: Comments --}}
    <div style="display:flex; flex-direction:column; gap:1.25rem">

        <div class="card">
            <div class="card-title" style="margin-bottom:1rem">Comentários</div>

            @forelse($demanda->comentarios as $comentario)
            <div style="padding:0.75rem 0; border-bottom:1px solid var(--border)">
                <div style="display:flex; justify-content:space-between; margin-bottom:0.3rem">
                    <span style="font-weight:500; font-size:0.85rem">{{ $comentario->user->name }}</span>
                    <span style="font-size:0.75rem; color:var(--muted)">{{ $comentario->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div style="font-size:0.875rem; line-height:1.5; white-space:pre-wrap">{{ $comentario->conteudo }}</div>
            </div>
            @empty
            <p style="text-align:center; color:var(--muted); padding:1rem 0; font-size:0.875rem">Nenhum comentário ainda.</p>
            @endforelse

            <form method="POST" action="{{ route('painel.demandas.comentarios.store', $demanda) }}" style="margin-top:1rem">
                @csrf
                <div class="form-group">
                    <label class="form-label">Novo Comentário</label>
                    <textarea name="conteudo" class="form-control" rows="3" placeholder="Escreva um comentário..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%">Comentar</button>
            </form>
        </div>

        {{-- Quick status change --}}
        @if($demanda->status !== 'Concluída' && $demanda->status !== 'Cancelada')
        <div class="card" style="padding:1rem">
            <div class="card-title" style="margin-bottom:0.75rem">Alterar Status</div>
            <form method="POST" action="{{ route('painel.demandas.update', $demanda) }}" style="display:flex; gap:0.5rem">
                @csrf @method('PUT')
                <input type="hidden" name="titulo" value="{{ $demanda->titulo }}">
                <input type="hidden" name="empresa_id" value="{{ $demanda->empresa_id }}">
                <input type="hidden" name="prioridade" value="{{ $demanda->prioridade }}">
                <select name="status" class="form-control">
                    @foreach(['Aberta','Em andamento','Aguardando cliente','Aguardando terceiros','Concluída','Cancelada'] as $s)
                        <option value="{{ $s }}" {{ $demanda->status === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary">OK</button>
            </form>
        </div>
        @endif

    </div>
</div>
@endsection
