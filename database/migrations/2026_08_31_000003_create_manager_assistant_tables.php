<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('assistant_profiles', function (Blueprint $table): void {
            $table->uuid('id')->primary(); $table->foreignUuid('user_id')->unique()->constrained('users')->cascadeOnDelete(); $table->foreignUuid('manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title')->default('Manager Assistant'); $table->string('status')->default('active'); $table->decimal('average_rating', 3, 2)->default(0); $table->timestamps();
        });
        Schema::create('assistant_activity_logs', function (Blueprint $table): void {
            $table->uuid('id')->primary(); $table->foreignUuid('assistant_id')->constrained('assistant_profiles')->cascadeOnDelete(); $table->foreignUuid('manager_id')->nullable()->constrained('users')->nullOnDelete(); $table->string('action'); $table->string('entity_type')->nullable(); $table->uuid('entity_id')->nullable(); $table->json('metadata')->nullable(); $table->timestamp('created_at')->useCurrent();
        });
        Schema::create('assistant_reviews', function (Blueprint $table): void {
            $table->uuid('id')->primary(); $table->foreignUuid('assistant_id')->constrained('assistant_profiles')->cascadeOnDelete(); $table->foreignUuid('manager_id')->constrained('users')->cascadeOnDelete(); $table->unsignedTinyInteger('rating'); $table->text('comment')->nullable(); $table->date('reviewed_for')->nullable(); $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('assistant_reviews'); Schema::dropIfExists('assistant_activity_logs'); Schema::dropIfExists('assistant_profiles'); }
};
