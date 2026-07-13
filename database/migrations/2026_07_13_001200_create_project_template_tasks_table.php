<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_template_tasks', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('project_template_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('priority')->nullable();
            $table->decimal('estimated_hours', 8, 2)->default(0);
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->foreign('project_template_id')->references('id')->on('project_templates')->cascadeOnDelete();
            $table->index(['project_template_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_template_tasks');
    }
};
