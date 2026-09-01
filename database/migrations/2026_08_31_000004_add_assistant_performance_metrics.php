<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('assistant_specialties', function (Blueprint $table): void {
            $table->uuid('id')->primary(); $table->foreignUuid('assistant_id')->constrained('assistant_profiles')->cascadeOnDelete(); $table->string('specialty'); $table->boolean('is_primary')->default(false); $table->timestamps(); $table->unique(['assistant_id', 'specialty']);
        });
        Schema::table('assistant_activity_logs', function (Blueprint $table): void {
            $table->string('specialty')->nullable()->after('action'); $table->string('outcome')->nullable()->after('specialty'); $table->unsignedInteger('duration_seconds')->nullable()->after('outcome'); $table->timestamp('completed_at')->nullable()->after('duration_seconds'); $table->index(['assistant_id', 'specialty', 'outcome']);
        });
    }
    public function down(): void {
        Schema::table('assistant_activity_logs', function (Blueprint $table): void { $table->dropIndex(['assistant_id', 'specialty', 'outcome']); $table->dropColumn(['specialty', 'outcome', 'duration_seconds', 'completed_at']); });
        Schema::dropIfExists('assistant_specialties');
    }
};
