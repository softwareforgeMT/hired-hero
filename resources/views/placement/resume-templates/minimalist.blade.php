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
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.5;
            color: #333;
            background: white;
        }

        .container {
            max-width: 8.5in;
            height: auto;
            margin: 0 auto;
            padding: 0.5in 0.6in;
            background: white;
        }

        header {
            margin-bottom: 25px;
            padding-bottom: 20px;
        }

        header h1 {
            font-size: 24px;
            font-weight: 600;
            color: #000;
            margin-bottom: 3px;
        }

        header p {
            font-size: 12px;
            color: #666;
            margin-bottom: 8px;
        }

        .contact-info {
            font-size: 10px;
            color: #777;
            line-height: 1.4;
        }

        .contact-info span {
            margin-right: 12px;
        }

        .section {
            margin-bottom: 16px;
        }

        .section-title {
            font-size: 11px;
            font-weight: 700;
            color: #000;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 10px;
            margin-top: 8px;
        }

        .item {
            margin-bottom: 10px;
        }

        .item-header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 2px;
        }

        .item-title {
            font-weight: 600;
            font-size: 12px;
            color: #000;
        }

        .item-meta {
            font-size: 10px;
            color: #999;
        }

        .item-subtitle {
            font-size: 11px;
            color: #666;
            margin-top: 1px;
        }

        .item-description {
            font-size: 11px;
            color: #666;
            margin-top: 3px;
            line-height: 1.4;
        }

        .skills-container {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 6px;
        }

        .skill-tag {
            display: inline-block;
            font-size: 10px;
            color: #555;
        }

        .languages-container {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 6px;
        }

        .language-item {
            font-size: 11px;
            color: #666;
        }

        .summary-text {
            font-size: 11px;
            color: #666;
            line-height: 1.5;
            margin-bottom: 8px;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .container {
                box-shadow: none;
                margin: 0;
                padding: 0.5in 0.6in;
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
            <div class="section-title">Summary</div>
            <p class="summary-text">{{ $personal_info['summary'] }}</p>
        </section>
        @endif

        <!-- Work Experience -->
        @if(!empty($work_experience))
        <section class="section">
            <div class="section-title">Experience</div>
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
        </section>
        @endif
    </div>
</body>

</html>