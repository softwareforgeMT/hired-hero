@extends('front.layouts.app')

@section('title', 'Job Placement Wizard')

@section('content')
<section class="min-h-screen py-12 px-4 md:px-8 relative overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1a1f3a 100%);">

    <div class="max-w-4xl mx-auto relative z-10 pt-12">
        <!-- Header Section -->
        <div class="text-center mb-16">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-xl bg-gradient-to-br from-red-500/20 to-orange-600/20 mb-8 mx-auto border border-red-500/30 relative">
                <i class="ri-briefcase-4-line text-4xl text-red-400"></i>
            </div>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-4">
                Start Your Job Placement Journey
            </h1>
            <p class="text-lg md:text-xl text-gray-300 max-w-2xl mx-auto leading-relaxed">
                Complete a simple 7-step wizard to get personalized job matches from top companies. Free 14-day access included!
            </p>
        </div>

        <!-- Features Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-20">
            <!-- Feature 1: Resume -->
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-lg p-8 border border-red-500/20 hover:border-red-500/50 hover:shadow-xl hover:shadow-red-500/20 transition-all duration-300 group">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-lg bg-red-500/10 mb-4 group-hover:bg-red-500/20 transition-colors">
                    <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3 3L22 4"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-white mb-2 group-hover:text-red-400 transition-colors">Smart Resume Parsing</h3>
                <p class="text-gray-400 leading-relaxed">Upload your resume and we'll extract your skills and experience automatically.</p>
            </div>

            <!-- Feature 2: AI -->
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-lg p-8 border border-red-500/20 hover:border-red-500/50 hover:shadow-xl hover:shadow-red-500/20 transition-all duration-300 group">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-lg bg-red-500/10 mb-4 group-hover:bg-red-500/20 transition-colors">
                    <svg class="w-6 h-6 text-red-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-white mb-2 group-hover:text-red-400 transition-colors">AI-Powered Insights</h3>
                <p class="text-gray-400 leading-relaxed">Get AI-generated role recommendations based on your profile.</p>
            </div>

            <!-- Feature 3: Matching -->
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-lg p-8 border border-red-500/20 hover:border-red-500/50 hover:shadow-xl hover:shadow-red-500/20 transition-all duration-300 group">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-lg bg-red-500/10 mb-4 group-hover:bg-red-500/20 transition-colors">
                    <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-white mb-2 group-hover:text-red-400 transition-colors">Intelligent Matching</h3>
                <p class="text-gray-400 leading-relaxed">Find jobs from 4 major job boards matched to your profile.</p>
            </div>


            <!-- Feature 5: ATS -->
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-lg p-8 border border-red-500/20 hover:border-red-500/50 hover:shadow-xl hover:shadow-red-500/20 transition-all duration-300 group">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-lg bg-red-500/10 mb-4 group-hover:bg-red-500/20 transition-colors">
                    <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 0a2 2 0 11-4 0 2 2 0 014 0zM5 20a6 6 0 0112 0v2H5v-2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-white mb-2 group-hover:text-red-400 transition-colors">Applicant Tracking System</h3>
                <p class="text-gray-400 leading-relaxed">Organize and manage all your job applications in one centralized dashboard.</p>
            </div>
        </div>

        <!-- How It Works Timeline -->
        <div class="mb-20">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-black text-white mb-4">How It Works</h2>
            </div>

            <div class="space-y-6 relative">
                <!-- Step 1 -->
                <div class="flex gap-6 relative">
                    <div class="flex flex-col items-center z-10">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-red-500 to-orange-600 text-white flex items-center justify-center font-bold text-lg flex-shrink-0 shadow-lg shadow-red-500/50">
                            1
                        </div>
                    </div>
                    <div class="flex-1 pb-8 bg-gradient-to-br from-slate-800 to-slate-900 rounded-lg p-6 border border-red-500/20 hover:border-red-500/50 hover:shadow-lg hover:shadow-red-500/20 transition-all">
                        <h4 class="text-lg font-bold text-white mb-2 flex items-center gap-2">
                            <i class="ri-upload-cloud-line text-red-400"></i>
                            Fill Out Your Profile
                        </h4>
                        <p class="text-gray-400 leading-relaxed">Complete 7 simple steps about your job preferences, location, and experience.</p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="flex gap-6 relative">
                    <div class="flex flex-col items-center z-10">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-red-500 to-orange-600 text-white flex items-center justify-center font-bold text-lg flex-shrink-0 shadow-lg shadow-red-500/50">
                            2
                        </div>
                    </div>
                    <div class="flex-1 pb-8 bg-gradient-to-br from-slate-800 to-slate-900 rounded-lg p-6 border border-red-500/20 hover:border-red-500/50 hover:shadow-lg hover:shadow-red-500/20 transition-all">
                        <h4 class="text-lg font-bold text-white mb-2 flex items-center gap-2">
                            <i class="ri-settings-3-line text-red-400"></i>
                            Upload Your Resume
                        </h4>
                        <p class="text-gray-400 leading-relaxed">We'll parse your resume and extract skills using AI.</p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="flex gap-6 relative">
                    <div class="flex flex-col items-center z-10">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-red-500 to-orange-600 text-white flex items-center justify-center font-bold text-lg flex-shrink-0 shadow-lg shadow-red-500/50">
                            3
                        </div>
                    </div>
                    <div class="flex-1 pb-8 bg-gradient-to-br from-slate-800 to-slate-900 rounded-lg p-6 border border-red-500/20 hover:border-red-500/50 hover:shadow-lg hover:shadow-red-500/20 transition-all">
                        <h4 class="text-lg font-bold text-white mb-2 flex items-center gap-2">
                            <i class="ri-search-eye-line text-red-400"></i>
                            Get Job Matches
                        </h4>
                        <p class="text-gray-400 leading-relaxed">Receive 20+ personalized job matches from top companies.</p>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="flex gap-6 relative">
                    <div class="flex flex-col items-center z-10">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-green-500 to-emerald-600 text-white flex items-center justify-center font-bold text-xl flex-shrink-0 shadow-lg shadow-green-500/50">
                            ✓
                        </div>
                    </div>
                    <div class="flex-1 bg-gradient-to-br from-slate-800 to-slate-900 rounded-lg p-6 border border-green-500/20 hover:border-green-500/50 hover:shadow-lg hover:shadow-green-500/20 transition-all">
                        <h4 class="text-lg font-bold text-white mb-2 flex items-center gap-2">
                            <i class="ri-rocket-line text-green-400"></i>
                            Track & Apply
                        </h4>
                        <p class="text-gray-400 leading-relaxed">Apply directly and track your applications in one place.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Free Access Info Box -->
        <div class="bg-gradient-to-r from-red-600/10 to-orange-600/10 rounded-lg p-8 md:p-10 mb-12 border border-red-500/30 backdrop-blur-sm relative overflow-hidden">
            <div class="flex items-start gap-6 relative z-10">
                <div class="flex-shrink-0">
                    <div class="w-14 h-14 rounded-lg bg-gradient-to-br from-red-500 to-orange-600 flex items-center justify-center shadow-lg shadow-red-500/50">
                        <i class="ri-gift-2-line text-2xl text-white"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="text-2xl md:text-3xl font-bold text-white mb-3">14 Days Free Access</h3>
                    <p class="text-gray-300 text-lg leading-relaxed">Get 14 days of free access to view job matches and apply. After that, upgrade to Job Matches Plan ($4.99/week) to continue.</p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 mb-16">
            <form method="POST" action="{{ route('placement.create') }}" class="flex-1">
                @csrf
                <button type="submit" class="w-full px-10 py-4 bg-gradient-to-r from-red-500 to-orange-600 text-white font-bold rounded-lg hover:from-red-600 hover:to-orange-700 transition-all duration-300 flex items-center justify-center gap-3 text-lg shadow-lg shadow-red-500/50 hover:shadow-xl hover:shadow-red-500/70 hover:-translate-y-0.5">
                    <span>Start your job Placement Journey</span>
                    <i class="ri-arrow-right-line text-xl"></i>
                </button>
            </form>

            <a href="{{ route('user.dashboard') }}" class="px-10 py-4 bg-gradient-to-br from-slate-800 to-slate-900 text-white font-bold rounded-lg border-2 border-red-500/30 hover:border-red-500/60 hover:shadow-lg hover:shadow-red-500/20 transition-all duration-300 flex items-center justify-center gap-3 text-lg">
                <i class="ri-arrow-left-line text-xl"></i>
                <span>Back to Dashboard</span>
            </a>
        </div>

        <!-- FAQ Section -->
        <div class="mt-24">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-black text-white mb-4">Frequently Asked Questions</h2>
            </div>

            <div class="space-y-4">
                <!-- FAQ Item 1 -->
                <details class="group bg-gradient-to-br from-slate-800 to-slate-900 rounded-lg border border-red-500/20 hover:border-red-500/50 transition-all duration-300 hover:shadow-lg hover:shadow-red-500/20">
                    <summary class="flex items-center justify-between cursor-pointer p-6 font-bold text-white hover:text-red-400 transition-colors list-none">
                        <span class="flex items-center gap-3 text-lg">
                            <i class="ri-question-line text-red-400 text-xl"></i>
                            How does resume parsing work?
                        </span>
                        <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-red-500/10 flex items-center justify-center border border-red-500/30 group-open:bg-red-500/20">
                            <i class="ri-add-line text-xl text-red-400 group-open:hidden"></i>
                            <i class="ri-subtract-line text-xl text-red-400 hidden group-open:block"></i>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 pt-0 border-t border-red-500/20">
                        <p class="text-gray-400 leading-relaxed mt-4">Our system uses advanced AI to extract your skills, experience, education, and work history from your resume. This helps us match you with the most relevant jobs.</p>
                    </div>
                </details>

                <!-- FAQ Item 2 -->
                <details class="group bg-gradient-to-br from-slate-800 to-slate-900 rounded-lg border border-red-500/20 hover:border-red-500/50 transition-all duration-300 hover:shadow-lg hover:shadow-red-500/20">
                    <summary class="flex items-center justify-between cursor-pointer p-6 font-bold text-white hover:text-red-400 transition-colors list-none">
                        <span class="flex items-center gap-3 text-lg">
                            <i class="ri-question-line text-red-400 text-xl"></i>
                            Can I edit my profile after completing the wizard?
                        </span>
                        <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-red-500/10 flex items-center justify-center border border-red-500/30 group-open:bg-red-500/20">
                            <i class="ri-add-line text-xl text-red-400 group-open:hidden"></i>
                            <i class="ri-subtract-line text-xl text-red-400 hidden group-open:block"></i>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 pt-0 border-t border-red-500/20">
                        <p class="text-gray-400 leading-relaxed mt-4">Yes! You can start a new wizard session at any time to update your profile. Your previous data is saved for reference.</p>
                    </div>
                </details>

                <!-- FAQ Item 3 -->
                <details class="group bg-gradient-to-br from-slate-800 to-slate-900 rounded-lg border border-red-500/20 hover:border-red-500/50 transition-all duration-300 hover:shadow-lg hover:shadow-red-500/20">
                    <summary class="flex items-center justify-between cursor-pointer p-6 font-bold text-white hover:text-red-400 transition-colors list-none">
                        <span class="flex items-center gap-3 text-lg">
                            <i class="ri-question-line text-red-400 text-xl"></i>
                            What happens after the 14-day free trial?
                        </span>
                        <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-red-500/10 flex items-center justify-center border border-red-500/30 group-open:bg-red-500/20">
                            <i class="ri-add-line text-xl text-red-400 group-open:hidden"></i>
                            <i class="ri-subtract-line text-xl text-red-400 hidden group-open:block"></i>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 pt-0 border-t border-red-500/20">
                        <p class="text-gray-400 leading-relaxed mt-4">After 14 days, you'll need to subscribe to the Job Matches Plan ($4.99/week) to continue viewing and applying to jobs.</p>
                    </div>
                </details>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
<style>
    /* Remove default details marker */
    details summary::-webkit-details-marker {
        display: none;
    }

    /* Smooth animations */
    details[open] summary~* {
        animation: slideDown 0.3s ease-in-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endsection