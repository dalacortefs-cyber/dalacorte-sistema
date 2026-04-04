<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('demandas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escritorio_id')->constrained('escritorios')->cascadeOnDelete();
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->enum('tipo', ['Pendência', 'OS Interna', 'Solicitação de Cliente', 'Melhoria', 'Bug/Erro', 'Rotina'])->default('Pendência');
            $table->enum('natureza', ['Extra', 'Recorrente'])->default('Extra');
            $table->enum('status', ['Aberta', 'Em Andamento', 'Aguardando', 'Concluída', 'Cancelada'])->default('Aberta');
            $table->enum('prioridade', ['Baixa', 'Normal', 'Alta', 'Urgente'])->default('Normal');
            $table->foreignId('empresa_id')->nullable()->constrained('empresas')->nullOnDelete();
            $table->string('empresa_nome')->nullable();
            $table->foreignId('projeto_id')->nullable()->constrained('projetos_internos')->nullOnDelete();
            $table->string('projeto_nome')->nullable();
            $table->foreignId('responsavel_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('responsavel_nome')->nullable();
            $table->foreignId('criado_por_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('criado_por_nome')->nullable();
            $table->date('data_abertura');
            $table->date('data_previsao')->nullable();
            $table->date('data_conclusao')->nullable();
            $table->date('data_real_conclusao')->nullable();
            $table->boolean('concluida_no_prazo')->nullable();
            $table->string('numero_os')->unique()->nullable(); // OS-YYYY-NNNN
            $table->enum('periodicidade', ['Diária', 'Semanal', 'Quinzenal', 'Mensal', 'Bimestral', 'Trimestral', 'Semestral', 'Anual'])->nullable();
            $table->tinyInteger('dia_recorrencia')->nullable();
            $table->foreignId('origem_recorrente_id')->nullable()->constrained('pendencias_recorrentes')->nullOnDelete();
            $table->string('tags')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demandas');
    }
};
