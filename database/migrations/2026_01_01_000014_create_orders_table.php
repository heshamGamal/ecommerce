<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
        {
                Schema::create('orders', function (Blueprint $table) {
                            $table->uuid('id')->primary();
                                        
                                                    // رقم طلب فريد للعرض والعملاء (مثال: ORD-20260101-ABCD)
                                                                $table->string('order_number')->unique();
                                                                            
                                                                                        // الربط بالمستخدم
                                                                                                    $table->foreignUuid('user_id')
                                                                                                                      ->nullable()
                                                                                                                                        ->constrained('users')
                                                                                                                                                          ->onDelete('set null');

                                                                                                                                                                      // المبالغ المالية
                                                                                                                                                                                  $table->decimal('subtotal', 10, 2); // مجموع المنتجات قبل الخصم والشحن
                                                                                                                                                                                              $table->decimal('discount_amount', 10, 2)->default(0.00); // قيمة الخصم (من كوبون مثلاً)
                                                                                                                                                                                                          $table->decimal('shipping_cost', 10, 2)->default(0.00); // مصاريف الشحن
                                                                                                                                                                                                                      $table->decimal('grand_total', 10, 2); // المبلغ الإجمالي المطلوب دفعه

                                                                                                                                                                                                                                  // حالات الطلب والدفع
                                                                                                                                                                                                                                              $table->string('status')->default('pending'); // pending, processing, shipped, delivered, cancelled
                                                                                                                                                                                                                                                          $table->string('payment_status')->default('pending'); // pending, paid, failed, refunded
                                                                                                                                                                                                                                                                      $table->string('payment_method')->nullable(); // cod, vodafone_cash, instapay, credit_card

                                                                                                                                                                                                                                                                                  // بيانات العنوان (نسخة جغرافية/نصية من العنوان لضمان عدم تغيرها مستقبلاً لو عدل العميل عنوانه)
                                                                                                                                                                                                                                                                                              $table->string('shipping_full_name');
                                                                                                                                                                                                                                                                                                          $table->string('shipping_phone');
                                                                                                                                                                                                                                                                                                                      $table->string('shipping_city');
                                                                                                                                                                                                                                                                                                                                  $table->string('shipping_address');
                                                                                                                                                                                                                                                                                                                                              $table->text('notes')->nullable(); // ملاحظات إضافية من العميل

                                                                                                                                                                                                                                                                                                                                                          $table->timestamps();
                                                                                                                                                                                                                                                                                                                                                                  });
                                                                                                                                                                                                                                                                                                                                                                      }

                                                                                                                                                                                                                                                                                                                                                                          public function down(): void
                                                                                                                                                                                                                                                                                                                                                                              {
                                                                                                                                                                                                                                                                                                                                                                                      Schema::dropIfExists('orders');
                                                                                                                                                                                                                                                                                                                                                                                          }
                                                                                                                                                                                                                                                                                                                                                                                          };
                                                                                                                                                                                                                                                                                                                                                                                          