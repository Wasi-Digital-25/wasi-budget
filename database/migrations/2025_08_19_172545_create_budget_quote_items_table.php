<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('budget_quote_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_id')->constrained('budget_quotes')->cascadeOnDelete();
            $table->string('sku')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('quantity', 12, 3)->default(1); // ej. 1.5 kg
            $table->string('unit', 16)->default('units');
            $table->unsignedBigInteger('unit_price_cents')->default(0); // centavos
            $table->unsignedBigInteger('discount_cents')->default(0);   // por línea
            $table->unsignedBigInteger('tax_cents')->default(0);        // por línea
            $table->unsignedBigInteger('line_total_cents')->default(0); // calculado
            $table->timestamps();

            $table->index(['quote_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('budget_quote_items');
    }
};
