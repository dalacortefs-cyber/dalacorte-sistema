<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contratos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escritorio_id')->constrained('escritorios')->cascadeOnDelete();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->string('empresa_nome');
            $table->decimal('valor_mensal', 10, 2);
            $table->tinyInteger('dia_vencimento');
            $table->enum('indice_reajuste', ['IPCA', 'IGP-M', 'Fixo'])->default('IPCA');
            $table->enum('periodicidade_reajuste', ['Anual', 'Semestral'])->default('Anual');
            $table->date('data_inicio');
            $table->date('data_ultimo_reajuste')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contratos');
    }
};
