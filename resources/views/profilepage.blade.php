<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUSTENA - Profile</title>
    @php
    use Illuminate\Support\Facades\Auth;
@endphp
 <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
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
<a href="{{ url('/challenge') }}" class="nav-item">
  <div class="nav-icon">🏆</div>
  <span>Challenges</span>
</a>
<a href="{{ url('/forum') }}" class="nav-item">
  <div class="nav-icon">💬</div>
  <span>MicroForum</span>
</a>
  <a href="{{ url('/profile') }}" class="nav-item active">
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
        <!-- Profile Header -->
        <div class="profile-header">
    <div class="profile-content">
        <div class="profile-avatar">👤</div>
        <div class="profile-info">
            @if(session()->has('username'))
                <h1 class="profile-name">{{ session('username') }} [Ecosaver]</h1>
            @else
                <h1 class="profile-name">Guest [Ecosaver]</h1>
            @endif
            <div class="profile-title">ECO CHAMPION</div>
            <div style="display: flex; gap: 10px; align-items: center;">
                <button class="edit-profile-btn">Edit Profile</button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="edit-profile-btn" style="background-color: #e74c3c;">Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>



        <!-- Content Layout -->
        <div class="content-layout">
            <!-- Left Content -->
            <div class="left-content">
                <!-- Lifestyle Info -->
                <div class="lifestyle-card">
                    <div class="card-header">
                        <div class="card-icon">🌿</div>
                        <div class="card-title">Lifestyle Info</div>
                    </div>
                    
                    <div class="lifestyle-item">
                        <div class="lifestyle-icon">🥗</div>
                        <div class="lifestyle-text">
                            <div class="lifestyle-label">Diet</div>
                            <div class="lifestyle-value">Vegan</div>
                        </div>
                    </div>
                    
                    <div class="lifestyle-item">
                        <div class="lifestyle-icon">🚲</div>
                        <div class="lifestyle-text">
                            <div class="lifestyle-label">Transport</div>
                            <div class="lifestyle-value">Bike + Public Transport</div>
                        </div>
                    </div>
                    
                    <div class="lifestyle-item">
                        <div class="lifestyle-icon">🏠</div>
                        <div class="lifestyle-text">
                            <div class="lifestyle-label">Home</div>
                            <div class="lifestyle-value">Apartment</div>
                        </div>
                    </div>
                    
                    <div class="lifestyle-item">
                        <div class="lifestyle-icon">⚡</div>
                        <div class="lifestyle-text">
                            <div class="lifestyle-label">Energy</div>
                            <div class="lifestyle-value">Renewable</div>
                        </div>
                    </div>
                </div>

                <!-- Carbon Stats -->
                <div class="carbon-card">
                    <div class="card-header">
                        <div class="card-icon">📊</div>
                        <div class="card-title">Carbon Stats</div>
                    </div>
                    
                    <div class="carbon-main">
                        <div class="carbon-total">Total CO₂: 3.2 tons/year</div>
                        <div class="carbon-subtitle">(↓ 10%)</div>
                    </div>
                    
                    <div class="carbon-item">
                        <div class="carbon-label">
                            🏆 Best: Transport
                            <span class="status-icon">✅</span>
                        </div>
                        <div class="carbon-value">(0.5 tons)</div>
                    </div>
                    
                    <div class="carbon-item">
                        <div class="carbon-label">
                            ⚠️ Improve: Diet
                            <span class="status-icon">🔄</span>
                        </div>
                        <div class="carbon-value">(1.8 tons)</div>
                    </div>
                    
                    <div class="carbon-item">
                        <div class="carbon-label">
                            🎯 Goal: Reduce 15% by 2025
                            <span class="status-icon">⏳</span>
                        </div>
                        <div class="carbon-value">[45% done]</div>
                    </div>
                </div>
            </div>

            <!-- Right Content -->
            <div class="right-content">
                <!-- Your Impact -->
                <div class="impact-card">
                    <div class="card-header">
                        <div class="card-icon">🌍</div>
                        <div class="card-title">Your Impact</div>
                    </div>
                    
                    <div class="impact-main">
                        <div class="impact-title">CO₂ SAVED</div>
                        <div class="impact-value">1.2 TONS</div>
                        <div class="progress-bar">
                            <div class="progress-fill"></div>
                        </div>
                        <div class="progress-text">75% to goal</div>
                    </div>
                </div>

                <!-- Achievements -->
                <div class="achievements-card">
                    <div class="card-header">
                        <div class="card-icon">🏅</div>
                        <div class="card-title">Achievements</div>
                    </div>
                    
                    <div class="achievement-badges">
                        <div class="badge">🌱</div>
                        <div class="badge">🚲</div>
                        <div class="badge">♻️</div>
                    </div>
                    
                    <div class="achievement-level">
                        <div class="level-stars">
                            <span class="star">⭐</span>
                            <span class="star">⭐</span>
                            <span class="star">⭐</span>
                            <span class="star">⭐</span>
                            <span class="star empty">⭐</span>
                        </div>
                        <div class="level-text">Level 5 Eco Champion</div>
                        <div class="challenges-joined">Joined: 3 Challenges</div>
                    </div>
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

        // Edit profile button
        document.querySelector('.edit-profile-btn').addEventListener('click', function() {
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
                alert('Edit profile functionality would be implemented here!');
            }, 150);
        });

        // Lifestyle items hover effects
        document.querySelectorAll('.lifestyle-item').forEach(item => {
            item.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(8px)';
            });
            
            item.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(5px)';
            });
        });

        // Badge hover effects
        document.querySelectorAll('.badge').forEach(badge => {
            badge.addEventListener('click', function() {
                this.style.transform = 'scale(1.2) rotate(360deg)';
                setTimeout(() => {
                    this.style.transform = 'scale(1.1)';
                }, 300);
            });
        });

        // Card hover effects
        document.querySelectorAll('.lifestyle-card, .carbon-card, .impact-card, .achievements-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0px)';
            });
        });

        // Animate progress bar on load
        window.addEventListener('load', function() {
            const progressFill = document.querySelector('.progress-fill');
            progressFill.style.width = '0%';
            setTimeout(() => {
                progressFill.style.width = '75%';
            }, 500);
        });

        // Animate carbon stats on scroll
        function animateOnScroll() {
            const carbonCard = document.querySelector('.carbon-card');
            const rect = carbonCard.getBoundingClientRect();
            const isVisible = rect.top < window.innerHeight && rect.bottom > 0;
            
            if (isVisible) {
                carbonCard.style.opacity = '1';
                carbonCard.style.transform = 'translateY(0)';
            }
        }

        window.addEventListener('scroll', animateOnScroll);
        
        // Initialize animations
        animateOnScroll();

          function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            }
    </script>
</body>
</html>