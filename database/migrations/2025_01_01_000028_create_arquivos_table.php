<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('arquivos', function (Blueprint $table) {
            $table->id();
            $table->enum('modulo', ['Empresas', 'Tarefas', 'Financeiro', 'Certidoes', 'Certificados', 'Documentos']);
            $table->foreignId('empresa_id')->nullable()->constrained('empresas')->nullOnDelete();
            $table->string('empresa_nome')->nullable();
            $table->string('nome_arquivo');
            $table->string('tipo')->nullable(); // MIME type
            $table->bigInteger('tamanho_bytes')->nullable();
            $table->string('arquivo_url');
            $table->string('usuario_upload')->nullable();
            $table->integer('versao')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arquivos');
    }
};
