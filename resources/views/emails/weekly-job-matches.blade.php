<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Weekly Job Matches</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: #2d3748;
            background-color: #f0f2f5;
            margin: 0;
            padding: 20px;
        }

        .wrapper {
            max-width: 600px;
            margin: 0 auto;
        }

        .container {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
            overflow: hidden;
        }

        /* Header with Logo */
        .header-top {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            padding: 24px 30px;
            text-align: center;
            border-bottom: 3px solid #ff6b35;
        }

        .logo {
            max-width: 100px;
            height: auto;
            margin-bottom: 16px;
            display: inline-block;
        }

        .logo img {
            width: 100%;
            height: auto;
        }

        .header {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-bottom: 3px solid #ff6b35;
        }

        .header h1 {
            margin: 0 0 8px 0;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .header p {
            margin: 0;
            font-size: 14px;
            opacity: 0.95;
            font-weight: 500;
            color: #cbd5e0;
        }

        .content {
            padding: 40px 30px;
        }

        .greeting {
            margin-bottom: 30px;
        }

        .greeting h2 {
            margin: 0 0 12px 0;
            font-size: 20px;
            color: #1a202c;
            font-weight: 700;
        }

        .greeting p {
            margin: 0;
            color: #4a5568;
            font-size: 14px;
            line-height: 1.7;
        }

        /* Role Badge */
        .role-badge {
            display: block;
            background: linear-gradient(135deg, #fff5f0 0%, #ffe6d5 100%);
            border-left: 4px solid #ff6b35;
            padding: 16px 18px;
            margin: 30px 0;
            border-radius: 6px;
        }

        .role-badge strong {
            color: #ff6b35;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .role-badge strong i {
            font-size: 16px;
        }

        .role-badge span {
            color: #2d3748;
            font-size: 16px;
            display: block;
            margin-left: 24px;
            font-weight: 600;
        }

        /* Jobs Section */
        .jobs-section {
            margin-bottom: 30px;
        }

        .jobs-section h3 {
            margin: 0 0 24px 0;
            font-size: 18px;
            color: #1a202c;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
            padding-bottom: 12px;
            border-bottom: 2px solid #e2e8f0;
        }

        .jobs-section h3 i {
            color: #ff6b35;
            font-size: 20px;
        }

        /* Job Card */
        .job-card {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 22px;
            margin-bottom: 16px;
            transition: all 0.3s ease;
            background: #f9fafb;
            position: relative;
            overflow: hidden;
        }

        .job-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #ff6b35 0%, #ff8960 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .job-card:hover {
            border-color: #ff6b35;
            box-shadow: 0 6px 20px rgba(255, 107, 53, 0.15);
            background: #ffffff;
        }

        .job-card:hover::before {
            opacity: 1;
        }

        .job-title {
            font-size: 16px;
            font-weight: 700;
            color: #1a202c;
            margin: 0 0 8px 0;
        }

        .job-title a {
            color: #ff6b35;
            text-decoration: none;
            font-weight: 700;
            transition: color 0.3s ease;
        }

        .job-title a:hover {
            color: #ff5722;
            text-decoration: underline;
        }

        .job-company {
            font-size: 14px;
            color: #4a5568;
            margin: 0 0 14px 0;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
        }

        .job-company i {
            color: #ff6b35;
            font-size: 16px;
        }

        .job-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 24px;
            font-size: 13px;
            color: #718096;
            margin-bottom: 14px;
            padding-bottom: 14px;
            border-bottom: 1px solid #e2e8f0;
        }

        .job-meta span {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 4px 0;
        }

        .job-meta i {
            font-size: 14px;
            color: #cbd5e0;
        }

        .job-meta strong {
            color: #2d3748;
            font-weight: 600;
        }

        .job-description {
            font-size: 13px;
            color: #4a5568;
            line-height: 1.6;
            margin-bottom: 14px;
            max-height: 80px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .job-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }

        .job-match-score {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #fef3e2;
            color: #d97706;
            padding: 7px 14px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 700;
        }

        .job-match-score i {
            font-size: 13px;
        }

        .cta-button {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, #ff6b35 0%, #ff5722 100%);
            color: white;
            padding: 10px 18px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(255, 107, 53, 0.3);
        }

        .cta-button:hover {
            background: linear-gradient(135deg, #ff5722 0%, #e64a19 100%);
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.4);
            transform: translateY(-2px);
        }

        .cta-button i {
            font-size: 12px;
        }

        /* View All Button */
        .view-all-button {
            text-align: center;
            margin: 40px 0;
        }

        .view-all-button a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: linear-gradient(135deg, #ff6b35 0%, #ff5722 100%);
            color: white;
            padding: 14px 32px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 700;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
        }

        .view-all-button a:hover {
            background: linear-gradient(135deg, #ff5722 0%, #e64a19 100%);
            text-decoration: none;
            box-shadow: 0 6px 20px rgba(255, 107, 53, 0.4);
            transform: translateY(-2px);
        }

        /* Info Box */
        .info-box {
            background: linear-gradient(135deg, #fff5f0 0%, #ffe6d5 100%);
            border-left: 4px solid #ff6b35;
            padding: 18px;
            border-radius: 6px;
            margin: 30px 0;
        }

        .info-box p {
            margin: 0;
            font-size: 13px;
            color: #2d3748;
            line-height: 1.7;
        }

        .info-box strong {
            color: #ff6b35;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .info-box i {
            font-size: 16px;
        }

        /* Divider */
        .divider {
            border: none;
            border-top: 1px solid #e2e8f0;
            margin: 30px 0;
        }

        /* Footer */
        .footer {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            border-top: 3px solid #ff6b35;
            padding: 30px;
            text-align: center;
            font-size: 12px;
            color: #a0aec0;
        }

        .footer p {
            margin: 0 0 10px 0;
        }

        .footer a {
            color: #ff6b35;
            text-decoration: none;
            font-weight: 600;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        .footer-brand {
            color: #ff6b35;
            font-weight: 700;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #2d3748;
        }

        @media (max-width: 600px) {
            .content {
                padding: 24px 20px;
            }

            .job-meta {
                flex-direction: column;
                gap: 8px;
            }

            .cta-button {
                padding: 12px 16px;
                width: 100%;
                justify-content: center;
            }

            .view-all-button a {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <!-- Logo Section -->
            <div class="header-top">
                <div class="logo">
                    <img src="{{ asset('assets/images/landing/hiredhero_brain.png') }}" alt="HiredHero Logo">
                </div>
            </div>

            <!-- Header -->
            <div class="header">
                <h1>Your Weekly Job Matches</h1>
                <p>4 Curated Opportunities for {{ $selectedRole }}</p>
            </div>

            <!-- Content -->
            <div class="content">
                <!-- Greeting -->
                <div class="greeting">
                    <h2>Hello {{ $user->name }},</h2>
                    <p>We found 4 fantastic job opportunities matching your profile. Explore the opportunities below and apply to the ones that interest you most.</p>
                </div>

                <!-- Role Badge -->
                <div class="role-badge">
                    <strong><i class="ri-briefcase-line"></i> Selected Role</strong>
                    <span>{{ $selectedRole }}</span>
                </div>

                <!-- Jobs Section -->
                <div class="jobs-section">
                    <h3><i class="ri-search-line"></i> Matching Opportunities</h3>

                    @foreach($jobs as $index => $job)
                    <div class="job-card">
                        <!-- Job Title -->
                        <div class="job-title">
                            {{ $job['job_title'] }}
                        </div>

                        <!-- Company -->
                        <div class="job-company">
                            <i class="ri-building-line"></i>
                            {{ $job['company_name'] ?? 'Company' }}
                        </div>

                        <!-- Meta Information -->
                        <div class="job-meta">
                            <span><i class="ri-map-pin-line"></i> <strong>{{ $job['location'] ?? 'Remote' }}</strong></span> 
                            <span><i class="ri-time-line"></i> <strong>{{ $job['time'] ?? 'Recently Posted' }}</strong></span>
                            @if(isset($job['platform']))
                            <span><i class="ri-global-line"></i> <strong>{{ ucfirst($job['platform']) }}</strong></span>
                            @endif
                        </div>

                        <!-- Description Snippet -->
                        @if(isset($job['job_description']) && !empty($job['job_description']))
                        <div class="job-description">
                            {{ Str::limit(strip_tags($job['job_description']), 100, '...') }}
                        </div>
                        @endif

                        <!-- Match Score & CTA -->
                        <div class="job-actions">
                            @if(isset($job['match_score']))
                            <span class="job-match-score"><i class="ri-checkbox-circle-line"></i> {{ $job['match_score'] }}% Match</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <hr class="divider">

                <!-- View All Button -->
                <div class="view-all-button">
                    <a href="{{ route('placement.jobs.index') }}"><i class="ri-arrow-right-line"></i> View All Job Matches</a>
                </div>

                <!-- Additional Info -->
                <div class="info-box">
                    <strong><i class="ri-lightbulb-line"></i> Tip</strong>
                    <p>You can manage your email preferences and customize your job search criteria from your account settings to get better matches.</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p>You're receiving this email because you've enabled weekly job match notifications.</p>
                <p style="margin-top: 12px;">
                    <a href="{{ route('email.unsubscribe', ['token' => 'unsubscribe-token']) }}">Unsubscribe</a> | 
                    <a href="{{ route('user.profile') }}">Manage Preferences</a>
                </p>
                <div class="footer-brand">
                    © {{ date('Y') }} HiredHero. All rights reserved.
                </div>
            </div>
        </div>
    </div>
</body>
</html>
