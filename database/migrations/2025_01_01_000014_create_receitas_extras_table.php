<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('receitas_extras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escritorio_id')->constrained('escritorios')->cascadeOnDelete();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->string('empresa_nome');
            $table->enum('tipo', ['Alteração Contratual', 'Abertura Empresa', 'Encerramento', 'Consultoria', 'Outros']);
            $table->string('descricao')->nullable();
            $table->decimal('valor_total', 10, 2);
            $table->integer('parcelas')->default(1);
            $table->decimal('valor_parcela', 10, 2)->nullable();
            $table->date('data_emissao')->nullable();
            $table->string('arquivo_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receitas_extras');
    }
};
