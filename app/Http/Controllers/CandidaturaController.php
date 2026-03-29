<?php

namespace App\Http\Controllers;

use App\Models\Candidatura;
use App\Models\Vaga;
use App\Services\AuditService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CandidaturaController extends Controller
{
    public function __construct(private AuditService $audit) {}

    // ─── Vagas ────────────────────────────────────────────────────────────────

    public function listarVagas(Request $request): JsonResponse
    {
        $vagas = Vaga::withCount('candidaturas')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->orderByDesc('created_at')
            ->paginate($request->per_page ?? 15);

        return response()->json($vagas);
    }

    public function criarVaga(Request $request): JsonResponse
    {
        $dados = $request->validate([
            'titulo'      => 'required|string|max:255',
            'descricao'   => 'required|string',
            'departamento'=> 'nullable|string',
            'regime'      => 'sometimes|in:clt,pj,estagio,freelance',
            'local'       => 'nullable|string',
            'remoto'      => 'sometimes|boolean',
            'salario_min' => 'nullable|numeric|min:0',
            'salario_max' => 'nullable|numeric|min:0',
            'status'      => 'sometimes|in:aberta,pausada,encerrada',
            'data_limite' => 'nullable|date',
        ]);

        $vaga = Vaga::create($dados);
        $this->audit->registrarCriacao('vagas', $vaga);

        return response()->json($vaga, 201);
    }

    public function atualizarVaga(Request $request, Vaga $vaga): JsonResponse
    {
        $dados = $request->validate([
            'titulo'      => 'sometimes|string|max:255',
            'descricao'   => 'sometimes|string',
            'departamento'=> 'nullable|string',
            'regime'      => 'sometimes|in:clt,pj,estagio,freelance',
            'local'       => 'nullable|string',
            'remoto'      => 'sometimes|boolean',
            'salario_min' => 'nullable|numeric',
            'salario_max' => 'nullable|numeric',
            'status'      => 'sometimes|in:aberta,pausada,encerrada',
            'data_limite' => 'nullable|date',
        ]);

        $antes = $vaga->toArray();
        $vaga->update($dados);
        $this->audit->registrarAtualizacao('vagas', $vaga, $antes);

        return response()->json($vaga);
    }

    // ─── Candidaturas ────────────────────────────────────────────────────────

    public function listarCandidaturas(Request $request): JsonResponse
    {
        $query = Candidatura::with('vaga:id,titulo')
            ->when($request->vaga_id, fn($q) => $q->where('vaga_id', $request->vaga_id))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->orderByDesc('created_at');

        return response()->json($query->paginate($request->per_page ?? 15));
    }

    // Público — recebe candidatura sem autenticação
    public function candidatar(Request $request): JsonResponse
    {
        $dados = $request->validate([
            'vaga_id'             => 'nullable|exists:vagas,id',
            'nome'                => 'required|string|max:255',
            'email'               => 'required|email',
            'telefone'            => 'nullable|string|max:20',
            'linkedin'            => 'nullable|url',
            'curriculo'           => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'carta_apresentacao'  => 'nullable|string|max:3000',
            'pretensao_salarial'  => 'nullable|numeric|min:0',
        ]);

        if ($request->hasFile('curriculo')) {
            $dados['curriculo_path'] = $request->file('curriculo')->store('curriculos', 'local');
        }

        unset($dados['curriculo']);
        $candidatura = Candidatura::create($dados);

        return response()->json([
            'message'     => 'Candidatura recebida com sucesso! Em breve entraremos em contato.',
            'candidatura' => $candidatura->only('id', 'nome', 'email', 'status'),
        ], 201);
    }

    public function atualizarStatus(Request $request, Candidatura $candidatura): JsonResponse
    {
        $request->validate([
            'status'              => 'required|in:recebida,triagem,entrevista,aprovado,reprovado',
            'observacoes_internas'=> 'nullable|string',
        ]);

        $antes = $candidatura->toArray();
        $candidatura->update([
            'status'               => $request->status,
            'observacoes_internas' => $request->observacoes_internas ?? $candidatura->observacoes_internas,
        ]);

        $this->audit->registrarAtualizacao('candidaturas', $candidatura, $antes);

        return response()->json(['message' => 'Status atualizado.', 'candidatura' => $candidatura]);
    }
}
