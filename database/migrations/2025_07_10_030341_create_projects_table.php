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
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->text('description')->nullable();
            $table->text('address');
            $table->unsignedBigInteger('location_id');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->integer('total_units');
            $table->decimal('land_area', 10, 2)->nullable();
            $table->decimal('building_area', 10, 2)->nullable();
            $table->json('facilities')->nullable();
            $table->string('master_plan', 255)->nullable();
            $table->enum('status', ['planning', 'development', 'ready', 'completed'])->default('planning');
            $table->date('launch_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->timestamps();

            $table->foreign('developer_id')->references('id')->on('developers');
            $table->foreign('location_id')->references('id')->on('locations');

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
