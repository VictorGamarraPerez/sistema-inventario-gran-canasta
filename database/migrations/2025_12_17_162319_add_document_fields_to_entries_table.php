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
        Schema::table('entries', function (Blueprint $table) {
            $table->string('document_type')->after('product_id')->nullable(); // Boleta De Compra o Factura
            $table->string('series')->after('document_type')->nullable(); // Serie del documento
            $table->string('number')->after('series')->nullable(); // Número del documento
            $table->decimal('total', 10, 2)->after('quantity')->nullable(); // Total de la compra
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entries', function (Blueprint $table) {
            $table->dropColumn(['document_type', 'series', 'number', 'total']);
        });
    }
};
