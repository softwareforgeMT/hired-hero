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
            font-family: 'Georgia', serif;
            line-height: 1.7;
            color: #2c2c2c;
            background: white;
        }

        .container {
            max-width: 8.5in;
            height: auto;
            margin: 0 auto;
            padding: 0.5in;
            background: white;
        }

        header {
            text-align: center;
            border-bottom: 2px solid #2c2c2c;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        header h1 {
            font-size: 26px;
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 5px;
            letter-spacing: 2px;
        }

        header p {
            font-size: 13px;
            color: #555;
            margin-bottom: 8px;
        }

        .contact-info {
            font-size: 11px;
            color: #666;
        }

        .contact-info span {
            margin: 0 8px;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #2c2c2c;
            border-bottom: 1px solid #2c2c2c;
            padding-bottom: 5px;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .item {
            margin-bottom: 12px;
        }

        .item-title {
            font-weight: bold;
            font-size: 13px;
            color: #1a1a1a;
        }

        .item-meta {
            font-size: 11px;
            color: #666;
            font-style: italic;
        }

        .item-description {
            font-size: 11px;
            color: #555;
            margin-top: 4px;
            line-height: 1.5;
        }

        .item-subtitle {
            font-size: 12px;
            color: #555;
            margin-top: 2px;
        }

        .skills-container {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 8px;
        }

        .skill-tag {
            display: inline-block;
            font-size: 11px;
            color: #555;
        }

        .languages-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
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

        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .container {
                box-shadow: none;
                margin: 0;
                padding: 0.5in;
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
            <div class="contact-info">
                @if($personal_info['email'])
                    <span>{{ $personal_info['email'] }}</span>
                @endif
                @if($personal_info['phone'])
                    <span>•</span>
                    <span>{{ $personal_info['phone'] }}</span>
                @endif
                @if($personal_info['location'])
                    <span>•</span>
                    <span>{{ $personal_info['location'] }}</span>
                @endif
            </div>
        </header>

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
                        <div style="display: flex; justify-content: space-between;">
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
                        <div class="item-subtitle">{{ $job['company'] }} @if($job['location'] ?? false) • {{ $job['location'] }} @endif</div>
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
                        <div style="display: flex; justify-content: space-between;">
                            <span class="item-title">{{ $edu['degree'] }}@if($edu['field'] ?? false) in {{ $edu['field'] }}@endif</span>
                            @if($edu['graduation_date'] ?? false)
                                <span class="item-meta">{{ \Carbon\Carbon::parse($edu['graduation_date'])->format('Y') }}</span>
                            @endif
                        </div>
                        <div class="item-subtitle">{{ $edu['institution'] }}</div>
                    </div>
                @endforeach
            </section>
        @endif

        <!-- Skills -->
        @if(!empty($skills))
            <section class="section">
                <div class="section-title">Skills</div>
                <div class="skills-container">
                    @foreach($skills as $skill)
                        <span class="skill-tag">• {{ $skill }}</span>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Languages -->
        @if(!empty($languages))
            <section class="section">
                <div class="section-title">Languages</div>
                <div class="languages-container">
                    @foreach($languages as $lang)
                        <span class="language-item">{{ $lang }}</span>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Certifications -->
        @if(!empty($certifications))
            <section class="section">
                <div class="section-title">Certifications</div>
                @foreach($certifications as $cert)
                    <div class="item">
                        <span class="item-title">{{ $cert['name'] }}</span>
                        @if($cert['issuer'])
                            <span class="item-meta" style="margin-left: 8px;">{{ $cert['issuer'] }}</span>
                        @endif
                        @if($cert['issue_date'])
                            <span class="item-meta" style="margin-left: 8px;">{{ \Carbon\Carbon::parse($cert['issue_date'])->format('M Y') }}</span>
                        @endif
                    </div>
                @endforeach
            </section>
        @endif
    </div>
</body>
</html>
