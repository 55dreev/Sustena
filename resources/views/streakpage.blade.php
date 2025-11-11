<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUSTENA - Streak & Achievements</title>
  <link rel="stylesheet" href="{{ asset('css/streakpage.css') }}">
</head>
<body>
    < <<div class="sidebar">
    <div class="logo">
      <div class="logo-icon">🌱</div>
      <div class="logo-text">SUSTENA</div>
    </div>
    <a href="{{ url('/landing-page') }}" class="nav-item ">
      <div class="nav-icon">🏠</div>
      <span>Home</span>
    </a>
    <a href="{{ url('/footprint-calculator') }}" class="nav-item">
      <div class="nav-icon">👣</div>
      <span>Footprint Tracker</span>
    </a>
    <a href="{{ url('/learn') }}" class="nav-item">
      <div class="nav-icon">📚</div>
      <span>Learn</span>
    </a>
    <a href="{{ url('/challenges') }}" class="nav-item">
      <div class="nav-icon">🏆</div>
      <span>Challenges</span>
    </a>
    <a href="{{ url('/microforum') }}" class="nav-item">
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
                <div class="welcome-text">Amazing work! You're on fire with your eco-friendly habits! 🔥</div>
            </div>
        </div>

        <div class="content-grid">
            <div class="streak-section">
                <div class="streak-flame">🔥</div>
                <div class="streak-title">You're in a<br>5 Day Streak</div>
                <div class="trophy">🏆</div>
                
                <div class="robot-mascot">
                    <div class="robot-head">
                        <div class="robot-antenna"></div>
                        <div class="robot-eyes">
                            <div class="robot-eye"></div>
                            <div class="robot-eye"></div>
                        </div>
                        <div class="robot-mouth"></div>
                    </div>
                    <div class="robot-body">
                        <div class="robot-arms">
                            <div class="robot-arm left"></div>
                            <div class="robot-arm right"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="achievements-section">
                <div class="achievements-header">
                    <div class="achievements-title">Achievement Badges</div>
                </div>

                <div class="badge-row">
                    <div class="achievement-badge badge-10-day">
                        🏅
                    </div>
                    <div class="achievement-badge badge-20-day">
                        🏅
                    </div>
                    <div class="achievement-badge badge-30-day">
                        🏅
                    </div>
                </div>

                <div class="badge-row" style="justify-content: center;">
                    <span style="font-size: 14px; color: #666; text-align: center;">10 DAY<br>STREAK</span>
                    <span style="font-size: 14px; color: #666; text-align: center; margin: 0 20px;">20 DAY<br>STREAK</span>
                    <span style="font-size: 14px; color: #666; text-align: center;">30 DAY<br>STREAK</span>
                </div>

                <div class="checklist">
                    <div class="checklist-title">To-Do Checklist</div>
                    
                    <div class="task-item">
                        <div class="task-checkbox">✓</div>
                        <div class="task-text">Task 1 - Use reusable water bottle</div>
                    </div>
                    
                    <div class="task-item">
                        <div class="task-checkbox">✓</div>
                        <div class="task-text">Task 2 - Walk instead of drive</div>
                    </div>
                    
                    <div class="task-item">
                        <div class="task-checkbox">✓</div>
                        <div class="task-text">Task 3 - Turn off unused lights</div>
                    </div>
                    
                    <div class="task-item">
                        <div class="task-checkbox">✓</div>
                        <div class="task-text">Task 4 - Recycle properly</div>
                    </div>
                    
                    <div class="task-item">
                        <div class="task-checkbox">✓</div>
                        <div class="task-text">Task 5 - Eat plant-based meal</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="floating-icons">
    <!-- Analytics -->
    <a href="{{ url('/analytics') }}" class="floating-icon" title="Analytics">
        🔥
    </a>

    <!-- Learning Modules -->
    <a href="{{ url('/learning-modules') }}" class="floating-icon" title="Learning Modules">
        🌱
    </a>

    <!-- Leaderboard -->
    <a href="{{ url('/leaderboard') }}" class="floating-icon" title="Leaderboard">
        🏆
    </a>

    <!-- Badges -->
    <a href="{{ url('/badges') }}" class="floating-icon" title="Badges">
        🥇
    </a>

    <!-- Settings -->
    <a href="{{ url('/settings') }}" class="floating-icon" title="Settings">
        ⚙️
    </a>
</div>



    <script>
        // Navigation interaction
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function(e) {
                
                document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Floating icons interaction
        document.querySelectorAll('.floating-icon').forEach((icon, index) => {
            icon.style.animationDelay = `${index * 0.2}s`;
            icon.addEventListener('click', function() {
                this.style.transform = 'scale(1.2) rotate(360deg)';
                setTimeout(() => {
                    this.style.transform = 'scale(1.1)';
                }, 300);
            });
        });

        // Task items interaction
        document.querySelectorAll('.task-item').forEach(task => {
            task.addEventListener('click', function() {
                this.style.transform = 'translateX(10px) scale(0.98)';
                setTimeout(() => {
                    this.style.transform = 'translateX(5px)';
                }, 150);
            });
        });

        // Achievement badges hover effect
        document.querySelectorAll('.achievement-badge').forEach(badge => {
            badge.addEventListener('mouseenter', function() {
                this.style.animation = 'bounce 0.6s ease';
            });
            
            badge.addEventListener('animationend', function() {
                this.style.animation = '';
            });
        });

        // Robot mascot click interaction
        document.querySelector('.robot-mascot').addEventListener('click', function() {
            const eyes = document.querySelectorAll('.robot-eye');
            eyes.forEach(eye => {
                eye.style.animation = 'none';
                eye.style.transform = 'scaleY(0.1)';
                setTimeout(() => {
                    eye.style.animation = 'blink 3s infinite';
                    eye.style.transform = 'scaleY(1)';
                }, 200);
            });
        });
    </script>
</body>
</html>