<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('indicadores_mensais', function (Blueprint $table) {
            $table->id();
            $table->string('competencia', 7)->unique(); // MM/YYYY
            $table->integer('total_empresas_ativas')->default(0);
            $table->decimal('mrr', 15, 2)->default(0);
            $table->decimal('receita_prevista', 15, 2)->default(0);
            $table->decimal('receita_recebida', 15, 2)->default(0);
            $table->decimal('taxa_inadimplencia', 5, 2)->default(0);
            $table->integer('total_obrigacoes_pendentes')->default(0);
            $table->integer('total_obrigacoes_concluidas')->default(0);
            $table->decimal('percentual_entrega_prazo', 5, 2)->default(0);
            $table->decimal('tempo_medio_conclusao_dias', 5, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indicadores_mensais');
    }
};
