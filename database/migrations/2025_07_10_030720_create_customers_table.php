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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 100);
            $table->string('email', 100)->nullable();
            $table->string('phone', 20);
            $table->text('address')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('occupation', 100)->nullable();
            $table->decimal('monthly_income', 15, 2)->nullable();
            $table->string('identity_number', 20)->nullable();
            $table->enum('identity_type', ['ktp', 'passport', 'sim'])->default('ktp');
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable();
            $table->enum('source', ['website', 'referral', 'advertisement', 'walk_in', 'social_media'])->default('website');
            $table->text('notes')->nullable();
            $table->timestamp('deleted_at')->nullable(); // Soft delete
            $table->timestamps();

            $table->index('phone');
            $table->index('email');
            $table->fullText(['full_name', 'email', 'phone']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
