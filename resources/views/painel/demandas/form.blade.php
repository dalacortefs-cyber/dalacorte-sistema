@extends('layouts.painel')
@section('title', $demanda ? 'Editar Demanda' : 'Nova Demanda')
@section('breadcrumb', 'Demandas / '.($demanda ? 'Editar' : 'Nova'))

@section('content')
<div class="page-header" style="display:flex; justify-content:space-between; align-items:center">
    <h1 class="page-title">{{ $demanda ? 'Editar: '.$demanda->titulo : 'Nova Demanda' }}</h1>
    <a href="{{ route('painel.demandas.index') }}" class="btn btn-ghost">← Voltar</a>
</div>

<div style="max-width:720px">
<form method="POST" action="{{ $demanda ? route('painel.demandas.update', $demanda) : route('painel.demandas.store') }}">
    @csrf
    @if($demanda) @method('PUT') @endif

    <div class="card" style="margin-bottom:1.25rem">
        <div class="card-title" style="margin-bottom:1rem">Identificação</div>

        <div class="form-group">
            <label class="form-label">Título *</label>
            <input type="text" name="titulo" class="form-control" required
                value="{{ old('titulo', $demanda?->titulo) }}" placeholder="Descreva brevemente a demanda">
            @error('titulo')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Empresa *</label>
            <select name="empresa_id" class="form-control" required>
                <option value="">Selecionar...</option>
                @foreach($empresas as $e)
                    <option value="{{ $e->id }}" {{ old('empresa_id', $demanda?->empresa_id) == $e->id ? 'selected' : '' }}>
                        {{ $e->razao_social }}
                    </option>
                @endforeach
            </select>
            @error('empresa_id')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="grid-2" style="gap:0.75rem">
            <div class="form-group">
                <label class="form-label">Prioridade</label>
                <select name="prioridade" class="form-control">
                    @foreach(['Baixa','Normal','Alta','Urgente'] as $p)
                        <option value="{{ $p }}" {{ old('prioridade', $demanda?->prioridade ?? 'Normal') === $p ? 'selected' : '' }}>{{ $p }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    @foreach(['Aberta','Em andamento','Aguardando cliente','Aguardando terceiros','Concluída','Cancelada'] as $s)
                        <option value="{{ $s }}" {{ old('status', $demanda?->status ?? 'Aberta') === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid-2" style="gap:0.75rem">
            <div class="form-group">
                <label class="form-label">Categoria</label>
                <select name="categoria" class="form-control">
                    <option value="">—</option>
                    @foreach(['Tributário','Trabalhista','Societário','Financeiro','Atendimento','Outros'] as $cat)
                        <option value="{{ $cat }}" {{ old('categoria', $demanda?->categoria) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Responsável</label>
                <input type="text" name="responsavel" class="form-control"
                    value="{{ old('responsavel', $demanda?->responsavel) }}" placeholder="Nome do responsável">
            </div>
        </div>

        <div class="grid-2" style="gap:0.75rem">
            <div class="form-group">
                <label class="form-label">Data de Abertura</label>
                <input type="date" name="data_abertura" class="form-control"
                    value="{{ old('data_abertura', $demanda?->data_abertura?->format('Y-m-d') ?? now()->format('Y-m-d')) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Prazo</label>
                <input type="date" name="data_prazo" class="form-control"
                    value="{{ old('data_prazo', $demanda?->data_prazo?->format('Y-m-d')) }}">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Descrição Detalhada</label>
            <textarea name="descricao" class="form-control" rows="5"
                placeholder="Detalhe o que precisa ser feito, contexto, referências...">{{ old('descricao', $demanda?->descricao) }}</textarea>
        </div>
    </div>

    <div style="display:flex; gap:0.75rem; justify-content:flex-end">
        <a href="{{ route('painel.demandas.index') }}" class="btn btn-ghost">Cancelar</a>
        <button type="submit" class="btn btn-primary">
            {{ $demanda ? 'Salvar Alterações' : 'Abrir Demanda' }}
        </button>
    </div>
</form>
</div>
@endsection
