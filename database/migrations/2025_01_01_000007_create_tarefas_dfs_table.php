<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tarefas_dfs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escritorio_id')->constrained('escritorios')->cascadeOnDelete();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->string('empresa_nome');
            $table->foreignId('obrigacao_id')->nullable()->constrained('obrigacoes')->nullOnDelete();
            $table->foreignId('obrigacao_personalizada_id')->nullable()->constrained('obrigacoes_personalizadas')->nullOnDelete();
            $table->string('obrigacao_nome');
            $table->string('competencia', 7); // MM/YYYY
            $table->date('data_vencimento')->nullable();
            $table->date('data_expectativa_envio')->nullable();
            $table->enum('status', ['Pendente', 'Em andamento', 'Concluído', 'Arquivado'])->default('Pendente');
            $table->string('responsavel')->nullable();
            $table->date('data_conclusao')->nullable();
            $table->date('data_real_conclusao')->nullable();
            $table->string('usuario_conclusao')->nullable();
            $table->string('comprovante_url')->nullable();
            $table->text('observacoes')->nullable();
            $table->enum('esfera', ['Federal', 'Estadual', 'Municipal', 'Trabalhista'])->nullable();
            $table->enum('periodicidade', ['Mensal', 'Trimestral', 'Semestral', 'Anual', 'Eventual'])->nullable();
            $table->enum('nivel_criticidade', ['Baixa', 'Média', 'Alta', 'Crítica'])->default('Média');
            $table->boolean('foi_retrabalho')->default(false);
            $table->boolean('concluida_no_prazo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tarefas_dfs');
    }
};
