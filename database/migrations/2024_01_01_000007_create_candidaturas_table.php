<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vagas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descricao');
            $table->string('departamento')->nullable();
            $table->enum('regime', ['clt', 'pj', 'estagio', 'freelance'])->default('clt');
            $table->string('local')->nullable();
            $table->boolean('remoto')->default(false);
            $table->decimal('salario_min', 10, 2)->nullable();
            $table->decimal('salario_max', 10, 2)->nullable();
            $table->enum('status', ['aberta', 'pausada', 'encerrada'])->default('aberta');
            $table->date('data_limite')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('candidaturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vaga_id')->nullable()->constrained()->nullOnDelete();
            $table->string('nome');
            $table->string('email');
            $table->string('telefone', 20)->nullable();
            $table->string('linkedin')->nullable();
            $table->string('curriculo_path')->nullable();
            $table->text('carta_apresentacao')->nullable();
            $table->enum('status', ['recebida', 'triagem', 'entrevista', 'aprovado', 'reprovado'])->default('recebida');
            $table->text('observacoes_internas')->nullable();
            $table->decimal('pretensao_salarial', 10, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('vaga_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidaturas');
        Schema::dropIfExists('vagas');
    }
};
