<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Extrato;
use App\Models\Lead;
use App\Models\Tarefa;
use App\Models\Noticia;
use App\Models\Candidatura;
use App\Services\ClaudeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct(private ClaudeService $claude) {}

    public function index(): JsonResponse
    {
        $dados = [
            'clientes'  => $this->resumoClientes(),
            'extratos'  => $this->resumoExtratos(),
            'tarefas'   => $this->resumoTarefas(),
            'leads'     => $this->resumoLeads(),
            'noticias'  => $this->resumoNoticias(),
        ];

        return response()->json($dados);
    }

    public function resumoComIA(): JsonResponse
    {
        $dados = [
            'clientes_ativos'    => Cliente::where('status', 'ativo')->count(),
            'extratos_pendentes' => Extrato::where('status', 'pendente')->count(),
            'tarefas_vencidas'   => Tarefa::vencidas()->count(),
            'leads_quentes'      => Lead::where('status', 'negociacao')->count(),
            'receita_estimada'   => Cliente::where('status', 'ativo')->sum('receita_mensal'),
        ];

        $resumo = $this->claude->gerarResumoDashboard($dados);

        return response()->json([
            'dados'  => $dados,
            'resumo_ia' => $resumo,
        ]);
    }

    public function kpis(): JsonResponse
    {
        return response()->json([
            'total_clientes'         => Cliente::count(),
            'clientes_ativos'        => Cliente::where('status', 'ativo')->count(),
            'clientes_novos_mes'     => Cliente::whereMonth('created_at', now()->month)->count(),
            'receita_mensal_total'   => Cliente::where('status', 'ativo')->sum('receita_mensal'),
            'extratos_processados'   => Extrato::where('status', 'processado')->count(),
            'tarefas_pendentes'      => Tarefa::whereIn('status', ['pendente', 'em_andamento'])->count(),
            'tarefas_vencidas'       => Tarefa::vencidas()->count(),
            'leads_total'            => Lead::count(),
            'leads_ativos'           => Lead::whereNotIn('status', ['ganho', 'perdido'])->count(),
            'conversao_leads'        => $this->calcularConversaoLeads(),
            'candidaturas_recebidas' => Candidatura::whereMonth('created_at', now()->month)->count(),
        ]);
    }

    public function graficoClientes(): JsonResponse
    {
        $porMes = Cliente::selectRaw('YEAR(created_at) as ano, MONTH(created_at) as mes, COUNT(*) as total')
            ->where('created_at', '>=', now()->subYear())
            ->groupBy('ano', 'mes')
            ->orderBy('ano')->orderBy('mes')
            ->get();

        $porStatus = Cliente::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->get();

        return response()->json([
            'por_mes'    => $porMes,
            'por_status' => $porStatus,
        ]);
    }

    public function graficoFinanceiro(): JsonResponse
    {
        $extratos = Extrato::selectRaw(
            'YEAR(created_at) as ano, MONTH(created_at) as mes, SUM(total_entradas) as entradas, SUM(total_saidas) as saidas'
        )
            ->where('status', 'processado')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('ano', 'mes')
            ->orderBy('ano')->orderBy('mes')
            ->get();

        return response()->json($extratos);
    }

    private function resumoClientes(): array
    {
        return [
            'total'    => Cliente::count(),
            'ativos'   => Cliente::where('status', 'ativo')->count(),
            'novos'    => Cliente::whereMonth('created_at', now()->month)->count(),
        ];
    }

    private function resumoExtratos(): array
    {
        return [
            'total'       => Extrato::count(),
            'pendentes'   => Extrato::where('status', 'pendente')->count(),
            'processados' => Extrato::where('status', 'processado')->count(),
        ];
    }

    private function resumoTarefas(): array
    {
        return [
            'total'    => Tarefa::whereIn('status', ['pendente', 'em_andamento'])->count(),
            'urgentes' => Tarefa::where('prioridade', 'urgente')->whereIn('status', ['pendente', 'em_andamento'])->count(),
            'vencidas' => Tarefa::vencidas()->count(),
        ];
    }

    private function resumoLeads(): array
    {
        return [
            'total'      => Lead::count(),
            'ativos'     => Lead::whereNotIn('status', ['ganho', 'perdido'])->count(),
            'conversao'  => $this->calcularConversaoLeads(),
        ];
    }

    private function resumoNoticias(): array
    {
        return [
            'total'       => Noticia::count(),
            'publicadas'  => Noticia::where('status', 'publicado')->count(),
            'rascunhos'   => Noticia::where('status', 'rascunho')->count(),
        ];
    }

    private function calcularConversaoLeads(): float
    {
        $total  = Lead::count();
        $ganhos = Lead::where('status', 'ganho')->count();
        return $total > 0 ? round(($ganhos / $total) * 100, 1) : 0;
    }
}
