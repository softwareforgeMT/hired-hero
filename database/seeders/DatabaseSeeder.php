<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        DB::table('sub_plans')->truncate();

        DB::table('sub_plans')->insert([
            [
                'name' => 'Free Plan',
                'slug' => Str::slug('Free Plan'),
                'interval' => 'lifetime',
                'duration_unit' => 'lifetime',
                'duration_value' => 1,
                'description' => 'Free Trial Plan',
                'price' => 0.00,
                'price_per_unit' => 0.00,
                'crossed_price_per_unit' => 0.00,
                'total_price' => 0.00,
                'crossed_total_price' => 0.00,
                'access_section' => json_encode([
                    'interviewAccess' => [
                        'limit' => 1,
                        'description' => '1 mock interview',
                        'questions' => 1
                    ],
                    'presentationAccess' => [
                        'limit' => 1,
                        'description' => '1 presentation access',
                        'time' => 2
                    ],
                    'questionAccess' => [
                        'levels' => ['beginner'],
                        'description' => 'Access to beginner-level questions'
                    ],
                    'support' => [
                        'type' => 'Limited email support',
                        'description' => 'Limited email support'
                    ],
                    'jobMatches' => [
                        'job_search' => 5,
                        'job_post_to_show' => 1,
                        'advanced_job_insights' => true,
                        'ai_tailored_resume' => false,
                        'ai_tailored_cover' => false,
                        'job_tracking' => false,
                        'ats_optimized_covers_resumes' => false,
                    ]
                ]),
                'status' => true
            ],

            [
                'name' => 'Starter Plan',
                'slug' => Str::slug('Starter Plan'),
                'interval' => 'biweekly',
                'duration_unit' => 'week',
                'duration_value' => 2,
                'description' => 'For Beginners',
                'price' => 11.99,
                'price_per_unit' => 5.99,
                'crossed_price_per_unit' => 6.50,
                'total_price' => 11.99,
                'crossed_total_price' => 13.00,
                'access_section' => json_encode([
                    'interviewAccess' => [
                        'limit' => 3,
                        'description' => '3 mock interviews per 2 Weeks',
                        'questions' => 5
                    ],
                    'presentationAccess' => [
                        'limit' => 5,
                        'description' => '5 presentation accesses per 2 Weeks',
                        'time' => 30
                    ],
                    'questionAccess' => [
                        'levels' => ['beginner'],
                        'description' => 'Access to beginner-level questions'
                    ],
                    'support' => [
                        'type' => 'Email support',
                        'description' => 'Email support'
                    ],
                    'jobMatches' => [
                        'job_search' => 20,
                        'job_post_to_show' => 4,
                        'advanced_job_insights' => true,
                        'ai_tailored_resume' => true,
                        'ai_tailored_cover' => 3,
                        'job_tracking' => true,
                        'ats_optimized_covers_resumes' => true,
                    ]
                ]),
                'status' => true
            ],

            [
                'name' => 'Pro Plan',
                'slug' => Str::slug('Pro Plan'),
                'interval' => 'monthly',
                'duration_unit' => 'month',
                'duration_value' => 1,
                'description' => 'For Medium-level',
                'price' => 29.99,
                'price_per_unit' => 7.49,
                'crossed_price_per_unit' => 9.99,
                'total_price' => 29.99,
                'crossed_total_price' => 39.99,
                'access_section' => json_encode([
                    'interviewAccess' => [
                        'limit' => 6,
                        'description' => '6 mock interviews per Month',
                        'questions' => 8
                    ],
                    'presentationAccess' => [
                        'limit' => 10,
                        'description' => '10 presentation accesses per Month',
                        'time' => 30
                    ],
                    'questionAccess' => [
                        'levels' => ['beginner', 'medium'],
                        'description' => 'Access to beginner and medium-level questions'
                    ],
                    'support' => [
                        'type' => 'Priority email support',
                        'description' => 'Priority email support'
                    ],
                    'jobMatches' => [
                        'job_search' => 'unlimited',
                        'job_post_to_show' => 16,
                        'advanced_job_insights' => true,
                        'ai_tailored_resume' => true,
                        'ai_tailored_cover' => 'unlimited',
                        'job_tracking' => true,
                        'ats_optimized_covers_resumes' => true,
                    ]
                ]),
                'status' => true
            ],

            [
                'name' => 'Premium Plan',
                'slug' => Str::slug('Premium Plan'),
                'interval' => 'quarterly',
                'duration_unit' => 'month',
                'duration_value' => 3,
                'description' => 'For Advanced Professionals',
                'price' => 42.99,
                'price_per_unit' => 3.31,
                'crossed_price_per_unit' => 6.38,
                'total_price' => 42.99,
                'crossed_total_price' => 82.99,
                'access_section' => json_encode([
                    'interviewAccess' => [
                        'limit' => 10,
                        'description' => 'Up to 10 mock interviews per 3 Months',
                        'questions' => 12
                    ],
                    'presentationAccess' => [
                        'limit' => 15,
                        'description' => 'Up to 15 presentation accesses per 3 Months',
                        'time' => 30
                    ],
                    'questionAccess' => [
                        'levels' => ['beginner', 'medium', 'advanced'],
                        'description' => 'Access to beginner, medium, and advanced-level questions'
                    ],
                    'support' => [
                        'type' => 'Priority email support',
                        'description' => 'Priority email support'
                    ],
                    'jobMatches' => [
                        'job_search' => 'unlimited',
                        'job_post_to_show' => 16,
                        'advanced_job_insights' => true,
                        'ai_tailored_resume' => true,
                        'ai_tailored_cover' => 'unlimited',
                        'job_tracking' => true,
                        'ats_optimized_covers_resumes' => true,
                    ]
                ]),
                'status' => true
            ],
        ]);

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
