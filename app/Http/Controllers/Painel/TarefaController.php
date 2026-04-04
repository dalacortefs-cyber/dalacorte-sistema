<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\LogAtividade;
use App\Models\Obrigacao;
use App\Models\TarefaDfs;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TarefaController extends Controller
{
    public function index(Request $request)
    {
        $escritorioId = auth()->user()->escritorio_id;
        $query = TarefaDfs::where('escritorio_id', $escritorioId)
            ->with('empresa')
            ->orderBy('data_vencimento');

        if ($request->filled('empresa_id'))  $query->where('empresa_id', $request->empresa_id);
        if ($request->filled('competencia')) $query->where('competencia', $request->competencia);
        if ($request->filled('status'))      $query->where('status', $request->status);
        if ($request->filled('criticidade')) $query->where('nivel_criticidade', $request->criticidade);
        if ($request->filled('responsavel')) $query->where('responsavel', 'like', "%{$request->responsavel}%");

        $tarefas  = $query->paginate(25)->withQueryString();
        $empresas = Empresa::where('escritorio_id', $escritorioId)->where('status','Ativa')->orderBy('razao_social')->get();

        // Kanban counts
        $kanban = [
            'Pendente'     => TarefaDfs::where('escritorio_id', $escritorioId)->where('status','Pendente')->count(),
            'Em andamento' => TarefaDfs::where('escritorio_id', $escritorioId)->where('status','Em andamento')->count(),
            'Concluído'    => TarefaDfs::where('escritorio_id', $escritorioId)->where('status','Concluído')->count(),
        ];

        return view('painel.tarefas.index', compact('tarefas', 'empresas', 'kanban'));
    }

    public function create()
    {
        $escritorioId = auth()->user()->escritorio_id;
        $empresas    = Empresa::where('escritorio_id', $escritorioId)->where('status','Ativa')->orderBy('razao_social')->get();
        $obrigacoes  = Obrigacao::where('escritorio_id', $escritorioId)->where('ativa', true)->orderBy('nome')->get();
        return view('painel.tarefas.form', ['tarefa' => null, 'empresas' => $empresas, 'obrigacoes' => $obrigacoes]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'empresa_id'       => 'required|exists:empresas,id',
            'obrigacao_nome'   => 'required|string|max:255',
            'competencia'      => 'required|string|max:7',
            'data_vencimento'  => 'nullable|date',
            'status'           => 'nullable|in:Pendente,Em andamento,Concluído,Arquivado',
            'responsavel'      => 'nullable|string|max:255',
            'nivel_criticidade'=> 'nullable|in:Baixa,Média,Alta,Crítica',
            'esfera'           => 'nullable|in:Federal,Estadual,Municipal,Trabalhista',
            'periodicidade'    => 'nullable|in:Mensal,Trimestral,Semestral,Anual,Eventual',
            'observacoes'      => 'nullable|string',
        ]);

        $empresa = Empresa::findOrFail($data['empresa_id']);
        $data['escritorio_id'] = auth()->user()->escritorio_id;
        $data['empresa_nome']  = $empresa->razao_social;

        $tarefa = TarefaDfs::create($data);
        LogAtividade::registrar('Tarefas', 'CREATE', "Tarefa {$tarefa->obrigacao_nome} criada para {$empresa->razao_social}.", $tarefa);

        return redirect()->route('painel.tarefas.index')->with('success', 'Tarefa criada com sucesso.');
    }

    public function show(TarefaDfs $tarefa)
    {
        $this->authorize_escritorio($tarefa);
        return view('painel.tarefas.show', compact('tarefa'));
    }

    public function edit(TarefaDfs $tarefa)
    {
        $this->authorize_escritorio($tarefa);
        $escritorioId = auth()->user()->escritorio_id;
        $empresas    = Empresa::where('escritorio_id', $escritorioId)->where('status','Ativa')->orderBy('razao_social')->get();
        $obrigacoes  = Obrigacao::where('escritorio_id', $escritorioId)->where('ativa', true)->orderBy('nome')->get();
        return view('painel.tarefas.form', compact('tarefa', 'empresas', 'obrigacoes'));
    }

    public function update(Request $request, TarefaDfs $tarefa)
    {
        $this->authorize_escritorio($tarefa);
        $data = $request->validate([
            'empresa_id'       => 'required|exists:empresas,id',
            'obrigacao_nome'   => 'required|string|max:255',
            'competencia'      => 'required|string|max:7',
            'data_vencimento'  => 'nullable|date',
            'status'           => 'nullable|in:Pendente,Em andamento,Concluído,Arquivado',
            'responsavel'      => 'nullable|string|max:255',
            'nivel_criticidade'=> 'nullable|in:Baixa,Média,Alta,Crítica',
            'esfera'           => 'nullable|in:Federal,Estadual,Municipal,Trabalhista',
            'observacoes'      => 'nullable|string',
        ]);
        $tarefa->update($data);
        LogAtividade::registrar('Tarefas', 'UPDATE', "Tarefa {$tarefa->obrigacao_nome} atualizada.", $tarefa);
        return redirect()->route('painel.tarefas.index')->with('success', 'Tarefa atualizada.');
    }

    public function destroy(TarefaDfs $tarefa)
    {
        $this->authorize_escritorio($tarefa);
        $tarefa->delete();
        return back()->with('success', 'Tarefa removida.');
    }

    public function concluir(Request $request, TarefaDfs $tarefa)
    {
        $this->authorize_escritorio($tarefa);
        $request->validate(['comprovante_url' => 'nullable|string|max:500']);

        $hoje = Carbon::today();
        $concluida_no_prazo = $tarefa->data_vencimento
            ? $hoje->lte(Carbon::parse($tarefa->data_vencimento))
            : null;

        $tarefa->update([
            'status'             => 'Concluído',
            'data_real_conclusao'=> $hoje,
            'usuario_conclusao'  => auth()->user()->name,
            'concluida_no_prazo' => $concluida_no_prazo,
            'comprovante_url'    => $request->comprovante_url ?? $tarefa->comprovante_url,
        ]);

        LogAtividade::registrar('Tarefas', 'UPDATE', "Tarefa {$tarefa->obrigacao_nome} concluída.", $tarefa);

        return back()->with('success', 'Tarefa marcada como concluída.');
    }

    private function authorize_escritorio(TarefaDfs $tarefa): void
    {
        if ($tarefa->escritorio_id !== auth()->user()->escritorio_id) abort(403);
    }
}
