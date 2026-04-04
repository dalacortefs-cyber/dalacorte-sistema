<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Models\Certidao;
use App\Models\Empresa;
use App\Models\LogAtividade;
use Illuminate\Http\Request;

class CertidaoController extends Controller
{
    public function index(Request $request)
    {
        $escritorioId = auth()->user()->escritorio_id;
        $query = Certidao::where('escritorio_id', $escritorioId)->with('empresa')->orderBy('data_validade');

        if ($request->filled('empresa_id')) $query->where('empresa_id', $request->empresa_id);
        if ($request->filled('tipo'))       $query->where('tipo', $request->tipo);
        if ($request->filled('status'))     $query->where('status', $request->status);

        $certidoes = $query->paginate(25)->withQueryString();
        $empresas  = Empresa::where('escritorio_id', $escritorioId)->where('status','Ativa')->orderBy('razao_social')->get();

        return view('painel.certidoes.index', compact('certidoes', 'empresas'));
    }

    public function create()
    {
        $empresas = Empresa::where('escritorio_id', auth()->user()->escritorio_id)->where('status','Ativa')->orderBy('razao_social')->get();
        return view('painel.certidoes.form', ['certidao' => null, 'empresas' => $empresas]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'empresa_id'    => 'required|exists:empresas,id',
            'tipo'          => 'required|in:CND Federal,CND Estadual,CND Municipal,FGTS,Trabalhista,Outra',
            'data_emissao'  => 'nullable|date',
            'data_validade' => 'required|date',
            'status'        => 'nullable|in:Válida,Vencida,A Vencer',
            'arquivo_url'   => 'nullable|string|max:500',
            'observacoes'   => 'nullable|string',
        ]);

        $empresa = Empresa::findOrFail($data['empresa_id']);
        $data['escritorio_id'] = auth()->user()->escritorio_id;
        $data['empresa_nome']  = $empresa->razao_social;
        $data['status']       ??= 'Válida';

        Certidao::create($data);
        LogAtividade::registrar('Certidoes', 'CREATE', "Certidão {$data['tipo']} cadastrada para {$empresa->razao_social}.");
        return redirect()->route('painel.certidoes.index')->with('success', 'Certidão cadastrada.');
    }

    public function edit(Certidao $certidao)
    {
        $this->authorize_escritorio($certidao);
        $empresas = Empresa::where('escritorio_id', auth()->user()->escritorio_id)->where('status','Ativa')->orderBy('razao_social')->get();
        return view('painel.certidoes.form', compact('certidao', 'empresas'));
    }

    public function update(Request $request, Certidao $certidao)
    {
        $this->authorize_escritorio($certidao);
        $data = $request->validate([
            'tipo'          => 'required|in:CND Federal,CND Estadual,CND Municipal,FGTS,Trabalhista,Outra',
            'data_emissao'  => 'nullable|date',
            'data_validade' => 'required|date',
            'status'        => 'nullable|in:Válida,Vencida,A Vencer',
            'arquivo_url'   => 'nullable|string|max:500',
            'observacoes'   => 'nullable|string',
        ]);
        $certidao->update($data);
        return redirect()->route('painel.certidoes.index')->with('success', 'Certidão atualizada.');
    }

    public function destroy(Certidao $certidao)
    {
        $this->authorize_escritorio($certidao);
        $certidao->delete();
        return back()->with('success', 'Certidão removida.');
    }

    public function show(Certidao $certidao) { return $this->edit($certidao); }

    private function authorize_escritorio(Certidao $certidao): void
    {
        if ($certidao->escritorio_id !== auth()->user()->escritorio_id) abort(403);
    }
}
