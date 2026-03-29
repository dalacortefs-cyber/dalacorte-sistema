<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Services\AuditService;
use App\Services\ClaudeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function __construct(
        private AuditService $audit,
        private ClaudeService $claude
    ) {}

    public function index(Request $request): JsonResponse
    {
        $query = Lead::with('responsavel:id,name')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->origem, fn($q) => $q->where('origem', $request->origem))
            ->when($request->responsavel_id, fn($q) => $q->where('responsavel_id', $request->responsavel_id))
            ->when($request->busca, fn($q) => $q->where(function ($q) use ($request) {
                $q->where('nome', 'like', "%{$request->busca}%")
                  ->orWhere('email', 'like', "%{$request->busca}%")
                  ->orWhere('empresa', 'like', "%{$request->busca}%");
            }))
            ->orderByDesc('created_at');

        return response()->json($query->paginate($request->per_page ?? 15));
    }

    public function store(Request $request): JsonResponse
    {
        $dados = $request->validate([
            'nome'               => 'required|string|max:255',
            'email'              => 'nullable|email',
            'telefone'           => 'nullable|string|max:20',
            'empresa'            => 'nullable|string',
            'cargo'              => 'nullable|string',
            'origem'             => 'sometimes|in:site,indicacao,linkedin,instagram,whatsapp,outros',
            'servico_interesse'  => 'sometimes|in:contabilidade,financeiro,consultoria,folha,outros',
            'valor_estimado'     => 'nullable|numeric|min:0',
            'observacoes'        => 'nullable|string',
            'data_proximo_contato' => 'nullable|date',
        ]);

        $dados['responsavel_id'] = auth('api')->id();
        $lead = Lead::create($dados);
        $this->audit->registrarCriacao('leads', $lead);

        return response()->json($lead, 201);
    }

    public function show(Lead $lead): JsonResponse
    {
        return response()->json($lead->load('responsavel:id,name'));
    }

    public function update(Request $request, Lead $lead): JsonResponse
    {
        $dados = $request->validate([
            'nome'               => 'sometimes|string|max:255',
            'email'              => 'nullable|email',
            'telefone'           => 'nullable|string|max:20',
            'empresa'            => 'nullable|string',
            'cargo'              => 'nullable|string',
            'origem'             => 'sometimes|in:site,indicacao,linkedin,instagram,whatsapp,outros',
            'status'             => 'sometimes|in:novo,contato,proposta,negociacao,ganho,perdido',
            'servico_interesse'  => 'sometimes|in:contabilidade,financeiro,consultoria,folha,outros',
            'responsavel_id'     => 'nullable|exists:users,id',
            'valor_estimado'     => 'nullable|numeric|min:0',
            'observacoes'        => 'nullable|string',
            'data_proximo_contato' => 'nullable|date',
        ]);

        $antes = $lead->toArray();
        $lead->update($dados);
        $this->audit->registrarAtualizacao('leads', $lead, $antes);

        return response()->json($lead);
    }

    public function destroy(Lead $lead): JsonResponse
    {
        $this->audit->registrarExclusao('leads', $lead);
        $lead->delete();
        return response()->json(['message' => 'Lead removido.']);
    }

    public function classificarIA(Lead $lead): JsonResponse
    {
        $classificacao = $this->claude->classificarLead($lead->toArray());
        $lead->update(['observacoes' => ($lead->observacoes ?? '') . "\n\n[IA] " . ($classificacao['observacoes'] ?? '')]);

        return response()->json([
            'message'       => 'Lead classificado pela IA.',
            'classificacao' => $classificacao,
        ]);
    }

    public function funil(): JsonResponse
    {
        $funil = Lead::selectRaw('status, COUNT(*) as total, SUM(valor_estimado) as valor_total')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        return response()->json($funil);
    }
}
