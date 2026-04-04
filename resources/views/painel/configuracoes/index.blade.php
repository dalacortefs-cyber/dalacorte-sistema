@extends('layouts.painel')
@section('title', 'Configurações')
@section('breadcrumb', 'Configurações')

@section('content')
<div class="page-header">
    <h1 class="page-title">Configurações do Sistema</h1>
</div>

@if(session('success'))
<div style="background:rgba(52,211,153,0.15); border:1px solid rgba(52,211,153,0.4); border-radius:6px; padding:0.75rem 1rem; margin-bottom:1.25rem; color:#34d399; font-size:0.875rem">
    {{ session('success') }}
</div>
@endif

<form method="POST" action="{{ route('painel.configuracoes.update') }}">
    @csrf

    <div class="grid-2" style="gap:1.25rem; align-items:start">

        {{-- Dados do escritório --}}
        <div style="display:flex; flex-direction:column; gap:1.25rem">
            <div class="card">
                <div class="card-title" style="margin-bottom:1rem">Dados do Escritório</div>

                <div class="form-group">
                    <label class="form-label">Nome do Escritório</label>
                    <input type="text" name="configs[escritorio_nome]" class="form-control"
                        value="{{ old('configs.escritorio_nome', $configs['escritorio_nome'] ?? '') }}"
                        placeholder="Dalacorte Financial Solutions">
                </div>
                <div class="form-group">
                    <label class="form-label">CNPJ</label>
                    <input type="text" name="configs[escritorio_cnpj]" class="form-control"
                        value="{{ old('configs.escritorio_cnpj', $configs['escritorio_cnpj'] ?? '') }}"
                        placeholder="00.000.000/0000-00">
                </div>
                <div class="form-group">
                    <label class="form-label">CRC</label>
                    <input type="text" name="configs[escritorio_crc]" class="form-control"
                        value="{{ old('configs.escritorio_crc', $configs['escritorio_crc'] ?? '') }}"
                        placeholder="RS-000000/O-5">
                </div>
                <div class="form-group">
                    <label class="form-label">E-mail de Contato</label>
                    <input type="email" name="configs[escritorio_email]" class="form-control"
                        value="{{ old('configs.escritorio_email', $configs['escritorio_email'] ?? '') }}"
                        placeholder="contato@escritorio.com.br">
                </div>
                <div class="form-group">
                    <label class="form-label">Telefone</label>
                    <input type="text" name="configs[escritorio_telefone]" class="form-control"
                        value="{{ old('configs.escritorio_telefone', $configs['escritorio_telefone'] ?? '') }}"
                        placeholder="(51) 00000-0000">
                </div>
            </div>
        </div>

        {{-- Alertas e comportamento --}}
        <div style="display:flex; flex-direction:column; gap:1.25rem">
            <div class="card">
                <div class="card-title" style="margin-bottom:1rem">Alertas Automáticos</div>

                <div class="form-group">
                    <label class="form-label">Alertar certidões vencendo em (dias)</label>
                    <input type="number" name="configs[alerta_certidao_dias]" class="form-control" min="1" max="90"
                        value="{{ old('configs.alerta_certidao_dias', $configs['alerta_certidao_dias'] ?? 30) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Alertar certificados vencendo em (dias)</label>
                    <input type="number" name="configs[alerta_certificado_dias]" class="form-control" min="1" max="90"
                        value="{{ old('configs.alerta_certificado_dias', $configs['alerta_certificado_dias'] ?? 60) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Alertar tarefas com prazo em (dias)</label>
                    <input type="number" name="configs[alerta_tarefa_dias]" class="form-control" min="1" max="30"
                        value="{{ old('configs.alerta_tarefa_dias', $configs['alerta_tarefa_dias'] ?? 3) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Alertar contas a pagar vencendo em (dias)</label>
                    <input type="number" name="configs[alerta_conta_dias]" class="form-control" min="1" max="30"
                        value="{{ old('configs.alerta_conta_dias', $configs['alerta_conta_dias'] ?? 5) }}">
                </div>
            </div>

            <div class="card">
                <div class="card-title" style="margin-bottom:1rem">Financeiro</div>

                <div class="form-group">
                    <label class="form-label">Dia de Corte para MRR</label>
                    <input type="number" name="configs[dia_corte_mrr]" class="form-control" min="1" max="28"
                        value="{{ old('configs.dia_corte_mrr', $configs['dia_corte_mrr'] ?? 1) }}"
                        placeholder="Dia de referência para calcular o MRR">
                </div>
                <div class="form-group">
                    <label class="form-label">Moeda (exibição)</label>
                    <select name="configs[moeda]" class="form-control">
                        <option value="BRL" {{ ($configs['moeda'] ?? 'BRL') === 'BRL' ? 'selected' : '' }}>R$ (Real Brasileiro)</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div style="margin-top:1.25rem; display:flex; justify-content:flex-end">
        <button type="submit" class="btn btn-primary" style="min-width:160px">Salvar Configurações</button>
    </div>
</form>

{{-- Info section --}}
<div class="card" style="margin-top:1.25rem">
    <div class="card-title" style="margin-bottom:0.75rem">Informações do Sistema</div>
    <table style="width:100%; font-size:0.875rem">
        @foreach([
            ['Versão', '1.0.0'],
            ['Laravel', app()->version()],
            ['PHP', PHP_VERSION],
            ['Banco de Dados', 'MySQL'],
            ['Ambiente', app()->environment()],
        ] as [$label, $value])
        <tr style="border-bottom:1px solid var(--border)">
            <td style="padding:0.5rem 0; color:var(--muted); width:30%">{{ $label }}</td>
            <td style="padding:0.5rem 0; font-family:monospace; font-size:0.8rem">{{ $value }}</td>
        </tr>
        @endforeach
    </table>
</div>
@endsection
