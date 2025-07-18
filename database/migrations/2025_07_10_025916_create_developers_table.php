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
        Schema::create('developers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('logo', 255)->nullable();
            $table->text('description')->nullable();
            $table->text('address')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('website', 255)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('established_year')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('developers');
    }
};
