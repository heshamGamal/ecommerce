<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
        {
                Schema::create('order_items', function (Blueprint $table) {
                            $table->uuid('id')->primary();

                                        // الربط بالطلب الرئيسي
                                                    $table->foreignUuid('order_id')
                                                                      ->constrained('orders')
                                                                                        ->onDelete('cascade');

                                                                                                    // الربط بالمنتج (set null لتجنب ضياع سجّل الطلبات إذا تم حذف المنتج)
                                                                                                                $table->foreignUuid('product_id')
                                                                                                                                  ->nullable()
                                                                                                                                                    ->constrained('products')
                                                                                                                                                                      ->onDelete('set null');

                                                                                                                                                                                  // الربط بمتغير المنتج (إن وجد)
                                                                                                                                                                                              $table->foreignUuid('product_variant_id')
                                                                                                                                                                                                                ->nullable()
                                                                                                                                                                                                                                  ->constrained('product_variants')
                                                                                                                                                                                                                                                    ->onDelete('set null');

                                                                                                                                                                                                                                                                // حفظ بيانات المنتج وقت الشراء (لحفظ السجلات)
                                                                                                                                                                                                                                                                            $table->string('product_name'); // اسم المنتج وقت الطلب
                                                                                                                                                                                                                                                                                        $table->string('variant_name')->nullable(); // تفاصيل المتغير (مثل: أحمر / XL) وقت الطلب
                                                                                                                                                                                                                                                                                                    $table->decimal('unit_price', 10, 2); // سعر القطعة الواحدة وقت الشراء
                                                                                                                                                                                                                                                                                                                $table->integer('quantity')->default(1); // الكمية المشتراة
                                                                                                                                                                                                                                                                                                                            $table->decimal('total_price', 10, 2); // إجمالي السعر (unit_price * quantity)

                                                                                                                                                                                                                                                                                                                                        $table->timestamps();
                                                                                                                                                                                                                                                                                                                                                });
                                                                                                                                                                                                                                                                                                                                                    }

                                                                                                                                                                                                                                                                                                                                                        public function down(): void
                                                                                                                                                                                                                                                                                                                                                            {
                                                                                                                                                                                                                                                                                                                                                                    Schema::dropIfExists('order_items');
                                                                                                                                                                                                                                                                                                                                                                        }
                                                                                                                                                                                                                                                                                                                                                                        };
                                                                                                                                                                                                                                                                                                                                                                        