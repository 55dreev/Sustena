<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoSteps - Environmental Tracking</title>
   <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>
<body>
    
<!-- Sidebar (default open) -->
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
                <div class="welcome-text">Welcome to SUSTENA! Let's shrink your footprint - one step at a time 🌱</div>
                <div class="forest-silhouette"></div>
            </div>
        </div>

        <div class="mission-section">
            <div class="mission-header">
                <div class="mission-icon">🎯</div>
                <div class="mission-title">Daily Mission:</div>
            </div>
            <div class="mission-text">Skip meat for lunch today!</div>
        </div>

        <div class="eco-tip">
            <div class="eco-tip-header">
                <div class="eco-tip-icon">💧</div>
                <div class="mission-title">Eco tip:</div>
            </div>
            <div class="mission-text">Did you know? Turning off the tap while brushing saves 6 liters of water a minute!</div>
        </div>

        <div class="cards-grid">
            <div class="card streak-card">
                <div class="card-icon">🔥</div>
                <div class="card-title">5-Day</div>
                <div class="card-subtitle">Streak</div>
                <div class="card-text">Keep it up!</div>
            </div>

            <div class="card badges-card">
                <div class="card-title">Badges Earned</div>
                <div class="badge-grid">
                    <div class="badge">🌿</div>
                    <div class="badge">🥕</div>
                    <div class="badge">🏆</div>
                    <div class="badge">♻️</div>
                    <div class="badge">🌍</div>
                    <div class="badge">⭐</div>
                </div>
                <button class="view-all-btn">View All</button>
            </div>

            <div class="card co2-card">
                <div class="card-icon">🌳</div>
                <div class="card-title">You've Saved</div>
                <div class="card-subtitle">12 kg of CO₂</div>
                <div class="card-text">this week!</div>
            </div>

            <div class="card progress-card">
                <div class="card-icon">📊</div>
                <div class="card-title">XP Progress</div>
                <div class="card-subtitle">530 XP</div>
                <div class="card-text">needed to next level</div>
                <div class="progress-bar">
                    <div class="progress-fill"></div>
                </div>
            </div>

            <div class="card water-saver-card">
                <div class="card-icon">💧</div>
                <div class="card-title">Earn the "water saver" badge</div>
                <div class="card-text">Complete water-saving challenges</div>
            </div>

            <div class="card energy-card">
                <div class="card-icon">⚡</div>
                <div class="card-title">Energy Saved</div>
                <div class="card-subtitle">24.5 kWh</div>
                <div class="card-text">this month!</div>
            </div>

            <div class="card leaderboard-card">
                <div class="card-icon">👥</div>
                <div class="card-title">Community Rank</div>
                <div class="card-subtitle">#47</div>
                <div class="card-text">out of 1,234 users</div>
                <div class="rank-change">↗️ +3 this week</div>
            </div>

            <div class="card weekly-goal-card">
                <div class="card-icon">🎯</div>
                <div class="card-title">Weekly Goal</div>
                <div class="card-subtitle">4/7 days</div>
                <div class="card-text">sustainable actions</div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 57%;"></div>
                </div>
            </div>

            
        </div>
    </div>

  <div class="floating-icons">
    <!-- Analytics -->
    <a href="{{ route('analytics') }}" class="floating-icon" title="Analytics">
        🔥
    </a>

    <!-- Learning Modules (or Sustainability Section) -->
    <a href="{{ route('learning-modules') }}" class="floating-icon" title="Learning Modules">
        🌱
    </a>

    <!-- Leaderboard -->
    <a href="{{ route('leaderboard') }}" class="floating-icon" title="Leaderboard">
        🏆
    </a>

    <!-- Badges / Achievements -->
    <a href="{{ route('badges') }}" class="floating-icon" title="Badges">
        🥇
    </a>

    <!-- Settings -->
    <a href="{{ route('settings') }}" class="floating-icon" title="Settings">
        ⚙️
    </a>
</div>


    <script>
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href === '#' || href === '') {
                    e.preventDefault();
                }
                document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
                this.classList.add('active');
            });
        });

        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('click', function() {
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = 'translateY(-4px)';
                }, 150);
            });
        });

        document.querySelectorAll('.floating-icon').forEach((icon, index) => {
            icon.style.animationDelay = `${index * 0.2}s`;
            icon.addEventListener('click', function() {
                this.style.transform = 'scale(1.2) rotate(360deg)';
                setTimeout(() => {
                    this.style.transform = 'scale(1.1)';
                }, 300);
            });
        });

        window.addEventListener('load', function() {
            const progressBar = document.querySelector('.progress-fill');
            setTimeout(() => {
                progressBar.style.width = '60%';
            }, 1000);
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