<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
        {
                Schema::create('user_addresses', function (Blueprint $table) {
                            $table->uuid('id')->primary();
                                        $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
                                                    $table->string('address_title'); // مثال: المنزل، العمل
                                                                $table->string('full_name');
                                                                            $table->string('phone');
                                                                                        $table->string('country')->default('Egypt');
                                                                                                    $table->string('city');
                                                                                                                $table->string('state')->nullable();
                                                                                                                            $table->string('street_address');
                                                                                                                                        $table->string('postal_code')->nullable();
                                                                                                                                                    $table->boolean('is_default')->default(false);
                                                                                                                                                                $table->timestamps();
                                                                                                                                                                        });
                                                                                                                                                                            }

                                                                                                                                                                                public function down(): void
                                                                                                                                                                                    {
                                                                                                                                                                                            Schema::dropIfExists('user_addresses');
                                                                                                                                                                                                }
                                                                                                                                                                                                };
                                                                                                                                                                                                