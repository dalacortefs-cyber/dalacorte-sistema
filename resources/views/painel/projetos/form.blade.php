@extends('layouts.painel')
@section('title', $projeto ? 'Editar Projeto' : 'Novo Projeto')
@section('breadcrumb', 'Projetos / '.($projeto ? 'Editar' : 'Novo'))

@section('content')
<div class="page-header" style="display:flex; justify-content:space-between; align-items:center">
    <h1 class="page-title">{{ $projeto ? 'Editar: '.$projeto->nome : 'Novo Projeto Interno' }}</h1>
    <a href="{{ route('painel.projetos.index') }}" class="btn btn-ghost">← Voltar</a>
</div>

<div style="max-width:720px">
<form method="POST" action="{{ $projeto ? route('painel.projetos.update', $projeto) : route('painel.projetos.store') }}">
    @csrf
    @if($projeto) @method('PUT') @endif

    <div class="card" style="margin-bottom:1.25rem">
        <div class="card-title" style="margin-bottom:1rem">Dados do Projeto</div>

        <div class="form-group">
            <label class="form-label">Nome do Projeto *</label>
            <input type="text" name="nome" class="form-control" required
                value="{{ old('nome', $projeto?->nome) }}" placeholder="Ex: Implantação ERP, Revisão processos internos">
            @error('nome')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="grid-2" style="gap:0.75rem">
            <div class="form-group">
                <label class="form-label">Prioridade</label>
                <select name="prioridade" class="form-control">
                    @foreach(['Baixa','Normal','Alta','Crítica'] as $p)
                        <option value="{{ $p }}" {{ old('prioridade', $projeto?->prioridade ?? 'Normal') === $p ? 'selected' : '' }}>{{ $p }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    @foreach(['Planejamento','Em andamento','Pausado','Concluído','Cancelado'] as $s)
                        <option value="{{ $s }}" {{ old('status', $projeto?->status ?? 'Planejamento') === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Responsável</label>
            <input type="text" name="responsavel" class="form-control"
                value="{{ old('responsavel', $projeto?->responsavel) }}" placeholder="Nome do responsável pelo projeto">
        </div>

        <div class="grid-2" style="gap:0.75rem">
            <div class="form-group">
                <label class="form-label">Data de Início</label>
                <input type="date" name="data_inicio" class="form-control"
                    value="{{ old('data_inicio', $projeto?->data_inicio?->format('Y-m-d') ?? now()->format('Y-m-d')) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Previsão de Término</label>
                <input type="date" name="data_fim_prevista" class="form-control"
                    value="{{ old('data_fim_prevista', $projeto?->data_fim_prevista?->format('Y-m-d')) }}">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Orçamento (R$)</label>
            <input type="number" name="orcamento" class="form-control" step="0.01" min="0"
                value="{{ old('orcamento', $projeto?->orcamento) }}" placeholder="0,00">
        </div>

        <div class="form-group">
            <label class="form-label">Descrição / Objetivo</label>
            <textarea name="descricao" class="form-control" rows="4"
                placeholder="Descreva o objetivo, escopo e entregas esperadas do projeto...">{{ old('descricao', $projeto?->descricao) }}</textarea>
        </div>
    </div>

    <div style="display:flex; gap:0.75rem; justify-content:flex-end">
        <a href="{{ route('painel.projetos.index') }}" class="btn btn-ghost">Cancelar</a>
        <button type="submit" class="btn btn-primary">
            {{ $projeto ? 'Salvar Alterações' : 'Criar Projeto' }}
        </button>
    </div>
</form>
</div>
@endsection
