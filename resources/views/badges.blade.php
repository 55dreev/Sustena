<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>SUSTENA - Achievements & Badges</title>
  <link rel="stylesheet" href="{{ asset('css/badges.css') }}">
  <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">

</head>
<body class="badges-page">
    <div class="mobile-hamburger" onclick="toggleMobileSidebar()">☰</div>

<div class="sidebar" id="sidebar">
   
    <div class="logo">
        <div class="logo-icon">🌱</div>
        <div class="logo-text">SUSTENA</div>
    </div>
    <a href="{{ url('/landing-page') }}" class="nav-item">
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



  <!-- Main Content -->
  <div class="main-content">
    <!-- Top Navigation Bar -->
  <div class="floating-icons">
      <a href="{{ url('/analytics') }}" class="floating-icon" title="Analytics">🔥</a>
      <a href="{{ url('/learning-modules') }}" class="floating-icon" title="Learning Modules">🌱</a>
      <a href="{{ url('/leaderboard') }}" class="floating-icon" title="Leaderboard">🏆</a>
      <a href="{{ url('/badges') }}" class="floating-icon" title="Badges">🥇</a>
      <a href="{{ url('/settings') }}" class="floating-icon" title="Settings">⚙️</a>
    </div>
    <div class="header">
      <div class="welcome-text">Your sustainability achievements and environmental impact badges! 🌍</div>
    </div>

    <!-- Current Badge and Profile Section -->
    @php
      use Illuminate\Support\Facades\DB;
      use Carbon\Carbon;

      // Prefer live values from users table for name/level/streak
      $user = auth()->user();
      $uid  = $user?->user_id ?? $user?->id;

      $displayName = $user->name
        ?? ($user->username ?? 'EcoSaver');

      // Always reflect the user's original/current level from DB
      $levelNum = (int) ($user->level ?? ($level ?? 1));

      // Points still come from the variable, fall back to DB column if present
      $points = isset($pointsTotal) ? (int)$pointsTotal : (int)($user->points_total ?? 0);

      // Compute day streak if not stored on users.streak_days
      $streakDays = $user->streak_days ?? null;

      if ($uid && is_null($streakDays)) {
          // Find the most recent day with any activity (official or practice)
          $lastActivity = DB::table('footprint_scores')
              ->where('user_id', $uid)
              ->max('created_at');

          $streakDays = 0;
          if ($lastActivity) {
              $anchor = Carbon::parse($lastActivity)->startOfDay();

              // Count consecutive days backward from the anchor day
              for ($i = 0; $i < 365; $i++) {
                  $ds = (clone $anchor)->subDays($i);
                  $de = (clone $ds)->endOfDay();

                  $had = DB::table('footprint_scores')
                      ->where('user_id', $uid)
                      ->whereBetween('created_at', [$ds, $de])
                      ->exists();

                  if ($had) { $streakDays++; } else { break; }
              }
          }
      }

      // Fallback to 0 if still null
      $streakDays = (int)($streakDays ?? 0);

      // Badges context (unchanged)
      $lastEarned = $earned->first();
      $catIcon = function($cat){
        switch (strtolower($cat ?? '')) {
          case 'energy': return '⚡';
          case 'water': return '🌊';
          case 'waste': return '🗑️';
          case 'carbon': return '✅';
          case 'trees': return '🌳';
          case 'transport': return '🚲';
          case 'home': return '🏠';
          default: return '🥇';
        }
      };
    @endphp

    <div class="current-badge-section">
      <div class="badge-display">
        <div class="badge-circle">
          <div class="badge-icon">{{ $lastEarned ? $catIcon($lastEarned->category) : '🥇' }}</div>
        </div>
        <div class="badge-text">{{ $lastEarned ? $lastEarned->name : 'No badge yet' }}</div>
        <div class="progress-points">{{ number_format($points) }} points total</div>
      </div>

      <div class="user-profile-section">
        <div class="profile-header">
          <div class="profile-avatar">👤</div>
          <div class="profile-info">
            <h3>{{ $displayName }}</h3>
            <div class="profile-title">Environmental Champion</div>
          </div>
        </div>
        <div class="stats-row">
          <div class="stat-item">
            <div class="stat-icon">🏅</div>
            <div class="stat-label">{{ $earned->count() }} / {{ $all->count() }} badges</div>
          </div>
          <div class="stat-item">
            <div class="stat-icon">🌸</div>
            <div class="stat-label">Lv {{ $levelNum }}</div>
          </div>
          <div class="stat-item">
            <div class="stat-icon">🔥</div>
            <div class="stat-label">{{ $streakDays }} Day Streak</div>
          </div>
        </div>
      </div>
    </div>
