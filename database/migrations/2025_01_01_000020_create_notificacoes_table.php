<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notificacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escritorio_id')->constrained('escritorios')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('tipo', ['Tarefa', 'Certificado', 'Certidao', 'Financeiro', 'Sistema']);
            $table->enum('prioridade', ['Baixa', 'Normal', 'Alta', 'Crítica'])->default('Normal');
            $table->string('titulo');
            $table->text('mensagem');
            $table->string('link_referencia')->nullable();
            $table->boolean('lida')->default(false);
            $table->dateTime('data_leitura')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificacoes');
    }
};
