<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            $table->decimal('sale_price', 10, 2)->unsigned()->nullable()->after('compare_price');
            $table->timestamp('sale_ends_at')->nullable()->after('sale_price');
            
        });

        Schema::table('product_variants', function (Blueprint $table): void {
            $table->json('attributes')->nullable()->after('product_id');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            $table->dropColumn(['sale_price', 'sale_ends_at']);
        });
        Schema::table('product_variants', function (Blueprint $table): void {
            $table->dropColumn('attributes');
        });
    }
};
