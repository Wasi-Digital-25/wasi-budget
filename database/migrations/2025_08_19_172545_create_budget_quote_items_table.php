<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('budget_quote_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_id')->constrained('budget_quotes')->cascadeOnDelete();
            $table->string('description')->nullable();
            $table->integer('quantity')->default(1);
            $table->unsignedBigInteger('unit_price_cents')->default(0);
            $table->unsignedBigInteger('total_cents')->default(0);
            $table->timestamps();

            $table->index(['quote_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('budget_quote_items');
    }
};
