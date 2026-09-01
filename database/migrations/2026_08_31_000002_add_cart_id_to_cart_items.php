<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('cart_items', function (Blueprint $table): void {
            $table->foreignUuid('cart_id')->nullable()->after('id')->constrained('carts')->cascadeOnDelete();
        });
    }
    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table): void { $table->dropForeign(['cart_id']); $table->dropColumn('cart_id'); });
    }
};
