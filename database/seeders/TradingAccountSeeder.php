<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Account;
use App\Models\TradingAccount;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TradingAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates trading accounts for all active users who don't have one yet.
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting TradingAccount seeder...');

        // Get all users with active accounts
        $users = User::whereHas('account', function ($query) {
            $query->where('is_active', true);
        })->with('account')->get();

        $this->command->info("📊 Found {$users->count()} active users");

        $created = 0;
        $skipped = 0;
        $failed = 0;

        foreach ($users as $user) {
            try {
                // Check if user already has a trading account
                if (TradingAccount::where('user_id', $user->id)->exists()) {
                    $this->command->warn("⏭️  User {$user->email} already has a trading account - skipping");
                    $skipped++;
                    continue;
                }

                $account = $user->account;

                if (!$account) {
                    $this->command->warn("⚠️  User {$user->email} has no account - skipping");
                    $skipped++;
                    continue;
                }

                // Create trading account
                DB::beginTransaction();

                $tradingAccount = $this->createTradingAccount($user, $account);

                // Update account with broker number if not already set
                if (empty($account->broker_account_number)) {
                    $account->update(['broker_account_number' => $tradingAccount->unique_code]);
                }

                DB::commit();

                $this->command->info("✅ Created trading account for {$user->email} - Code: {$tradingAccount->unique_code}");
                $created++;
            } catch (\Exception $e) {
                DB::rollBack();
                $failed++;
                $this->command->error("❌ Failed to create trading account for {$user->email}: {$e->getMessage()}");
                Log::error('TradingAccountSeeder Error', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        $this->command->newLine();
        $this->command->info('📈 Seeder Summary:');
        $this->command->info("   ✅ Created: {$created}");
        $this->command->info("   ⏭️  Skipped: {$skipped}");
        $this->command->info("   ❌ Failed: {$failed}");
        $this->command->newLine();
    }

    /**
     * Create a trading account with realistic data
     */
    private function createTradingAccount(User $user, Account $account): TradingAccount
    {
        // Trading experience options
        $tradingExperiences = ['none', 'less_than_1_year', '1_to_3_years', '3_to_5_years', 'more_than_5_years'];
        $riskTolerances = ['low', 'medium', 'high'];
        $employmentStatuses = ['Employé', 'Indépendant', 'Entrepreneur', 'Fonctionnaire', 'Retraité', 'Étudiant'];
        $nationalities = ['Congolaise', 'Française', 'Belge', 'Camerounaise', 'Ivoirienne', 'Sénégalaise'];
        $countries = ['RDC', 'France', 'Belgique', 'Cameroun', 'Côte d\'Ivoire', 'Sénégal'];
        $cities = ['Kinshasa', 'Lubumbashi', 'Goma', 'Bukavu', 'Kisangani', 'Matadi', 'Kolwezi'];

        // Split user name into first and last name
        $fullName = trim($account->name . ' ' . $account->lastname);
        $nameParts = explode(' ', $fullName);
        $firstName = $nameParts[0] ?? 'John';
        $lastName = isset($nameParts[1]) ? implode(' ', array_slice($nameParts, 1)) : 'Doe';

        // Use existing broker_account_number if available, otherwise generate new
        $uniqueCode = !empty($account->broker_account_number)
            ? $account->broker_account_number
            : TradingAccount::generateUniqueCode();

        // Generate random but realistic data
        $data = [
            'user_id' => $user->id,
            'unique_code' => $uniqueCode,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'date_of_birth' => now()->subYears(rand(25, 65))->subMonths(rand(0, 11))->subDays(rand(0, 30))->format('Y-m-d'),
            'nationality' => $nationalities[array_rand($nationalities)],
            'phone' => $account->phone ?? '+243' . str_pad(rand(100000000, 999999999), 9, '0', STR_PAD_LEFT),
            'address' => 'Avenue ' . rand(1, 100) . ', Quartier ' . ['Gombe', 'Kinshasa', 'Lemba', 'Matete', 'Kintambo'][array_rand(['Gombe', 'Kinshasa', 'Lemba', 'Matete', 'Kintambo'])],
            'city' => $cities[array_rand($cities)],
            'postal_code' => str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
            'country' => $countries[array_rand($countries)],
            'trading_experience' => $tradingExperiences[array_rand($tradingExperiences)],
            'risk_tolerance' => $riskTolerances[array_rand($riskTolerances)],
            'employment_status' => $employmentStatuses[array_rand($employmentStatuses)],
            'annual_income' => rand(500000, 5000000), // Random income between 500k and 5M CDF
        ];

        return TradingAccount::create($data);
    }
}
