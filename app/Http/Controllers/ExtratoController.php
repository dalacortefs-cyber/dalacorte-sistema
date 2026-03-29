<?php

namespace App\Http\Controllers;

use App\Models\Extrato;
use App\Models\Cliente;
use App\Services\AuditService;
use App\Services\ClaudeService;
use App\Services\ExtratoParserService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExtratoController extends Controller
{
    public function __construct(
        private ExtratoParserService $parser,
        private ClaudeService $claude,
        private AuditService $audit
    ) {}

    public function index(Request $request): JsonResponse
    {
        $query = Extrato::with('cliente:id,nome')
            ->when($request->cliente_id, fn($q) => $q->where('cliente_id', $request->cliente_id))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->banco, fn($q) => $q->where('banco', 'like', "%{$request->banco}%"))
            ->orderByDesc('created_at');

        return response()->json($query->paginate($request->per_page ?? 15));
    }

    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'arquivo'    => 'required|file|mimes:csv,txt,ofx,xlsx|max:10240',
            'banco'      => 'nullable|string|max:100',
            'agencia'    => 'nullable|string|max:10',
            'conta'      => 'nullable|string|max:20',
        ]);

        $arquivo  = $request->file('arquivo');
        $tipo     = strtolower($arquivo->getClientOriginalExtension());
        $tipo     = $tipo === 'txt' ? 'csv' : $tipo;
        $caminho  = $arquivo->store("extratos/{$request->cliente_id}", 'local');

        $extrato = Extrato::create([
            'cliente_id'     => $request->cliente_id,
            'user_id'        => auth('api')->id(),
            'nome_arquivo'   => $arquivo->getClientOriginalName(),
            'caminho_arquivo'=> $caminho,
            'tipo_arquivo'   => $tipo,
            'banco'          => $request->banco,
            'agencia'        => $request->agencia,
            'conta'          => $request->conta,
            'status'         => 'pendente',
        ]);

        $this->audit->registrarCriacao('extratos', $extrato);
        $this->processarAsync($extrato, $arquivo);

        return response()->json([
            'message' => 'Extrato enviado. Processamento iniciado.',
            'extrato' => $extrato,
        ], 201);
    }

    public function show(Extrato $extrato): JsonResponse
    {
        $extrato->load('cliente:id,nome', 'user:id,name');
        return response()->json($extrato);
    }

    public function analisarIa(Extrato $extrato): JsonResponse
    {
        if ($extrato->status !== 'processado') {
            return response()->json(['message' => 'Extrato ainda não processado.'], 422);
        }

        $transacoes = $extrato->dados_processados['transacoes'] ?? [];
        $analise    = $this->claude->analisarExtrato($transacoes, $extrato->cliente->nome);

        $extrato->update(['analise_ia' => $analise]);
        $this->audit->registrar('analise_ia', 'extratos', null, null, Extrato::class, $extrato->id);

        return response()->json(['analise' => $analise]);
    }

    public function exportarPdf(Extrato $extrato)
    {
        $extrato->load('cliente');
        $pdf = Pdf::loadView('extratos.relatorio', ['extrato' => $extrato]);
        return $pdf->download("extrato-{$extrato->cliente->nome}-{$extrato->id}.pdf");
    }

    public function destroy(Extrato $extrato): JsonResponse
    {
        Storage::disk('local')->delete($extrato->caminho_arquivo);
        $this->audit->registrarExclusao('extratos', $extrato);
        $extrato->delete();
        return response()->json(['message' => 'Extrato removido.']);
    }

    private function processarAsync(Extrato $extrato, $arquivo): void
    {
        try {
            $extrato->update(['status' => 'processando']);
            $resultado = $this->parser->processar($arquivo, $extrato->tipo_arquivo);

            $extrato->update([
                'status'            => 'processado',
                'total_transacoes'  => $resultado['total'],
                'total_entradas'    => $resultado['total_entradas'],
                'total_saidas'      => $resultado['total_saidas'],
                'dados_processados' => $resultado,
            ]);
        } catch (\Exception $e) {
            $extrato->update(['status' => 'erro', 'observacoes' => $e->getMessage()]);
        }
    }
}
