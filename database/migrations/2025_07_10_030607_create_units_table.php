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
            $table->string('block', 10);
            $table->string('number', 10);
            $table->enum('status', ['available', 'booked', 'sold', 'reserved'])->default('available');
            $table->decimal('base_price', 15, 2);
            $table->decimal('selling_price', 15, 2);
            $table->decimal('booking_fee', 15, 2);
            $table->enum('facing', ['north', 'south', 'east', 'west', 'northeast', 'northwest', 'southeast', 'southwest'])->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['project_id', 'block', 'number']);
            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('unit_type_id')->references('id')->on('unit_types');

            $table->index(['project_id', 'status']);
            $table->fullText(['block', 'number']);
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
