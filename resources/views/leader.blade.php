<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUSTENA - Leaderboards</title>
 <link rel="stylesheet" href="{{ asset('css/leader.css') }}">
 <link rel="stylesheet" href="{{ asset('css/leaderresponsive.css') }}">
</head>
<body>
 <div class="sidebar" id="sidebar">
    <div class="sidebar-toggle" onclick="toggleSidebar()">☰</div>
    <div class="logo">
        <div class="logo-icon">🌱</div>
        <div class="logo-text">SUSTENA</div>
    </div>
    <a href="{{ url('/landing-page') }}" class="nav-item">
        <div class="nav-icon">🏠</div>
        <span>Home</span>
    </a>
    <a href="{{ url('/footprint-calculator') }}" class="nav-item active">
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

  @php
    // Expecting $leaders from the controller: id, name, points_total, xp_total, level
    $leaders = collect($leaders ?? []);
    $top3    = $leaders->take(3)->values();
    $rest    = $leaders->slice(3)->values();
    function rankBadgeClass($rank) {
        return $rank === 1 ? 'gold' : ($rank === 2 ? 'silver' : ($rank === 3 ? 'bronze' : ''));
    }
  @endphp

  <div class="main-content">
    <div class="header">
      <div class="header-content">
        <div class="trophy-icon">🏆</div>
        <div class="welcome-text">Compete with eco-warriors worldwide and climb the sustainability rankings! 🌍</div>
      </div>
    </div>

    <h1 class="leaderboard-title">LEADERBOARDS</h1>

    <div class="leaderboard-container">
      {{-- Podium (Top 3) --}}
      <div class="podium-section">
        @for ($i = 0; $i < 3; $i++)
          @php
            $user = $top3->get($i);
            $rank = $i + 1;
          @endphp
          @if($user)
            <div class="podium-entry {{ $rank === 1 ? 'first' : ($rank === 2 ? 'second' : 'third') }}">
              <div class="podium-rank {{ $rank === 1 ? 'first' : ($rank === 2 ? 'second' : 'third') }}">{{ $rank }}</div>
              <div class="podium-name">{{ $user->display_name }}</div>
              <div class="podium-score">{{ number_format((int)($user->points_total ?? 0)) }} 🌱</div>
            </div>
          @endif
        @endfor

        @if($top3->isEmpty())
          <div class="podium-entry">
            <div class="podium-name">No rankings yet</div>
            <div class="podium-score">0 🌱</div>
          </div>
        @endif
      </div>

      {{-- Full list --}}
      @foreach($leaders as $index => $user)
        @php
          $rank = $index + 1;
          $badge = rankBadgeClass($rank);
        @endphp
        <div class="leaderboard-entry">
          <div class="rank-number {{ $badge }}">{{ $rank }}</div>
          <div class="user-avatar">👤</div>
          <div class="user-info">
            <div class="user-name">{{ $user->display_name }}</div>
          </div>
          <div class="user-score">
            {{ number_format((int)($user->points_total ?? 0)) }} <span class="score-icon">🌱</span>
          </div>
        </div>
      @endforeach

      @if($leaders->isEmpty())
        <div class="leaderboard-entry" style="background: #bbb;">
          <div class="rank-number">—</div>
          <div class="user-avatar">👤</div>
          <div class="user-info">
            <div class="user-name">No users yet</div>
          </div>
          <div class="user-score">0 <span class="score-icon">🌱</span></div>
        </div>
      @endif
    </div>
  </div>

  <div class="floating-icons">
    <a href="{{ url('/analytics') }}" class="floating-icon" title="Analytics">🔥</a>
    <a href="{{ url('/learning-modules') }}" class="floating-icon" title="Learning Modules">🌱</a>
    <a href="{{ url('/leaderboard') }}" class="floating-icon" title="Leaderboard">🏆</a>
    <a href="{{ url('/badges') }}" class="floating-icon" title="Badges">🥇</a>
    <a href="{{ url('/settings') }}" class="floating-icon" title="Settings">⚙️</a>
  </div>

  <script>
    // Toggle sidebar
  function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    sidebar.classList.toggle('collapsed');
    mainContent.classList.toggle('expanded');
  }
    document.querySelectorAll('.nav-item').forEach(item => {
      item.addEventListener('click', function() {
        document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
        this.classList.add('active');
      });
    });

    document.querySelectorAll('.leaderboard-entry').forEach((entry, index) => {
      entry.addEventListener('click', function() {
        this.style.transform = 'translateY(-6px) scale(1.02)';
        setTimeout(() => { this.style.transform = 'translateY(-3px)'; }, 200);
      });
      entry.style.opacity = '0';
      entry.style.transform = 'translateY(30px)';
      setTimeout(() => {
        entry.style.transition = 'all 0.6s ease';
        entry.style.opacity = '1';
        entry.style.transform = 'translateY(0)';
      }, index * 100);
    });

    document.querySelectorAll('.floating-icon').forEach((icon, index) => {
      icon.style.animationDelay = `${index * 0.2}s`;
      icon.addEventListener('click', function() {
        this.style.transform = 'scale(1.2) rotate(360deg)';
        setTimeout(() => { this.style.transform = 'scale(1.1)'; }, 300);
      });
    });

    document.querySelectorAll('.podium-entry').forEach((entry, index) => {
      entry.style.opacity = '0';
      entry.style.transform = 'translateY(-20px)';
      setTimeout(() => {
        entry.style.transition = 'all 0.6s ease';
        entry.style.opacity = '1';
        entry.style.transform = 'translateY(0)';
      }, 200 + index * 150);
    });
  </script>
</body>
</html>
