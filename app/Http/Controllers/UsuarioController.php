<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function __construct(private AuditService $audit) {}

    public function index(Request $request): JsonResponse
    {
        $query = User::query()
            ->when($request->tipo, fn($q) => $q->where('tipo', $request->tipo))
            ->when($request->busca, fn($q) => $q->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->busca}%")
                  ->orWhere('email', 'like', "%{$request->busca}%");
            }))
            ->when($request->has('ativo'), fn($q) => $q->where('ativo', filter_var($request->ativo, FILTER_VALIDATE_BOOLEAN)))
            ->select(['id', 'name', 'email', 'tipo', 'ativo', 'ultimo_acesso', 'created_at'])
            ->orderBy('name');

        return response()->json($query->paginate($request->per_page ?? 20));
    }

    public function store(Request $request): JsonResponse
    {
        $dados = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'tipo'     => 'required|in:admin,funcionario,cliente',
            'telefone' => 'nullable|string|max:20',
        ]);

        $user = User::create($dados);
        $user->assignRole($user->tipo);
        $this->audit->registrarCriacao('usuarios', $user);

        return response()->json(['message' => 'Usuário criado com sucesso.', 'user' => $user], 201);
    }

    public function show(User $usuario): JsonResponse
    {
        return response()->json($usuario->only(['id', 'name', 'email', 'tipo', 'ativo', 'telefone', 'ultimo_acesso', 'created_at']));
    }

    public function update(Request $request, User $usuario): JsonResponse
    {
        $dados = $request->validate([
            'name'     => 'sometimes|string|max:255',
            'email'    => "sometimes|email|unique:users,email,{$usuario->id}",
            'tipo'     => 'sometimes|in:admin,funcionario,cliente',
            'ativo'    => 'sometimes|boolean',
            'telefone' => 'nullable|string|max:20',
        ]);

        if (isset($dados['tipo']) && $dados['tipo'] !== $usuario->tipo) {
            $usuario->syncRoles([$dados['tipo']]);
        }

        $usuario->update($dados);
        $this->audit->registrarAtualizacao('usuarios', $usuario);

        return response()->json(['message' => 'Usuário atualizado.', 'user' => $usuario]);
    }

    public function resetSenha(Request $request, User $usuario): JsonResponse
    {
        $request->validate(['password' => 'required|string|min:6|confirmed']);

        $usuario->update(['password' => $request->password]);
        $this->audit->registrar('reset_senha', 'usuarios', $usuario->id);

        return response()->json(['message' => 'Senha redefinida com sucesso.']);
    }

    public function destroy(User $usuario): JsonResponse
    {
        if ($usuario->id === auth('api')->id()) {
            return response()->json(['message' => 'Não é possível excluir o próprio usuário.'], 422);
        }

        $this->audit->registrarExclusao('usuarios', $usuario);
        $usuario->delete();

        return response()->json(['message' => 'Usuário removido.']);
    }
}
