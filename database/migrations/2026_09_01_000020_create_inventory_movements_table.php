<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('warehouse_id')
                ->constrained('warehouses')
                ->cascadeOnDelete();

            $table->foreignUuid('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            $table->foreignUuid('product_variant_id')
                ->nullable()
                ->constrained('product_variants')
                ->nullOnDelete();

            $table->string('type');

            $table->integer('quantity');

            $table->string('reference_type')->nullable();
            $table->uuid('reference_id')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['warehouse_id', 'type']);
            $table->index(['product_id', 'product_variant_id']);
            $table->index(['reference_type', 'reference_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};
