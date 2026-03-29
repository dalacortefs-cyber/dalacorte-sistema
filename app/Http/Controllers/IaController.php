<?php

namespace App\Http\Controllers;

use App\Services\ClaudeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IaController extends Controller
{
    public function __construct(private ClaudeService $claude) {}

    public function chat(Request $request): JsonResponse
    {
        $request->validate([
            'mensagem'    => 'required|string|max:4000',
            'historico'   => 'sometimes|array',
            'historico.*.role'    => 'required_with:historico|in:user,assistant',
            'historico.*.content' => 'required_with:historico|string',
            'system_prompt' => 'sometimes|string|max:2000',
        ]);

        $resposta = $this->claude->chat(
            mensagem: $request->mensagem,
            systemPrompt: $request->system_prompt ?? '',
            historico: $request->historico ?? []
        );

        return response()->json(['resposta' => $resposta]);
    }

    public function resumoDashboard(Request $request): JsonResponse
    {
        $request->validate([
            'dados' => 'required|array',
        ]);

        $resumo = $this->claude->gerarResumoDashboard($request->dados);
        return response()->json(['resumo' => $resumo]);
    }

    public function analisarTexto(Request $request): JsonResponse
    {
        $request->validate([
            'texto'    => 'required|string|max:8000',
            'objetivo' => 'sometimes|string|max:500',
        ]);

        $sistema  = 'Você é um analista financeiro especializado da Dalacorte Financial Solutions.';
        $prompt   = ($request->objetivo ? "Objetivo: {$request->objetivo}\n\n" : '')
            . "Analise o seguinte texto:\n\n{$request->texto}";

        $analise = $this->claude->chat($prompt, $sistema);
        return response()->json(['analise' => $analise]);
    }
}
