<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
        {
                Schema::create('product_reviews', function (Blueprint $table) {
                            $table->uuid('id')->primary();

                                        // الربط بالمنتج
                                                    $table->foreignUuid('product_id')
                                                                      ->constrained('products')
                                                                                        ->onDelete('cascade');

                                                                                                    // الربط بالعميل
                                                                                                                $table->foreignUuid('user_id')
                                                                                                                                  ->constrained('users')
                                                                                                                                                    ->onDelete('cascade');

                                                                                                                                                                // الربط بالطلب للتأكد من الشراء الفعلي (Verified Purchase)
                                                                                                                                                                            $table->foreignUuid('order_id')
                                                                                                                                                                                              ->nullable()
                                                                                                                                                                                                                ->constrained('orders')
                                                                                                                                                                                                                                  ->onDelete('set null');

                                                                                                                                                                                                                                              // التقييم والمراجعة
                                                                                                                                                                                                                                                          $table->unsignedTinyInteger('rating'); // من 1 إلى 5
                                                                                                                                                                                                                                                                      $table->string('title')->nullable(); // عنوان المراجعة
                                                                                                                                                                                                                                                                                  $table->text('comment')->nullable(); // نص المراجعة التفصيلي

                                                                                                                                                                                                                                                                                              // الحالة والموافقة (pending, approved, rejected)
                                                                                                                                                                                                                                                                                                          $table->string('status')->default('pending');
                                                                                                                                                                                                                                                                                                                      $table->boolean('is_verified_purchase')->default(false);

                                                                                                                                                                                                                                                                                                                                  $table->timestamps();

                                                                                                                                                                                                                                                                                                                                              // منع العميل من تقديم أكثر من تقييم لنفس المنتج (إلا إذا أردت السماح بالتكرار)
                                                                                                                                                                                                                                                                                                                                                          $table->unique(['product_id', 'user_id']);
                                                                                                                                                                                                                                                                                                                                                                  });
                                                                                                                                                                                                                                                                                                                                                                      }

                                                                                                                                                                                                                                                                                                                                                                          public function down(): void
                                                                                                                                                                                                                                                                                                                                                                              {
                                                                                                                                                                                                                                                                                                                                                                                      Schema::dropIfExists('product_reviews');
                                                                                                                                                                                                                                                                                                                                                                                          }
                                                                                                                                                                                                                                                                                                                                                                                          };
                                                                                                                                                                                                                                                                                                                                                                                          