<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Models\ContaPagar;
use App\Models\ContaReceber;
use App\Models\Empresa;
use App\Models\LogAtividade;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class FinanceiroController extends Controller
{
    public function index()
    {
        return redirect()->route('painel.financeiro.contas-receber');
    }

    // ── Contas a Receber ──────────────────────────────────────────────

    public function contasReceber(Request $request)
    {
        $escritorioId = auth()->user()->escritorio_id;
        $query = ContaReceber::where('escritorio_id', $escritorioId)->orderBy('data_vencimento');

        if ($request->filled('competencia')) $query->where('competencia', $request->competencia);
        if ($request->filled('status'))      $query->where('status', $request->status);
        if ($request->filled('empresa_id'))  $query->where('empresa_id', $request->empresa_id);

        $contas   = $query->paginate(25)->withQueryString();
        $empresas = Empresa::where('escritorio_id', $escritorioId)->where('status','Ativa')->orderBy('razao_social')->get();

        $kpis = [
            'total'    => ContaReceber::where('escritorio_id', $escritorioId)->where('competencia', now()->format('m/Y'))->sum('valor'),
            'recebido' => ContaReceber::where('escritorio_id', $escritorioId)->where('competencia', now()->format('m/Y'))->where('status','Pago')->sum('valor'),
            'areceber' => ContaReceber::where('escritorio_id', $escritorioId)->where('competencia', now()->format('m/Y'))->whereIn('status',['Pendente','Atrasado'])->sum('valor'),
            'atrasado' => ContaReceber::where('escritorio_id', $escritorioId)->where('status','Atrasado')->count(),
        ];

        return view('painel.financeiro.contas_receber', compact('contas', 'empresas', 'kpis'));
    }

    public function storeContaReceber(Request $request)
    {
        $data = $request->validate([
            'empresa_id'      => 'required|exists:empresas,id',
            'descricao'       => 'nullable|string|max:255',
            'valor'           => 'required|numeric|min:0.01',
            'data_vencimento' => 'required|date',
            'competencia'     => 'nullable|string|max:7',
            'tipo_origem'     => 'nullable|string',
        ]);

        $empresa = Empresa::findOrFail($data['empresa_id']);
        $data['escritorio_id'] = auth()->user()->escritorio_id;
        $data['empresa_nome']  = $empresa->razao_social;
        $data['competencia']   ??= now()->format('m/Y');
        $data['status']        = 'Pendente';

        ContaReceber::create($data);
        return back()->with('success', 'Conta a receber lançada.');
    }

    public function baixarContaReceber(Request $request, ContaReceber $conta)
    {
        $request->validate([
            'data_pagamento'  => 'required|date',
            'forma_pagamento' => 'required|in:PIX,Boleto,Transferência,Cartão,Dinheiro',
        ]);

        $anterior = $conta->toArray();
        $conta->update([
            'status'          => 'Pago',
            'data_pagamento'  => $request->data_pagamento,
            'forma_pagamento' => $request->forma_pagamento,
        ]);

        LogAtividade::registrar('Financeiro', 'BAIXA', "Baixa da conta #{$conta->id} de {$conta->empresa_nome}.", $conta, $anterior);

        return back()->with('success', 'Pagamento registrado com sucesso.');
    }

    public function destroyContaReceber(ContaReceber $conta)
    {
        $conta->delete();
        return back()->with('success', 'Conta removida.');
    }

    // ── Contas a Pagar ────────────────────────────────────────────────

    public function contasPagar(Request $request)
    {
        $query = ContaPagar::orderBy('data_vencimento');
        if ($request->filled('status'))   $query->where('status', $request->status);
        if ($request->filled('categoria'))$query->where('categoria', $request->categoria);

        $contas = $query->paginate(25)->withQueryString();

        $kpis = [
            'total'    => ContaPagar::whereMonth('data_vencimento', now()->month)->sum('valor'),
            'pago'     => ContaPagar::whereMonth('data_vencimento', now()->month)->where('status','Pago')->sum('valor'),
            'apagar'   => ContaPagar::whereMonth('data_vencimento', now()->month)->whereIn('status',['Pendente','Atrasado'])->sum('valor'),
            'atrasado' => ContaPagar::where('status','Atrasado')->count(),
        ];

        return view('painel.financeiro.contas_pagar', compact('contas', 'kpis'));
    }

    public function storeContaPagar(Request $request)
    {
        $data = $request->validate([
            'descricao'       => 'required|string|max:255',
            'fornecedor'      => 'nullable|string|max:255',
            'categoria'       => 'nullable|string',
            'valor'           => 'required|numeric|min:0.01',
            'data_vencimento' => 'required|date',
            'competencia'     => 'nullable|string|max:7',
            'recorrente'      => 'nullable|boolean',
        ]);
        $data['status']    = 'Pendente';
        $data['competencia'] ??= now()->format('m/Y');
        ContaPagar::create($data);
        return back()->with('success', 'Conta a pagar lançada.');
    }

    public function baixarContaPagar(Request $request, ContaPagar $conta)
    {
        $request->validate([
            'data_pagamento'  => 'required|date',
            'forma_pagamento' => 'required|in:PIX,Boleto,Transferência,Cartão,Dinheiro',
        ]);
        $conta->update([
            'status'          => 'Pago',
            'data_pagamento'  => $request->data_pagamento,
            'forma_pagamento' => $request->forma_pagamento,
        ]);
        LogAtividade::registrar('Financeiro', 'BAIXA', "Pagamento da conta #{$conta->id} - {$conta->descricao} registrado.");
        return back()->with('success', 'Pagamento registrado.');
    }

    public function destroyContaPagar(ContaPagar $conta)
    {
        $conta->delete();
        return back()->with('success', 'Conta removida.');
    }
}
