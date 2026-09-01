<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
        {
                Schema::create('carts', function (Blueprint $table) {
                            $table->uuid('id')->primary();

                                        // ربط السلة بالمستخدم (إذا كان مسجلاً دخول)
                                                    $table->foreignUuid('user_id')
                                                                      ->nullable()
                                                                                        ->constrained('users')
                                                                                                          ->onDelete('cascade');

                                                                                                                      // معرف الجلسة للزوار غير المسجلين (Guest Session)
                                                                                                                                  $table->string('session_id')->nullable()->index();

                                                                                                                                              // الكوبون المطبق على السلة حالياً
                                                                                                                                                          $table->foreignUuid('coupon_id')
                                                                                                                                                                            ->nullable()
                                                                                                                                                                                              ->constrained('coupons')
                                                                                                                                                                                                                ->onDelete('set null');

                                                                                                                                                                                                                            $table->timestamps();
                                                                                                                                                                                                                                    });
                                                                                                                                                                                                                                        }

                                                                                                                                                                                                                                            public function down(): void
                                                                                                                                                                                                                                                {
                                                                                                                                                                                                                                                        Schema::dropIfExists('carts');
                                                                                                                                                                                                                                                            }
                                                                                                                                                                                                                                                            };
                                                                                                                                                                                                                                                            