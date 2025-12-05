<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUSTENA - Your Planet's Journey</title>
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/visual-progress.css') }}">
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
            <div class="nav-icon">🏠</div><span>Home</span>
        </a>
        <a href="{{ url('/footprint-calculator') }}" class="nav-item">
            <div class="nav-icon">👣</div><span>Footprint Tracker</span>
        </a>
        <a href="{{ url('/challenge') }}" class="nav-item">
            <div class="nav-icon">🏆</div><span>Challenges</span>
        </a>
        <a href="{{ url('/forum') }}" class="nav-item">
            <div class="nav-icon">💬</div><span>MicroForum</span>
        </a>
        <a href="{{ url('/visual-progress') }}" class="nav-item active">
            <div class="nav-icon">🌍</div><span>Your Planet</span>
        </a>
        <a href="{{ url('/profile') }}" class="nav-item">
            <div class="nav-icon">👤</div><span>Profile</span>
        </a>
    </div>

    <div class="main-content">
        <div class="header">
            <div class="header-content">
                <h1>Your Planet's Journey</h1>
                <p class="subtitle">Watch your planet heal as you make sustainable choices</p>
            </div>
        </div>

        <div class="content-container">
            <!-- Planet Stage Display -->
            <div class="planet-container">
                <div class="planet-wrapper">
                    <div class="planet stage-{{ $planet_stage }}" data-stage="{{ $planet_stage }}">
                        <!-- Atmosphere layers -->
                        <div class="atmosphere"></div>
                        <div class="atmosphere-glow"></div>

                        <!-- Planet surface -->
                        <div class="planet-surface">
                            <!-- Land masses -->
                            <div class="land land-1"></div>
                            <div class="land land-2"></div>
                            <div class="land land-3"></div>

                            <!-- Oceans -->
                            <div class="ocean"></div>

                            <!-- Clouds -->
                            <div class="cloud cloud-1"></div>
                            <div class="cloud cloud-2"></div>
                            <div class="cloud cloud-3"></div>

                            <!-- Pollution (visible in early stages) -->
                            <div class="pollution"></div>

                            <!-- Green areas (visible in later stages) -->
                            <div class="forest forest-1"></div>
                            <div class="forest forest-2"></div>
                            <div class="forest forest-3"></div>
                        </div>

                        <!-- Stars background -->
                        <div class="stars"></div>
                    </div>

                    <!-- Stage info overlay -->
                    <div class="stage-info">
                        <div class="stage-badge">Stage {{ $planet_stage + 1 }} of 7</div>
                        <h2 class="stage-name">{{ $stage_name }}</h2>
                        <p class="stage-message">{{ $stage_message }}</p>
                    </div>
                </div>
            </div>

            <!-- Progress Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">⚡</div>
                    <div class="stat-content">
                        <div class="stat-label">Level Progress</div>
                        <div class="stat-value">{{ $level_progress_percent }}%</div>
                        <div class="stat-subtext">Level {{ $level }} → {{ $next_level }}</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">🏆</div>
                    <div class="stat-content">
                        <div class="stat-label">Total XP</div>
                        <div class="stat-value">{{ number_format($xp_total) }}</div>
                        <div class="stat-subtext">{{ number_format($xp_needed) }} to next level</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">🔥</div>
                    <div class="stat-content">
                        <div class="stat-label">Day Streak</div>
                        <div class="stat-value">{{ $streak_days }}</div>
                        <div class="stat-subtext">Consecutive days</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">📅</div>
                    <div class="stat-content">
                        <div class="stat-label">Week Streak</div>
                        <div class="stat-value">{{ $streak_weeks }}</div>
                        <div class="stat-subtext">Consecutive weeks</div>
                    </div>
                </div>
            </div>

            <!-- Overall Progress Bar -->
            <div class="progress-section">
                <div class="progress-header">
                    <h3>Overall Planet Health</h3>
                    <span class="progress-percent">{{ $composite_score }}%</span>
                </div>
                <div class="progress-bar-container">
                    <div class="progress-bar" style="width: {{ $composite_score }}%"></div>
                </div>
                <div class="progress-milestones">
                    <div class="milestone {{ $composite_score >= 10 ? 'achieved' : '' }}">
                        <div class="milestone-marker">10%</div>
                        <div class="milestone-label">Polluted</div>
                    </div>
                    <div class="milestone {{ $composite_score >= 25 ? 'achieved' : '' }}">
                        <div class="milestone-marker">25%</div>
                        <div class="milestone-label">Healing</div>
                    </div>
                    <div class="milestone {{ $composite_score >= 40 ? 'achieved' : '' }}">
                        <div class="milestone-marker">40%</div>
                        <div class="milestone-label">Improving</div>
                    </div>
                    <div class="milestone {{ $composite_score >= 60 ? 'achieved' : '' }}">
                        <div class="milestone-marker">60%</div>
                        <div class="milestone-label">Healthy</div>
                    </div>
                    <div class="milestone {{ $composite_score >= 80 ? 'achieved' : '' }}">
                        <div class="milestone-marker">80%</div>
                        <div class="milestone-label">Thriving</div>
                    </div>
                    <div class="milestone {{ $composite_score >= 95 ? 'achieved' : '' }}">
                        <div class="milestone-marker">95%</div>
                        <div class="milestone-label">Paradise</div>
                    </div>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="next-steps">
                <h3>Keep Your Planet Healthy</h3>
                <div class="tips-grid">
                    <div class="tip-card">
                        <div class="tip-icon">👣</div>
                        <div class="tip-text">Complete weekly footprint calculations</div>
                    </div>
                    <div class="tip-card">
                        <div class="tip-icon">📈</div>
                        <div class="tip-text">Maintain your streaks for bonus progress</div>
                    </div>
                    <div class="tip-card">
                        <div class="tip-icon">🎯</div>
                        <div class="tip-text">Level up to unlock new planet stages</div>
                    </div>
                    <div class="tip-card">
                        <div class="tip-icon">🏆</div>
                        <div class="tip-text">Earn XP through eco-challenges</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating quick links -->
<div class="floating-icons">
      <a href="{{ url('/analytics') }}" class="floating-icon" title="Analytics">📅</a>
      <a href="{{ url('/streaks') }}" class="floating-icon" title="Streaks">🔥</a>
      <a href="{{ url('/learning-modules') }}" class="floating-icon" title="Learning Modules">🌱</a>
      <a href="{{ url('/leaderboard') }}" class="floating-icon" title="Leaderboard">🏆</a>
      <a href="{{ url('/badges') }}" class="floating-icon" title="Badges">🥇</a>
      <a href="{{ url('/settings') }}" class="floating-icon" title="Settings">⚙️</a>
    </div>

    <script>
        // Add sparkle effect on planet hover
        const planet = document.querySelector('.planet');
        planet.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05) rotateY(5deg)';
        });
        planet.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1) rotateY(0deg)';
        });

        // Sidebar toggle functionality
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
