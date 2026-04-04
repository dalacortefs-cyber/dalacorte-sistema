<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Models\ProjetoInterno;
use App\Models\User;
use Illuminate\Http\Request;

class ProjetoInternoController extends Controller
{
    public function index()
    {
        $projetos = ProjetoInterno::where('escritorio_id', auth()->user()->escritorio_id)
            ->with('responsavel')->orderByDesc('created_at')->paginate(20);
        return view('painel.projetos.index', compact('projetos'));
    }

    public function create()
    {
        $usuarios = User::where('escritorio_id', auth()->user()->escritorio_id)->where('active', true)->get();
        return view('painel.projetos.form', ['projeto' => null, 'usuarios' => $usuarios]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo'         => 'required|string|max:255',
            'descricao'      => 'nullable|string',
            'responsavel_id' => 'nullable|exists:users,id',
            'status'         => 'nullable|in:Planejamento,Em Andamento,Concluído,Cancelado',
            'prioridade'     => 'nullable|in:Baixa,Normal,Alta,Urgente',
            'data_inicio'    => 'nullable|date',
            'data_previsao'  => 'nullable|date',
            'cor_identificacao' => 'nullable|string|max:10',
        ]);
        $data['escritorio_id']   = auth()->user()->escritorio_id;
        $data['responsavel_nome']= User::find($data['responsavel_id'] ?? null)?->name;
        ProjetoInterno::create($data);
        return redirect()->route('painel.projetos.index')->with('success', 'Projeto criado.');
    }

    public function show(ProjetoInterno $projeto)
    {
        return $this->edit($projeto);
    }

    public function edit(ProjetoInterno $projeto)
    {
        $usuarios = User::where('escritorio_id', auth()->user()->escritorio_id)->where('active', true)->get();
        return view('painel.projetos.form', compact('projeto', 'usuarios'));
    }

    public function update(Request $request, ProjetoInterno $projeto)
    {
        $data = $request->validate([
            'titulo'    => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'status'    => 'nullable|in:Planejamento,Em Andamento,Concluído,Cancelado',
            'prioridade'=> 'nullable|in:Baixa,Normal,Alta,Urgente',
            'data_previsao' => 'nullable|date',
            'data_conclusao'=> 'nullable|date',
        ]);
        $projeto->update($data);
        return redirect()->route('painel.projetos.index')->with('success', 'Projeto atualizado.');
    }

    public function destroy(ProjetoInterno $projeto)
    {
        $projeto->delete();
        return back()->with('success', 'Projeto removido.');
    }
}
