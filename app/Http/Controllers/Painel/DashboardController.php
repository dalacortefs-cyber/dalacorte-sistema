<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Models\CertificadoDigital;
use App\Models\Certidao;
use App\Models\ContaReceber;
use App\Models\Empresa;
use App\Models\TarefaDfs;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $escritorioId = auth()->user()->escritorio_id;
        $hoje         = Carbon::today();
        $mesAtual     = $hoje->format('m/Y');

        // ── KPIs ──────────────────────────────────────────────────────────
        $totalEmpresas = Empresa::where('escritorio_id', $escritorioId)
            ->where('status', 'Ativa')
            ->count();

        $mrr = ContaReceber::where('escritorio_id', $escritorioId)
            ->where('competencia', $mesAtual)
            ->whereIn('status', ['Pendente', 'Atrasado', 'Pago'])
            ->sum('valor');

        $recebidoMes = ContaReceber::where('escritorio_id', $escritorioId)
            ->where('competencia', $mesAtual)
            ->where('status', 'Pago')
            ->sum('valor');

        $inadimplentes = ContaReceber::where('escritorio_id', $escritorioId)
            ->where('competencia', $mesAtual)
            ->where('status', 'Atrasado')
            ->count();

        $totalCobrancas = ContaReceber::where('escritorio_id', $escritorioId)
            ->where('competencia', $mesAtual)
            ->whereIn('status', ['Pendente', 'Atrasado', 'Pago'])
            ->count();

        $taxaAdimplencia = $totalCobrancas > 0
            ? round((($totalCobrancas - $inadimplentes) / $totalCobrancas) * 100, 1)
            : 100;

        $tarefasPendentes = TarefaDfs::where('escritorio_id', $escritorioId)
            ->where('competencia', $mesAtual)
            ->whereIn('status', ['Pendente', 'Em andamento'])
            ->count();

        $tarefasVencendo7 = TarefaDfs::where('escritorio_id', $escritorioId)
            ->where('status', '!=', 'Concluído')
            ->where('data_vencimento', '<=', $hoje->copy()->addDays(7))
            ->where('data_vencimento', '>=', $hoje)
            ->count();

        $certifVencendo = CertificadoDigital::where('escritorio_id', $escritorioId)
            ->where('data_validade', '<=', $hoje->copy()->addDays(30))
            ->where('data_validade', '>=', $hoje)
            ->count();

        $certidoesAlerta = Certidao::where('escritorio_id', $escritorioId)
            ->where(function ($q) use ($hoje) {
                $q->where('status', 'Vencida')
                  ->orWhere(function ($q2) use ($hoje) {
                      $q2->where('data_validade', '<=', $hoje->copy()->addDays(30))
                         ->where('data_validade', '>=', $hoje);
                  });
            })
            ->count();

        // ── Listas ────────────────────────────────────────────────────────
        $tarefasRecentes = TarefaDfs::where('escritorio_id', $escritorioId)
            ->orderByDesc('updated_at')
            ->limit(6)
            ->get();

        $alertasCriticos = TarefaDfs::where('escritorio_id', $escritorioId)
            ->where('nivel_criticidade', 'Crítica')
            ->whereIn('status', ['Pendente', 'Em andamento'])
            ->orderBy('data_vencimento')
            ->limit(5)
            ->get();

        $contasReceberMes = ContaReceber::where('escritorio_id', $escritorioId)
            ->where('competencia', $mesAtual)
            ->whereIn('status', ['Pendente', 'Atrasado'])
            ->orderBy('data_vencimento')
            ->limit(8)
            ->get();

        $totalAReceber = ContaReceber::where('escritorio_id', $escritorioId)
            ->where('competencia', $mesAtual)
            ->whereIn('status', ['Pendente', 'Atrasado'])
            ->sum('valor');

        return view('painel.dashboard', compact(
            'totalEmpresas', 'mrr', 'recebidoMes', 'taxaAdimplencia',
            'tarefasPendentes', 'tarefasVencendo7', 'certifVencendo', 'certidoesAlerta',
            'tarefasRecentes', 'alertasCriticos', 'contasReceberMes',
            'totalAReceber', 'inadimplentes', 'mesAtual'
        ));
    }
}
