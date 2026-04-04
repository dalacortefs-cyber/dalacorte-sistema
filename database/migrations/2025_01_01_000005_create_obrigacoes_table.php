<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('obrigacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escritorio_id')->constrained('escritorios')->cascadeOnDelete();
            $table->string('nome');
            $table->enum('esfera', ['Federal', 'Estadual', 'Municipal', 'Trabalhista'])->nullable();
            $table->enum('periodicidade', ['Mensal', 'Trimestral', 'Semestral', 'Anual', 'Eventual'])->nullable();
            $table->tinyInteger('dia_vencimento')->nullable();
            $table->integer('dias_antecedencia_envio_cliente')->default(0);
            $table->integer('sla_dias_internos')->default(0);
            $table->enum('nivel_criticidade', ['Baixa', 'Média', 'Alta', 'Crítica'])->default('Média');
            $table->text('regimes_aplicaveis')->nullable();
            $table->text('ufs_aplicaveis')->nullable();
            $table->string('tipo_atividade_aplicavel')->nullable();
            $table->boolean('requer_empregados')->default(false);
            $table->boolean('centralizada_matriz')->default(false);
            $table->boolean('ativa')->default(true);
            $table->boolean('eh_padrao_sistema')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('obrigacoes');
    }
};
