@extends('layouts.painel')
@section('title', $empresa->razao_social)
@section('breadcrumb', 'Empresas / Ficha')

@section('content')
<div class="page-header" style="display:flex; justify-content:space-between; align-items:flex-start">
    <div>
        <h1 class="page-title">{{ $empresa->razao_social }}</h1>
        <div style="display:flex; gap:0.5rem; margin-top:0.4rem; flex-wrap:wrap">
            @if($empresa->nome_fantasia)
                <span class="text-muted">{{ $empresa->nome_fantasia }}</span>
                <span class="text-muted">·</span>
            @endif
            <span style="font-family:monospace; font-size:0.85rem; color:var(--muted)">{{ $empresa->cnpj }}</span>
            @php $sc = match($empresa->status) { 'Ativa' => 'badge-green', 'Inativa' => 'badge-gray', default => 'badge-yellow' }; @endphp
            <span class="badge {{ $sc }}">{{ $empresa->status }}</span>
            @if($empresa->regime_tributario)
                <span class="badge badge-teal">{{ $empresa->regime_tributario }}</span>
            @endif
        </div>
    </div>
    <div style="display:flex; gap:0.5rem">
        <a href="{{ route('painel.empresas.edit', $empresa) }}" class="btn btn-ghost">Editar</a>
        <a href="{{ route('painel.empresas.socios', $empresa) }}" class="btn btn-ghost">Sócios</a>
        <a href="{{ route('painel.empresas.index') }}" class="btn btn-ghost">← Voltar</a>
    </div>
</div>

<div class="grid-2" style="gap:1.25rem; align-items:start">

    {{-- Dados principais --}}
    <div style="display:flex; flex-direction:column; gap:1.25rem">
        <div class="card">
            <div class="card-title" style="margin-bottom:1rem">Dados da Empresa</div>
            <table style="width:100%; font-size:0.875rem">
                @foreach([
                    ['UF / Município', ($empresa->uf ?? '?').' / '.($empresa->municipio ?? '—')],
                    ['Tipo de Atividade', $empresa->tipo_atividade ?? '—'],
                    ['CNAE Principal', $empresa->cnae_principal ?? '—'],
                    ['Complexidade', $empresa->complexidade_tributaria ?? '—'],
                    ['E-mail', $empresa->email ?? '—'],
                    ['Telefone', $empresa->telefone ?? '—'],
                    ['Funcionários', $empresa->possui_empregados ? ($empresa->qtd_empregados ?? '?').' func.' : 'Não'],
                    ['Início da Atividade', $empresa->data_inicio_atividade?->format('d/m/Y') ?? '—'],
                ] as [$label, $value])
                <tr style="border-bottom:1px solid var(--border)">
                    <td style="padding:0.6rem 0; color:var(--muted); width:45%">{{ $label }}</td>
                    <td style="padding:0.6rem 0; color:var(--text)">{{ $value }}</td>
                </tr>
                @endforeach
            </table>
        </div>

        <div class="card">
            <div class="card-title" style="margin-bottom:1rem">Contrato</div>
            <table style="width:100%; font-size:0.875rem">
                @foreach([
                    ['Honorário Mensal', $empresa->valor_honorario_mensal ? 'R$ '.number_format($empresa->valor_honorario_mensal, 2, ',', '.') : '—'],
                    ['Início do Contrato', $empresa->data_inicio_contrato?->format('d/m/Y') ?? '—'],
                    ['Índice Reajuste', $empresa->indice_reajuste ?? '—'],
                    ['Mês Reajuste', $empresa->mes_reajuste ? 'Mês '.$empresa->mes_reajuste : '—'],
                    ['Responsável Interno', $empresa->responsavel_interno ?? '—'],
                    ['Score Cliente', $empresa->score_cliente ?? 100],
                ] as [$label, $value])
                <tr style="border-bottom:1px solid var(--border)">
                    <td style="padding:0.6rem 0; color:var(--muted); width:45%">{{ $label }}</td>
                    <td style="padding:0.6rem 0; color:var(--text)">{{ $value }}</td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>

    {{-- Lado direito: Tarefas recentes, sócios, certidões --}}
    <div style="display:flex; flex-direction:column; gap:1.25rem">

        {{-- Sócios --}}
        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.75rem">
                <div class="card-title" style="margin:0">Sócios</div>
                <a href="{{ route('painel.empresas.socios', $empresa) }}" class="btn btn-ghost btn-sm">Gerenciar</a>
            </div>
            @forelse($empresa->socios as $socio)
            <div style="padding:0.5rem 0; border-bottom:1px solid var(--border); font-size:0.85rem">
                <div style="font-weight:500">{{ $socio->nome }}</div>
                <div style="color:var(--muted)">
                    {{ $socio->tipo ?? '' }}
                    {{ $socio->participacao ? ' · '.number_format($socio->participacao,1).'%' : '' }}
                </div>
            </div>
            @empty
            <p class="text-muted" style="text-align:center; padding:0.75rem 0">Nenhum sócio cadastrado.</p>
            @endforelse
        </div>

        {{-- Tarefas recentes --}}
        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.75rem">
                <div class="card-title" style="margin:0">Tarefas Recentes</div>
                <a href="{{ route('painel.tarefas.index', ['empresa_id' => $empresa->id]) }}" class="btn btn-ghost btn-sm">Ver todas</a>
            </div>
            @forelse($empresa->tarefas as $tarefa)
            <div style="padding:0.5rem 0; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; font-size:0.85rem">
                <div>
                    <div>{{ $tarefa->obrigacao_nome }}</div>
                    <div style="color:var(--muted)">{{ $tarefa->competencia }}</div>
                </div>
                @php $sc = match($tarefa->status) { 'Concluído' => 'badge-green', 'Em andamento' => 'badge-blue', default => 'badge-yellow' }; @endphp
                <span class="badge {{ $sc }}">{{ $tarefa->status }}</span>
            </div>
            @empty
            <p class="text-muted" style="text-align:center; padding:0.75rem 0">Nenhuma tarefa registrada.</p>
            @endforelse
        </div>

        {{-- Certidões --}}
        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.75rem">
                <div class="card-title" style="margin:0">Certidões</div>
                <a href="{{ route('painel.certidoes.index', ['empresa_id' => $empresa->id]) }}" class="btn btn-ghost btn-sm">Ver todas</a>
            </div>
            @forelse($empresa->certidoes as $c)
            <div style="padding:0.5rem 0; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; font-size:0.85rem">
                <div>
                    <div>{{ $c->tipo }}</div>
                    <div style="color:var(--muted)">Validade: {{ $c->data_validade->format('d/m/Y') }}</div>
                </div>
                @php $sc = match($c->status) { 'Válida' => 'badge-green', 'Vencida' => 'badge-red', default => 'badge-yellow' }; @endphp
                <span class="badge {{ $sc }}">{{ $c->status }}</span>
            </div>
            @empty
            <p class="text-muted" style="text-align:center; padding:0.75rem 0">Nenhuma certidão registrada.</p>
            @endforelse
        </div>

    </div>
</div>
@endsection
