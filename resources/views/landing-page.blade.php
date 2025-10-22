<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUSTENA – Home</title>
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>
<body>
@php
    // ===== Dynamic values with safe fallbacks (do NOT clobber controller data) =====

    $level               = $level               ?? (int) (Auth::user()->level ?? 1);
    $xp_total            = $xp_total            ?? (int) (Auth::user()->xp_total ?? 0);
    $xp_to_next          = $xp_to_next          ?? null;                 // e.g., 530
    $xp_percent          = $xp_percent          ?? 0;                    // 0–100
    $streak_weeks        = $streak_weeks        ?? (int) (Auth::user()->streak_weeks ?? 0);
    $streak_days         = $streak_days         ?? null;                 // optional if you also track daily streaks
    $badges              = $badges              ?? [];
    $weekly_savings_kg   = $weekly_savings_kg   ?? 0.0;
    $energy_saved_kwh    = $energy_saved_kwh    ?? 0.0;

    // Rank pieces — prefer what the controller sent
    // If the controller passed $community_total, keep it; else fall back to *_users if provided
    $community_total     = $community_total      ?? ($community_total_users ?? null);

    // If controller passed $rank_text, keep it; else build it from $rank (if provided)
    if (!isset($rank_text)) {
        if (isset($rank)) {
            $rank_text = "#{$rank}";
        } else {
            $rank_text = "—";
        }
    }

    // If controller passed $rank_delta, keep it; else derive from $rank_change if we have it
    if (!isset($rank_delta)) {
        $rank_change = $rank_change ?? 0;
        $rank_delta  = ($rank_change === 0) ? "—"
                     : (($rank_change > 0 ? "↗️ +" : "↘️ ").abs($rank_change));
    }

    $daily_mission       = $daily_mission       ?? "Skip meat for lunch today!";
    $eco_tip             = $eco_tip             ?? "Turning off the tap while brushing saves ~6 L of water per minute.";

        $weekly_goal_count  = isset($weekly_goal_count)  ? (int) $weekly_goal_count  : 0;
    $weekly_goal_target = isset($weekly_goal_target) ? (int) $weekly_goal_target : 7;
    if ($weekly_goal_target < 1) { $weekly_goal_target = 7; } // avoid divide-by-zero

    // Derived
    $goal_percent = $weekly_goal_target > 0 ? min(100, round(($weekly_goal_count / $weekly_goal_target) * 100)) : 0;
    $badge_icons  = array_slice(array_map(fn($b) => $b['icon'] ?? '⭐', $badges), 0, 6); // show up to 6
    $streak_label = $streak_days !== null ? "{$streak_days}-Day" : "{$streak_weeks}-Week";

    // Compute day streak here only if controller didn’t send it
    if ($streak_days === null) {
        $uid = Auth::guard('web')->user()?->user_id ?? Auth::id();
        $streak_days = 0;
        if ($uid) {
            $last = DB::table('footprint_scores')->where('user_id', $uid)->max('created_at');
            if ($last) {
                $anchor = \Carbon\Carbon::parse($last)->startOfDay();
                for ($i = 0; $i < 365; $i++) {
                    $ds = (clone $anchor)->subDays($i);
                    $de = (clone $ds)->endOfDay();
                    $had = DB::table('footprint_scores')->where('user_id', $uid)
                        ->whereBetween('created_at', [$ds, $de])->exists();
                    if ($had) { $streak_days++; } else { break; }
                }
            }
        }
    }
@endphp

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-toggle" onclick="toggleSidebar()">☰</div>
    <div class="logo">
        <div class="logo-icon">🌱</div>
        <div class="logo-text">SUSTENA</div>
    </div>

    <a href="{{ url('/landing-page') }}" class="nav-item active">
        <div class="nav-icon">🏠</div>
        <span>Home</span>
    </a>
    <a href="{{ url('/footprint-calculator') }}" class="nav-item">
        <div class="nav-icon">👣</div>
        <span>Footprint Tracker</span>
    </a>
    <a href="{{ url('/learning-modules') }}" class="nav-item">
        <div class="nav-icon">📚</div>
        <span>Learn</span>
    </a>
    <a href="{{ url('/challenge') }}" class="nav-item">
        <div class="nav-icon">🏆</div>
        <span>Challenges</span>
    </a>
    <a href="{{ url('/forum') }}" class="nav-item">
        <div class="nav-icon">💬</div>
        <span>MicroForum</span>
    </a>
    <a href="{{ url('/profile') }}" class="nav-item">
        <div class="nav-icon">👤</div>
        <span>Profile</span>
    </a>
</div>

