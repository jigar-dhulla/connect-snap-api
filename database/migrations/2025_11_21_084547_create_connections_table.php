<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('connections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scanner_profile_id')->constrained('profiles')->cascadeOnDelete();
            $table->foreignId('scanned_profile_id')->constrained('profiles')->cascadeOnDelete();
            $table->text('notes')->nullable();
            $table->timestamp('met_at')->useCurrent();
            $table->timestamps();

            $table->unique(['scanner_profile_id', 'scanned_profile_id']);
            $table->index('scanner_profile_id');
            $table->index('scanned_profile_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('connections');
    }
};
