<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Noticia extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'titulo', 'slug', 'resumo', 'conteudo',
        'imagem_capa', 'categoria', 'status', 'destaque',
        'visivel_portal', 'publicado_em', 'visualizacoes', 'tags',
    ];

    protected $casts = [
        'destaque'       => 'boolean',
        'visivel_portal' => 'boolean',
        'publicado_em'   => 'datetime',
        'tags'           => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($noticia) {
            if (empty($noticia->slug)) {
                $noticia->slug = Str::slug($noticia->titulo);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePublicadas($query)
    {
        return $query->where('status', 'publicado')
                     ->where('publicado_em', '<=', now());
    }

    public function scopeVisivelPortal($query)
    {
        return $query->where('visivel_portal', true);
    }

    public function incrementarVisualizacao(): void
    {
        $this->increment('visualizacoes');
    }
}
