<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use App\Services\AuditService;
use App\Services\NoticiaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NoticiaController extends Controller
{
    public function __construct(
        private NoticiaService $service,
        private AuditService $audit
    ) {}

    public function index(Request $request): JsonResponse
    {
        $query = Noticia::with('user:id,name')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->categoria, fn($q) => $q->where('categoria', $request->categoria))
            ->when($request->busca, fn($q) => $q->where('titulo', 'like', "%{$request->busca}%"))
            ->orderByDesc('created_at');

        return response()->json($query->paginate($request->per_page ?? 15));
    }

    public function store(Request $request): JsonResponse
    {
        $dados = $request->validate([
            'titulo'         => 'required|string|max:255',
            'resumo'         => 'nullable|string|max:500',
            'conteudo'       => 'required|string',
            'categoria'      => 'sometimes|in:financeiro,contabil,fiscal,trabalhista,empresarial,geral',
            'status'         => 'sometimes|in:rascunho,publicado,arquivado',
            'destaque'       => 'sometimes|boolean',
            'visivel_portal' => 'sometimes|boolean',
            'tags'           => 'nullable|array',
            'publicado_em'   => 'nullable|date',
        ]);

        $noticia = $this->service->criar($dados, auth('api')->id());
        $this->audit->registrarCriacao('noticias', $noticia);

        return response()->json($noticia, 201);
    }

    public function show(Noticia $noticia): JsonResponse
    {
        return response()->json($noticia->load('user:id,name'));
    }

    public function update(Request $request, Noticia $noticia): JsonResponse
    {
        $dados = $request->validate([
            'titulo'         => 'sometimes|string|max:255',
            'resumo'         => 'nullable|string|max:500',
            'conteudo'       => 'sometimes|string',
            'categoria'      => 'sometimes|in:financeiro,contabil,fiscal,trabalhista,empresarial,geral',
            'status'         => 'sometimes|in:rascunho,publicado,arquivado',
            'destaque'       => 'sometimes|boolean',
            'visivel_portal' => 'sometimes|boolean',
            'tags'           => 'nullable|array',
            'publicado_em'   => 'nullable|date',
        ]);

        $antes = $noticia->toArray();
        $noticia = $this->service->atualizar($noticia, $dados);
        $this->audit->registrarAtualizacao('noticias', $noticia, $antes);

        return response()->json($noticia);
    }

    public function destroy(Noticia $noticia): JsonResponse
    {
        $this->audit->registrarExclusao('noticias', $noticia);
        $noticia->delete();
        return response()->json(['message' => 'Notícia removida.']);
    }

    public function publicar(Noticia $noticia): JsonResponse
    {
        $noticia = $this->service->publicar($noticia);
        return response()->json(['message' => 'Notícia publicada.', 'noticia' => $noticia]);
    }

    // Público - portal
    public function listarPortal(Request $request): JsonResponse
    {
        $resultado = $this->service->buscarParaPortal(
            pagina: (int) $request->get('page', 1),
            perPage: (int) $request->get('per_page', 10),
            categoria: $request->categoria
        );
        return response()->json($resultado);
    }

    public function mostrarPortal(string $slug): JsonResponse
    {
        $noticia = Noticia::publicadas()->visivelPortal()->where('slug', $slug)->firstOrFail();
        $noticia->incrementarVisualizacao();
        return response()->json($noticia->load('user:id,name'));
    }
}
