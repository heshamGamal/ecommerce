<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
        {
                Schema::create('attribute_values', function (Blueprint $table) {
                            $table->uuid('id')->primary();
                                        
                                                    // الربط بجدول الخصائص (Attributes)
                                                                $table->foreignUuid('attribute_id')
                                                                                  ->constrained('attributes')
                                                                                                    ->onDelete('cascade');

                                                                                                                $table->string('value'); // مثال: أحمر، XL، 256GB
                                                                                                                            $table->string('color_code')->nullable(); // اختياري: كود اللون الهيكس (مثل #FF0000) للواجهات
                                                                                                                                        $table->timestamps();
                                                                                                                                                });
                                                                                                                                                    }

                                                                                                                                                        public function down(): void
                                                                                                                                                            {
                                                                                                                                                                    Schema::dropIfExists('attribute_values');
                                                                                                                                                                        }
                                                                                                                                                                        };
                                                                                                                                                                        