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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('area_id')->constrained()->restrictOnDelete();
            $table->string('title', 150);
            $table->text('description');
            $table->dateTime('incident_at');
            $table->text('address');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('status', 20)->default('baru');
            $table->string('forwarded_to', 150)->nullable();
            $table->text('forwarding_note')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index(['area_id', 'status']);
            $table->index(['area_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
