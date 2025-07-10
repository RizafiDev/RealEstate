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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->unsignedBigInteger('sales_agent_id')->nullable();
            $table->enum('status', ['new', 'contacted', 'qualified', 'proposal', 'negotiation', 'closed_won', 'closed_lost'])->default('new');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->decimal('budget_min', 15, 2)->nullable();
            $table->decimal('budget_max', 15, 2)->nullable();
            $table->text('preferred_location')->nullable();
            $table->text('requirements')->nullable();
            $table->timestamp('last_contact_date')->nullable();
            $table->timestamp('next_follow_up')->nullable();
            $table->timestamp('conversion_date')->nullable();
            $table->timestamp('deleted_at')->nullable(); // Soft delete
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('unit_id')->references('id')->on('units');
            $table->foreign('sales_agent_id')->references('id')->on('users');

            $table->index(['status', 'sales_agent_id']);
            $table->index('next_follow_up');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
