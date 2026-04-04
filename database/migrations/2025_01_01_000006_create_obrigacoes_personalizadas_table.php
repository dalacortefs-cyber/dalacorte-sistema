<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('obrigacoes_personalizadas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->string('empresa_nome');
            $table->foreignId('obrigacao_id')->nullable()->constrained('obrigacoes')->nullOnDelete();
            $table->string('nome_obrigacao');
            $table->enum('esfera', ['Federal', 'Estadual', 'Municipal', 'Trabalhista'])->nullable();
            $table->enum('periodicidade', ['Mensal', 'Trimestral', 'Semestral', 'Anual', 'Eventual'])->nullable();
            $table->tinyInteger('dia_vencimento')->nullable();
            $table->boolean('ativa')->default(true);
            $table->text('motivo_excecao')->nullable();
            $table->date('data_inicio')->nullable();
            $table->date('data_fim')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('obrigacoes_personalizadas');
    }
};
