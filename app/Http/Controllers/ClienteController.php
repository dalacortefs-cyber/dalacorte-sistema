<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Services\AuditService;
use App\Services\OnvioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function __construct(
        private AuditService $audit,
        private OnvioService $onvio
    ) {}

    public function index(Request $request): JsonResponse
    {
        $query = Cliente::query()
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->tipo_pessoa, fn($q) => $q->where('tipo_pessoa', $request->tipo_pessoa))
            ->when($request->busca, fn($q) => $q->where(function ($q) use ($request) {
                $q->where('nome', 'like', "%{$request->busca}%")
                  ->orWhere('email', 'like', "%{$request->busca}%")
                  ->orWhere('cpf_cnpj', 'like', "%{$request->busca}%");
            }))
            ->orderBy('nome');

        $clientes = $query->paginate($request->per_page ?? 15);

        return response()->json($clientes);
    }

    public function store(Request $request): JsonResponse
    {
        $dados = $request->validate([
            'nome'        => 'required|string|max:255',
            'email'       => 'required|email|unique:clientes',
            'cpf_cnpj'    => 'required|string|max:20|unique:clientes',
            'tipo_pessoa' => 'required|in:fisica,juridica',
            'telefone'    => 'nullable|string|max:20',
            'celular'     => 'nullable|string|max:20',
            'cep'         => 'nullable|string|max:10',
            'logradouro'  => 'nullable|string',
            'numero'      => 'nullable|string|max:20',
            'complemento' => 'nullable|string',
            'bairro'      => 'nullable|string',
            'cidade'      => 'nullable|string',
            'estado'      => 'nullable|string|max:2',
            'status'      => 'sometimes|in:ativo,inativo,prospecto',
            'observacoes' => 'nullable|string',
            'receita_mensal' => 'nullable|numeric|min:0',
        ]);

        $dados['user_id'] = auth('api')->id();
        $cliente = Cliente::create($dados);
        $this->audit->registrarCriacao('clientes', $cliente);

        return response()->json($cliente, 201);
    }

    public function show(Cliente $cliente): JsonResponse
    {
        $cliente->load(['extratos' => fn($q) => $q->latest()->limit(5), 'tarefas' => fn($q) => $q->pendentes()->limit(5)]);
        return response()->json($cliente);
    }

    public function update(Request $request, Cliente $cliente): JsonResponse
    {
        $dados = $request->validate([
            'nome'        => 'sometimes|string|max:255',
            'email'       => 'sometimes|email|unique:clientes,email,' . $cliente->id,
            'cpf_cnpj'    => 'sometimes|string|max:20|unique:clientes,cpf_cnpj,' . $cliente->id,
            'tipo_pessoa' => 'sometimes|in:fisica,juridica',
            'telefone'    => 'nullable|string|max:20',
            'celular'     => 'nullable|string|max:20',
            'cep'         => 'nullable|string|max:10',
            'logradouro'  => 'nullable|string',
            'numero'      => 'nullable|string|max:20',
            'complemento' => 'nullable|string',
            'bairro'      => 'nullable|string',
            'cidade'      => 'nullable|string',
            'estado'      => 'nullable|string|max:2',
            'status'      => 'sometimes|in:ativo,inativo,prospecto',
            'observacoes' => 'nullable|string',
            'receita_mensal' => 'nullable|numeric|min:0',
        ]);

        $antes = $cliente->toArray();
        $cliente->update($dados);
        $this->audit->registrarAtualizacao('clientes', $cliente, $antes);

        return response()->json($cliente);
    }

    public function destroy(Cliente $cliente): JsonResponse
    {
        $this->audit->registrarExclusao('clientes', $cliente);
        $cliente->delete();
        return response()->json(['message' => 'Cliente removido com sucesso.']);
    }

    public function sincronizarOnvio(Cliente $cliente): JsonResponse
    {
        $dadosOnvio = $this->onvio->buscarCliente($cliente->cpf_cnpj);

        if (!$dadosOnvio) {
            return response()->json(['message' => 'Cliente não encontrado no Onvio.'], 404);
        }

        $antes = $cliente->toArray();
        $cliente->update([
            'responsavel_onvio' => $dadosOnvio['responsavel'] ?? $cliente->responsavel_onvio,
            'codigo_onvio'      => $dadosOnvio['codigo'] ?? $cliente->codigo_onvio,
        ]);

        $this->audit->registrarAtualizacao('clientes', $cliente, $antes, 'Sincronização Onvio');

        return response()->json([
            'message'    => 'Cliente sincronizado com o Onvio.',
            'dados_onvio' => $dadosOnvio,
        ]);
    }
}
