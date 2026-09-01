<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
        {
                Schema::create('attributes', function (Blueprint $table) {
                            $table->uuid('id')->primary();
                                        
                                                    // الربط بجدول مجموعات الخصائص (Attribute Groups)
                                                                $table->foreignUuid('attribute_group_id')
                                                                                  ->nullable()
                                                                                                    ->constrained('attribute_groups')
                                                                                                                      ->onDelete('set null');

                                                                                                                                  $table->string('name'); // مثال: اللون، المقاس، سعة الذاكرة
                                                                                                                                              $table->string('slug')->unique(); // مثال: color, size, storage
                                                                                                                                                          $table->timestamps();
                                                                                                                                                                  });
                                                                                                                                                                      }

                                                                                                                                                                          public function down(): void
                                                                                                                                                                              {
                                                                                                                                                                                      Schema::dropIfExists('attributes');
                                                                                                                                                                                          }
                                                                                                                                                                                          };
                                                                                                                                                                                          