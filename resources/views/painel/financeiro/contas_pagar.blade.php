@extends('layouts.painel')
@section('title', 'Contas a Pagar')
@section('breadcrumb', 'Financeiro / Contas a Pagar')

@section('content')
<div class="page-header" style="display:flex; justify-content:space-between; align-items:flex-start">
    <h1 class="page-title">Financeiro</h1>
    <div style="display:flex; gap:0.75rem">
        <a href="{{ route('painel.financeiro.contas-receber') }}" class="btn btn-ghost">A Receber</a>
        <a href="{{ route('painel.financeiro.contas-pagar') }}" class="btn btn-primary">A Pagar</a>
    </div>
</div>

<div class="grid-4" style="margin-bottom:1.25rem">
    <div class="card">
        <div class="card-title">Total do Mês</div>
        <div class="kpi-value" style="font-size:1.4rem">R$ {{ number_format($kpis['total'], 2, ',', '.') }}</div>
    </div>
    <div class="card">
        <div class="card-title">Pago</div>
        <div class="kpi-value kpi-up" style="font-size:1.4rem">R$ {{ number_format($kpis['pago'], 2, ',', '.') }}</div>
    </div>
    <div class="card">
        <div class="card-title">A Pagar</div>
        <div class="kpi-value" style="font-size:1.4rem">R$ {{ number_format($kpis['apagar'], 2, ',', '.') }}</div>
    </div>
    <div class="card">
        <div class="card-title">Atrasadas</div>
        <div class="kpi-value kpi-down">{{ $kpis['atrasado'] }}</div>
    </div>
</div>

<div class="card" style="margin-bottom:1.25rem; padding:1rem" x-data="{ modal: false }">
    <div style="display:flex; justify-content:space-between; align-items:flex-end; flex-wrap:wrap; gap:0.75rem">
        <form method="GET" style="display:flex; gap:0.75rem; flex-wrap:wrap; align-items:flex-end">
            <div>
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <option value="">Todos</option>
                    @foreach(['Pendente','Pago','Atrasado','Cancelado'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Categoria</label>
                <select name="categoria" class="form-control">
                    <option value="">Todas</option>
                    @foreach(['Aluguel','Salários','Software','Material','Impostos','Marketing','Telefone/Internet','Outros'] as $c)
                        <option value="{{ $c }}" {{ request('categoria') === $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </form>
        <button @click="modal = true" class="btn btn-gold">+ Lançar Despesa</button>
    </div>

    <div x-show="modal" x-transition class="modal-overlay" @click.self="modal = false">
        <div class="modal">
            <div class="modal-title">Nova Conta a Pagar</div>
            <form method="POST" action="{{ route('painel.financeiro.cp.store') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Descrição *</label>
                    <input type="text" name="descricao" class="form-control" required>
                </div>
                <div class="grid-2" style="gap:0.75rem">
                    <div class="form-group">
                        <label class="form-label">Fornecedor</label>
                        <input type="text" name="fornecedor" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Categoria</label>
                        <select name="categoria" class="form-control">
                            @foreach(['Aluguel','Salários','Software','Material','Impostos','Marketing','Telefone/Internet','Outros'] as $c)
                                <option value="{{ $c }}">{{ $c }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid-2" style="gap:0.75rem">
                    <div class="form-group">
                        <label class="form-label">Valor *</label>
                        <input type="number" name="valor" class="form-control" step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Vencimento *</label>
                        <input type="date" name="data_vencimento" class="form-control" required>
                    </div>
                </div>
                <div style="display:flex; gap:0.75rem; justify-content:flex-end; margin-top:1rem">
                    <button type="button" @click="modal = false" class="btn btn-ghost">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Descrição</th><th>Fornecedor</th><th>Categoria</th>
                    <th>Valor</th><th>Vencimento</th><th>Status</th><th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contas as $conta)
                <tr x-data="{ baixa: false }">
                    <td style="font-weight:500">{{ $conta->descricao }}</td>
                    <td style="font-size:0.85rem; color:var(--muted)">{{ $conta->fornecedor ?: '—' }}</td>
                    <td><span class="badge badge-gray">{{ $conta->categoria ?: '—' }}</span></td>
                    <td>R$ {{ number_format($conta->valor, 2, ',', '.') }}</td>
                    <td style="{{ $conta->status === 'Atrasado' ? 'color:#f87171' : '' }}">
                        {{ $conta->data_vencimento->format('d/m/Y') }}
                    </td>
                    <td>
                        @php $sc = match($conta->status) { 'Pago' => 'badge-green', 'Atrasado' => 'badge-red', 'Cancelado' => 'badge-gray', default => 'badge-yellow' }; @endphp
                        <span class="badge {{ $sc }}">{{ $conta->status }}</span>
                    </td>
                    <td>
                        <div style="display:flex; gap:0.4rem">
                            @if($conta->status !== 'Pago')
                            <button @click="baixa = true" class="btn btn-ghost btn-sm">Baixar</button>
                            <div x-show="baixa" x-transition class="modal-overlay" @click.self="baixa = false">
                                <div class="modal">
                                    <div class="modal-title">Registrar Pagamento</div>
                                    <form method="POST" action="{{ route('painel.financeiro.cp.baixar', $conta) }}">
                                        @csrf @method('PATCH')
                                        <div class="form-group">
                                            <label class="form-label">Data do Pagamento *</label>
                                            <input type="date" name="data_pagamento" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Forma de Pagamento *</label>
                                            <select name="forma_pagamento" class="form-control" required>
                                                @foreach(['PIX','Boleto','Transferência','Cartão','Dinheiro'] as $f)
                                                    <option>{{ $f }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div style="display:flex; gap:0.75rem; justify-content:flex-end; margin-top:1rem">
                                            <button type="button" @click="baixa = false" class="btn btn-ghost">Cancelar</button>
                                            <button type="submit" class="btn btn-primary">Confirmar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @endif
                            <form method="POST" action="{{ route('painel.financeiro.cp.destroy', $conta) }}" onsubmit="return confirm('Remover?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">✕</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center; padding:2rem; color:var(--muted)">Nenhuma conta encontrada.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($contas->hasPages())
        <div style="padding:1rem; border-top:1px solid var(--border)">{{ $contas->links() }}</div>
    @endif
</div>
@endsection
