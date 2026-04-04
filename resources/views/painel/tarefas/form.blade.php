@extends('layouts.painel')
@section('title', $tarefa ? 'Editar Tarefa' : 'Nova Tarefa')
@section('breadcrumb', 'Tarefas / '.($tarefa ? 'Editar' : 'Nova'))

@section('content')
<div class="page-header" style="display:flex; justify-content:space-between; align-items:center">
    <h1 class="page-title">{{ $tarefa ? 'Editar Tarefa' : 'Nova Tarefa' }}</h1>
    <a href="{{ route('painel.tarefas.index') }}" class="btn btn-ghost">← Voltar</a>
</div>

<div style="max-width:680px">
<form method="POST" action="{{ $tarefa ? route('painel.tarefas.update', $tarefa) : route('painel.tarefas.store') }}">
    @csrf
    @if($tarefa) @method('PUT') @endif

    <div class="card" style="margin-bottom:1.25rem">
        <div class="card-title" style="margin-bottom:1rem">Dados da Tarefa</div>

        <div class="form-group">
            <label class="form-label">Empresa *</label>
            <select name="empresa_id" class="form-control" required>
                <option value="">Selecionar...</option>
                @foreach($empresas as $e)
                    <option value="{{ $e->id }}" {{ old('empresa_id', $tarefa?->empresa_id) == $e->id ? 'selected' : '' }}>
                        {{ $e->razao_social }}
                    </option>
                @endforeach
            </select>
            @error('empresa_id')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Obrigação / Nome da Tarefa *</label>
            <input type="text" name="obrigacao_nome" class="form-control"
                value="{{ old('obrigacao_nome', $tarefa?->obrigacao_nome) }}"
                list="obrigacoes-list" required placeholder="Ex: GFIP, DCTF, DAS...">
            <datalist id="obrigacoes-list">
                @foreach($obrigacoes as $o)
                    <option value="{{ $o->nome }}">
                @endforeach
            </datalist>
            @error('obrigacao_nome')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="grid-2" style="gap:0.75rem">
            <div class="form-group">
                <label class="form-label">Competência * (MM/YYYY)</label>
                <input type="text" name="competencia" class="form-control"
                    value="{{ old('competencia', $tarefa?->competencia ?? now()->format('m/Y')) }}"
                    placeholder="{{ now()->format('m/Y') }}" pattern="\d{2}/\d{4}" required>
                @error('competencia')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Vencimento</label>
                <input type="date" name="data_vencimento" class="form-control"
                    value="{{ old('data_vencimento', $tarefa?->data_vencimento?->format('Y-m-d')) }}">
            </div>
        </div>

        <div class="grid-2" style="gap:0.75rem">
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    @foreach(['Pendente','Em andamento','Concluído','Arquivado'] as $s)
                        <option value="{{ $s }}" {{ old('status', $tarefa?->status ?? 'Pendente') === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Criticidade</label>
                <select name="nivel_criticidade" class="form-control">
                    @foreach(['Baixa','Média','Alta','Crítica'] as $c)
                        <option value="{{ $c }}" {{ old('nivel_criticidade', $tarefa?->nivel_criticidade ?? 'Média') === $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid-2" style="gap:0.75rem">
            <div class="form-group">
                <label class="form-label">Esfera</label>
                <select name="esfera" class="form-control">
                    <option value="">—</option>
                    @foreach(['Federal','Estadual','Municipal','Trabalhista'] as $e)
                        <option value="{{ $e }}" {{ old('esfera', $tarefa?->esfera) === $e ? 'selected' : '' }}>{{ $e }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Responsável</label>
                <input type="text" name="responsavel" class="form-control"
                    value="{{ old('responsavel', $tarefa?->responsavel) }}" placeholder="Nome do responsável">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Observações</label>
            <textarea name="observacoes" class="form-control" rows="3"
                placeholder="Notas, instruções, links...">{{ old('observacoes', $tarefa?->observacoes) }}</textarea>
        </div>
    </div>

    <div style="display:flex; gap:0.75rem; justify-content:flex-end">
        <a href="{{ route('painel.tarefas.index') }}" class="btn btn-ghost">Cancelar</a>
        <button type="submit" class="btn btn-primary">
            {{ $tarefa ? 'Salvar Alterações' : 'Criar Tarefa' }}
        </button>
    </div>
</form>
</div>
@endsection
