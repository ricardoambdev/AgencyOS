<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contratos', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('company_id');
            $table->string('number')->nullable();
            $table->foreignId('client_id')->nullable();
            $table->foreignId('responsavel_id')->nullable();
            $table->string('title');
            $table->string('type')->default('fixed');
            $table->decimal('value', 12, 2)->default(0);
            $table->string('currency', 3)->default('BRL');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('status')->default('rascunho');
            $table->string('renewal_type')->default('none');
            $table->date('renewal_date')->nullable();
            $table->date('signed_at')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->foreign('client_id')->references('id')->on('clientes')->nullOnDelete();
            $table->foreign('responsavel_id')->references('id')->on('users')->nullOnDelete();
            $table->index(['company_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contratos');
    }
};
