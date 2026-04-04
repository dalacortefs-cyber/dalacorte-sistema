<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('comentarios_demandas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demanda_id')->constrained('demandas')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('usuario_nome');
            $table->text('mensagem');
            $table->enum('tipo', ['Comentário', 'Atualização de Status', 'Atribuição', 'Arquivo'])->default('Comentário');
            $table->string('arquivo_url')->nullable();
            $table->string('nome_arquivo')->nullable();
            $table->boolean('editado')->default(false);
            $table->date('data_edicao')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comentarios_demandas');
    }
};
