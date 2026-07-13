<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflow_states', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('entity_type');
            $table->string('slug');
            $table->string('name');
            $table->string('color')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_initial')->default(false);
            $table->boolean('is_final')->default(false);
            $table->timestamps();

            $table->unique(['company_id', 'entity_type', 'slug']);
            $table->index(['company_id', 'entity_type', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflow_states');
    }
};
