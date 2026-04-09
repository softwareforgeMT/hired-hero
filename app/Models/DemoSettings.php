<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemoSettings extends Model
{
    use HasFactory;

    protected $table = 'demo_settings';

    protected $fillable = [
        'section',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    /**
     * Get demo data by section
     */
    public static function getBySection(string $section): array
    {
        $demo = self::where('section', $section)->first();
        return $demo ? $demo->data : [];
    }

    /**
     * Save demo data for a section
     */
    public static function saveForSection(string $section, array $data): void
    {
        self::updateOrCreate(
            ['section' => $section],
            ['data' => $data]
        );

        // Clear cache
        \Illuminate\Support\Facades\Cache::forget("demo_settings_{$section}");
    }

    /**
     * Get cached demo data by section
     */
    public static function getCachedBySection(string $section): array
    {
        return \Illuminate\Support\Facades\Cache::remember(
            "demo_settings_{$section}",
            3600, // 1 hour
            function () use ($section) {
                return self::getBySection($section);
            }
        );
    }

    /**
     * Populate initial dummy demo data if empty
     */
    public static function populateInitialDemoData(): void
    {
        // General Settings - Use real data or default dummy data
        if (empty(self::getBySection('general_settings'))) {
            $realSettings = GeneralSetting::find(1);
            $generalData = [
                'name' => $realSettings?->name ?? 'HiredHero Demo',
                'slogan' => $realSettings?->slogan ?? 'Your AI-Powered Job Matching Platform',
            ];
            self::saveForSection('general_settings', $generalData);
        }

        // Social Settings - Use real data or default
        if (empty(self::getBySection('social_settings'))) {
            $realSocial = SocialSetting::find(1);
            $socialData = [
                'facebook' => $realSocial?->facebook ?? 'https://facebook.com/hiredhero',
                'twitter' => $realSocial?->twitter ?? 'https://twitter.com/hiredhero',
                'instagram' => $realSocial?->instagram ?? 'https://instagram.com/hiredhero',
                'youtube' => $realSocial?->youtube ?? 'https://youtube.com/hiredhero',
                'linkedin' => $realSocial?->linkedin ?? 'https://linkedin.com/company/hiredhero',
            ];
            self::saveForSection('social_settings', $socialData);
        }

        // Profile Settings - Use real admin profile or default
        if (empty(self::getBySection('profile_settings'))) {
            $admin = \App\Models\Admin::find(1) ?? null;
            $profileData = [
                'name' => $admin?->name ?? 'Demo Admin',
                'email' => $admin?->email ?? 'admin@hiredhero.demo',
            ];
            self::saveForSection('profile_settings', $profileData);
        }

        // Pages - Create sample pages if empty
        if (empty(self::getBySection('pages'))) {
            $pagesData = [
                [
                    'id' => 1,
                    'slug' => 'about-us',
                    'title' => 'About Us',
                    'details' => '<p>Welcome to HiredHero Demo! We are an AI-powered job matching platform designed to connect talented professionals with their ideal career opportunities.</p>',
                    'status' => 1,
                ],
                [
                    'id' => 2,
                    'slug' => 'privacy-policy',
                    'title' => 'Privacy Policy',
                    'details' => '<p>Your privacy is important to us. This page outlines how we collect, use, and protect your data.</p>',
                    'status' => 1,
                ],
                [
                    'id' => 3,
                    'slug' => 'terms-of-service',
                    'title' => 'Terms of Service',
                    'details' => '<p>By using HiredHero, you agree to our terms and conditions. Please read them carefully.</p>',
                    'status' => 1,
                ],
                [
                    'id' => 4,
                    'slug' => 'contact-us',
                    'title' => 'Contact Us',
                    'details' => '<p>Have questions? We\'d love to hear from you. Contact our support team at support@hiredhero.demo</p>',
                    'status' => 1,
                ],
            ];
            self::saveForSection('pages', $pagesData);
        }
    }
}
