<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('controle_faturamento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->string('empresa_nome');
            $table->string('mes_referencia', 7); // MM/YYYY
            $table->decimal('receita_bruta', 15, 2)->default(0);
            $table->decimal('acumulado_12_meses', 15, 2)->default(0);
            $table->boolean('ultrapassou_sublimite')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('controle_faturamento');
    }
};
