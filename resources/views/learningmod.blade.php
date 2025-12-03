<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>SUSTENA - Environmental Learning App</title>
  <link rel="stylesheet" href="{{ asset('css/learningmod.css') }}">
  <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
  

</head>
<body>
   <!-- Sidebar Backdrop -->
<div class="sidebar-backdrop" id="sidebar-backdrop" onclick="closeSidebar()"></div>

<!-- Sidebar Toggle Button -->
<button class="mobile-hamburger" id="hamburger-btn" onclick="toggleMobileSidebar()" aria-label="Toggle navigation menu" aria-expanded="false">☰</button>

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
 <div class="floating-icons">
    <a href="{{ route('analytics') }}" class="floating-icon" title="Analytics">🔥</a>
    <a href="{{ route('learning-modules') }}" class="floating-icon" title="Learning Modules">🌱</a>
    <a href="{{ route('leaderboard') }}" class="floating-icon" title="Leaderboard">🏆</a>
    <a href="{{ route('badges') }}" class="floating-icon" title="Badges">🥇</a>
    <a href="{{ route('settings') }}" class="floating-icon" title="Settings">⚙️</a>
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
   document.addEventListener('DOMContentLoaded', () => {
    if (localStorage.getItem('darkMode') === 'enabled') {
        document.body.classList.add('dark-mode');
    }
});
function toggleMobileSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const body = document.body;
    const hamburger = document.getElementById('hamburger-btn');
    const isOpen = sidebar.classList.contains('open');

    sidebar.classList.toggle('open');
    body.classList.toggle('sidebar-open');

    // Update aria-expanded for accessibility
    hamburger.setAttribute('aria-expanded', !isOpen);
}

function closeSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const body = document.body;
    const hamburger = document.getElementById('hamburger-btn');

    if (sidebar.classList.contains('open')) {
        sidebar.classList.remove('open');
        body.classList.remove('sidebar-open');
        hamburger.setAttribute('aria-expanded', 'false');
    }
}

// Close sidebar when clicking on nav items
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.nav-item').forEach(item => {
        item.addEventListener('click', closeSidebar);
    });
});

</script>

</body>
</html>