<div class="main-content">
    <div class="header">
        <div class="header-content">
            <div class="welcome-text">
                Welcome{{ Auth::check() ? ', '.e(Auth::user()->name) : '' }} to SUSTENA! Let’s shrink your footprint—one step at a time 🌱
            </div>
            <div class="forest-silhouette"></div>
        </div>
    </div>

    <!-- Dynamic mission & eco-tip -->
    <div class="mission-section">
        <div class="mission-header">
            <div class="mission-icon">🎯</div>
            <div class="mission-title">Daily Mission:</div>
        </div>
        <div class="mission-text">{{ $daily_mission }}</div>
    </div>

    <div class="eco-tip">
        <div class="eco-tip-header">
            <div class="eco-tip-icon">💧</div>
            <div class="mission-title">Eco tip:</div>
        </div>
        <div class="mission-text">{{ $eco_tip }}</div>
    </div>

{{-- === Streak (compact tile) === --}}
@php
  $uid = Auth::id();
  $current = (int) ($streak_days ?? 0);

  // milestone progress (tiny bar)
  $milestones = [1,3,7,14,30,60,100];
  sort($milestones);
  $next  = collect($milestones)->first(fn($m) => $m > $current);
  $prev  = collect(array_reverse($milestones))->first(fn($m) => $m <= $current) ?? 0;
  $pct   = $next ? (int) round(($current - $prev) / max(1, $next - $prev) * 100) : 100;

  // compact encouragement
  $msg = match (true) {
    $current === 0 => 'Start today 🔥',
    $current === 1 => 'Back tomorrow for 2 ✨',
    $current < 7   => 'Close to a week!',
    $current < 14  => 'Halfway to 2 weeks 💪',
    $current < 30  => '30-day badge in sight 🌟',
    default        => 'Keep the flame alive 🔥',
  };

  // last activity (small muted line)
  $lastAt = $uid
      ? \Illuminate\Support\Facades\DB::table('footprint_scores')
          ->where('user_id', $uid)->max('created_at')
      : null;
@endphp

    <div class="cards-grid">
        <!-- Streak -->
        <div class="card streak-card" title="Your current streak">
            <div class="card-icon">🔥</div>
            <div class="card-title">{{ $current }}</div>
            <div class="card-subtitle">Day Streak</div>

            <div class="mini-progress" @if(!$next) aria-hidden="true" @endif>
                <div class="mini-fill" style="width: {{ $pct }}%"></div>
            </div>

            <div class="tiny-note">
                @if($next)
                  Next: {{ $next }} • {{ max(0, $next - $current) }}
                  day{{ $next - $current === 1 ? '' : 's' }} to go
                @else
                  Milestones complete 🎉
                @endif
            </div>
            <div class="card-text one-line">{{ $msg }}</div>

            @if($lastAt)
              <div class="tiny-note faint">Last activity • {{ \Carbon\Carbon::parse($lastAt)->diffForHumans() }}</div>
            @endif
        </div>

@php
// Pull badges here only if controller didn't pass them
if (empty($badges)) {
    $uid = Auth::user()?->user_id ?? Auth::id();
    $rows = DB::table('user_badges as ub')
        ->join('badges as b','b.id','=','ub.badge_id')
        ->where('ub.user_id',$uid)
        ->orderByDesc('ub.awarded_at')
        ->get(['b.slug','b.name','b.category','b.points_reward','ub.awarded_at']);

    $iconByCat = [
      'energy'=>'⚡','water'=>'🌊','waste'=>'🗑️','carbon'=>'✅',
      'trees'=>'🌳','transport'=>'🚲','home'=>'🏠','meta'=>'🏅',
    ];

    $badges = $rows->map(function($r) use ($iconByCat){
        $cat = strtolower($r->category ?? '');
        return [
            'icon'       => $iconByCat[$cat] ?? '🥇',
            'name'       => $r->name,
            'slug'       => $r->slug,
            'points'     => (int) $r->points_reward,
            'awarded_at' => $r->awarded_at,
        ];
    })->values()->toArray();

    $badges_count = count($badges);
}
@endphp

@php
  $badges = is_array($badges) ? $badges : collect($badges)->values()->toArray();
  $badges_count = $badges_count ?? count($badges);
  $total_badges_available = $total_badges_available ?? ($badges_count ?: null);

  // Most recent first
  usort($badges, fn($a,$b) => strcmp($b['awarded_at'] ?? '', $a['awarded_at'] ?? ''));
  $topList = array_slice($badges, 0, 3);
  $moreNum = max(0, $badges_count - 6);
