<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('budget_quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('number')->index(); // QB-YYYY-000001
            $table->string('client_name');
            $table->string('client_email')->nullable();
            $table->string('client_phone')->nullable();
            $table->string('currency', 3)->default('PEN');

            // guarda centavos como enteros (evita problemas float)
            $table->unsignedBigInteger('subtotal_cents')->default(0);
            $table->unsignedBigInteger('discount_cents')->default(0);
            $table->unsignedBigInteger('tax_cents')->default(0);
            $table->unsignedBigInteger('total_cents')->default(0);

            $table->enum('status', ['draft','sent','accepted','rejected','expired'])->default('draft');
            $table->dateTime('valid_until')->nullable();
            $table->string('public_hash')->nullable()->unique(); // para enlace público
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id','status']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('budget_quotes');
    }
};
