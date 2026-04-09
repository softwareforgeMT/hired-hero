<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hired Hero</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .email-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
        }
        .email-header {
            background-color: #0f192a;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        /*.email-header img {
            max-width: 150px;
        }*/
        .email-body {
            padding: 20px;
            color: #333333;
        }
        .email-body h1 {
            font-size: 24px;
            color: #1a73e8;
        }
        .email-body p {
            font-size: 16px;
            line-height: 1.5;
        }
        .email-body ul {
            list-style-type: none;
            padding: 0;
        }
        .email-body ul li {
            margin-bottom: 10px;
        }
        .email-footer {
            background-color: #0f192a;
            color: #ffffff;
            text-align: center;
            padding: 20px;
            font-size: 14px;
        }
        .email-footer a {
            color: #1a73e8;
            text-decoration: none;
        }
        .social-icons {
            margin: 10px 0;
        }
        .social-icons a {
            margin: 0 10px;
            display: inline-block;
            color: #ffffff;
            font-size: 24px;
        }
    </style>
    <!-- Link to Font Awesome for social media icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <img src="{{ url('logo/hiredhero-light.png') }}" alt="HiredHeroAI" style="max-width:180px; height:auto;">
            <h2>Hired Hero - AI Skills Enhancement Platform</h2>
        </div>
        
        <!-- Body -->
        <div class="email-body">
           
            <p>{!! $email_body !!}</p>
        </div>
        
        <!-- Footer -->
        <div class="email-footer">
            <p>&copy; <script>document.write(new Date().getFullYear())</script> Hired Hero. All rights reserved.</p>
            <p>AI Job Interview System</p>
            <p>Revolutionizing the way interviews are conducted. Utilizing AI to ensure precision and efficiency in hiring.</p>
            {{-- <div class="social-icons">
                <a href="{{ $sociallinks->facebook }}"><i class="fab fa-facebook-f"></i></a>
                <a href="{{ $sociallinks->twitter }}"><i class="fab fa-twitter"></i></a>
                <a href="{{ $sociallinks->linkedin }}"><i class="fab fa-linkedin-in"></i></a>
            </div>
            <p>
                <a href="{{ route('front.index') }}">Home</a> | 
                <a href="{{ route('front.pricing') }}">Plans</a> | 
                <a href="{{ route('front.page', \App\Models\Page::find(1)->slug) }}">About Us</a>
            </p> --}}
            <p>Contact us: <a href="mailto:{{ $gs->from_email }}">{{ $gs->from_email }}</a> </p>
        </div>
    </div>
</body>
</html>
