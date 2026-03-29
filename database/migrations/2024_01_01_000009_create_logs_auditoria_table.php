<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logs_auditoria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('acao');
            $table->string('modulo');
            $table->string('modelo_tipo')->nullable();
            $table->unsignedBigInteger('modelo_id')->nullable();
            $table->json('dados_antes')->nullable();
            $table->json('dados_depois')->nullable();
            $table->string('ip', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->text('observacao')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('user_id');
            $table->index('modulo');
            $table->index(['modelo_tipo', 'modelo_id']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logs_auditoria');
    }
};
