<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
        {
                Schema::create('media', function (Blueprint $table) {
                            $table->uuid('id')->primary();

                                        // مسار واسم الملف
                                                    $table->string('file_path');
                                                                $table->string('file_name');
                                                                            $table->string('mime_type')->nullable();
                                                                                        $table->unsignedBigInteger('file_size')->nullable();

                                                                                                    // العلاقة متعددة الأشكال (mediable_type & mediable_id من نوع UUID)
                                                                                                                $table->uuidMorphs('mediable');

                                                                                                                            // إعدادات إضافية للصورة
                                                                                                                                        $table->string('collection')->default('default'); // مثل: thumbnail, gallery, avatar
                                                                                                                                                    $table->integer('sort_order')->default(0); // الترتيب
                                                                                                                                                                $table->boolean('is_primary')->default(false); // هل هي الصورة الرئيسية؟

                                                                                                                                                                            $table->timestamps();
                                                                                                                                                                                    });
                                                                                                                                                                                        }

                                                                                                                                                                                            public function down(): void
                                                                                                                                                                                                {
                                                                                                                                                                                                        Schema::dropIfExists('media');
                                                                                                                                                                                                            }
                                                                                                                                                                                                            };
                                                                                                                                                                                                            