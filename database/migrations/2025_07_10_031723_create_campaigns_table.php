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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->enum('type', ['discount', 'cashback', 'free_facilities', 'special_price']);
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('unit_type_id')->nullable();
            $table->decimal('discount_percentage', 5, 2)->nullable();
            $table->decimal('discount_amount', 15, 2)->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('max_usage')->nullable();
            $table->integer('current_usage')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('deleted_at')->nullable(); // Soft delete
            $table->timestamps();

            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('unit_type_id')->references('id')->on('unit_types');

            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