@endphp

        <!-- Badges (compact) -->
        <div class="card badges-card">
          <div class="card-icon">📊</div>
          <div class="card-title">Badges Earned</div>

          @if($badges_count > 0)
            <div class="badges-summary">
              <span class="count-pill">
                {{ $badges_count }}{{ $total_badges_available ? ' / '.$total_badges_available : '' }}
              </span>
              <span class="summary-note">Keep collecting to level up!</span>
            </div>

            <ul class="recent-badges">
              @foreach($topList as $b)
                <li class="recent-item">
                  <span class="recent-icon" aria-hidden="true">{{ $b['icon'] }}</span>
                  <span class="recent-text">
                    <strong>{{ $b['name'] }}</strong>
                    <span class="muted">+{{ (int)($b['points'] ?? 0) }} pts</span>
                    @if(!empty($b['awarded_at']))
                      <span class="muted">• {{ \Carbon\Carbon::parse($b['awarded_at'])->diffForHumans() }}</span>
                    @endif
                  </span>
                </li>
              @endforeach
            </ul>

            @if($moreNum > 0)
              <div class="tiny-note" style="opacity:.8;margin-top:8px;">
                +{{ $moreNum }} more — see full list
              </div>
            @endif
          @else
            <div class="card-text" style="opacity:.9;">
              No badges yet — complete a challenge to earn your first ✨
            </div>
            <div class="badge-ctas">
              <a class="primary-btn" href="{{ route('challenge') }}">Start a Challenge</a>
              <a class="view-all-btn" href="{{ route('badges') }}">See Available Badges</a>
            </div>
          @endif
        </div>

@php
    // Last two entries for this user (for the CO2 delta)
    $uid = Auth::guard('web')->user()?->user_id ?? Auth::id();
    $latestVal = null; $prevVal = null; $deltaPct = null; $deltaKg = null; $improved = null;

    if ($uid) {
        $two = DB::table('footprint_scores')
            ->where('user_id', $uid)
            ->whereNotNull('kg_per_week')
            ->orderByDesc('id')
            ->limit(2)
            ->pluck('kg_per_week')
            ->values(); // [0]=latest, [1]=prev

        if ($two->count() === 2) {
            $latestVal = (float) $two[0];
            $prevVal   = (float) $two[1];

            $deltaKg   = round($latestVal - $prevVal, 1);              // signed
            $deltaPct  = $prevVal != 0.0 ? round((($latestVal - $prevVal)/$prevVal)*100, 1) : 0.0;
            $improved  = ($latestVal < $prevVal);                      // lower = good
        }
    }

    $hasData   = is_numeric($deltaPct) && is_numeric($deltaKg);
    $pillClass = !$hasData ? 'neutral' : ($improved ? 'good' : 'bad');
    $arrow     = !$hasData ? '' : ($improved ? '↓' : '↑');

    $pctText = $hasData
        ? number_format(abs($deltaPct), 1) . '% ' . ($improved ? 'lower than last run' : 'higher than last run')
        : 'No previous run';

    $kgText = $hasData
        ? ($improved ? 'Saved ' : 'Up by ') . number_format(abs($deltaKg), 1) . ' kg'
        : '—';

    $trailText = $hasData
        ? 'Last: ' . number_format($prevVal, 1) . ' → Now: ' . number_format($latestVal, 1) . ' kg/wk'
        : '';
@endphp

        <!-- CO2 delta -->
        <div class="card co2-card" title="Change from your last run">
          <div class="card-icon">🌳</div>
          <div class="card-title">You've Saved</div>

          <div class="delta-pill {{ $pillClass }}">
            <span class="arrow">{{ $arrow }}</span>{{ $pctText }}
          </div>

          <div class="kg-delta" style="margin-top:8px;opacity:.9;">{{ $kgText }}</div>
          @if($trailText)
            <div class="tiny-note" style="margin-top:4px;opacity:.7;font-size:.9rem;">{{ $trailText }}</div>
          @endif
        </div>

        <!-- XP / Level progress -->
        <div class="card progress-card" title="XP towards your next level">
            <div class="card-icon">📊</div>
            <div class="card-title">Level {{ $level }}</div>
            <div class="card-subtitle">
                @if(!is_null($xp_to_next))
                    {{ number_format($xp_to_next) }} XP to next level
                @else
                    {{ number_format($xp_total) }} XP total
                @endif
            </div>
            <div class="card-text">Keep completing trackers & challenges</div>
            <div class="progress-bar">
                <div class="progress-fill" data-progress="{{ (int) $xp_percent }}"></div>
            </div>
        </div>

        <!-- Energy -->
        <div class="card energy-card" title="Estimated household energy saved this month">
            <div class="card-icon">⚡</div>
            <div class="card-title">Energy Saved</div>
            <div class="card-subtitle">{{ number_format(max(0,$energy_saved_kwh), 1) }} kWh</div>
            <div class="card-text">this month</div>
        </div>

       {{-- ===== Leaderboard: compute in Blade (compact, safe) ===== --}}
