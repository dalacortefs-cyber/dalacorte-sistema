<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('responsavel_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nome');
            $table->string('email')->nullable();
            $table->string('telefone', 20)->nullable();
            $table->string('empresa')->nullable();
            $table->string('cargo')->nullable();
            $table->enum('origem', ['site', 'indicacao', 'linkedin', 'instagram', 'whatsapp', 'outros'])->default('outros');
            $table->enum('status', ['novo', 'contato', 'proposta', 'negociacao', 'ganho', 'perdido'])->default('novo');
            $table->enum('servico_interesse', ['contabilidade', 'financeiro', 'consultoria', 'folha', 'outros'])->default('outros');
            $table->decimal('valor_estimado', 15, 2)->nullable();
            $table->text('observacoes')->nullable();
            $table->dateTime('data_proximo_contato')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('responsavel_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
