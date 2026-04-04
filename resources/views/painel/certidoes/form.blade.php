@extends('layouts.painel')
@section('title', $certidao ? 'Editar Certidão' : 'Nova Certidão')
@section('breadcrumb', 'Certidões / '.($certidao ? 'Editar' : 'Nova'))

@section('content')
<div class="page-header" style="display:flex; justify-content:space-between; align-items:center">
    <h1 class="page-title">{{ $certidao ? 'Editar Certidão' : 'Nova Certidão' }}</h1>
    <a href="{{ route('painel.certidoes.index') }}" class="btn btn-ghost">← Voltar</a>
</div>

<div style="max-width:680px">
<form method="POST" action="{{ $certidao ? route('painel.certidoes.update', $certidao) : route('painel.certidoes.store') }}"
      enctype="multipart/form-data">
    @csrf
    @if($certidao) @method('PUT') @endif

    <div class="card" style="margin-bottom:1.25rem">
        <div class="card-title" style="margin-bottom:1rem">Dados da Certidão</div>

        <div class="form-group">
            <label class="form-label">Empresa *</label>
            <select name="empresa_id" class="form-control" required>
                <option value="">Selecionar...</option>
                @foreach($empresas as $e)
                    <option value="{{ $e->id }}" {{ old('empresa_id', $certidao?->empresa_id) == $e->id ? 'selected' : '' }}>
                        {{ $e->razao_social }}
                    </option>
                @endforeach
            </select>
            @error('empresa_id')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="grid-2" style="gap:0.75rem">
            <div class="form-group">
                <label class="form-label">Tipo *</label>
                <select name="tipo" class="form-control" required>
                    @foreach(['CND Federal','CND Estadual','CND Municipal','FGTS','Trabalhista','Outros'] as $t)
                        <option value="{{ $t }}" {{ old('tipo', $certidao?->tipo) === $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
                @error('tipo')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    @foreach(['Válida','Vencida','Pendente'] as $s)
                        <option value="{{ $s }}" {{ old('status', $certidao?->status ?? 'Válida') === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid-2" style="gap:0.75rem">
            <div class="form-group">
                <label class="form-label">Data de Emissão</label>
                <input type="date" name="data_emissao" class="form-control"
                    value="{{ old('data_emissao', $certidao?->data_emissao?->format('Y-m-d')) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Data de Validade *</label>
                <input type="date" name="data_validade" class="form-control" required
                    value="{{ old('data_validade', $certidao?->data_validade?->format('Y-m-d')) }}">
                @error('data_validade')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Número / Protocolo</label>
            <input type="text" name="numero_protocolo" class="form-control"
                value="{{ old('numero_protocolo', $certidao?->numero_protocolo) }}" placeholder="Código da certidão">
        </div>

        <div class="form-group">
            <label class="form-label">Responsável</label>
            <input type="text" name="responsavel" class="form-control"
                value="{{ old('responsavel', $certidao?->responsavel) }}" placeholder="Quem obteve a certidão">
        </div>

        <div class="form-group">
            <label class="form-label">URL do Arquivo (PDF)</label>
            <input type="url" name="arquivo_url" class="form-control"
                value="{{ old('arquivo_url', $certidao?->arquivo_url) }}" placeholder="https://...">
            @if($certidao?->arquivo_url)
                <div style="margin-top:0.4rem; font-size:0.8rem">
                    <a href="{{ $certidao->arquivo_url }}" target="_blank" style="color:var(--teal)">Ver arquivo atual</a>
                </div>
            @endif
        </div>

        <div class="form-group">
            <label class="form-label">Observações</label>
            <textarea name="observacoes" class="form-control" rows="3"
                placeholder="Notas adicionais...">{{ old('observacoes', $certidao?->observacoes) }}</textarea>
        </div>
    </div>

    <div style="display:flex; gap:0.75rem; justify-content:flex-end">
        <a href="{{ route('painel.certidoes.index') }}" class="btn btn-ghost">Cancelar</a>
        <button type="submit" class="btn btn-primary">
            {{ $certidao ? 'Salvar Alterações' : 'Cadastrar Certidão' }}
        </button>
    </div>
</form>
</div>
@endsection
