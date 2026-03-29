<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('extratos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->string('nome_arquivo');
            $table->string('caminho_arquivo');
            $table->enum('tipo_arquivo', ['csv', 'ofx', 'pdf', 'xlsx'])->default('csv');
            $table->string('banco')->nullable();
            $table->string('agencia', 10)->nullable();
            $table->string('conta', 20)->nullable();
            $table->date('data_inicio')->nullable();
            $table->date('data_fim')->nullable();
            $table->decimal('saldo_inicial', 15, 2)->nullable();
            $table->decimal('saldo_final', 15, 2)->nullable();
            $table->integer('total_transacoes')->default(0);
            $table->decimal('total_entradas', 15, 2)->default(0);
            $table->decimal('total_saidas', 15, 2)->default(0);
            $table->enum('status', ['pendente', 'processando', 'processado', 'erro'])->default('pendente');
            $table->json('dados_processados')->nullable();
            $table->text('analise_ia')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('cliente_id');
            $table->index(['data_inicio', 'data_fim']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('extratos');
    }
};
