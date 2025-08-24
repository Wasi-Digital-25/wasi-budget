<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('budget_quotes', function (Blueprint $table) {
            $table->foreignId('client_id')->nullable()->after('user_id')->constrained('clients')->nullOnDelete();
            $table->index(['company_id','client_id']);
        });
    }

    public function down(): void
    {
        Schema::table('budget_quotes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('client_id');
        });
    }
};

