@extends('front.layouts.app')

@section('title', 'Resources for Job Seekers')

@section('css')
<style>
    .hh-hero {
        background: radial-gradient(circle at top left, var(--brand-blue, #00A3FF), #0b0f16 45%, #000000 100%);
        color: #ffffff;
        padding: 72px 0 48px;
    }
    .hh-hero-eyebrow {
        text-transform: uppercase;
        letter-spacing: .12em;
        font-size: .75rem;
        color: #9ca3af;
    }
    .hh-hero-title {
        font-size: clamp(2rem, 3vw, 2.5rem);
        font-weight: 700;
        margin-bottom: .75rem;
    }
    .hh-hero-subtitle {
        max-width: 640px;
        color: #e5e7eb;
        opacity: 1;
    }
    .hh-pill {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        border-radius: 999px;
        padding: .3rem .9rem;
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.16);
        font-size: .8rem;
    }

    .hh-section {
        background: var(--light-bg, #f7f9fc);
        padding: 48px 0 56px;
    }
    .hh-softtext {
        color: #4b5563;
    }

    .hh-card {
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid #e2e6f0;
        padding: 20px 20px 18px;
        height: 100%;
        box-shadow: 0 16px 40px rgba(15, 23, 42, .06);
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .hh-card-eyebrow {
        text-transform: uppercase;
        letter-spacing: .13em;
        font-size: .7rem;
        color: #64748b;
        font-weight: 600;
    }
    .hh-card-title {
        font-size: 1.05rem;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 4px;
    }
    .hh-card-body {
        font-size: .9rem;
        color: #374151;
        flex-grow: 1;
    }

    .hh-chip-row {
        display: flex;
        flex-wrap: wrap;
        gap: .4rem;
        margin-top: 4px;
    }
    .hh-chip {
        border-radius: 999px;
        padding: .15rem .6rem;
        font-size: .72rem;
        background: #eff6ff;
        color: #1d4ed8;
    }

    .hh-cta-row {
        display: flex;
        flex-wrap: wrap;
        gap: .5rem;
        margin-top: 4px;
    }

    .hh-prompt-block {
        background: #f9fafb;
        border-radius: 12px;
        padding: 10px 11px;
        font-size: .78rem;
        border: 1px dashed #d1d5db;
        margin-top: 4px;
        max-height: none;
        overflow: visible;
    }
    .hh-prompt-label {
        font-size: .72rem;
        text-transform: uppercase;
        letter-spacing: .14em;
        color: #6b7280;
        margin-bottom: 4px;
        font-weight: 600;
    }
    .hh-prompt-text {
        white-space: pre-wrap;
        font-size: .82rem;
        line-height: 1.4;
        color: #374151;
        margin: 0;
    }
    .hh-prompt-actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: .5rem;
        margin-top: .4rem;
    }
    .copy-prompt-btn {
        font-size: .75rem;
        padding: .2rem .6rem;
    }
    
    #individuals-tools-tabs .nav-link {
    color: #4b5563 !important; /* gray-600 */
    font-weight: 500;
    opacity: 1;
    }
#individuals-tools-tabs .nav-link.active {
    color: #ffffff !important; /* gray-900 */
    background: #4A67B3 !important; /* subtle light brand-ish */
    }

</style>
@endsection

