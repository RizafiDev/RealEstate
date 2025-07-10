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
        Schema::create('marketing_materials', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
            $table->enum('type', ['brochure', 'video', 'presentation', 'flyer', 'banner']);
            $table->string('file_url', 255);
            $table->unsignedBigInteger('project_id')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('deleted_at')->nullable(); // Soft delete

            $table->foreign('project_id')->references('id')->on('projects');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_materials');
    }
};
