<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_templates', function (Blueprint $table) {
            $table->json('checklist')->nullable();
        });

        Schema::create('checklist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('project_id');
            $table->string('label');
            $table->boolean('done')->default(false);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();

            $table->index(['company_id', 'project_id']);
        });
    }

    public function down(): void
    {
        Schema::table('project_templates', function (Blueprint $table) {
            $table->dropColumn('checklist');
        });

        Schema::dropIfExists('checklist_items');
    }
};
