@extends('layouts.painel')
@section('title', $empresa ? 'Editar Empresa' : 'Nova Empresa')
@section('breadcrumb', $empresa ? 'Empresas / Editar' : 'Empresas / Nova')

@section('content')
<div class="page-header" style="display:flex; justify-content:space-between; align-items:center">
    <h1 class="page-title">{{ $empresa ? 'Editar: '.$empresa->razao_social : 'Nova Empresa' }}</h1>
    <a href="{{ route('painel.empresas.index') }}" class="btn btn-ghost">← Voltar</a>
</div>

<form method="POST" action="{{ $empresa ? route('painel.empresas.update', $empresa) : route('painel.empresas.store') }}">
    @csrf
    @if($empresa) @method('PUT') @endif

    <div class="grid-2" style="gap:1.25rem; align-items:start">

        {{-- Coluna 1: Dados principais --}}
        <div style="display:flex; flex-direction:column; gap:1.25rem">
            <div class="card">
                <div class="card-title" style="margin-bottom:1rem">Identificação</div>

                <div class="form-group">
                    <label class="form-label">Razão Social *</label>
                    <input type="text" name="razao_social" class="form-control" value="{{ old('razao_social', $empresa?->razao_social) }}" required>
                    @error('razao_social')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Nome Fantasia</label>
                    <input type="text" name="nome_fantasia" class="form-control" value="{{ old('nome_fantasia', $empresa?->nome_fantasia) }}">
                </div>

                <div class="grid-2" style="gap:0.75rem">
                    <div class="form-group">
                        <label class="form-label">CNPJ *</label>
                        <input type="text" name="cnpj" class="form-control" value="{{ old('cnpj', $empresa?->cnpj) }}" placeholder="00.000.000/0000-00" required>
                        @error('cnpj')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            @foreach(['Ativa','Inativa','Suspensa'] as $s)
                                <option value="{{ $s }}" {{ old('status', $empresa?->status) === $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid-2" style="gap:0.75rem">
                    <div class="form-group">
                        <label class="form-label">Inscrição Estadual</label>
                        <input type="text" name="inscricao_estadual" class="form-control" value="{{ old('inscricao_estadual', $empresa?->inscricao_estadual) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Inscrição Municipal</label>
                        <input type="text" name="inscricao_municipal" class="form-control" value="{{ old('inscricao_municipal', $empresa?->inscricao_municipal) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">CNAE Principal</label>
                    <input type="text" name="cnae_principal" class="form-control" value="{{ old('cnae_principal', $empresa?->cnae_principal) }}" placeholder="0000-0/00">
                </div>
            </div>

            <div class="card">
                <div class="card-title" style="margin-bottom:1rem">Tributário</div>

                <div class="grid-2" style="gap:0.75rem">
                    <div class="form-group">
                        <label class="form-label">Regime Tributário</label>
                        <select name="regime_tributario" class="form-control">
                            <option value="">Selecionar...</option>
                            @foreach(['MEI','Simples Nacional','Lucro Presumido','Lucro Real'] as $r)
                                <option value="{{ $r }}" {{ old('regime_tributario', $empresa?->regime_tributario) === $r ? 'selected' : '' }}>{{ $r }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tipo de Atividade</label>
                        <select name="tipo_atividade" class="form-control">
                            <option value="">Selecionar...</option>
                            @foreach(['Comércio','Serviço','Indústria','Misto'] as $t)
                                <option value="{{ $t }}" {{ old('tipo_atividade', $empresa?->tipo_atividade) === $t ? 'selected' : '' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid-2" style="gap:0.75rem">
                    <div class="form-group">
                        <label class="form-label">Complexidade</label>
                        <select name="complexidade_tributaria" class="form-control">
                            @foreach(['Baixa','Média','Alta'] as $c)
                                <option value="{{ $c }}" {{ old('complexidade_tributaria', $empresa?->complexidade_tributaria) === $c ? 'selected' : '' }}>{{ $c }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Início da Atividade</label>
                        <input type="date" name="data_inicio_atividade" class="form-control" value="{{ old('data_inicio_atividade', $empresa?->data_inicio_atividade?->format('Y-m-d')) }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- Coluna 2: Contrato + Contato --}}
        <div style="display:flex; flex-direction:column; gap:1.25rem">
            <div class="card">
                <div class="card-title" style="margin-bottom:1rem">Contrato com o Escritório</div>

                <div class="grid-2" style="gap:0.75rem">
                    <div class="form-group">
                        <label class="form-label">Honorário Mensal (R$)</label>
                        <input type="number" name="valor_honorario_mensal" class="form-control" step="0.01" min="0"
                            value="{{ old('valor_honorario_mensal', $empresa?->valor_honorario_mensal) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Início do Contrato</label>
                        <input type="date" name="data_inicio_contrato" class="form-control"
                            value="{{ old('data_inicio_contrato', $empresa?->data_inicio_contrato?->format('Y-m-d')) }}">
                    </div>
                </div>

                <div class="grid-2" style="gap:0.75rem">
                    <div class="form-group">
                        <label class="form-label">Índice de Reajuste</label>
                        <select name="indice_reajuste" class="form-control">
                            <option value="">—</option>
                            @foreach(['IPCA','IGP-M','Fixo'] as $i)
                                <option value="{{ $i }}" {{ old('indice_reajuste', $empresa?->indice_reajuste) === $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Mês de Reajuste</label>
                        <input type="number" name="mes_reajuste" class="form-control" min="1" max="12"
                            value="{{ old('mes_reajuste', $empresa?->mes_reajuste) }}" placeholder="1-12">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Responsável Interno</label>
                    <input type="text" name="responsavel_interno" class="form-control"
                        value="{{ old('responsavel_interno', $empresa?->responsavel_interno) }}" placeholder="Nome do responsável">
                </div>
            </div>

            <div class="card">
                <div class="card-title" style="margin-bottom:1rem">Localização e Contato</div>

                <div class="grid-2" style="gap:0.75rem">
                    <div class="form-group">
                        <label class="form-label">UF</label>
                        <select name="uf" class="form-control">
                            <option value="">—</option>
                            @foreach(['AC','AL','AM','AP','BA','CE','DF','ES','GO','MA','MG','MS','MT','PA','PB','PE','PI','PR','RJ','RN','RO','RR','RS','SC','SE','SP','TO'] as $uf)
                                <option value="{{ $uf }}" {{ old('uf', $empresa?->uf) === $uf ? 'selected' : '' }}>{{ $uf }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Município</label>
                        <input type="text" name="municipio" class="form-control" value="{{ old('municipio', $empresa?->municipio) }}">
                    </div>
                </div>

                <div class="grid-2" style="gap:0.75rem">
                    <div class="form-group">
                        <label class="form-label">E-mail</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $empresa?->email) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Telefone</label>
                        <input type="text" name="telefone" class="form-control" value="{{ old('telefone', $empresa?->telefone) }}">
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-title" style="margin-bottom:1rem">Funcionários</div>
                <div class="grid-2" style="gap:0.75rem">
                    <div class="form-group">
                        <label class="form-label">Possui Funcionários?</label>
                        <select name="possui_empregados" class="form-control">
                            <option value="0" {{ !old('possui_empregados', $empresa?->possui_empregados) ? 'selected' : '' }}>Não</option>
                            <option value="1" {{ old('possui_empregados', $empresa?->possui_empregados) ? 'selected' : '' }}>Sim</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Quantidade</label>
                        <input type="number" name="qtd_empregados" class="form-control" min="0"
                            value="{{ old('qtd_empregados', $empresa?->qtd_empregados) }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div style="margin-top:1.25rem; display:flex; gap:0.75rem; justify-content:flex-end">
        <a href="{{ route('painel.empresas.index') }}" class="btn btn-ghost">Cancelar</a>
        <button type="submit" class="btn btn-primary">
            {{ $empresa ? 'Salvar Alterações' : 'Cadastrar Empresa' }}
        </button>
    </div>
</form>
@endsection
