<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
        {
                Schema::create('settings', function (Blueprint $table) {
                            $table->uuid('id')->primary();

                                        // المفتاح الفريد للاستدعاء البرمجي (مثال: site.logo, shipping.default_cost)
                                                    $table->string('key')->unique()->index();

                                                                // الاسم التوضيحي للعرض في لوحة التحكم (مثال: شعار المتجر، سعر الشحن الافتراضي)
                                                                            $table->string('display_name');

                                                                                        // القيمة (تخزن كنص، أرقام، مسارات ملفات، أو JSON)
                                                                                                    $table->text('value')->nullable();

                                                                                                                // نوع البيانات لتحديد عنصر الواجهة بالـ Admin (text, textarea, boolean, number, file, json, color, select)
                                                                                                                            $table->string('type')->default('text');

                                                                                                                                        // التبويب أو المزيج المخصص (site_info, shipping, payment, theme)
                                                                                                                                                    $table->string('group')->default('general')->index();

                                                                                                                                                                // هل هذا الإعداد نظامي محمي لا يمكن حذفه من قبل المستخدمين؟
                                                                                                                                                                            $table->boolean('is_locked')->default(false);

                                                                                                                                                                                        $table->timestamps();
                                                                                                                                                                                                });
                                                                                                                                                                                                    }

                                                                                                                                                                                                        public function down(): void
                                                                                                                                                                                                            {
                                                                                                                                                                                                                    Schema::dropIfExists('settings');
                                                                                                                                                                                                                        }
                                                                                                                                                                                                                        };
                                                                                                                                                                                                                        