@section('content')
    {{-- Hero --}}
    <section class="hh-hero">
        <div class="container">
            <div class="row align-items-center gy-3">
                <div class="col-lg-7">
                    <div class="hh-hero-eyebrow">For Individuals</div>
                    <h1 class="hh-hero-title">
                        Job search tools that move you forward, not just “teach you.”
                    </h1>
                    <p class="hh-hero-subtitle">
                        Use ready-made prompts, templates, and interview practice flows so you can
                        apply faster, follow up better, and feel prepared when opportunities show up.
                    </p>

                    <div class="d-flex flex-wrap gap-2 mt-3">
                        <div class="hh-pill">
                            <i class="fas fa-bolt"></i> Conversion-first playbooks
                        </div>
                        <div class="hh-pill">
                            <i class="fas fa-user-check"></i> Built for real-world job search
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 text-lg-end">
                    <a href="https://81e41aba.sibforms.com/serve/MUIFAPR5ekYe5uipLxwj5WRoZnJgIDmC_oCsFm5CTdjH4IT7vRhGlnqvIEcywdzWGQsXfE5jYDKFxcTjIoli3HsH-hxD8QTl5kD1mxwMW3NaVw6yQCoUcZoaicotjoLXdjoCpD_WYGTugU0laQZv7amn0jJb4JXFmqbKoIZgbhhc3G8XEw0qERfaVyKmuUbAl12T5bsGAaQZgZVEMg=="
                    target="_blank"
                    rel="noopener"
                    class="btn btn-primary btn-lg mb-2">
                        Subscribe for job search tips
                        </a>

                    <div class="hh-softtext small">
                        Start with a prompt, finish with an application or interview.
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Tools section with tabs --}}
    <section class="hh-section" id="tools">
        <div class="container">
            <div class="mb-3">
                <h2 class="h5 mb-1">Job search tools</h2>
                <p class="hh-softtext mb-0" style="font-size:.88rem;">
                    Pick what you’re working on right now – discovering roles, applying, or following up.
                </p>
            </div>

            {{-- Internal sub-nav (tabs) --}}
            <ul class="nav nav-pills mb-3" id="individuals-tools-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active"
                            id="tab-trends-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#tab-trends"
                            type="button"
                            role="tab"
                            aria-controls="tab-trends"
                            aria-selected="true">
                        Job Search Trends
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link"
                            id="tab-cover-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#tab-cover"
                            type="button"
                            role="tab"
                            aria-controls="tab-cover"
                            aria-selected="false">
                        Cover Letter Prompts
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link"
                            id="tab-followup-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#tab-followup"
                            type="button"
                            role="tab"
                            aria-controls="tab-followup"
                            aria-selected="false">
                        Follow-Up & Outreach
                    </button>
                </li>
            </ul>

            {{-- Tab content --}}
            <div class="tab-content" id="individuals-tools-tabsContent">

                {{-- Tab 1: Job Search Trends --}}
                <div class="tab-pane fade show active"
                     id="tab-trends"
                     role="tabpanel"
                     aria-labelledby="tab-trends-tab">
                    <div class="hh-card mt-2">
                        <div class="hh-card-eyebrow">Discover</div>
                        <div class="hh-card-title">Job Search Trends & Where Roles Are Moving</div>
                        <div class="hh-card-body">
                            See how hiring is shifting by role and sector so you don’t waste time chasing
                            dead-end postings. Use this to pick where to focus applications and prep.
                            <div class="hh-chip-row">
                                <span class="hh-chip">Market shifts</span>
                                <span class="hh-chip">High-demand roles</span>
                                <span class="hh-chip">Hybrid & remote</span>
                            </div>
                        </div>

                        <div class="hh-prompt-block">
                            <div class="hh-prompt-label">Use this with ChatGPT</div>
                            <pre class="hh-prompt-text">
Act as a career coach. Based on current trends, what roles are in demand for someone with experience in [your field] who wants [remote / hybrid / in-person] work? Give 5 role ideas and why each is realistic for me. Keep it short
                            </pre>
                            <div class="hh-prompt-actions">
                                <button type="button"
                                        class="copy-prompt-btn btn btn-sm btn-outline-secondary"
                                        data-prompt="Act as a career coach. Based on current trends, what roles are in demand for someone with experience in [your field] who wants [remote / hybrid / in-person] work? Give 5 role ideas and why each is realistic for me.">
                                    Copy prompt
                                </button>
                                <a href="https://chat.openai.com/"
                                   target="_blank"
                                   rel="noopener"
                                   class="small text-primary">
                                    Open in ChatGPT →
                                </a>
                            </div>
                        </div>

                        <div class="hh-cta-row">
    <a href="{{ route('user.register') }}"
       class="btn btn-sm btn-primary mt-2">
        Practice interview for a target role
    </a>

    <a href="{{ route('trends.index') }}"
       class="btn btn-sm btn-link mt-2 px-0 text-primary fw-semibold">
        See latest job market trend posts →
    </a>
</div>
                    </div>
                </div>

                {{-- Tab 2: Cover Letter Prompt Pack --}}
                <div class="tab-pane fade"
                     id="tab-cover"
                     role="tabpanel"
                     aria-labelledby="tab-cover-tab">
                    <div class="hh-card mt-2">
                        <div class="hh-card-eyebrow">Apply</div>
                        <div class="hh-card-title">Cover Letter Prompt Pack (Paste & Personalize)</div>
                        <div class="hh-card-body">
                            Stop staring at a blank page. Use these structured prompts to generate a draft in
                            minutes, then tweak it to sound like you.
                            <div class="hh-chip-row">
                                <span class="hh-chip">Entry-level</span>
                                <span class="hh-chip">Career switch</span>
                                <span class="hh-chip">Internal move</span>
                            </div>
                        </div>

                        <div class="hh-prompt-block">
                            <div class="hh-prompt-label">General prompt</div>
                            <pre class="hh-prompt-text">
