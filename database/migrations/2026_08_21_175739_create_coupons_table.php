<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
        {
                Schema::create('coupons', function (Blueprint $table) {
                            $table->uuid('id')->primary();

                                        $table->string('code')->unique(); // رمز الكوبون (مثال: SUMMER2026)
                                                    $table->string('type'); // نوع الخصم: 'fixed' (مبلغ ثابت) أو 'percentage' (نسبة مئوية)
                                                                $table->decimal('value', 10, 2); // قيمة الخصم (مثال: 50.00 أو 15.00%)
                                                                            
                                                                                        $table->decimal('min_order_amount', 10, 2)->nullable(); // الحد الأدنى لإجمالي الطلب لتطبيق الكوبون
                                                                                                    $table->integer('usage_limit')->nullable(); // الحد الأقصى لإجمالي مرات استخدام الكوبون للجميع
                                                                                                                $table->integer('user_limit')->default(1); // الحد الأقصى لاستخدام الكوبون لكل مستخدم
                                                                                                                            $table->integer('used_count')->default(0); // عدد مرات الاستخدام الفعلية حتى الآن

                                                                                                                                        $table->boolean('is_active')->default(true); // حالة تفعيل الكوبون

                                                                                                                                                    $table->timestamp('starts_at')->nullable(); // بداية تاريخ الصلاحية
                                                                                                                                                                $table->timestamp('expires_at')->nullable(); // انتهاء تاريخ الصلاحية

                                                                                                                                                                            $table->timestamps();
                                                                                                                                                                                    });
                                                                                                                                                                                        }

                                                                                                                                                                                            public function down(): void
                                                                                                                                                                                                {
                                                                                                                                                                                                        Schema::dropIfExists('coupons');
                                                                                                                                                                                                            }
                                                                                                                                                                                                            };
                                                                                                                                                                                                            