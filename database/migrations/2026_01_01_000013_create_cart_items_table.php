<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
        {
                Schema::create('cart_items', function (Blueprint $table) {
                            $table->uuid('id')->primary();
                                        
                                                    // ربط السلة بالمستخدم (إذا كان مسجلاً)
                                                                $table->foreignUuid('user_id')
                                                                                  ->nullable()
                                                                                                    ->constrained('users')
                                                                                                                      ->onDelete('cascade');

                                                                                                                                  // معرف الجلسة (للمستخدمين غير المسجلين Guest Cart)
                                                                                                                                              $table->string('session_id')->nullable()->index();

                                                                                                                                                          // الربط بالمنتج والمتغير المحدد
                                                                                                                                                                      $table->foreignUuid('product_id')
                                                                                                                                                                                        ->constrained('products')
                                                                                                                                                                                                          ->onDelete('cascade');

                                                                                                                                                                                                                      $table->foreignUuid('product_variant_id')
                                                                                                                                                                                                                                        ->nullable()
                                                                                                                                                                                                                                                          ->constrained('product_variants')
                                                                                                                                                                                                                                                                            ->onDelete('cascade');

                                                                                                                                                                                                                                                                                        $table->integer('quantity')->default(1);

                                                                                                                                                                                                                                                                                                    $table->timestamps();
                                                                                                                                                                                                                                                                                                            });
                                                                                                                                                                                                                                                                                                                }

                                                                                                                                                                                                                                                                                                                    public function down(): void
                                                                                                                                                                                                                                                                                                                        {
                                                                                                                                                                                                                                                                                                                                Schema::dropIfExists('cart_items');
                                                                                                                                                                                                                                                                                                                                    }
                                                                                                                                                                                                                                                                                                                                    };
                                                                                                                                                                                                                                                                                                                                    