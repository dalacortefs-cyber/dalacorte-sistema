<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('escritorios', function (Blueprint $table) {
            $table->id();
            $table->string('nome_escritorio');
            $table->string('cnpj', 18)->nullable();
            $table->string('endereco')->nullable();
            $table->string('telefone', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('logo_url')->nullable();
            $table->string('logo_dark_url')->nullable();
            $table->string('cor_primaria', 10)->default('#1a3a4a');
            $table->string('cor_secundaria', 10)->default('#b8935a');
            $table->string('cor_destaque', 10)->default('#245266');
            $table->string('slogan')->nullable();
            $table->string('website')->nullable();
            $table->string('portal_titulo')->nullable();
            $table->text('portal_boas_vindas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('escritorios');
    }
};
