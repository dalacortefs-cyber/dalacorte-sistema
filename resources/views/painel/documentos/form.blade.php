@extends('layouts.painel')
@section('title', $documento ? 'Editar Documento' : 'Novo Documento')
@section('breadcrumb', 'Documentos / '.($documento ? 'Editar' : 'Novo'))

@section('content')
<div class="page-header" style="display:flex; justify-content:space-between; align-items:center">
    <h1 class="page-title">{{ $documento ? 'Editar Documento' : 'Novo Documento' }}</h1>
    <a href="{{ route('painel.documentos.index') }}" class="btn btn-ghost">← Voltar</a>
</div>

<div style="max-width:680px">
<form method="POST" action="{{ $documento ? route('painel.documentos.update', $documento) : route('painel.documentos.store') }}">
    @csrf
    @if($documento) @method('PUT') @endif

    <div class="card" style="margin-bottom:1.25rem">
        <div class="card-title" style="margin-bottom:1rem">Dados do Documento</div>

        <div class="form-group">
            <label class="form-label">Nome do Documento *</label>
            <input type="text" name="nome" class="form-control" required
                value="{{ old('nome', $documento?->nome) }}" placeholder="Ex: Contrato Social 2024">
            @error('nome')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Empresa *</label>
            <select name="empresa_id" class="form-control" required>
                <option value="">Selecionar...</option>
                @foreach($empresas as $e)
                    <option value="{{ $e->id }}" {{ old('empresa_id', $documento?->empresa_id) == $e->id ? 'selected' : '' }}>
                        {{ $e->razao_social }}
                    </option>
                @endforeach
            </select>
            @error('empresa_id')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="grid-2" style="gap:0.75rem">
            <div class="form-group">
                <label class="form-label">Categoria</label>
                <select name="categoria" class="form-control">
                    <option value="">—</option>
                    @foreach(['Contrato','Procuração','Declaração','Relatório','Nota Fiscal','Alvará','Outros'] as $cat)
                        <option value="{{ $cat }}" {{ old('categoria', $documento?->categoria) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Competência (MM/YYYY)</label>
                <input type="text" name="competencia" class="form-control"
                    value="{{ old('competencia', $documento?->competencia) }}"
                    placeholder="{{ now()->format('m/Y') }}" pattern="\d{2}/\d{4}">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">URL do Arquivo</label>
            <input type="url" name="arquivo_url" class="form-control"
                value="{{ old('arquivo_url', $documento?->arquivo_url) }}"
                placeholder="https://drive.google.com/...">
            @if($documento?->arquivo_url)
                <div style="margin-top:0.4rem; font-size:0.8rem">
                    <a href="{{ $documento->arquivo_url }}" target="_blank" style="color:var(--teal)">Ver arquivo atual</a>
                </div>
            @endif
        </div>

        <div class="form-group">
            <label class="form-label">Descrição</label>
            <textarea name="descricao" class="form-control" rows="3"
                placeholder="Descrição ou observações sobre o documento...">{{ old('descricao', $documento?->descricao) }}</textarea>
        </div>
    </div>

    <div style="display:flex; gap:0.75rem; justify-content:flex-end">
        <a href="{{ route('painel.documentos.index') }}" class="btn btn-ghost">Cancelar</a>
        <button type="submit" class="btn btn-primary">
            {{ $documento ? 'Salvar Alterações' : 'Salvar Documento' }}
        </button>
    </div>
</form>
</div>
@endsection
