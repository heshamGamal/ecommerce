<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
        {
                Schema::create('shipping_providers', function (Blueprint $table) {
                            $table->uuid('id')->primary();
                                        
                                                    $table->string('name'); // اسم الشركة (مثال: Bosta, Aramex, MNT-Halan)
                                                                $table->string('code')->unique(); // رمز فريد للشركة لربطه مع الـ Drivers (مثال: bosta, aramex, internal)
                                                                            $table->string('tracking_url_template')->nullable(); // رابط تتبع الشحنة مع تعويض رقم التتبع (مثال: https://bosta.co/tracking/{tracking_number})
                                                                                        $table->boolean('is_active')->default(true);
                                                                                                    
                                                                                                                // بيانات الاعتماد والإعدادات لشركة الشحن (API Keys, Webhook Secrets, Account ID)
                                                                                                                            $table->json('credentials')->nullable(); 

                                                                                                                                        $table->timestamps();
                                                                                                                                                });
                                                                                                                                                    }

                                                                                                                                                        public function down(): void
                                                                                                                                                            {
                                                                                                                                                                    Schema::dropIfExists('shipping_providers');
                                                                                                                                                                        }
                                                                                                                                                                        };
                                                                                                                                                                    