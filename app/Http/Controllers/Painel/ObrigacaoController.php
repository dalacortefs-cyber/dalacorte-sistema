<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Models\Obrigacao;
use Illuminate\Http\Request;

class ObrigacaoController extends Controller
{
    public function index()
    {
        $obrigacoes = Obrigacao::where('escritorio_id', auth()->user()->escritorio_id)->orderBy('nome')->paginate(30);
        return view('painel.obrigacoes.index', compact('obrigacoes'));
    }

    public function create()
    {
        return view('painel.obrigacoes.form', ['obrigacao' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'              => 'required|string|max:255',
            'esfera'            => 'nullable|in:Federal,Estadual,Municipal,Trabalhista',
            'periodicidade'     => 'nullable|in:Mensal,Trimestral,Semestral,Anual,Eventual',
            'dia_vencimento'    => 'nullable|integer|min:1|max:31',
            'nivel_criticidade' => 'nullable|in:Baixa,Média,Alta,Crítica',
            'sla_dias_internos' => 'nullable|integer|min:0',
            'requer_empregados' => 'nullable|boolean',
            'ativa'             => 'nullable|boolean',
        ]);
        $data['escritorio_id'] = auth()->user()->escritorio_id;
        Obrigacao::create($data);
        return redirect()->route('painel.obrigacoes.index')->with('success', 'Obrigação cadastrada.');
    }

    public function edit(Obrigacao $obrigacao)
    {
        return view('painel.obrigacoes.form', compact('obrigacao'));
    }

    public function update(Request $request, Obrigacao $obrigacao)
    {
        $data = $request->validate([
            'nome'              => 'required|string|max:255',
            'esfera'            => 'nullable|in:Federal,Estadual,Municipal,Trabalhista',
            'periodicidade'     => 'nullable|in:Mensal,Trimestral,Semestral,Anual,Eventual',
            'dia_vencimento'    => 'nullable|integer|min:1|max:31',
            'nivel_criticidade' => 'nullable|in:Baixa,Média,Alta,Crítica',
            'sla_dias_internos' => 'nullable|integer|min:0',
            'ativa'             => 'nullable|boolean',
        ]);
        $obrigacao->update($data);
        return redirect()->route('painel.obrigacoes.index')->with('success', 'Obrigação atualizada.');
    }

    public function destroy(Obrigacao $obrigacao)
    {
        $obrigacao->delete();
        return back()->with('success', 'Obrigação removida.');
    }

    public function show(Obrigacao $obrigacao) { return $this->edit($obrigacao); }
}
