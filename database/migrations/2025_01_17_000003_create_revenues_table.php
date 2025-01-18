<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('revenues', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('extraction_id')->comment('Chave estrangeira que referencia o registro de extração');
            $table->unsignedBigInteger('company_id')->comment('Chave estrangeira que referencia o registro da empresa');
            $table->unsignedInteger('ranking')->comment('Classificação da empresa');
            $table->decimal('revenue', 20)->comment('Receita total associada à empresa');
            $table->decimal('profit', 20)->comment('Lucro total associado à empresa');
            $table->decimal('asset', 20)->comment('Total de ativos associados à empresa');
            $table->decimal('value', 20)->comment('Valor de mercado associado à empresa');
            $table->timestamps();

            $table->foreign('extraction_id')->references('id')->on('extractions');
            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revenues');
    }
};
