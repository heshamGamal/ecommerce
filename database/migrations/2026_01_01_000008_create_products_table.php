<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
        {
                Schema::create('products', function (Blueprint $table) {
                            $table->uuid('id')->primary();
                                        
                                                    // الربط بتصنيف المنتج (Categories)
                                                                $table->foreignUuid('category_id')
                                                                                  ->nullable()
                                                                                                    ->constrained('categories')
                                                                                                                      ->onDelete('set null');

                                                                                                                                  $table->string('name');
                                                                                                                                              $table->string('slug')->unique();
                                                                                                                                                          $table->text('short_description')->nullable();
                                                                                                                                                                      $table->longText('description')->nullable();
                                                                                                                                                                                  
                                                                                                                                                                                              // الأسعار
                                                                                                                                                                                                          $table->decimal('price', 10, 2)->unsigned();
                                                                                                                                                                                                                      $table->decimal('compare_price', 10, 2)->unsigned()->nullable(); // السعر قبل الخصم
                                                                                                                                                                                                                                  
                                                                                                                                                                                                                                              // بيانات المخزون والتتبع الأساسية
                                                                                                                                                                                                                                                          $table->string('sku')->unique()->nullable();
                                                                                                                                                                                                                                                                      $table->boolean('is_active')->default(true);
                                                                                                                                                                                                                                                                                  $table->boolean('is_featured')->default(false); // منتج مميز لصفحة العرض
                                                                                                                                                                                                                                                                                              
                                                                                                                                                                                                                                                                                                          $table->timestamps();
                                                                                                                                                                                                                                                                                                                  });
                                                                                                                                                                                                                                                                                                                      }

                                                                                                                                                                                                                                                                                                                          public function down(): void
                                                                                                                                                                                                                                                                                                                              {
                                                                                                                                                                                                                                                                                                                                      Schema::dropIfExists('products');
                                                                                                                                                                                                                                                                                                                                          }
                                                                                                                                                                                                                                                                                                                                          };
                                                                                                                                                                                                                                                                                                                                          