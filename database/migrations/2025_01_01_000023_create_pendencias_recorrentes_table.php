<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pendencias_recorrentes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escritorio_id')->constrained('escritorios')->cascadeOnDelete();
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->enum('periodicidade', ['Diária', 'Semanal', 'Quinzenal', 'Mensal', 'Bimestral', 'Trimestral', 'Semestral', 'Anual']);
            $table->tinyInteger('dia_vencimento')->nullable();
            $table->enum('tipo', ['Pendência', 'OS Interna', 'Solicitação de Cliente', 'Rotina'])->default('Pendência');
            $table->enum('prioridade', ['Baixa', 'Normal', 'Alta', 'Urgente'])->default('Normal');
            $table->foreignId('empresa_id')->nullable()->constrained('empresas')->nullOnDelete();
            $table->string('empresa_nome')->nullable();
            $table->foreignId('responsavel_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('responsavel_nome')->nullable();
            $table->boolean('ativa')->default(true);
            $table->date('proxima_geracao')->nullable();
            $table->date('ultima_geracao')->nullable();
            $table->integer('total_ciclos_gerados')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendencias_recorrentes');
    }
};
