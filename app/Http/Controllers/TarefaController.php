<?php

namespace App\Http\Controllers;

use App\Models\Tarefa;
use App\Services\AuditService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TarefaController extends Controller
{
    public function __construct(private AuditService $audit) {}

    public function index(Request $request): JsonResponse
    {
        $query = Tarefa::with(['responsavel:id,name', 'cliente:id,nome'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->prioridade, fn($q) => $q->where('prioridade', $request->prioridade))
            ->when($request->categoria, fn($q) => $q->where('categoria', $request->categoria))
            ->when($request->responsavel_id, fn($q) => $q->where('responsavel_id', $request->responsavel_id))
            ->when($request->cliente_id, fn($q) => $q->where('cliente_id', $request->cliente_id))
            ->when($request->apenas_minhas, fn($q) => $q->where('responsavel_id', auth('api')->id()))
            ->orderByRaw("FIELD(prioridade, 'urgente', 'alta', 'media', 'baixa')")
            ->orderBy('data_vencimento');

        return response()->json($query->paginate($request->per_page ?? 20));
    }

    public function store(Request $request): JsonResponse
    {
        $dados = $request->validate([
            'titulo'          => 'required|string|max:255',
            'descricao'       => 'nullable|string',
            'prioridade'      => 'sometimes|in:baixa,media,alta,urgente',
            'status'          => 'sometimes|in:pendente,em_andamento,concluida,cancelada',
            'categoria'       => 'sometimes|in:financeiro,contabil,administrativo,comercial,outros',
            'responsavel_id'  => 'nullable|exists:users,id',
            'cliente_id'      => 'nullable|exists:clientes,id',
            'data_vencimento' => 'nullable|date',
        ]);

        $dados['user_id'] = auth('api')->id();
        if (empty($dados['responsavel_id'])) {
            $dados['responsavel_id'] = auth('api')->id();
        }

        $tarefa = Tarefa::create($dados);
        $this->audit->registrarCriacao('tarefas', $tarefa);

        return response()->json($tarefa->load(['responsavel:id,name', 'cliente:id,nome']), 201);
    }

    public function show(Tarefa $tarefa): JsonResponse
    {
        return response()->json($tarefa->load(['responsavel:id,name', 'cliente:id,nome', 'user:id,name']));
    }

    public function update(Request $request, Tarefa $tarefa): JsonResponse
    {
        $dados = $request->validate([
            'titulo'          => 'sometimes|string|max:255',
            'descricao'       => 'nullable|string',
            'prioridade'      => 'sometimes|in:baixa,media,alta,urgente',
            'status'          => 'sometimes|in:pendente,em_andamento,concluida,cancelada',
            'categoria'       => 'sometimes|in:financeiro,contabil,administrativo,comercial,outros',
            'responsavel_id'  => 'nullable|exists:users,id',
            'cliente_id'      => 'nullable|exists:clientes,id',
            'data_vencimento' => 'nullable|date',
        ]);

        if (isset($dados['status']) && $dados['status'] === 'concluida' && !$tarefa->data_conclusao) {
            $dados['data_conclusao'] = now();
        }

        $antes = $tarefa->toArray();
        $tarefa->update($dados);
        $this->audit->registrarAtualizacao('tarefas', $tarefa, $antes);

        return response()->json($tarefa->load(['responsavel:id,name', 'cliente:id,nome']));
    }

    public function destroy(Tarefa $tarefa): JsonResponse
    {
        $this->audit->registrarExclusao('tarefas', $tarefa);
        $tarefa->delete();
        return response()->json(['message' => 'Tarefa removida.']);
    }

    public function concluir(Tarefa $tarefa): JsonResponse
    {
        $tarefa->update(['status' => 'concluida', 'data_conclusao' => now()]);
        $this->audit->registrar('conclusao', 'tarefas', null, null, Tarefa::class, $tarefa->id);
        return response()->json(['message' => 'Tarefa concluída.', 'tarefa' => $tarefa]);
    }
}
