@extends('front.layouts.app')

@section('title', 'Latest Job Trends in North America | HiredHeroAI')
@section('meta_description', 'Monthly job trends for job seekers and employers: most searched roles, hiring trends, and interview focus.')

@section('content')
<style>
  :root{
    --hh-bg: #0b1220;
    --hh-card: rgba(255,255,255,.06);
    --hh-card-border: rgba(255,255,255,.10);
    --hh-text: #e5e7eb;
    --hh-muted: #cbd5e1;
    --hh-soft: rgba(255,255,255,.08);
  }

  .hh-trends-wrap { color: var(--hh-text); }

  .hh-header {
    background: linear-gradient(180deg, rgba(59,130,246,.18), rgba(16,185,129,.08));
    border: 1px solid var(--hh-card-border);
    border-radius: 18px;
    padding: 38px;
  }

  .hh-header h2 { margin: 0; font-weight: 800; letter-spacing: -.2px; }
  .hh-header .sub { color: var(--hh-muted); margin-top: 8px; }

  .hh-trends-card{
    background: var(--hh-card);
    border: 1px solid var(--hh-card-border);
    border-radius: 18px;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
    box-shadow: 0 12px 30px rgba(0,0,0,.22);
  }

  .hh-card-img{
    width: 100%;
    height: 170px;
    object-fit: cover;
    display: block;
    filter: saturate(1.05) contrast(1.02);
  }

  .hh-card-body{
    padding: 18px 18px 16px 18px;
    display: flex;
    flex-direction: column;
    flex: 1;
  }

  .hh-badge{
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-weight: 700;
    font-size: 12px;
    padding: 8px 10px;
    border-radius: 999px;
    width: fit-content;
  }

  .hh-badge-job_seekers { background: rgba(59,130,246,.18); border: 1px solid rgba(59,130,246,.35); color: #dbeafe; }
  .hh-badge-employers   { background: rgba(56,189,248,.16); border: 1px solid rgba(56,189,248,.35); color: #cffafe; }
  .hh-badge-interview   { background: rgba(16,185,129,.16); border: 1px solid rgba(16,185,129,.35); color: #d1fae5; }
  .hh-badge-default     { background: rgba(148,163,184,.14); border: 1px solid rgba(148,163,184,.28); color: #e5e7eb; }

  .hh-trends-card h5{
    margin-top: 12px;
    margin-bottom: 10px;
    font-weight: 800;
    letter-spacing: -.2px;
    color: #fff;
  }

  .hh-summary{
    color: var(--hh-muted);
    line-height: 1.65;
    font-size: 14px;
  }

  .hh-summary p{ margin: 0 0 12px 0; }
  .hh-summary p:last-child{ margin-bottom: 0; }

  .hh-summary ul{
    margin: 0 0 12px 0;
    padding-left: 18px;
  }

  .hh-summary li{
    margin: 6px 0;
  }

  .hh-btn-outline {
    border: 1px solid rgba(148,163,184,.55);
    color: #e5e7eb;
    background: transparent;
  }
  .hh-btn-outline:hover {
    background: rgba(255,255,255,.08);
    color: #fff;
  }

  .hh-footer-row{
    display: flex;
    gap: 10px;
    margin-top: 14px;
  }

  .hh-disclaimer{
    margin-top: 18px;
    background: rgba(255,255,255,.05);
    border: 1px solid var(--hh-card-border);
    border-radius: 14px;
    padding: 14px 16px;
    color: var(--hh-muted);
  }

  .hh-brevo-wrap{
    max-width: 560px;
    margin: 22px auto 0 auto;
    background: rgba(255,255,255,.06);
    border: 1px solid var(--hh-card-border);
    border-radius: 16px;
    padding: 18px;
  }
  .hh-brevo-title{ color: #fff; font-weight: 800; font-size: 18px; margin-bottom: 6px; }
  .hh-brevo-sub{ color: var(--hh-muted); font-size: 14px; margin-bottom: 14px; }
</style>

@php
  // Use published_at if you have it; fallback to updated_at
  $lastUpdatedModel = $cards->max('published_at') ?? $cards->max('updated_at');
  $lastUpdated = $lastUpdatedModel ? \Carbon\Carbon::parse($lastUpdatedModel)->format('M j, Y') : null;

  // Map category => image (put these files in: public/assets/images/)
  $categoryImages = [
    'job_seekers' => asset('assets/images/Jobseeker-photo.png'),
    'employers'   => asset('assets/images/Employers-photo.png'),
    'interview'   => asset('assets/images/Interviews-Person.png'),
  ];

  /**
   * Render DB summary into nice HTML:
   * - Paragraphs
   * - Dash bullets (- item) or dot bullets (• item) => <ul><li>
   * - Blank lines separate paragraphs/blocks
   */
  $renderSummary = function (?string $text) {
    $text = trim((string) $text);
    if ($text === '') return '';

    $lines = preg_split("/\r\n|\n|\r/", $text);

    $html = '';
    $inList = false;
    $para = '';

    $flushPara = function() use (&$html, &$para, &$inList) {
      if ($para !== '') {
        if ($inList) { $html .= "</ul>"; $inList = false; }
        $html .= '<p>' . e(trim($para)) . '</p>';
        $para = '';
      }
    };

    $flushListClose = function() use (&$html, &$inList) {
      if ($inList) { $html .= "</ul>"; $inList = false; }
    };

    foreach ($lines as $raw) {
      $line = trim($raw);

      // Blank line => paragraph break / list break
      if ($line === '') {
        $flushPara();
        $flushListClose();
        continue;
      }

      // Bullet detection
      $isDash = str_starts_with($line, '- ');
      $isDot  = str_starts_with($line, '• ');
      if ($isDash || $isDot) {
        // close paragraph if we were building one
        $flushPara();

        if (!$inList) {
          $html .= '<ul>';
          $inList = true;
        }

        $item = trim(mb_substr($line, 2));
        $html .= '<li>' . e($item) . '</li>';
        continue;
      }

      // Normal text line:
      // If we were in a list and a normal line starts, end list and start paragraph.
      if ($inList) {
        $html .= "</ul>";
        $inList = false;
      }

      // Keep accumulating paragraph lines (so 2 lines becomes one paragraph)
      $para .= ($para === '' ? '' : ' ') . $line;
    }

    // flush any leftovers
    $flushPara();
    $flushListClose();

    return $html;
  };
@endphp

<div class="container py-5 hh-trends-wrap">

  <div class="hh-header mb-4">
    <h2>Latest Job Trends in North America</h2>
    <div class="sub">
      Updated Monthly
      @if($lastUpdated)
        <span class="ms-2">• Last updated {{ $lastUpdated }}</span>
      @endif
    </div>
  </div>

  <div class="row g-4">
    @forelse ($cards as $card)
      @php
        $category = $card->category ?? 'default';

        $badgeClass = match($category) {
          'job_seekers' => 'hh-badge-job_seekers',
          'employers'   => 'hh-badge-employers',
          'interview'   => 'hh-badge-interview',
          default       => 'hh-badge-default',
        };

        $isInterview = $category === 'interview';

        // Link rules:
        $url = $card->read_url ?: ($isInterview ? url('/mock/add-job-details') : '#');

        // Image:
        $img = $categoryImages[$category] ?? asset('assets/images/Jobseeker-photo.png');
      @endphp

      <div class="col-12 col-lg-4">
        <div class="hh-trends-card">

          <img class="hh-card-img" src="{{ $img }}" alt="{{ $card->badge_text ?? 'Trend' }}">

          <div class="hh-card-body">

            <span class="hh-badge {{ $badgeClass }}">
              {{ $card->badge_text }}
            </span>

            <h5>{{ $card->title }}</h5>

            <div class="hh-summary">
              {!! $renderSummary($card->summary) !!}
            </div>

            <div class="mt-auto pt-2">
              <a href="{{ $url }}"
                 class="btn w-100 {{ $isInterview ? 'btn-primary' : 'hh-btn-outline' }}">
                {{ $isInterview ? 'Practice Now' : 'Read' }}
              </a>
            </div>

          </div>
        </div>
      </div>
    @empty
      <div class="col-12">
        <div class="hh-disclaimer">
          <small>No trends have been published yet.</small>
        </div>
      </div>
    @endforelse
  </div>

  <div class="hh-disclaimer">
    <small>
      These updates are based on publicly available information and summaries. Trends can change quickly and some details may be incomplete.
      Please verify critical information independently before making career decisions.
    </small>
  </div>

  <div class="hh-brevo-wrap">
    <div class="hh-brevo-title">Get the Latest Job Trends</div>
    <div class="hh-brevo-sub">No spam. Unsubscribe anytime.</div>

    <a
      href="https://81e41aba.sibforms.com/serve/MUIFAPR5ekYe5uipLxwj5WRoZnJgIDmC_oCsFm5CTdjH4IT7vRhGlnqvIEcywdzWGQsXfE5jYDKFxcTjIoli3HsH-hxD8QTl5kD1mxwMW3NaVw6yQCoUcZoaicotjoLXdjoCpD_WYGTugU0laQZv7amn0jJb4JXFmqbKoIZgbhhc3G8XEw0qERfaVyKmuUbAl12T5bsGAaQZgZVEMg=="
      target="_blank"
      class="btn btn-primary w-100"
    >
      Subscribe to Job Trends
    </a>
  </div>

</div>
@endsection
