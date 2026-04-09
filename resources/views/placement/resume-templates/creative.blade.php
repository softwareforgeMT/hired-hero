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
            color: #2d3748;
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
            background: #4a54d4;
            color: white;
            padding: 25px;
            margin: -5px -5px 25px -5px;
            border-radius: 8px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
            letter-spacing: -0.5px;
        }

        header p {
            font-size: 14px;
            font-weight: 500;
            color: white;
        }

        .contact-info {
            font-size: 11px;
            margin-top: 12px;
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            color: white;
        }

        .contact-info span {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .section {
            margin-bottom: 18px;
        }

        .section-title {
            font-size: 12px;
            font-weight: 700;
            color: #3854b4;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 12px;
            padding-bottom: 6px;
            border-bottom: 2px solid #3854b4;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .item {
            margin-bottom: 13px;
            padding-left: 12px;
            border-left: 3px solid #3854b4;
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
            color: #1a202c;
        }

        .item-meta {
            font-size: 10px;
            color: #718096;
            font-style: italic;
        }

        .item-subtitle {
            font-size: 12px;
            color: #4a5568;
            margin: 2px 0;
            font-weight: 500;
        }

        .item-description {
            font-size: 11px;
            color: #4a5568;
            margin-top: 4px;
            line-height: 1.5;
        }

        .skills-container {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }

        .skill-tag {
            display: inline-block;
            background: #3854b4;
            color: white;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .languages-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 10px;
        }

        .language-item {
            font-size: 12px;
            color: #4a5568;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .summary-text {
            font-size: 12px;
            color: #2d3748;
            line-height: 1.6;
            margin-bottom: 10px;
            padding: 12px;
            background: #e8f0ff;
            border-radius: 6px;
            border-left: 4px solid #3854b4;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .accent {
            color: #3854b4;
            font-weight: 600;
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
                <span>{{ $personal_info['phone'] }}</span>
                @endif
                @if($personal_info['location'])
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
            <div class="section-title">Work Experience</div>
            @foreach($work_experience as $job)
            <div class="item">
                <div class="item-header">
                    <span class="item-title">{{ $job['job_title'] }}</span>
                    <span class="item-meta">
                        @if($job['start_date'] ?? false)
                        {{ \Carbon\Carbon::parse($job['start_date'])->format('M Y') }} -
                        @if($job['currently_working'] ?? false)
                        <span class="accent">Present</span>
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
        </section>
        @endif

        <!-- Education -->
        @if(!empty($education))
        <section class="section">
            <div class="section-title">Education</div>
            @foreach($education as $edu)
            <div class="item">
                <div class="item-header">
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
                <span class="skill-tag">{{ $skill }}</span>
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
                <span class="language-item">• {{ $lang }}</span>
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
                <div class="item-header">
                    <span class="item-title">{{ $cert['name'] }}</span>
                    @if($cert['issue_date'] ?? false)
                    <span class="item-meta">{{ \Carbon\Carbon::parse($cert['issue_date'])->format('M Y') }}</span>
                    @endif
                </div>
                @if($cert['issuer'] ?? false)
                <div class="item-subtitle">{{ $cert['issuer'] }}</div>
                @endif
            </div>
            @endforeach
        </section>
        @endif
    </div>
</body>

</html>