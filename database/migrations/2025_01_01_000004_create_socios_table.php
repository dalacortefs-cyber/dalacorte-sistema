<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('socios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->string('nome');
            $table->string('cpf', 14);
            $table->decimal('participacao', 5, 2)->nullable();
            $table->enum('tipo', ['Administrador', 'Sócio', 'Sócio-Administrador'])->nullable();
            $table->string('email')->nullable();
            $table->string('telefone', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('socios');
    }
};
