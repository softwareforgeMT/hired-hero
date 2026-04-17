<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Laravel\Sanctum\Sanctum;

class StressTestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if stress test user already exists
        $user = User::where('email', 'stress-test@hiredhero.local')->first();

        if (!$user) {
            $user = User::create([
                'name' => 'Stress Test User',
                'email' => 'stress-test@hiredhero.local',
                'password' => bcrypt('stress-test-password-' . now()->timestamp),
                'email_verified_at' => now(),
            ]);

            echo "✓ Created stress test user: {$user->email}\n";
        } else {
            echo "✓ Stress test user already exists: {$user->email}\n";
        }

        // Generate or regenerate Sanctum token
        $token = $user->createToken('stress-test', ['*'])->plainTextToken;
        
        echo "✓ Generated Sanctum token\n";
        echo "\nAdd this to your .env file:\n";
        echo "STRESS_TEST_API_TOKEN={$token}\n";
    }
}
