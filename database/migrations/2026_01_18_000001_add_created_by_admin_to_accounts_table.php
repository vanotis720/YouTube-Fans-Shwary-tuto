<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->boolean('created_by_admin')->default(false)->after('is_active');
            $table->foreignId('created_by_admin_id')->nullable()->constrained('users')->onDelete('set null')->after('created_by_admin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropForeign(['created_by_admin_id']);
            $table->dropColumn(['created_by_admin', 'created_by_admin_id']);
        });
    }
};
