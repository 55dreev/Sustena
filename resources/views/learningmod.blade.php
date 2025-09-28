<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>SUSTENA - Environmental Learning App</title>
  <link rel="stylesheet" href="{{ asset('css/learningmod.css') }}">
  <style>
    /* Make cards clickable with pointer cursor */
    .learning-card {
      cursor: pointer;
      text-decoration: none; /* Prevent underline */
      color: inherit; /* Prevent default link color */
    }
  </style>
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
    <a href="{{ url('/footprint-calculator') }}" class="nav-item">
        <div class="nav-icon">👣</div>
        <span>Footprint Tracker</span>
    </a>
    <a href="{{ url('/learning-modules') }}" class="nav-item active">
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
 <div class="top-nav">
    <!-- Analytics -->
    <a href="{{ route('analytics') }}" class="nav-icon-top" title="Analytics">
        🔥
    </a>

    <!-- Learning Modules -->
    <a href="{{ route('learning-modules') }}" class="nav-icon-top" title="Learning Modules">
        🌱
    </a>

    <!-- Leaderboard -->
    <a href="{{ route('leaderboard') }}" class="nav-icon-top" title="Leaderboard">
        🏆
    </a>

    <!-- Badges / Achievements -->
    <a href="{{ route('badges') }}" class="nav-icon-top" title="Badges">
        🥇
    </a>

    <!-- Settings -->
    <a href="{{ route('settings') }}" class="nav-icon-top" title="Settings">
        ⚙️
    </a>
</div>


  <div class="header-section">
    <div class="cloud cloud-1">☁️</div>
    <div class="cloud cloud-2">☁️</div>
    <div class="cloud cloud-3">☁️</div>
    <div class="cloud cloud-4">☁️</div>

    <h1 class="header-title">Learn</h1>
    <div class="header-subtitle">How to reduce your carbon footprint?</div>
  </div>

  <div class="learning-grid">
    <div class="learning-card climate-change" data-link="{{ url('/climatechange') }}">
      <div class="card-icon">🌍</div>
      <div class="card-title">Climate Change</div>
    </div>

    <div class="learning-card recycling" data-link="{{ url('/recycling') }}">
      <div class="card-icon">♻️</div>
      <div class="card-title">Recycling & Waste</div>
    </div>

    <div class="learning-card energy-saving" data-link="{{ url('/energy-saving') }}">
      <div class="card-icon">💡</div>
      <div class="card-title">Energy Saving</div>
    </div>

    <div class="learning-card water-conservation" data-link="{{ url('/waterconservation') }}">
      <div class="card-icon">💧</div>
      <div class="card-title">Water Conservation</div>
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

  // Make cards clickable and redirect
  document.querySelectorAll('.learning-card').forEach(card => {
    card.addEventListener('click', function() {
      const link = this.getAttribute('data-link');
      if (link) {
        window.location.href = link;
      }
    });
  });
</script>

</body>
</html>
