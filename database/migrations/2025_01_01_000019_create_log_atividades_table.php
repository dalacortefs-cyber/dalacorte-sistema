<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('log_atividades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escritorio_id')->nullable()->constrained('escritorios')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('usuario_nome');
            $table->enum('modulo', ['Empresas', 'Obrigacoes', 'Tarefas', 'Financeiro', 'Certidoes', 'Certificados', 'Documentos', 'Configuracoes', 'Usuarios', 'Auth']);
            $table->enum('acao', ['CREATE', 'UPDATE', 'DELETE', 'BAIXA', 'LOGIN', 'LOGOUT']);
            $table->string('registro_id')->nullable();
            $table->text('descricao')->nullable();
            $table->json('dados_anteriores')->nullable();
            $table->json('dados_novos')->nullable();
            $table->string('ip', 45)->nullable();
            $table->timestamps();
            // Sem softDeletes — logs são imutáveis
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_atividades');
    }
};
