<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Models\CertificadoDigital;
use App\Models\Empresa;
use App\Models\LogAtividade;
use Illuminate\Http\Request;

class CertificadoDigitalController extends Controller
{
    public function index(Request $request)
    {
        $escritorioId = auth()->user()->escritorio_id;
        $query = CertificadoDigital::where('escritorio_id', $escritorioId)->with('empresa')->orderBy('data_validade');

        if ($request->filled('empresa_id')) $query->where('empresa_id', $request->empresa_id);
        if ($request->filled('tipo'))       $query->where('tipo', $request->tipo);
        if ($request->filled('status'))     $query->where('status', $request->status);

        $certificados = $query->paginate(25)->withQueryString();
        $empresas     = Empresa::where('escritorio_id', $escritorioId)->where('status','Ativa')->orderBy('razao_social')->get();

        return view('painel.certificados.index', compact('certificados', 'empresas'));
    }

    public function create()
    {
        $empresas = Empresa::where('escritorio_id', auth()->user()->escritorio_id)->where('status','Ativa')->orderBy('razao_social')->get();
        return view('painel.certificados.form', ['certificado' => null, 'empresas' => $empresas]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'empresa_id'    => 'required|exists:empresas,id',
            'tipo'          => 'required|in:A1,A3,e-CPF,e-CNPJ',
            'data_validade' => 'required|date',
            'responsavel'   => 'nullable|string|max:255',
            'arquivo_url'   => 'nullable|string|max:500',
            'observacoes'   => 'nullable|string',
        ]);
        $empresa = Empresa::findOrFail($data['empresa_id']);
        $data['escritorio_id'] = auth()->user()->escritorio_id;
        $data['empresa_nome']  = $empresa->razao_social;
        $data['status'] = now()->gt($data['data_validade']) ? 'Vencido'
            : (now()->addDays(30)->gt($data['data_validade']) ? 'A Vencer' : 'Válido');

        CertificadoDigital::create($data);
        LogAtividade::registrar('Certificados', 'CREATE', "Certificado {$data['tipo']} de {$empresa->razao_social} cadastrado.");
        return redirect()->route('painel.certificados.index')->with('success', 'Certificado cadastrado.');
    }

    public function edit(CertificadoDigital $certificado)
    {
        if ($certificado->escritorio_id !== auth()->user()->escritorio_id) abort(403);
        $empresas = Empresa::where('escritorio_id', auth()->user()->escritorio_id)->where('status','Ativa')->orderBy('razao_social')->get();
        return view('painel.certificados.form', compact('certificado', 'empresas'));
    }

    public function update(Request $request, CertificadoDigital $certificado)
    {
        if ($certificado->escritorio_id !== auth()->user()->escritorio_id) abort(403);
        $data = $request->validate([
            'tipo'          => 'required|in:A1,A3,e-CPF,e-CNPJ',
            'data_validade' => 'required|date',
            'responsavel'   => 'nullable|string|max:255',
            'arquivo_url'   => 'nullable|string|max:500',
            'observacoes'   => 'nullable|string',
        ]);
        $certificado->update($data);
        return redirect()->route('painel.certificados.index')->with('success', 'Certificado atualizado.');
    }

    public function destroy(CertificadoDigital $certificado)
    {
        if ($certificado->escritorio_id !== auth()->user()->escritorio_id) abort(403);
        $certificado->delete();
        return back()->with('success', 'Certificado removido.');
    }

    public function show(CertificadoDigital $certificado) { return $this->edit($certificado); }
}
