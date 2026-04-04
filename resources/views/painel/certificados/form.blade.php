@extends('layouts.painel')
@section('title', $certificado ? 'Editar Certificado' : 'Novo Certificado Digital')
@section('breadcrumb', 'Certificados / '.($certificado ? 'Editar' : 'Novo'))

@section('content')
<div class="page-header" style="display:flex; justify-content:space-between; align-items:center">
    <h1 class="page-title">{{ $certificado ? 'Editar Certificado' : 'Novo Certificado Digital' }}</h1>
    <a href="{{ route('painel.certificados.index') }}" class="btn btn-ghost">← Voltar</a>
</div>

<div style="max-width:680px">
<form method="POST" action="{{ $certificado ? route('painel.certificados.update', $certificado) : route('painel.certificados.store') }}">
    @csrf
    @if($certificado) @method('PUT') @endif

    <div class="card" style="margin-bottom:1.25rem">
        <div class="card-title" style="margin-bottom:1rem">Dados do Certificado</div>

        <div class="form-group">
            <label class="form-label">Empresa *</label>
            <select name="empresa_id" class="form-control" required>
                <option value="">Selecionar...</option>
                @foreach($empresas as $e)
                    <option value="{{ $e->id }}" {{ old('empresa_id', $certificado?->empresa_id) == $e->id ? 'selected' : '' }}>
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
                    @foreach(['e-CNPJ A1','e-CNPJ A3','e-CPF A1','e-CPF A3','NF-e','CT-e'] as $t)
                        <option value="{{ $t }}" {{ old('tipo', $certificado?->tipo) === $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
                @error('tipo')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    @foreach(['Válido','Vencido','Revogado'] as $s)
                        <option value="{{ $s }}" {{ old('status', $certificado?->status ?? 'Válido') === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Titular / Nome no Certificado</label>
            <input type="text" name="titular" class="form-control"
                value="{{ old('titular', $certificado?->titular) }}" placeholder="Razão Social ou nome do titular">
        </div>

        <div class="grid-2" style="gap:0.75rem">
            <div class="form-group">
                <label class="form-label">Data de Emissão</label>
                <input type="date" name="data_emissao" class="form-control"
                    value="{{ old('data_emissao', $certificado?->data_emissao?->format('Y-m-d')) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Data de Vencimento *</label>
                <input type="date" name="data_validade" class="form-control" required
                    value="{{ old('data_validade', $certificado?->data_validade?->format('Y-m-d')) }}">
                @error('data_validade')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="grid-2" style="gap:0.75rem">
            <div class="form-group">
                <label class="form-label">Onde está armazenado</label>
                <select name="local_armazenamento" class="form-control">
                    <option value="">—</option>
                    @foreach(['Token A3','Nuvem','Computador escritório','Pendrive','Cliente'] as $l)
                        <option value="{{ $l }}" {{ old('local_armazenamento', $certificado?->local_armazenamento) === $l ? 'selected' : '' }}>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Responsável pelo Certificado</label>
                <input type="text" name="responsavel" class="form-control"
                    value="{{ old('responsavel', $certificado?->responsavel) }}">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Observações</label>
            <textarea name="observacoes" class="form-control" rows="3"
                placeholder="Senha armazenada em cofre, notas sobre renovação...">{{ old('observacoes', $certificado?->observacoes) }}</textarea>
        </div>
    </div>

    <div style="display:flex; gap:0.75rem; justify-content:flex-end">
        <a href="{{ route('painel.certificados.index') }}" class="btn btn-ghost">Cancelar</a>
        <button type="submit" class="btn btn-primary">
            {{ $certificado ? 'Salvar Alterações' : 'Cadastrar Certificado' }}
        </button>
    </div>
</form>
</div>
@endsection
