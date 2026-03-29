<?php

namespace App\Services;

use App\Models\Noticia;
use Illuminate\Support\Str;

class NoticiaService
{
    public function criar(array $dados, int $userId): Noticia
    {
        $dados['user_id'] = $userId;
        $dados['slug']    = $this->gerarSlugUnico($dados['titulo']);

        if (isset($dados['status']) && $dados['status'] === 'publicado' && empty($dados['publicado_em'])) {
            $dados['publicado_em'] = now();
        }

        return Noticia::create($dados);
    }

    public function atualizar(Noticia $noticia, array $dados): Noticia
    {
        if (isset($dados['titulo']) && $dados['titulo'] !== $noticia->titulo) {
            $dados['slug'] = $this->gerarSlugUnico($dados['titulo'], $noticia->id);
        }

        if (
            isset($dados['status']) &&
            $dados['status'] === 'publicado' &&
            $noticia->status !== 'publicado' &&
            empty($dados['publicado_em'])
        ) {
            $dados['publicado_em'] = now();
        }

        $noticia->update($dados);
        return $noticia->fresh();
    }

    public function publicar(Noticia $noticia): Noticia
    {
        $noticia->update([
            'status'       => 'publicado',
            'publicado_em' => $noticia->publicado_em ?? now(),
        ]);
        return $noticia;
    }

    public function arquivar(Noticia $noticia): Noticia
    {
        $noticia->update(['status' => 'arquivado']);
        return $noticia;
    }

    public function buscarParaPortal(int $pagina = 1, int $perPage = 10, ?string $categoria = null): array
    {
        $query = Noticia::publicadas()
            ->visivelPortal()
            ->with('user:id,name')
            ->orderByDesc('publicado_em');

        if ($categoria) {
            $query->where('categoria', $categoria);
        }

        $resultado = $query->paginate($perPage, ['*'], 'page', $pagina);

        return [
            'data'  => $resultado->items(),
            'total' => $resultado->total(),
            'pagina_atual' => $resultado->currentPage(),
            'total_paginas' => $resultado->lastPage(),
        ];
    }

    private function gerarSlugUnico(string $titulo, ?int $ignorarId = null): string
    {
        $slug  = Str::slug($titulo);
        $base  = $slug;
        $count = 1;

        while (true) {
            $query = Noticia::where('slug', $slug);
            if ($ignorarId) {
                $query->where('id', '!=', $ignorarId);
            }
            if (!$query->exists()) {
                break;
            }
            $slug = "{$base}-{$count}";
            $count++;
        }

        return $slug;
    }
}