You are an expert in writing human, non-generic cover letters. Write a cover letter for the following role using my resume and the job posting. Keep it under 400 words. Ask me any missing questions first, then show me the letter:
1) My resume: [paste here]
2) Job posting: [paste here]
                            </pre>
                            <div class="hh-prompt-actions">
                                <button type="button"
                                        class="copy-prompt-btn btn btn-sm btn-outline-secondary"
                                        data-prompt="You are an expert in writing human, non-generic cover letters. Write a cover letter for the following role using my resume and the job posting. Keep it under 400 words. Ask me any missing questions first, then show me the letter:
1) My resume: [paste here]
2) Job posting: [paste here]">
                                    Copy prompt
                                </button>
                                <a href="https://chat.openai.com/"
                                   target="_blank"
                                   rel="noopener"
                                   class="small text-primary">
                                    Open in ChatGPT →
                                </a>
                            </div>
                        </div>

                        <div class="hh-cta-row">
                            <a href="#"
                               class="btn btn-sm btn-primary mt-2" disabled>
                                Upload resume for feedback (Coming Soon)
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Tab 3: Follow-Up Outreach Prompt Pack --}}
                <div class="tab-pane fade"
                     id="tab-followup"
                     role="tabpanel"
                     aria-labelledby="tab-followup-tab">
                    <div class="hh-card mt-2">
                        <div class="hh-card-eyebrow">Follow up</div>
                        <div class="hh-card-title">Follow-Up & Outreach Prompts</div>
                        <div class="hh-card-body">
                            Use simple templates for post-interview follow-up, checking in on applications, and
                            reconnecting with recruiters without sounding needy.
                            <div class="hh-chip-row">
                                <span class="hh-chip">After interview</span>
                                <span class="hh-chip">Application follow-up</span>
                                <span class="hh-chip">Networking</span>
                            </div>
                        </div>

                        <div class="hh-prompt-block">
                            <div class="hh-prompt-label">After an interview</div>
                            <pre class="hh-prompt-text">
Help me write a short thank-you email after an interview. I want to:
1) Thank them for their time,
2) Mention 1–2 specific things we discussed,
3) Re-confirm my interest,
4) Stay under 160 words.
Here are the details: [what you talked about]
                            </pre>
                            <div class="hh-prompt-actions">
                                <button type="button"
                                        class="copy-prompt-btn btn btn-sm btn-outline-secondary"
                                        data-prompt="Help me write a short thank-you email after an interview. I want to:
1) Thank them for their time,
2) Mention 1–2 specific things we discussed,
3) Re-confirm my interest,
4) Stay under 160 words.
Here are the details: [what you talked about]">
                                    Copy prompt
                                </button>
                                <a href="https://chat.openai.com/"
                                   target="_blank"
                                   rel="noopener"
                                   class="small text-primary">
                                    Open in ChatGPT →
                                </a>
                            </div>
                        </div>

                        <div class="hh-cta-row">
                            <a href="{{ route('user.register') }}"
                               class="btn btn-sm btn-primary mt-2">
                                Practice likely interview questions
                            </a>
                        </div>
                    </div>
                </div>

            </div> {{-- /.tab-content --}}
        </div>
    </section>
@endsection

@push('js')
<script>
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.copy-prompt-btn');
        if (!btn) return;

        const text = btn.getAttribute('data-prompt') || '';
        if (!text) return;

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(function () {
                const original = btn.innerText;
                btn.innerText = 'Copied!';
                btn.disabled = true;
                setTimeout(function () {
                    btn.innerText = original;
                    btn.disabled = false;
                }, 1200);
            }).catch(function () {
                alert('Could not copy. Please copy manually.');
            });
        } else {
            // Fallback for older browsers
            const textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.style.position = 'fixed';
            textarea.style.opacity = '0';
            document.body.appendChild(textarea);
            textarea.select();
            try {
                document.execCommand('copy');
            } catch (err) {
                alert('Could not copy. Please copy manually.');
            }
            document.body.removeChild(textarea);
        }
    });
</script>
@endpush
