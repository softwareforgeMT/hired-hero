<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $personal_info['full_name'] }} - Resume</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Calibri', 'Segoe UI', sans-serif;
            line-height: 1.6;
            color: #1a1a1a;
            background: white;
        }

        .container {
            max-width: 8.5in;
            height: auto;
            margin: 0 auto;
            padding: 0.6in 0.75in;
            background: white;
        }

        /* Two Column Layout */
        .content-wrapper {
            display: flex;
            gap: 25px;
        }

        .sidebar {
            width: 200px;
            flex-shrink: 0;
        }

        .main-content {
            flex: 1;
        }

        header {
            border-bottom: 3px solid #1a1a1a;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        header h1 {
            font-size: 26px;
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 3px;
        }

        header p {
            font-size: 13px;
            color: #555;
            font-weight: 500;
        }

        .contact-info {
            font-size: 10px;
            color: #666;
            line-height: 1.5;
            margin-top: 10px;
        }

        .section {
            margin-bottom: 18px;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: white;
            background: #1a1a1a;
            padding: 6px 10px;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .sidebar .section-title {
            background: #333;
            padding: 5px 8px;
            font-size: 11px;
            margin-bottom: 8px;
        }

        .item {
            margin-bottom: 12px;
        }

        .item-title {
            font-weight: bold;
            font-size: 12px;
            color: #1a1a1a;
            margin-bottom: 2px;
        }

        .item-meta {
            font-size: 10px;
            color: #777;
            font-style: italic;
        }

        .item-detail {
            font-size: 11px;
            color: #555;
            margin-top: 2px;
        }

        .item-description {
            font-size: 11px;
            color: #555;
            margin-top: 4px;
            line-height: 1.4;
        }

        .skills-list {
            font-size: 10px;
            color: #555;
            line-height: 1.6;
        }

        .skill-item {
            margin-bottom: 4px;
        }

        .languages-list {
            font-size: 10px;
            color: #555;
            line-height: 1.6;
        }

        .language-item {
            margin-bottom: 4px;
        }

        .summary-text {
            font-size: 11px;
            color: #555;
            line-height: 1.5;
            margin-bottom: 10px;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .container {
                margin: 0;
                padding: 0.5in 0.75in;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <header>
            <h1>{{ $personal_info['full_name'] }}</h1>
            @if($personal_info['professional_title'])
            <p>{{ $personal_info['professional_title'] }}</p>
            @endif
            <div class="contact-info" style="display: flex; gap: 15px; flex-wrap: wrap;">
                @if($personal_info['email'])
                <span>{{ $personal_info['email'] }} |</span>
                @endif
                @if($personal_info['phone'])
                <span>{{ $personal_info['phone'] }} |</span>
                @endif
                @if($personal_info['location'])
                <span>{{ $personal_info['location'] }}</span>
                @endif
            </div>
        </header>

        <div class="content-wrapper">
            <!-- Sidebar -->
            <aside class="sidebar">
                <!-- Languages Sidebar -->
                @if(!empty($languages))
                <section class="section">
                    <div class="section-title">Languages</div>
                    <div class="languages-list">
                        @foreach($languages as $lang)
                        <div class="language-item">{{ $lang }}</div>
                        @endforeach
                    </div>
                </section>
                @endif
            </aside>

            <!-- Main Content -->
            <main class="main-content">
                <!-- Professional Summary -->
                @if($personal_info['summary'])
                <section class="section">
                    <div class="section-title">Professional Summary</div>
                    <p class="summary-text">{{ $personal_info['summary'] }}</p>
                </section>
                @endif

                <!-- Work Experience -->
                @if(!empty($work_experience))
                <section class="section">
                    <div class="section-title">Professional Experience</div>
                    @foreach($work_experience as $job)
                    <div class="item">
                        <div class="item-title">{{ $job['job_title'] }}</div>
                        <div class="item-detail">
                            {{ $job['company'] }}
                            @if($job['location'])
                            • {{ $job['location'] }}
                            @endif
                        </div>
                        <div class="item-meta">
                            @if($job['start_date'] ?? false)
                            {{ \Carbon\Carbon::parse($job['start_date'])->format('M Y') }} -
                            @if($job['currently_working'] ?? false)
                            Present
                            @elseif($job['end_date'] ?? false)
                            {{ \Carbon\Carbon::parse($job['end_date'])->format('M Y') }}
                            @endif
                            @endif
                        </div>
                        @if($job['description'])
                        <div class="item-description">{{ $job['description'] }}</div>
                        @endif
                    </div>
                    @endforeach
                </section>
                @endif

                <!-- Education -->
                @if(!empty($education))
                <section class="section">
                    <div class="section-title">Education</div>
                    @foreach($education as $edu)
                    <div class="item">
                        <div class="item-title">{{ $edu['degree'] }}@if($edu['field'] ?? false) in {{ $edu['field'] }}@endif</div>
                        <div class="item-detail">{{ $edu['institution'] }}</div>
                        @if($edu['graduation_date'] ?? false)
                        <div class="item-meta">Graduated: {{ \Carbon\Carbon::parse($edu['graduation_date'])->format('Y') }}</div>
                        @endif
                    </div>
                    @endforeach
                </section>
                @endif

                <!-- Certifications -->
                @if(!empty($certifications))
                <section class="section">
                    <div class="section-title">Certifications</div>
                    @foreach($certifications as $cert)
                    <div class="item">
                        <div class="item-title">{{ $cert['name'] }}</div>
                        @if($cert['issuer'] ?? false)
                        <div class="item-detail">{{ $cert['issuer'] }}</div>
                        @endif
                        @if($cert['issue_date'] ?? false)
                        <div class="item-meta">{{ \Carbon\Carbon::parse($cert['issue_date'])->format('M Y') }}</div>
                        @endif
                    </div>
                    @endforeach
                </section>
                @endif

                <!-- Skills -->
                @if(!empty($skills))
                <section class="section">
                    <div class="section-title">Skills</div>
                    <div class="skills-list">
                        @foreach($skills as $skill)
                        <div class="skill-item">• {{ $skill }}</div>
                        @endforeach
                    </div>
                </section>
                @endif
            </main>
        </div>
    </div>
</body>

</html>