<br>
    <!-- Achievements Section -->
    <div class="achievements-section">
      <h2 class="section-header">Environmental Achievements</h2>

      @php
        // Map DB categories -> CSS class for colored circle
        $cssByCat = [
          'energy'    => 'energy-saver',
          'water'     => 'water-warrior',
          'waste'     => 'waste-reducer',
          'carbon'    => 'carbon-neutral',
          'trees'     => 'tree-planter',
          'transport' => 'eco-transport',
          'home'      => 'green-home',
          'meta'      => 'earth-guardian',
        ];
      @endphp

      <div class="achievements-grid">
        @foreach($all as $b)
          @php
            $hasIt   = in_array($b->slug, $earnedSlugs, true);
            $css     = $cssByCat[strtolower($b->category)] ?? '';
            $awarded = $hasIt ? optional($earned->firstWhere('slug', $b->slug))->awarded_at : null;
          @endphp

          <div class="achievement-badge {{ $css }} {{ $hasIt ? '' : 'locked' }}"
               title="{{ $hasIt && $awarded ? 'Earned on '.$awarded : 'Not earned yet' }}">
            <div class="achievement-icon-wrapper">
              <div class="achievement-icon">
                {{ $catIcon($b->category) }}
              </div>
            </div>
            <div class="achievement-name">{{ $b->name }}</div>
            <div class="achievement-status">
              {{ $hasIt ? 'Unlocked' : 'Locked' }}
              <span style="display:block;opacity:.7;">+{{ (int)$b->points_reward }} pts</span>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>

  <script>
    // Toggle sidebar
  function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    sidebar.classList.toggle('collapsed');
    mainContent.classList.toggle('expanded');
  }
    // Nav active
    document.querySelectorAll('.nav-item').forEach(el=>{
      el.addEventListener('click',function(){
        document.querySelectorAll('.nav-item').forEach(n=>n.classList.remove('active'));
        this.classList.add('active');
      });
    });

    // Interactions
    document.querySelectorAll('.floating-icon').forEach((icon, i)=>{
      icon.style.animationDelay = `${i*0.1}s`;
      icon.addEventListener('click', function(){
        this.style.transform = 'scale(1.2) rotate(360deg)';
        setTimeout(()=>{ this.style.transform = 'scale(1.1)'; }, 400);
      });
    });

    const badgeDisplay = document.querySelector('.badge-display');
    if (badgeDisplay){
      badgeDisplay.addEventListener('mouseenter', function(){
        this.querySelector('.badge-circle').style.transform = 'scale(1.1) rotate(5deg)';
      });
      badgeDisplay.addEventListener('mouseleave', function(){
        this.querySelector('.badge-circle').style.transform = 'scale(1) rotate(0deg)';
      });
    }

    // Entrance animation
    const observer = new IntersectionObserver((entries)=>{
      entries.forEach(entry=>{
        if(entry.isIntersecting){
          entry.target.style.opacity='1';
          entry.target.style.transform='translateY(0)';
        }
      });
    });
    document.querySelectorAll('.achievement-badge').forEach((badge, idx)=>{
      badge.style.opacity='0';
      badge.style.transform='translateY(20px)';
      badge.style.transition=`all 0.5s ease ${idx*0.1}s`;
      observer.observe(badge);
    });

    // Click animation (only if unlocked)
    document.querySelectorAll('.achievement-badge').forEach(b=>{
      b.addEventListener('click', function(){
        if(!this.classList.contains('locked')){
          this.style.transform='translateY(-8px) scale(1.05)';
          setTimeout(()=>{ this.style.transform='translateY(-5px)'; }, 200);
        }
      });
    });
    function toggleMobileSidebar() {
    document.querySelector('.sidebar').classList.toggle('open');
}
  </script>
</body>
</html>
