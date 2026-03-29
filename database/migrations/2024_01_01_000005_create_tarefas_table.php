<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tarefas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('responsavel_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('cliente_id')->nullable()->constrained()->nullOnDelete();
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->enum('prioridade', ['baixa', 'media', 'alta', 'urgente'])->default('media');
            $table->enum('status', ['pendente', 'em_andamento', 'concluida', 'cancelada'])->default('pendente');
            $table->enum('categoria', ['financeiro', 'contabil', 'administrativo', 'comercial', 'outros'])->default('outros');
            $table->dateTime('data_vencimento')->nullable();
            $table->dateTime('data_conclusao')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('prioridade');
            $table->index('data_vencimento');
            $table->index('responsavel_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tarefas');
    }
};
