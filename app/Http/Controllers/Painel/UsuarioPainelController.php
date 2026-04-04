<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\LogAtividade;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsuarioPainelController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('escritorio_id', auth()->user()->escritorio_id)
            ->orderBy('name');

        if ($request->filled('role'))  $query->where('role', $request->role);
        if ($request->filled('ativo')) $query->where('active', $request->ativo === '1');

        $usuarios = $query->paginate(20)->withQueryString();
        return view('painel.usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $escritorioId = auth()->user()->escritorio_id;
        $empresas = Empresa::where('escritorio_id', $escritorioId)->where('status','Ativa')->orderBy('razao_social')->get();
        return view('painel.usuarios.form', ['usuario' => null, 'empresas' => $empresas]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|string|min:8|confirmed',
            'role'       => 'required|in:admin,gestor,operacional,cliente',
            'cargo'      => 'nullable|string|max:255',
            'telefone'   => 'nullable|string|max:20',
            'empresa_id' => 'nullable|exists:empresas,id',
        ], [], [
            'name'     => 'nome',
            'email'    => 'e-mail',
            'password' => 'senha',
            'role'     => 'perfil',
        ]);

        $data['escritorio_id'] = auth()->user()->escritorio_id;
        $data['active']        = true;
        $data['password']      = Hash::make($data['password']);
        // Legado
        $data['tipo']  = match($data['role']) {
            'admin'      => 'admin',
            'gestor'     => 'funcionario',
            'operacional'=> 'funcionario',
            'cliente'    => 'cliente',
        };
        $data['ativo'] = true;

        $usuario = User::create($data);
        LogAtividade::registrar('Usuarios', 'CREATE', "Usuário {$usuario->name} criado.", $usuario);

        return redirect()->route('painel.usuarios.index')->with('success', "Usuário {$usuario->name} criado.");
    }

    public function edit(User $usuario)
    {
        $this->authorize_escritorio($usuario);
        $escritorioId = auth()->user()->escritorio_id;
        $empresas = Empresa::where('escritorio_id', $escritorioId)->where('status','Ativa')->orderBy('razao_social')->get();
        return view('painel.usuarios.form', compact('usuario', 'empresas'));
    }

    public function update(Request $request, User $usuario)
    {
        $this->authorize_escritorio($usuario);
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => "required|email|unique:users,email,{$usuario->id}",
            'role'       => 'required|in:admin,gestor,operacional,cliente',
            'cargo'      => 'nullable|string|max:255',
            'telefone'   => 'nullable|string|max:20',
            'empresa_id' => 'nullable|exists:empresas,id',
            'password'   => 'nullable|string|min:8|confirmed',
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $usuario->update($data);
        LogAtividade::registrar('Usuarios', 'UPDATE', "Usuário {$usuario->name} atualizado.", $usuario);

        return redirect()->route('painel.usuarios.index')->with('success', 'Usuário atualizado.');
    }

    public function destroy(User $usuario)
    {
        $this->authorize_escritorio($usuario);
        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'Você não pode desativar a si mesmo.');
        }
        $usuario->update(['active' => false, 'ativo' => false]);
        LogAtividade::registrar('Usuarios', 'DELETE', "Usuário {$usuario->name} desativado.", $usuario);
        return back()->with('success', "Usuário {$usuario->name} desativado.");
    }

    public function toggleAtivo(User $usuario)
    {
        $this->authorize_escritorio($usuario);
        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'Você não pode desativar a si mesmo.');
        }
        $novoStatus = !$usuario->active;
        $usuario->update(['active' => $novoStatus, 'ativo' => $novoStatus]);
        $msg = $novoStatus ? 'ativado' : 'desativado';
        return back()->with('success', "Usuário {$usuario->name} {$msg}.");
    }

    public function resetSenha(User $usuario)
    {
        $this->authorize_escritorio($usuario);
        $novaSenha = Str::random(12);
        $usuario->update(['password' => Hash::make($novaSenha)]);
        LogAtividade::registrar('Usuarios', 'UPDATE', "Senha de {$usuario->name} redefinida.", $usuario);
        return back()->with('nova_senha', $novaSenha)->with('success', 'Senha redefinida. Copie a nova senha abaixo.');
    }

    private function authorize_escritorio(User $usuario): void
    {
        if ($usuario->escritorio_id !== auth()->user()->escritorio_id) abort(403);
    }
}
