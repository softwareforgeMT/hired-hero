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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: white;
        }

        .container {
            max-width: 8.5in;
            height: 11in;
            margin: 0 auto;
            padding: 0.4in;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        header {
            border-bottom: 3px solid #2563eb;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .header-left h1 {
            font-size: 28px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 5px;
        }

        .header-left p {
            font-size: 14px;
            color: #2563eb;
            font-weight: 600;
        }

        .header-right {
            text-align: right;
            font-size: 12px;
            color: #666;
        }

        .header-right p {
            margin-bottom: 3px;
        }

        .section {
            margin-bottom: 18px;
        }

        .section-title {
            font-size: 13px;
            font-weight: 700;
            color: white;
            background: #2563eb;
            padding: 8px 12px;
            border-radius: 4px;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .section-content {
            padding-left: 0;
        }

        .item {
            margin-bottom: 14px;
        }

        .item-header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 3px;
        }

        .item-title {
            font-weight: 700;
            font-size: 13px;
            color: #1a1a1a;
        }

        .item-subtitle {
            font-size: 12px;
            color: #2563eb;
            font-weight: 600;
        }

        .item-meta {
            font-size: 11px;
            color: #999;
            font-style: italic;
        }

        .item-description {
            font-size: 12px;
            color: #555;
            margin-top: 5px;
            line-height: 1.5;
        }

        .skills-container {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 8px;
        }

        .skill-tag {
            display: inline-block;
            background: #e0e7ff;
            color: #2563eb;
            padding: 4px 10px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 600;
        }

        .languages-container {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 8px;
        }

        .language-item {
            font-size: 12px;
            color: #555;
        }

        .summary-text {
            font-size: 12px;
            color: #555;
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .page-break {
            page-break-after: always;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .container {
                box-shadow: none;
                margin: 0;
                padding: 0.4in;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header>
            <div class="header-content">
                <div class="header-left">
                    <h1>{{ $personal_info['full_name'] }}</h1>
                    <p>{{ $personal_info['professional_title'] }}</p>
                </div>
                <div class="header-right">
                    @if($personal_info['email'])
                        <p><strong>Email:</strong> {{ $personal_info['email'] }}</p>
                    @endif
                    @if($personal_info['phone'])
                        <p><strong>Phone:</strong> {{ $personal_info['phone'] }}</p>
                    @endif
                    @if($personal_info['location'])
                        <p><strong>Location:</strong> {{ $personal_info['location'] }}</p>
                    @endif
                </div>
            </div>
        </header>

        <!-- Professional Summary -->
        @if($personal_info['summary'])
            <section class="section">
                <div class="section-title">Professional Summary</div>
                <div class="section-content">
                    <p class="summary-text">{{ $personal_info['summary'] }}</p>
                </div>
            </section>
        @endif

        <!-- Work Experience -->
        @if(!empty($work_experience))
            <section class="section">
                <div class="section-title">Work Experience</div>
                <div class="section-content">
                    @foreach($work_experience as $job)
                        <div class="item">
                            <div class="item-header">
                                <span class="item-title">{{ $job['job_title'] }}</span>
                                <span class="item-meta">
                                    @if($job['start_date'] ?? false)
                                        {{ \Carbon\Carbon::parse($job['start_date'])->format('M Y') }} -
                                        @if($job['currently_working'] ?? false)
                                            Present
                                        @elseif($job['end_date'] ?? false)
                                            {{ \Carbon\Carbon::parse($job['end_date'])->format('M Y') }}
                                        @endif
                                    @endif
                                </span>
                            </div>
                            <div class="item-subtitle">{{ $job['company'] }} @if($job['location']) • {{ $job['location'] }} @endif</div>
                            @if($job['description'])
                                <div class="item-description">{{ $job['description'] }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Education -->
        @if(!empty($education))
            <section class="section">
                <div class="section-title">Education</div>
                <div class="section-content">
                    @foreach($education as $edu)
                        <div class="item">
                            <div class="item-header">
                                <span class="item-title">{{ $edu['degree'] }}@if($edu['field'] ?? false) in {{ $edu['field'] }}@endif</span>
                                @if($edu['graduation_date'] ?? false)
                                    <span class="item-meta">{{ \Carbon\Carbon::parse($edu['graduation_date'])->format('Y') }}</span>
                                @endif
                            </div>
                            <div class="item-subtitle">{{ $edu['institution'] }}</div>
                            @if($edu['description'] ?? false)
                                <div class="item-description">{{ $edu['description'] }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Skills -->
        @if(!empty($skills))
            <section class="section">
                <div class="section-title">Skills</div>
                <div class="section-content">
                    <div class="skills-container">
                        @foreach($skills as $skill)
                            <span class="skill-tag">{{ $skill }}</span>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <!-- Languages -->
        @if(!empty($languages))
            <section class="section">
                <div class="section-title">Languages</div>
                <div class="section-content">
                    <div class="languages-container">
                        @foreach($languages as $lang)
                            <span class="language-item">{{ $lang }}</span>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <!-- Certifications -->
        @if(!empty($certifications))
            <section class="section">
                <div class="section-title">Certifications</div>
                <div class="section-content">
                    @foreach($certifications as $cert)
                        <div class="item">
                            <div class="item-header">
                                <span class="item-title">{{ $cert['name'] }}</span>
                                @if($cert['issue_date'])
                                    <span class="item-meta">{{ \Carbon\Carbon::parse($cert['issue_date'])->format('M Y') }}</span>
                                @endif
                            </div>
                            @if($cert['issuer'])
                                <div class="item-subtitle">{{ $cert['issuer'] }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</body>
</html>
