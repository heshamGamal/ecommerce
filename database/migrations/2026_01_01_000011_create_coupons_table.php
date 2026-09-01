<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
        {
                Schema::create('coupon_user', function (Blueprint $table) {
                            $table->uuid('id')->primary();

                                        $table->foreignUuid('coupon_id')
                                                          ->constrained('coupons')
                                                                            ->onDelete('cascade');

                                                                                        $table->foreignUuid('user_id')
                                                                                                          ->constrained('users')
                                                                                                                            ->onDelete('cascade');

                                                                                                                                        $table->foreignUuid('order_id')
                                                                                                                                                          ->nullable()
                                                                                                                                                                            ->constrained('orders')
                                                                                                                                                                                              ->onDelete('set null');

                                                                                                                                                                                                          $table->timestamp('used_at')->useCurrent();
                                                                                                                                                                                                                  });
                                                                                                                                                                                                                      }

                                                                                                                                                                                                                          public function down(): void
                                                                                                                                                                                                                              {
                                                                                                                                                                                                                                      Schema::dropIfExists('coupon_user');
                                                                                                                                                                                                                                          }
                                                                                                                                                                                                                                          };
                                                                                                                                                                                                                                          