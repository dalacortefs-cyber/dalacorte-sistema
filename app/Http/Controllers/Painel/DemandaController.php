<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Models\ChecklistItem;
use App\Models\ComentarioDemanda;
use App\Models\Demanda;
use App\Models\Empresa;
use App\Models\LogAtividade;
use App\Models\ProjetoInterno;
use App\Models\User;
use Illuminate\Http\Request;

class DemandaController extends Controller
{
    public function index(Request $request)
    {
        $escritorioId = auth()->user()->escritorio_id;
        $query = Demanda::where('escritorio_id', $escritorioId)
            ->with(['empresa', 'responsavel'])
            ->orderByDesc('created_at');

        if ($request->filled('status'))    $query->where('status', $request->status);
        if ($request->filled('prioridade'))$query->where('prioridade', $request->prioridade);
        if ($request->filled('tipo'))      $query->where('tipo', $request->tipo);
        if ($request->filled('empresa_id'))$query->where('empresa_id', $request->empresa_id);

        $demandas = $query->paginate(20)->withQueryString();
        $empresas = Empresa::where('escritorio_id', $escritorioId)->where('status','Ativa')->orderBy('razao_social')->get();

        return view('painel.demandas.index', compact('demandas', 'empresas'));
    }

    public function create()
    {
        $escritorioId = auth()->user()->escritorio_id;
        $empresas  = Empresa::where('escritorio_id', $escritorioId)->where('status','Ativa')->orderBy('razao_social')->get();
        $projetos  = ProjetoInterno::where('escritorio_id', $escritorioId)->whereIn('status',['Planejamento','Em Andamento'])->get();
        $usuarios  = User::where('escritorio_id', $escritorioId)->where('active', true)->whereIn('role',['admin','gestor','operacional'])->get();
        return view('painel.demandas.form', ['demanda' => null, 'empresas' => $empresas, 'projetos' => $projetos, 'usuarios' => $usuarios]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo'        => 'required|string|max:255',
            'descricao'     => 'nullable|string',
            'tipo'          => 'nullable|in:Pendência,OS Interna,Solicitação de Cliente,Melhoria,Bug/Erro,Rotina',
            'natureza'      => 'required|in:Extra,Recorrente',
            'prioridade'    => 'nullable|in:Baixa,Normal,Alta,Urgente',
            'empresa_id'    => 'nullable|exists:empresas,id',
            'projeto_id'    => 'nullable|exists:projetos_internos,id',
            'responsavel_id'=> 'nullable|exists:users,id',
            'data_abertura' => 'required|date',
            'data_previsao' => 'nullable|date|after_or_equal:data_abertura',
            'tags'          => 'nullable|string|max:255',
        ]);

        $data['escritorio_id'] = auth()->user()->escritorio_id;
        $data['criado_por_id'] = auth()->id();
        $data['criado_por_nome'] = auth()->user()->name;

        if (!empty($data['empresa_id'])) {
            $data['empresa_nome'] = Empresa::find($data['empresa_id'])?->razao_social;
        }
        if (!empty($data['responsavel_id'])) {
            $data['responsavel_nome'] = User::find($data['responsavel_id'])?->name;
        }
        if (!empty($data['projeto_id'])) {
            $data['projeto_nome'] = ProjetoInterno::find($data['projeto_id'])?->titulo;
        }

        $demanda = Demanda::create($data);

        // Checklist items opcionais
        if ($request->filled('checklist_items')) {
            foreach (explode("\n", $request->checklist_items) as $i => $item) {
                $item = trim($item);
                if ($item) {
                    ChecklistItem::create(['demanda_id' => $demanda->id, 'descricao' => $item, 'ordem' => $i]);
                }
            }
        }

        LogAtividade::registrar('Tarefas', 'CREATE', "Demanda {$demanda->numero_os} criada.", $demanda);

        return redirect()->route('painel.demandas.show', $demanda)
            ->with('success', "Demanda {$demanda->numero_os} criada.");
    }

    public function show(Demanda $demanda)
    {
        $this->authorize_escritorio($demanda);
        $demanda->load(['empresa', 'responsavel', 'criadoPor', 'checklistItems', 'comentarios.user']);
        return view('painel.demandas.show', compact('demanda'));
    }

    public function edit(Demanda $demanda)
    {
        $this->authorize_escritorio($demanda);
        $escritorioId = auth()->user()->escritorio_id;
        $empresas  = Empresa::where('escritorio_id', $escritorioId)->where('status','Ativa')->orderBy('razao_social')->get();
        $projetos  = ProjetoInterno::where('escritorio_id', $escritorioId)->get();
        $usuarios  = User::where('escritorio_id', $escritorioId)->where('active', true)->whereIn('role',['admin','gestor','operacional'])->get();
        return view('painel.demandas.form', compact('demanda', 'empresas', 'projetos', 'usuarios'));
    }

    public function update(Request $request, Demanda $demanda)
    {
        $this->authorize_escritorio($demanda);
        $data = $request->validate([
            'titulo'        => 'required|string|max:255',
            'descricao'     => 'nullable|string',
            'tipo'          => 'nullable|string',
            'status'        => 'nullable|in:Aberta,Em Andamento,Aguardando,Concluída,Cancelada',
            'prioridade'    => 'nullable|in:Baixa,Normal,Alta,Urgente',
            'responsavel_id'=> 'nullable|exists:users,id',
            'data_previsao' => 'nullable|date',
            'tags'          => 'nullable|string|max:255',
        ]);

        if ($data['status'] === 'Concluída' && !$demanda->data_real_conclusao) {
            $data['data_real_conclusao'] = now()->toDateString();
            $data['concluida_no_prazo']  = $demanda->data_previsao
                ? now()->lte($demanda->data_previsao)
                : null;
        }

        $demanda->update($data);
        LogAtividade::registrar('Tarefas', 'UPDATE', "Demanda {$demanda->numero_os} atualizada.", $demanda);
        return redirect()->route('painel.demandas.show', $demanda)->with('success', 'Demanda atualizada.');
    }

    public function destroy(Demanda $demanda)
    {
        $this->authorize_escritorio($demanda);
        $demanda->delete();
        return redirect()->route('painel.demandas.index')->with('success', 'Demanda removida.');
    }

    public function storeComentario(Request $request, Demanda $demanda)
    {
        $this->authorize_escritorio($demanda);
        $request->validate(['mensagem' => 'required|string|max:2000']);

        ComentarioDemanda::create([
            'demanda_id'  => $demanda->id,
            'user_id'     => auth()->id(),
            'usuario_nome'=> auth()->user()->name,
            'mensagem'    => $request->mensagem,
            'tipo'        => 'Comentário',
        ]);

        return back()->with('success', 'Comentário adicionado.');
    }

    public function toggleChecklist(Demanda $demanda, ChecklistItem $item)
    {
        $this->authorize_escritorio($demanda);
        $item->update([
            'concluido'        => !$item->concluido,
            'data_conclusao'   => !$item->concluido ? now()->toDateString() : null,
            'usuario_conclusao'=> !$item->concluido ? auth()->user()->name : null,
        ]);
        return back();
    }

    public function storeChecklist(Request $request, Demanda $demanda)
    {
        $this->authorize_escritorio($demanda);
        $request->validate(['descricao' => 'required|string|max:255']);
        $demanda->checklistItems()->create([
            'descricao' => $request->descricao,
            'concluido' => false,
        ]);
        return back()->with('success', 'Item adicionado.');
    }

    private function authorize_escritorio(Demanda $demanda): void
    {
        if ($demanda->escritorio_id !== auth()->user()->escritorio_id) abort(403);
    }
}
