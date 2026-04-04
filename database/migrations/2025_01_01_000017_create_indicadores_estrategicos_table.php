<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('indicadores_estrategicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escritorio_id')->constrained('escritorios')->cascadeOnDelete();
            $table->string('competencia', 7); // MM/YYYY
            $table->integer('total_empresas_ativas')->default(0);
            $table->decimal('mrr', 15, 2)->default(0);
            $table->decimal('receita_honorarios', 15, 2)->default(0);
            $table->decimal('receita_extras', 15, 2)->default(0);
            $table->decimal('receita_total', 15, 2)->default(0);
            $table->decimal('taxa_inadimplencia', 5, 2)->default(0);
            $table->integer('total_tarefas')->default(0);
            $table->integer('tarefas_concluidas')->default(0);
            $table->integer('tarefas_no_prazo')->default(0);
            $table->decimal('taxa_conclusao_prazo', 5, 2)->default(0);
            $table->decimal('sla_medio_dias', 5, 2)->default(0);
            $table->integer('certificados_vencendo')->default(0);
            $table->integer('certidoes_vencendo')->default(0);
            $table->integer('obrigacoes_criticas_pendentes')->default(0);
            $table->timestamps();
            $table->unique(['escritorio_id', 'competencia']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indicadores_estrategicos');
    }
};
