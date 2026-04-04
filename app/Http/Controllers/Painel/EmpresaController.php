<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\LogAtividade;
use App\Models\Socio;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function index(Request $request)
    {
        $query = Empresa::where('escritorio_id', auth()->user()->escritorio_id)
            ->with('socios')
            ->orderBy('razao_social');

        if ($request->filled('busca')) {
            $busca = $request->busca;
            $query->where(function ($q) use ($busca) {
                $q->where('razao_social', 'like', "%{$busca}%")
                  ->orWhere('nome_fantasia', 'like', "%{$busca}%")
                  ->orWhere('cnpj', 'like', "%{$busca}%");
            });
        }
        if ($request->filled('regime'))  $query->where('regime_tributario', $request->regime);
        if ($request->filled('status'))  $query->where('status', $request->status);

        $empresas = $query->paginate(20)->withQueryString();

        return view('painel.empresas.index', compact('empresas'));
    }

    public function create()
    {
        return view('painel.empresas.form', ['empresa' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'razao_social'           => 'required|string|max:255',
            'cnpj'                   => 'required|string|max:18|unique:empresas,cnpj',
            'nome_fantasia'          => 'nullable|string|max:255',
            'regime_tributario'      => 'nullable|in:MEI,Simples Nacional,Lucro Presumido,Lucro Real',
            'uf'                     => 'nullable|string|size:2',
            'municipio'              => 'nullable|string|max:255',
            'tipo_atividade'         => 'nullable|in:Comércio,Serviço,Indústria,Misto',
            'email'                  => 'nullable|email',
            'telefone'               => 'nullable|string|max:20',
            'valor_honorario_mensal' => 'nullable|numeric|min:0',
            'status'                 => 'nullable|in:Ativa,Inativa,Suspensa',
            'complexidade_tributaria'=> 'nullable|in:Baixa,Média,Alta',
            'data_inicio_contrato'   => 'nullable|date',
            'data_inicio_atividade'  => 'nullable|date',
        ], [], [
            'razao_social'      => 'razão social',
            'cnpj'              => 'CNPJ',
            'regime_tributario' => 'regime tributário',
        ]);

        $data['escritorio_id'] = auth()->user()->escritorio_id;
        $empresa = Empresa::create($data);

        LogAtividade::registrar('Empresas', 'CREATE', "Empresa {$empresa->razao_social} criada.", $empresa, [], $data);

        return redirect()->route('painel.empresas.show', $empresa)
            ->with('success', "Empresa {$empresa->razao_social} cadastrada com sucesso.");
    }

    public function show(Empresa $empresa)
    {
        $this->authorize_escritorio($empresa);
        $empresa->load(['socios', 'tarefas' => fn($q) => $q->latest()->limit(10),
                        'certidoes', 'certificadosDigitais', 'documentos', 'contratos']);

        return view('painel.empresas.show', compact('empresa'));
    }

    public function edit(Empresa $empresa)
    {
        $this->authorize_escritorio($empresa);
        return view('painel.empresas.form', compact('empresa'));
    }

    public function update(Request $request, Empresa $empresa)
    {
        $this->authorize_escritorio($empresa);

        $data = $request->validate([
            'razao_social'           => 'required|string|max:255',
            'cnpj'                   => "required|string|max:18|unique:empresas,cnpj,{$empresa->id}",
            'nome_fantasia'          => 'nullable|string|max:255',
            'regime_tributario'      => 'nullable|in:MEI,Simples Nacional,Lucro Presumido,Lucro Real',
            'uf'                     => 'nullable|string|size:2',
            'municipio'              => 'nullable|string|max:255',
            'tipo_atividade'         => 'nullable|in:Comércio,Serviço,Indústria,Misto',
            'email'                  => 'nullable|email',
            'telefone'               => 'nullable|string|max:20',
            'valor_honorario_mensal' => 'nullable|numeric|min:0',
            'status'                 => 'nullable|in:Ativa,Inativa,Suspensa',
            'complexidade_tributaria'=> 'nullable|in:Baixa,Média,Alta',
            'data_inicio_contrato'   => 'nullable|date',
            'data_inicio_atividade'  => 'nullable|date',
        ]);

        $anterior = $empresa->toArray();
        $empresa->update($data);

        LogAtividade::registrar('Empresas', 'UPDATE', "Empresa {$empresa->razao_social} atualizada.", $empresa, $anterior, $data);

        return redirect()->route('painel.empresas.show', $empresa)
            ->with('success', 'Empresa atualizada com sucesso.');
    }

    public function destroy(Empresa $empresa)
    {
        $this->authorize_escritorio($empresa);
        $nome = $empresa->razao_social;
        $empresa->delete();
        LogAtividade::registrar('Empresas', 'DELETE', "Empresa {$nome} removida.");
        return redirect()->route('painel.empresas.index')->with('success', "Empresa {$nome} removida.");
    }

    public function socios(Empresa $empresa)
    {
        $this->authorize_escritorio($empresa);
        return view('painel.empresas.socios', compact('empresa'));
    }

    public function storeSocio(Request $request, Empresa $empresa)
    {
        $this->authorize_escritorio($empresa);
        $data = $request->validate([
            'nome'         => 'required|string|max:255',
            'cpf'          => 'required|string|max:14',
            'participacao' => 'nullable|numeric|min:0|max:100',
            'tipo'         => 'nullable|in:Administrador,Sócio,Sócio-Administrador',
            'email'        => 'nullable|email',
            'telefone'     => 'nullable|string|max:20',
        ]);
        $data['empresa_id'] = $empresa->id;
        $socio = Socio::create($data);
        return redirect()->route('painel.empresas.socios', $empresa)
            ->with('success', "Sócio {$socio->nome} adicionado.");
    }

    public function destroySocio(Empresa $empresa, Socio $socio)
    {
        $this->authorize_escritorio($empresa);
        $socio->delete();
        return back()->with('success', 'Sócio removido.');
    }

    private function authorize_escritorio(Empresa $empresa): void
    {
        if ($empresa->escritorio_id !== auth()->user()->escritorio_id) {
            abort(403);
        }
    }
}
