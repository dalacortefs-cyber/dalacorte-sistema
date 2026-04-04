<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('projetos_internos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escritorio_id')->constrained('escritorios')->cascadeOnDelete();
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->foreignId('responsavel_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('responsavel_nome')->nullable();
            $table->enum('status', ['Planejamento', 'Em Andamento', 'Concluído', 'Cancelado'])->default('Planejamento');
            $table->enum('prioridade', ['Baixa', 'Normal', 'Alta', 'Urgente'])->default('Normal');
            $table->date('data_inicio')->nullable();
            $table->date('data_previsao')->nullable();
            $table->date('data_conclusao')->nullable();
            $table->string('cor_identificacao', 10)->default('#1B4A52');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projetos_internos');
    }
};
