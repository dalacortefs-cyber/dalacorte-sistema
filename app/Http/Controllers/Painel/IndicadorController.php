<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Models\Certidao;
use App\Models\CertificadoDigital;
use App\Models\ContaReceber;
use App\Models\Empresa;
use App\Models\TarefaDfs;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class IndicadorController extends Controller
{
    public function index(Request $request)
    {
        $competencia = $request->filled('competencia')
            ? $request->competencia
            : now()->format('m/Y');

        return view('painel.indicadores.index', compact('competencia'));
    }

    public function dados(Request $request)
    {
        $escritorioId = auth()->user()->escritorio_id;
        $competencia  = $request->get('competencia', now()->format('m/Y'));

        [$mes, $ano] = explode('/', $competencia);

        // MRR dos últimos 6 meses
        $mrrMeses = [];
        for ($i = 5; $i >= 0; $i--) {
            $dt = Carbon::create($ano, $mes)->subMonths($i);
            $comp = $dt->format('m/Y');
            $mrrMeses[] = [
                'label' => $dt->format('M/y'),
                'value' => (float) ContaReceber::where('escritorio_id', $escritorioId)
                    ->where('competencia', $comp)
                    ->whereIn('status', ['Pago', 'Pendente', 'Atrasado'])
                    ->sum('valor'),
            ];
        }

        // Tarefas por status
        $tarefasStatus = TarefaDfs::where('escritorio_id', $escritorioId)
            ->where('competencia', $competencia)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Taxa de entrega no prazo
        $totalConcluidas = TarefaDfs::where('escritorio_id', $escritorioId)
            ->where('competencia', $competencia)
            ->where('status', 'Concluído')
            ->count();

        $noPrazo = TarefaDfs::where('escritorio_id', $escritorioId)
            ->where('competencia', $competencia)
            ->where('status', 'Concluído')
            ->where('concluida_no_prazo', true)
            ->count();

        $taxaPrazo = $totalConcluidas > 0 ? round(($noPrazo / $totalConcluidas) * 100, 1) : 0;

        // Empresas por regime
        $porRegime = Empresa::where('escritorio_id', $escritorioId)
            ->where('status', 'Ativa')
            ->selectRaw('regime_tributario as regime, count(*) as total')
            ->groupBy('regime_tributario')
            ->pluck('total', 'regime')
            ->toArray();

        return response()->json([
            'mrr_meses'     => $mrrMeses,
            'tarefas_status'=> $tarefasStatus,
            'taxa_prazo'    => $taxaPrazo,
            'por_regime'    => $porRegime,
            'total_empresas'=> Empresa::where('escritorio_id', $escritorioId)->where('status','Ativa')->count(),
        ]);
    }
}
