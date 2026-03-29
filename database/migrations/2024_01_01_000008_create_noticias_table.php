<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('noticias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->string('titulo');
            $table->string('slug')->unique();
            $table->text('resumo')->nullable();
            $table->longText('conteudo');
            $table->string('imagem_capa')->nullable();
            $table->enum('categoria', ['financeiro', 'contabil', 'fiscal', 'trabalhista', 'empresarial', 'geral'])->default('geral');
            $table->enum('status', ['rascunho', 'publicado', 'arquivado'])->default('rascunho');
            $table->boolean('destaque')->default(false);
            $table->boolean('visivel_portal')->default(true);
            $table->dateTime('publicado_em')->nullable();
            $table->integer('visualizacoes')->default(0);
            $table->json('tags')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('categoria');
            $table->index('publicado_em');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('noticias');
    }
};
