<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Models\Configuracao;
use Illuminate\Http\Request;

class ConfiguracaoController extends Controller
{
    public function index()
    {
        $configs = Configuracao::pluck('valor', 'chave')->toArray();
        return view('painel.configuracoes.index', compact('configs'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'configs'   => 'nullable|array',
            'configs.*' => 'nullable|string|max:1000',
        ]);

        foreach ($data['configs'] ?? [] as $chave => $valor) {
            Configuracao::updateOrCreate(
                ['chave' => $chave],
                ['valor' => $valor]
            );
        }

        return back()->with('success', 'Configurações salvas.');
    }
}
