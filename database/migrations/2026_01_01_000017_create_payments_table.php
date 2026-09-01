<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
        {
                Schema::create('payments', function (Blueprint $table) {
                            $table->uuid('id')->primary();
                                        
                                                    // الربط بالطلب
                                                                $table->foreignUuid('order_id')
                                                                                  ->constrained('orders')
                                                                                                    ->onDelete('cascade');

                                                                                                                // الربط بالمستخدم
                                                                                                                            $table->foreignUuid('user_id')
                                                                                                                                              ->constrained('users')
                                                                                                                                                                ->onDelete('cascade');

                                                                                                                                                                            // وسيلة الدفع (cod, vodafone_cash, instapay, stripe, paypal)
                                                                                                                                                                                        $table->string('provider'); 
                                                                                                                                                                                                    
                                                                                                                                                                                                                // المبلغ المدفوع
                                                                                                                                                                                                                            $table->decimal('amount', 10, 2);
                                                                                                                                                                                                                                        
                                                                                                                                                                                                                                                    // حالة العملية (pending, completed, failed, refunded)
                                                                                                                                                                                                                                                                $table->string('status')->default('pending');
                                                                                                                                                                                                                                                                            
                                                                                                                                                                                                                                                                                        // معرف المعاملة من بوابة الدفع (Transaction ID from Provider)
                                                                                                                                                                                                                                                                                                    $table->string('transaction_id')->nullable()->index();
                                                                                                                                                                                                                                                                                                                
                                                                                                                                                                                                                                                                                                                            // بيانات إضافية (JSON لتخزين رد بوابة الدفع الخام - Raw Response)
                                                                                                                                                                                                                                                                                                                                        $table->json('payload')->nullable();

                                                                                                                                                                                                                                                                                                                                                    $table->timestamps();
                                                                                                                                                                                                                                                                                                                                                            });
                                                                                                                                                                                                                                                                                                                                                                }

                                                                                                                                                                                                                                                                                                                                                                    public function down(): void
                                                                                                                                                                                                                                                                                                                                                                        {
                                                                                                                                                                                                                                                                                                                                                                                Schema::dropIfExists('payments');
                                                                                                                                                                                                                                                                                                                                                                                    }
                                                                                                                                                                                                                                                                                                                                                                                    };
                                                                                                                                                                                                                                                                                                                                                                                    