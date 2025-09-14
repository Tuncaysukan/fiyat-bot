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
        Schema::create('products', function (Blueprint $t) {
            $t->id();
            $t->foreignId('marketplace_id')->constrained()->cascadeOnDelete();
            $t->string('title');
            $t->text('url');
            $t->enum('parse_strategy', ['selector','ldjson','api','custom'])->default('selector');
            $t->string('selector')->nullable(); // CSS selector veya JSONPath benzeri
            $t->string('currency', 8)->default('TRY');
            $t->decimal('last_price', 12, 2)->nullable();
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
