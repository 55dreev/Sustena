<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUSTENA - Challenges</title>
    <link rel="stylesheet" href="{{ asset('css/challenges.css') }}">
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
  <a href="{{ url('/learning-modules') }}" class="nav-item">
  <div class="nav-icon">📚</div>
  <span>Learn</span>
</a>
<a href="{{ url('/challenge') }}" class="nav-item active">
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

    <!-- Top Navigation -->
    <div class="floating-icons">
        <div class="floating-icon">🔥</div>
        <div class="floating-icon">🌱</div>
        <div class="floating-icon">🏆</div>
        <div class="floating-icon">🥇</div>
        <div class="floating-icon">⚙️</div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header Section -->
        <div class="header-section">
            <div class="cloud cloud-1">☁️</div>
            <div class="cloud cloud-2">☁️</div>
            <div class="cloud cloud-3">☁️</div>
            <div class="cloud cloud-4">☁️</div>
            
            <h1 class="header-title">Challenges</h1>
            <div class="header-subtitle">Pick a challenge to complete</div>
        </div>

        <!-- Challenge Cards -->
        <div class="challenges-grid">
            <div class="challenge-card meatless">
                <div class="challenge-points">+50 XP</div>
                <div class="challenge-icon">🥩</div>
                <div class="challenge-content">
                    <h3 class="challenge-title">Go Meatless</h3>
                    <p class="challenge-subtitle">Eat Vegan Meals</p>
                    <div class="difficulty">
                        <div class="difficulty-dot active"></div>
                        <div class="difficulty-dot"></div>
                        <div class="difficulty-dot"></div>
                    </div>
                    <p class="challenge-description">
                        Choose plant-based meals for the day and reduce your carbon footprint while discovering delicious alternatives.
                    </p>
                    <button class="challenge-button">Start Challenge</button>
                </div>
            </div>

            <div class="challenge-card energy">
                <div class="challenge-points">+75 XP</div>
                <div class="challenge-icon">💡</div>
                <div class="challenge-content">
                    <h3 class="challenge-title">Conserve Energy</h3>
                    <p class="challenge-subtitle">Less Use of Electricity and Turn Off the Electronics</p>
                    <div class="difficulty">
                        <div class="difficulty-dot active"></div>
                        <div class="difficulty-dot active"></div>
                        <div class="difficulty-dot"></div>
                    </div>
                    <p class="challenge-description">
                        Reduce energy consumption by turning off unused electronics and being mindful of electricity usage.
                    </p>
                    <button class="challenge-button">Start Challenge</button>
                </div>
            </div>

            <div class="challenge-card bike">
                <div class="challenge-points">+60 XP</div>
                <div class="challenge-icon">🚲</div>
                <div class="challenge-content">
                    <h3 class="challenge-title">Bike Instead of Drive</h3>
                    <p class="challenge-subtitle">Use Your Bicycle for Transportation</p>
                    <div class="difficulty">
                        <div class="difficulty-dot active"></div>
                        <div class="difficulty-dot active"></div>
                        <div class="difficulty-dot"></div>
                    </div>
                    <p class="challenge-description">
                        Choose cycling over driving for short trips and contribute to cleaner air while staying healthy.
                    </p>
                    <button class="challenge-button">Start Challenge</button>
                </div>
            </div>

            <div class="challenge-card waste">
                <div class="challenge-points">+40 XP</div>
                <div class="challenge-icon">♻️</div>
                <div class="challenge-content">
                    <h3 class="challenge-title">Reduce Waste</h3>
                    <p class="challenge-subtitle">Use a Reusable Bag</p>
                    <div class="difficulty">
                        <div class="difficulty-dot active"></div>
                        <div class="difficulty-dot"></div>
                        <div class="difficulty-dot"></div>
                    </div>
                    <p class="challenge-description">
                        Carry reusable bags for shopping and reduce single-use plastic consumption in your daily routine.
                    </p>
                    <button class="challenge-button">Start Challenge</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Navigation menu interaction
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function(e) {
               
                document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Challenge card interactions
        document.querySelectorAll('.challenge-card').forEach(card => {
            card.addEventListener('click', function(e) {
                if (!e.target.classList.contains('challenge-button')) {
                    const button = this.querySelector('.challenge-button');
                    button.click();
                }
            });
        });

        // Challenge button interactions
        document.querySelectorAll('.challenge-button').forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                const card = this.closest('.challenge-card');
                const title = card.querySelector('.challenge-title').textContent;
                const points = card.querySelector('.challenge-points').textContent;
                
                // Animation feedback
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 150);
                
                // Show success message
                setTimeout(() => {
                    alert(`${title} challenge started! You can earn ${points} by completing this challenge.`);
                }, 200);
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

        // Add some dynamic behavior
        document.querySelectorAll('.challenge-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
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