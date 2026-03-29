<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(private AuditService $audit) {}

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $token = auth('api')->attempt($request->only('email', 'password'));

        if (!$token) {
            $this->audit->registrarLogin(0, false, $request->email);
            return response()->json(['message' => 'Credenciais inválidas.'], 401);
        }

        $user = auth('api')->user();

        if (!$user->ativo) {
            auth('api')->logout();
            return response()->json(['message' => 'Usuário inativo.'], 403);
        }

        $user->update(['ultimo_acesso' => now()]);
        $this->audit->registrarLogin($user->id, true, $user->email);

        return $this->respondWithToken($token, $user);
    }

    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'tipo'     => 'sometimes|in:admin,funcionario,cliente',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password,
            'tipo'     => $request->tipo ?? 'funcionario',
        ]);

        $user->assignRole($user->tipo);
        $this->audit->registrarCriacao('usuarios', $user);

        $token = auth('api')->login($user);
        return $this->respondWithToken($token, $user, 201);
    }

    public function me(): JsonResponse
    {
        $user = auth('api')->user();
        return response()->json([
            'user'        => $user,
            'roles'       => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name'),
        ]);
    }

    public function logout(): JsonResponse
    {
        $this->audit->registrarLogout(auth('api')->id());
        auth('api')->logout();
        return response()->json(['message' => 'Logout realizado com sucesso.']);
    }

    public function refresh(): JsonResponse
    {
        try {
            $token = auth('api')->refresh();
            return $this->respondWithToken($token, auth('api')->user());
        } catch (\Exception $e) {
            return response()->json(['message' => 'Token não pode ser renovado.'], 401);
        }
    }

    public function alterarSenha(Request $request): JsonResponse
    {
        $request->validate([
            'senha_atual' => 'required|string',
            'nova_senha'  => 'required|string|min:6|confirmed',
        ]);

        $user = auth('api')->user();

        if (!Hash::check($request->senha_atual, $user->password)) {
            return response()->json(['message' => 'Senha atual incorreta.'], 422);
        }

        $user->update(['password' => $request->nova_senha]);
        $this->audit->registrar('alteracao_senha', 'auth');

        return response()->json(['message' => 'Senha alterada com sucesso.']);
    }

    private function respondWithToken(string $token, $user, int $status = 200): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth('api')->factory()->getTTL() * 60,
            'user'         => $user,
        ], $status);
    }
}
