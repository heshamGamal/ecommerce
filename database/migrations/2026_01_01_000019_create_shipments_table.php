<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
        {
                Schema::create('shipments', function (Blueprint $table) {
                            $table->uuid('id')->primary();

                                        // الربط بالطلب
                                                    $table->foreignUuid('order_id')
                                                                      ->constrained('orders')
                                                                                        ->onDelete('cascade');

                                                                                                    // الربط بـ مزود الشحن (Bosta, Aramex, etc.)
                                                                                                                $table->foreignUuid('shipping_provider_id')
                                                                                                                                  ->nullable()
                                                                                                                                                    ->constrained('shipping_providers')
                                                                                                                                                                      ->onDelete('set null');

                                                                                                                                                                                  // الربط بـ منطقة الشحن
                                                                                                                                                                                              $table->foreignUuid('shipping_zone_id')
                                                                                                                                                                                                                ->nullable()
                                                                                                                                                                                                                                  ->constrained('shipping_zones')
                                                                                                                                                                                                                                                    ->onDelete('set null');

                                                                                                                                                                                                                                                                $table->string('tracking_number')->nullable()->index(); // رقم التتبع الخاص بإنشاء الشحنة لدى الشركة
                                                                                                                                                                                                                                                                            $table->string('status')->default('pending'); // pending, picked_up, in_transit, out_for_delivery, delivered, failed, returned
                                                                                                                                                                                                                                                                                        $table->decimal('shipping_cost', 10, 2); // تكلفة الشحن الفعلية لهذا الطلب

                                                                                                                                                                                                                                                                                                    $table->timestamp('shipped_at')->nullable(); // تاريخ الخروج للشحن
                                                                                                                                                                                                                                                                                                                $table->timestamp('delivered_at')->nullable(); // تاريخ الاستلام الفعلي

                                                                                                                                                                                                                                                                                                                            $table->timestamps();
                                                                                                                                                                                                                                                                                                                                    });
                                                                                                                                                                                                                                                                                                                                        }

                                                                                                                                                                                                                                                                                                                                            public function down(): void
                                                                                                                                                                                                                                                                                                                                                {
                                                                                                                                                                                                                                                                                                                                                        Schema::dropIfExists('shipments');
                                                                                                                                                                                                                                                                                                                                                            }
                                                                                                                                                                                                                                                                                                                                                            };
                                                                                                                                                                                                                                                                                                                                                            