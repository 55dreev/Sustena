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
    // ===== Dynamic values with safe fallbacks =====
    // Expect these to be passed by your HomeController:
    // $level, $xp_total, $xp_to_next, $xp_percent, $streak_weeks, $streak_days,
    // $badges (array of ['icon' => '🌿', 'name' => 'Green Starter']), $weekly_savings_kg,
    // $energy_saved_kwh, $rank, $rank_change, $weekly_goal_count, $weekly_goal_target,
    // $community_total_users, $daily_mission (string), $eco_tip (string)

    $level               = $level               ?? (int) (Auth::user()->level ?? 1);
    $xp_total            = $xp_total            ?? (int) (Auth::user()->xp_total ?? 0);
    $xp_to_next          = $xp_to_next          ?? null;                 // e.g., 530
    $xp_percent          = $xp_percent          ?? 0;                    // 0–100
    $streak_weeks        = $streak_weeks        ?? (int) (Auth::user()->streak_weeks ?? 0);
    $streak_days         = $streak_days         ?? null;                 // optional if you also track daily streaks
    $badges              = $badges              ?? [];                   // [['icon'=>'🌿','name'=>'Green Starter'], ...]
    $weekly_savings_kg   = $weekly_savings_kg   ?? 0.0;                  // latest official vs previous official
    $energy_saved_kwh    = $energy_saved_kwh    ?? 0.0;                  // month-to-date
    $rank                = $rank                ?? null;                 // integer like 47
    $rank_change         = $rank_change         ?? 0;                    // +3 / -2 number
    $weekly_goal_count   = $weekly_goal_count   ?? 0;                    // days completed
    $weekly_goal_target  = $weekly_goal_target  ?? 7;                    // default weekly target
    $community_total     = $community_total_users ?? null;               // total users
    $daily_mission       = $daily_mission       ?? "Skip meat for lunch today!";
    $eco_tip             = $eco_tip             ?? "Turning off the tap while brushing saves ~6 L of water per minute.";

    // Derived
    $goal_percent = $weekly_goal_target > 0 ? min(100, round(($weekly_goal_count / $weekly_goal_target) * 100)) : 0;
    $rank_text    = $rank ? "#{$rank}" : "—";
    $rank_delta   = ($rank_change === 0) ? "—" : (($rank_change > 0 ? "↗️ +" : "↘️ ").abs($rank_change));
    $badge_icons  = array_slice(array_map(fn($b) => $b['icon'] ?? '⭐', $badges), 0, 6); // show up to 6
    $streak_label = $streak_days !== null ? "{$streak_days}-Day" : "{$streak_weeks}-Week";
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

    <div class="cards-grid">
        <!-- Streak (shows Day streak if provided; else Week streak from XP system) -->
        <div class="card streak-card" title="Your current streak">
            <div class="card-icon">🔥</div>
            <div class="card-title">{{ $streak_label }}</div>
            <div class="card-subtitle">Streak</div>
            <div class="card-text">{{ $streak_days !== null ? 'Keep it up!' : 'Consecutive official weeks' }}</div>
        </div>

        <!-- Badges -->
        <div class="card badges-card">
            <div class="card-title">Badges Earned</div>

            @if (count($badge_icons))
                <div class="badge-grid">
                    @foreach ($badge_icons as $icon)
                        <div class="badge">{{ $icon }}</div>
                    @endforeach
                </div>
            @else
                <div class="card-text" style="opacity:.8;">No badges yet — complete challenges to earn your first! ✨</div>
            @endif
            <br>
            <a class="view-all-btn" href="{{ route('badges') }}">View All</a>
        </div>

@php
    // Last two entries for this user
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

    // Human-readable labels
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

        <!-- Water / Energy -->
        <div class="card energy-card" title="Estimated household energy saved this month">
            <div class="card-icon">⚡</div>
            <div class="card-title">Energy Saved</div>
            <div class="card-subtitle">{{ number_format(max(0,$energy_saved_kwh), 1) }} kWh</div>
            <div class="card-text">this month</div>
        </div>

        <!-- Leaderboard -->
        <div class="card leaderboard-card" title="Your community rank">
            <div class="card-icon">👥</div>
            <div class="card-title">Community Rank</div>
            <div class="card-subtitle">
                {{ $rank_text }}
                @if($community_total) <span style="opacity:.7;">of {{ number_format($community_total) }}</span>@endif
            </div>
            <div class="card-text">Keep climbing!</div>
            <div class="rank-change">{{ $rank_delta }}</div>
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
            // apply after a tick for simple transition effect
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
