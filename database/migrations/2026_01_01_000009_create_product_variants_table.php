<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
        {
                Schema::create('product_variants', function (Blueprint $table) {
                            $table->uuid('id')->primary();
                                        
                                                    // الربط بالمنتج الرئيسي
                                                                $table->foreignUuid('product_id')
                                                                                  ->constrained('products')
                                                                                                    ->onDelete('cascade');

                                                                                                                $table->string('sku')->unique(); // رمز المخزون الفريد للمتغير
                                                                                                                            $table->decimal('price', 10, 2)->unsigned()->nullable(); // السعر إذا كان يختلف عن سعر المنتج الأصلي
                                                                                                                                        $table->decimal('compare_price', 10, 2)->unsigned()->nullable();
                                                                                                                                                    $table->integer('stock_quantity')->default(0); // كمية المتوفر في المخزن
                                                                                                                                                                $table->string('image')->nullable(); // صورة خاصة بالمتغير (مثلاً لون معين)
                                                                                                                                                                            $table->boolean('is_active')->default(true);

                                                                                                                                                                                        $table->timestamps();
                                                                                                                                                                                                });
                                                                                                                                                                                                    }

                                                                                                                                                                                                        public function down(): void
                                                                                                                                                                                                            {
                                                                                                                                                                                                                    Schema::dropIfExists('product_variants');
                                                                                                                                                                                                                        }
                                                                                                                                                                                                                        };
                                                                                                                                                                                                                        