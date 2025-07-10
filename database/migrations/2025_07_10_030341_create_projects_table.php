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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('developer_id');
            $table->unsignedBigInteger('location_id');
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->text('address')->nullable();
            $table->enum('status', ['planning', 'development', 'ready', 'completed'])->default('planning');
            $table->date('start_date')->nullable();
            $table->date('estimated_completion')->nullable();
            $table->integer('total_units')->nullable();
            $table->json('facilities')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('sales_phone', 20)->nullable();
            $table->string('sales_email')->nullable();
            $table->json('images')->nullable();
            $table->string('master_plan')->nullable();
            $table->string('brochure_url')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('developer_id')->references('id')->on('developers')->onDelete('cascade');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');

            $table->index(['status', 'developer_id']);
            $table->fullText(['name', 'description']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
