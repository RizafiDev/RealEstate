<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('unit_type_id');
            $table->string('unit_code')->unique();
            $table->enum('status', ['available', 'booked', 'sold'])->default('available');
            $table->decimal('price', 15, 2);
            $table->decimal('discount_price', 15, 2)->nullable();
            $table->string('facing')->nullable();
            $table->string('certificate')->nullable();
            $table->decimal('cash_hard_percentage', 5, 2)->nullable();
            $table->decimal('cash_tempo_percentage', 5, 2)->nullable();
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->json('images')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('unit_type_id')->references('id')->on('unit_types')->onDelete('cascade');

            $table->index(['project_id', 'status']);
            $table->index('unit_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
