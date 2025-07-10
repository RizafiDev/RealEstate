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
        Schema::table('leads', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->string('email')->after('name');
            $table->string('phone')->nullable()->after('email');
            $table->string('source')->nullable()->after('phone');
            $table->text('notes')->nullable()->after('source');
            $table->unsignedBigInteger('assigned_to')->nullable()->after('notes');

            // Add foreign key for assigned_to
            $table->foreign('assigned_to')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign(['assigned_to']);
            $table->dropColumn([
                'name',
                'email',
                'phone',
                'source',
                'notes',
                'assigned_to'
            ]);
        });
    }
};
