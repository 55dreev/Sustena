<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>SUSTENA - Environmental Learning App</title>
   <link rel="stylesheet" href="{{ asset('css/learningmod.css') }}">
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
      <div class="nav-icon-top">🔥</div>
      <div class="nav-icon-top">🌱</div>
      <div class="nav-icon-top">🏆</div>
      <div class="nav-icon-top">💰</div>
      <div class="nav-icon-top">⚙️</div>
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
      <div class="learning-card climate-change">
        <div class="card-icon">🌍</div>
        <div class="card-title">Climate Change</div>
      </div>

      <div class="learning-card recycling">
        <div class="card-icon">♻️</div>
        <div class="card-title">Recycling & Waste</div>
      </div>

      <div class="learning-card energy-saving">
        <div class="card-icon">💡</div>
        <div class="card-title">Energy Saving</div>
      </div>

      <div class="learning-card water-conservation">
        <div class="card-icon">💧</div>
        <div class="card-title">Water Conservation</div>
      </div>
    </div>
  </div>

  <script>
    // Sidebar active click highlight
    document.querySelectorAll('.nav-item').forEach(item => {
      item.addEventListener('click', function () {
        document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
        this.classList.add('active');
      });
    });

    // Learning card click alert
    document.querySelectorAll('.learning-card').forEach(card => {
      card.addEventListener('click', function () {
        const title = this.querySelector('.card-title').textContent;
        alert(`Opening ${title} learning module...`);
      });
    });
    
     function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            }
  </script>
</body>
</html>
