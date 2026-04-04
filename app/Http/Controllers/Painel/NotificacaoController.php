<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Models\Notificacao;
use Illuminate\Http\Request;

class NotificacaoController extends Controller
{
    public function index(Request $request)
    {
        $query = Notificacao::where('user_id', auth()->id())
            ->orderByDesc('created_at');

        if ($request->filled('tipo')) $query->where('tipo', $request->tipo);
        if ($request->has('nao_lidas')) $query->where('lida', false);

        $notificacoes = $query->paginate(20)->withQueryString();
        $totalNaoLidas = Notificacao::where('user_id', auth()->id())->where('lida', false)->count();

        return view('painel.notificacoes.index', compact('notificacoes', 'totalNaoLidas'));
    }

    public function marcarLida(Notificacao $notificacao)
    {
        if ($notificacao->user_id !== auth()->id()) abort(403);

        $notificacao->update(['lida' => true, 'data_leitura' => now()]);

        if (request()->ajax()) {
            return response()->json(['ok' => true]);
        }
        return back();
    }

    public function marcarTodasLidas()
    {
        Notificacao::where('user_id', auth()->id())
            ->where('lida', false)
            ->update(['lida' => true, 'data_leitura' => now()]);

        return back()->with('success', 'Todas as notificações marcadas como lidas.');
    }
}
