<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
        {
                Schema::create('variant_attribute_values', function (Blueprint $table) {
                            $table->uuid('id')->primary();
                                        
                                                    // الربط بجدول متغيرات المنتجات
                                                                $table->foreignUuid('product_variant_id')
                                                                                  ->constrained('product_variants')
                                                                                                    ->onDelete('cascade');

                                                                                                                // الربط بجدول قيم الخصائص
                                                                                                                            $table->foreignUuid('attribute_value_id')
                                                                                                                                              ->constrained('attribute_values')
                                                                                                                                                                ->onDelete('cascade');

                                                                                                                                                                            $table->timestamps();

                                                                                                                                                                                        // منع تكرار نفس القيمة لنفس المتغير
                                                                                                                                                                                                    $table->unique(['product_variant_id', 'attribute_value_id'], 'variant_attribute_unique');
                                                                                                                                                                                                            });
                                                                                                                                                                                                                }

                                                                                                                                                                                                                    public function down(): void
                                                                                                                                                                                                                        {
                                                                                                                                                                                                                                Schema::dropIfExists('variant_attribute_values');
                                                                                                                                                                                                                                    }
                                                                                                                                                                                                                                    };
                                                                                                                                                                                                                                    