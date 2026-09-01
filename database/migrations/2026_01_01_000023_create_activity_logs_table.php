<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
        {
                Schema::create('activity_logs', function (Blueprint $table) {
                            $table->uuid('id')->primary();

                                        // الفاعل (المستخدم أو الأدمن الذي قام بالنشاط)
                                                    $table->foreignUuid('user_id')
                                                                      ->nullable()
                                                                                        ->constrained('users')
                                                                                                          ->onDelete('set null');

                                                                                                                      $table->string('action'); // نوع الحركة (created, updated, deleted, logged_in)
                                                                                                                                  $table->string('description')->nullable(); // وصف نصي تفصيلي للنشاط

                                                                                                                                              // العلاقة المتعددة (Polymorphic) لربط الحركة بأي عنصر بالأنظمة (Product, Order, User, etc.)
                                                                                                                                                          $table->nullableUuidMorphs('subject'); // يولد subject_type و subject_id (UUID)

                                                                                                                                                                      // البيانات التوضيحية والتغييرات (القيم القديمة والجديدة)
                                                                                                                                                                                  $table->json('properties')->nullable();

                                                                                                                                                                                              // بيانات الأمان والتتبع
                                                                                                                                                                                                          $table->string('ip_address', 45)->nullable();
                                                                                                                                                                                                                      $table->text('user_agent')->nullable();

                                                                                                                                                                                                                                  $table->timestamp('created_at')->useCurrent();
                                                                                                                                                                                                                                          });
                                                                                                                                                                                                                                              }

                                                                                                                                                                                                                                                  public function down(): void
                                                                                                                                                                                                                                                      {
                                                                                                                                                                                                                                                              Schema::dropIfExists('activity_logs');
                                                                                                                                                                                                                                                                  }
                                                                                                                                                                                                                                                                  };
                                                                                                                                                                                                                                                                  