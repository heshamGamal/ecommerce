<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
        {
                Schema::create('shipment_logs', function (Blueprint $table) {
                            $table->uuid('id')->primary();

                                        // الربط الشحنة الرئيسية
                                                    $table->foreignUuid('shipment_id')
                                                                      ->constrained('shipments')
                                                                                        ->onDelete('cascade');

                                                                                                    // حالة الشحنة في هذه اللحظة (مثال: picked_up, out_for_delivery, failed, delivered)
                                                                                                                $table->string('status');

                                                                                                                            // وصف الحركة أو الموقع الحالي (مثال: "الشحنة خرجت من مخزن القاهرة", "العميل لم يرد على الهاتف")
                                                                                                                                        $table->string('description')->nullable();

                                                                                                                                                    // الموقع الجغرافي للحادث إن وجد (مثل اسم الفرع/المحطة أو الإحداثيات)
                                                                                                                                                                $table->string('location')->nullable();

                                                                                                                                                                            // البيانات الخام الواردة من Webhook شركة الشحن للتتبع والتدقيق الفني
                                                                                                                                                                                        $table->json('payload')->nullable();

                                                                                                                                                                                                    $table->timestamp('created_at')->useCurrent();
                                                                                                                                                                                                            });
                                                                                                                                                                                                                }

                                                                                                                                                                                                                    public function down(): void
                                                                                                                                                                                                                        {
                                                                                                                                                                                                                                Schema::dropIfExists('shipment_logs');
                                                                                                                                                                                                                                    }
                                                                                                                                                                                                                                    };
                                                                                                                                                                                                                                    