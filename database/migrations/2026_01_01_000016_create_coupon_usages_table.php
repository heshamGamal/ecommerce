<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
        {
                Schema::create('coupon_usages', function (Blueprint $table) {
                            $table->uuid('id')->primary();

                                        // الربط بالكوبون
                                                    $table->foreignUuid('coupon_id')
                                                                      ->constrained('coupons')
                                                                                        ->onDelete('cascade');

                                                                                                    // الربط بالمستخدم الذي استخدم الكوبون
                                                                                                                $table->foreignUuid('user_id')
                                                                                                                                  ->constrained('users')
                                                                                                                                                    ->onDelete('cascade');

                                                                                                                                                                // الربط بالطلب الذي تم تطبيق الكوبون عليه
                                                                                                                                                                            $table->foreignUuid('order_id')
                                                                                                                                                                                              ->constrained('orders')
                                                                                                                                                                                                                ->onDelete('cascade');

                                                                                                                                                                                                                            // قيمة الخصم التي استنفع بها العميل في هذا الطلب
                                                                                                                                                                                                                                        $table->decimal('discount_amount', 10, 2);

                                                                                                                                                                                                                                                    $table->timestamp('used_at')->useCurrent();
                                                                                                                                                                                                                                                            });
                                                                                                                                                                                                                                                                }

                                                                                                                                                                                                                                                                    public function down(): void
                                                                                                                                                                                                                                                                        {
                                                                                                                                                                                                                                                                                Schema::dropIfExists('coupon_usages');
                                                                                                                                                                                                                                                                                    }
                                                                                                                                                                                                                                                                                    };
                                                                                                                                                                                                                                                                                    