<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Models\Documento;
use App\Models\Empresa;
use Illuminate\Http\Request;

class DocumentoController extends Controller
{
    public function index(Request $request)
    {
        $escritorioId = auth()->user()->escritorio_id;
        $query = Documento::where('escritorio_id', $escritorioId)->with('empresa')->orderByDesc('created_at');

        if ($request->filled('empresa_id'))    $query->where('empresa_id', $request->empresa_id);
        if ($request->filled('tipo_documento'))$query->where('tipo_documento', $request->tipo_documento);

        $documentos = $query->paginate(25)->withQueryString();
        $empresas   = Empresa::where('escritorio_id', $escritorioId)->where('status','Ativa')->orderBy('razao_social')->get();

        return view('painel.documentos.index', compact('documentos', 'empresas'));
    }

    public function create()
    {
        $empresas = Empresa::where('escritorio_id', auth()->user()->escritorio_id)->where('status','Ativa')->orderBy('razao_social')->get();
        return view('painel.documentos.form', ['documento' => null, 'empresas' => $empresas]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'empresa_id'    => 'required|exists:empresas,id',
            'tipo_documento'=> 'required|string',
            'descricao'     => 'nullable|string|max:255',
            'data_validade' => 'nullable|date',
            'arquivo_url'   => 'nullable|string|max:500',
            'categoria'     => 'nullable|string|max:255',
        ]);
        $empresa = Empresa::findOrFail($data['empresa_id']);
        $data['escritorio_id'] = auth()->user()->escritorio_id;
        $data['empresa_nome']  = $empresa->razao_social;
        $data['status']        = 'Válido';
        Documento::create($data);
        return redirect()->route('painel.documentos.index')->with('success', 'Documento cadastrado.');
    }

    public function edit(Documento $documento)
    {
        if ($documento->escritorio_id !== auth()->user()->escritorio_id) abort(403);
        $empresas = Empresa::where('escritorio_id', auth()->user()->escritorio_id)->where('status','Ativa')->orderBy('razao_social')->get();
        return view('painel.documentos.form', compact('documento', 'empresas'));
    }

    public function update(Request $request, Documento $documento)
    {
        if ($documento->escritorio_id !== auth()->user()->escritorio_id) abort(403);
        $data = $request->validate([
            'tipo_documento'=> 'required|string',
            'descricao'     => 'nullable|string|max:255',
            'data_validade' => 'nullable|date',
            'arquivo_url'   => 'nullable|string|max:500',
        ]);
        $documento->update($data);
        return redirect()->route('painel.documentos.index')->with('success', 'Documento atualizado.');
    }

    public function destroy(Documento $documento)
    {
        if ($documento->escritorio_id !== auth()->user()->escritorio_id) abort(403);
        $documento->delete();
        return back()->with('success', 'Documento removido.');
    }

    public function show(Documento $documento) { return $this->edit($documento); }
}
