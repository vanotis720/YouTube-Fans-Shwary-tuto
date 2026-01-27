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
        Schema::create('trading_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('unique_code', 20)->unique();

            // Identity Information
            $table->string('first_name');
            $table->string('last_name');
            $table->date('date_of_birth');
            $table->string('nationality');
            $table->string('phone');
            $table->text('address');
            $table->string('city');
            $table->string('postal_code');
            $table->string('country');

            // Trading Experience Questions (like AvaTrade)
            $table->enum('trading_experience', [
                'none',
                'less_than_1_year',
                '1_to_3_years',
                '3_to_5_years',
                'more_than_5_years'
            ]);
            $table->enum('risk_tolerance', [
                'low',
                'medium',
                'high'
            ])->comment('How much risk are you willing to take?');

            // Employment and Financial Info
            $table->string('employment_status');
            $table->decimal('annual_income', 15, 2)->nullable();

            $table->timestamps();

            $table->index(['user_id', 'unique_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trading_accounts');
    }
};
