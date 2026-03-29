<?php

namespace App\Http\Controllers;

use App\Models\Extrato;
use App\Models\Noticia;
use App\Services\ClaudeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PortalClienteController extends Controller
{
    public function __construct(private ClaudeService $claude) {}

    public function meusDados(): JsonResponse
    {
        $user    = auth('api')->user();
        $cliente = $user->clientes()->first();

        if (!$cliente) {
            return response()->json(['message' => 'Perfil de cliente não encontrado.'], 404);
        }

        return response()->json([
            'user'    => $user->only('id', 'name', 'email', 'telefone', 'avatar'),
            'cliente' => $cliente,
        ]);
    }

    public function meusExtratos(Request $request): JsonResponse
    {
        $user    = auth('api')->user();
        $cliente = $user->clientes()->firstOrFail();

        $extratos = Extrato::where('cliente_id', $cliente->id)
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->orderByDesc('created_at')
            ->paginate(10);

        return response()->json($extratos);
    }

    public function verExtrato(int $extratoId): JsonResponse
    {
        $user    = auth('api')->user();
        $cliente = $user->clientes()->firstOrFail();

        $extrato = Extrato::where('id', $extratoId)
            ->where('cliente_id', $cliente->id)
            ->firstOrFail();

        return response()->json($extrato);
    }

    public function assistenteIA(Request $request): JsonResponse
    {
        $request->validate([
            'pergunta'  => 'required|string|max:2000',
            'historico' => 'sometimes|array',
        ]);

        $user    = auth('api')->user();
        $cliente = $user->clientes()->first();

        $dadosCliente = $cliente ? [
            'nome'    => $cliente->nome,
            'tipo'    => $cliente->tipo_pessoa,
            'status'  => $cliente->status,
        ] : [];

        $resposta = $this->claude->responderPortalCliente(
            pergunta: $request->pergunta,
            dadosCliente: $dadosCliente
        );

        return response()->json(['resposta' => $resposta]);
    }

    public function noticias(Request $request): JsonResponse
    {
        $noticias = Noticia::publicadas()
            ->visivelPortal()
            ->with('user:id,name')
            ->when($request->categoria, fn($q) => $q->where('categoria', $request->categoria))
            ->orderByDesc('publicado_em')
            ->paginate(10);

        return response()->json($noticias);
    }

    public function resumoFinanceiro(): JsonResponse
    {
        $user    = auth('api')->user();
        $cliente = $user->clientes()->firstOrFail();

        $extratos = Extrato::where('cliente_id', $cliente->id)
            ->where('status', 'processado')
            ->orderByDesc('data_inicio')
            ->limit(6)
            ->get(['data_inicio', 'data_fim', 'total_entradas', 'total_saidas', 'banco']);

        $totalEntradas = $extratos->sum('total_entradas');
        $totalSaidas   = $extratos->sum('total_saidas');

        return response()->json([
            'historico'      => $extratos,
            'total_entradas' => $totalEntradas,
            'total_saidas'   => $totalSaidas,
            'saldo_liquido'  => $totalEntradas - $totalSaidas,
        ]);
    }
}