@php
    use Illuminate\Support\Facades\DB;

    // Current user + points column you rank by (change to xp_total if that’s your metric)
    $me         = Auth::user();
    $myPoints   = (int) ($me->points_total ?? $me->xp_total ?? 0);

    // Compute only if not already passed by controller
    if (!isset($community_total) || !isset($rank_text) || !isset($rank_percentile) || !isset($rank_delta)) {
        // Community size
        $community_total = (int) DB::table('users')->count();

        // Dense rank: strictly greater points + 1
        $rank_number = $community_total > 0
            ? ((int) DB::table('users')->where('points_total', '>', $myPoints)->count() + 1)
            : null;

        // Text form
        $rank_text = $rank_number ? "#{$rank_number}" : '—';

        // Percentile (Top X%) — higher is better
        $rank_percentile = $rank_number && $community_total > 0
            ? max(1, min(100, (int) round(100 * (1 - ($rank_number - 1) / max(1, $community_total)))))
            : null;

        // Movement delta (optional) if you track previous rank on users.last_rank
        $last_rank = $me->last_rank ?? null; // change to your column if different
        if (is_null($last_rank) || is_null($rank_number)) {
            $rank_delta = '—';
        } else {
            $diff = (int) $last_rank - (int) $rank_number; // positive = improved
            $rank_delta = $diff === 0 ? '—' : ($diff > 0 ? "↗️ +{$diff}" : "↘️ " . abs($diff));
        }
    }
@endphp

{{-- ===== Leaderboard card (compact) ===== --}}
<div class="card leaderboard-card" title="Your community rank">
  <div class="card-icon">👥</div>

  <!-- Big rank number -->
  <div class="card-title">{{ $rank_text }}</div>

  <!-- “of N users” -->
  <div class="card-subtitle">
    @if(!empty($community_total))
      of {{ number_format($community_total) }}
    @else
      —
    @endif
  </div>

  <!-- Friendly line + optional Top % -->
  <div class="card-text">
    @if(isset($rank_percentile))
      Top {{ (int) $rank_percentile }}% in the community
    @else
      Keep climbing!
    @endif
  </div>

  <!-- Tiny percentile bar (higher is better) -->
  @if(isset($rank_percentile))
    <div class="progress-bar" style="margin-top:6px;">
      <div class="progress-fill" data-progress="{{ (int) $rank_percentile }}"></div>
    </div>
  @endif

  <!-- Movement chip -->
  <div class="rank-change">{{ $rank_delta ?? '—' }}</div>
<br>
  <!-- CTA -->
  <a href="{{ route('leaderboard') }}" class="view-all-btn" style="margin-top:10px;">View Leaderboard</a>
</div>

        <!-- Weekly goal -->
        <div class="card weekly-goal-card" title="Complete sustainable actions this week">
    <div class="card-icon">🎯</div>
    <div class="card-title">Weekly Goal</div>
    <div class="card-subtitle">{{ $weekly_goal_count }}/{{ $weekly_goal_target }} days</div>
    <div class="card-text">sustainable actions</div>
    <div class="progress-bar">
        <div class="progress-fill" data-progress="{{ (int) $goal_percent }}"></div>
    </div>
</div>

    </div>
</div>

<!-- Floating quick links -->
<div class="floating-icons">
    <a href="{{ route('analytics') }}" class="floating-icon" title="Analytics">🔥</a>
    <a href="{{ route('learning-modules') }}" class="floating-icon" title="Learning Modules">🌱</a>
    <a href="{{ route('leaderboard') }}" class="floating-icon" title="Leaderboard">🏆</a>
    <a href="{{ route('badges') }}" class="floating-icon" title="Badges">🥇</a>
    <a href="{{ route('settings') }}" class="floating-icon" title="Settings">⚙️</a>
</div>

<script>
    // Sidebar active state + click animation (unchanged)
    document.querySelectorAll('.nav-item').forEach(item => {
        item.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href === '#' || href === '') e.preventDefault();
            document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
            this.classList.add('active');
        });
    });

    document.querySelectorAll('.card').forEach(card => {
        card.addEventListener('click', function() {
            this.style.transform = 'scale(0.95)';
            setTimeout(() => { this.style.transform = 'translateY(-4px)'; }, 150);
        });
    });

    document.querySelectorAll('.floating-icon').forEach((icon, index) => {
        icon.style.animationDelay = `${index * 0.2}s`;
        icon.addEventListener('click', function() {
            this.style.transform = 'scale(1.2) rotate(360deg)';
            setTimeout(() => { this.style.transform = 'scale(1.1)'; }, 300);
        });
    });

    // Progress bars from data-progress
    function initProgressBars() {
        document.querySelectorAll('.progress-fill').forEach(fill => {
            const pct = parseInt(fill.getAttribute('data-progress') || '0', 10);
            setTimeout(() => { fill.style.width = Math.max(0, Math.min(100, pct)) + '%'; }, 300);
        });
    }
    window.addEventListener('load', initProgressBars);

    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('.main-content');
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('expanded');
    }
</script>
</body>
</html>
