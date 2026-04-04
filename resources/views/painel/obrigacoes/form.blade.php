@extends('layouts.painel')
@section('title', $obrigacao ? 'Editar Obrigação' : 'Nova Obrigação')
@section('breadcrumb', 'Obrigações / '.($obrigacao ? 'Editar' : 'Nova'))

@section('content')
<div class="page-header" style="display:flex; justify-content:space-between; align-items:center">
    <h1 class="page-title">{{ $obrigacao ? 'Editar Obrigação' : 'Nova Obrigação Fiscal' }}</h1>
    <a href="{{ route('painel.obrigacoes.index') }}" class="btn btn-ghost">← Voltar</a>
</div>

<div style="max-width:680px">
<form method="POST" action="{{ $obrigacao ? route('painel.obrigacoes.update', $obrigacao) : route('painel.obrigacoes.store') }}">
    @csrf
    @if($obrigacao) @method('PUT') @endif

    <div class="card" style="margin-bottom:1.25rem">
        <div class="card-title" style="margin-bottom:1rem">Dados da Obrigação</div>

        <div class="form-group">
            <label class="form-label">Nome da Obrigação *</label>
            <input type="text" name="nome" class="form-control" required
                value="{{ old('nome', $obrigacao?->nome) }}" placeholder="Ex: GFIP, DCTF, DAS, SPED...">
            @error('nome')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="grid-2" style="gap:0.75rem">
            <div class="form-group">
                <label class="form-label">Esfera</label>
                <select name="esfera" class="form-control">
                    <option value="">—</option>
                    @foreach(['Federal','Estadual','Municipal','Trabalhista'] as $e)
                        <option value="{{ $e }}" {{ old('esfera', $obrigacao?->esfera) === $e ? 'selected' : '' }}>{{ $e }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Regime Tributário</label>
                <select name="regime_tributario" class="form-control">
                    <option value="Todos" {{ old('regime_tributario', $obrigacao?->regime_tributario ?? 'Todos') === 'Todos' ? 'selected' : '' }}>Todos</option>
                    @foreach(['MEI','Simples Nacional','Lucro Presumido','Lucro Real'] as $r)
                        <option value="{{ $r }}" {{ old('regime_tributario', $obrigacao?->regime_tributario) === $r ? 'selected' : '' }}>{{ $r }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid-2" style="gap:0.75rem">
            <div class="form-group">
                <label class="form-label">Periodicidade</label>
                <select name="periodicidade" class="form-control">
                    <option value="">—</option>
                    @foreach(['Mensal','Trimestral','Semestral','Anual','Eventual'] as $p)
                        <option value="{{ $p }}" {{ old('periodicidade', $obrigacao?->periodicidade) === $p ? 'selected' : '' }}>{{ $p }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Dia de Vencimento</label>
                <input type="number" name="dia_vencimento" class="form-control" min="1" max="31"
                    value="{{ old('dia_vencimento', $obrigacao?->dia_vencimento) }}" placeholder="Ex: 15">
            </div>
        </div>

        <div class="grid-2" style="gap:0.75rem">
            <div class="form-group">
                <label class="form-label">Órgão Responsável</label>
                <input type="text" name="orgao" class="form-control"
                    value="{{ old('orgao', $obrigacao?->orgao) }}" placeholder="Ex: Receita Federal">
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="ativo" class="form-control">
                    <option value="1" {{ old('ativo', $obrigacao?->ativo ?? true) ? 'selected' : '' }}>Ativo</option>
                    <option value="0" {{ old('ativo', $obrigacao?->ativo) === '0' || $obrigacao?->ativo === false ? 'selected' : '' }}>Inativo</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Descrição / Instruções</label>
            <textarea name="descricao" class="form-control" rows="3"
                placeholder="Orientações, link para portal, observações...">{{ old('descricao', $obrigacao?->descricao) }}</textarea>
        </div>
    </div>

    <div style="display:flex; gap:0.75rem; justify-content:flex-end">
        <a href="{{ route('painel.obrigacoes.index') }}" class="btn btn-ghost">Cancelar</a>
        <button type="submit" class="btn btn-primary">
            {{ $obrigacao ? 'Salvar Alterações' : 'Cadastrar Obrigação' }}
        </button>
    </div>
</form>
</div>
@endsection
