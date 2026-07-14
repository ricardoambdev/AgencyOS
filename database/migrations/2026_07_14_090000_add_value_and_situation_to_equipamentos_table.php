<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('equipamentos', function (Blueprint $table) {
            $table->decimal('valor', 14, 2)->default(0)->after('description');
            $table->boolean('tem_seguro')->default(false)->after('valor');
            $table->decimal('valor_seguro', 14, 2)->nullable()->after('tem_seguro');
            $table->string('situacao')->default('funcional')->after('valor_seguro');
        });
    }

    public function down(): void
    {
        Schema::table('equipamentos', function (Blueprint $table) {
            $table->dropColumn(['valor', 'tem_seguro', 'valor_seguro', 'situacao']);
        });
    }
};
