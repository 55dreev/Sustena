<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUSTENA - Profile</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #a8e6cf 0%, #7fcdcd 100%);
            min-height: 100vh;
            display: flex;
            position: relative;
            overflow-x: hidden;
        }

        /* Sidebar Styles - Based on uploaded code */
        .sidebar {
            width: 200px;
            background: linear-gradient(180deg, #4a7c59 0%, #2d5a3d 100%);
            padding: 20px;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .logo {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: #66bb6a;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .logo-text {
            color: white;
            font-weight: bold;
            font-size: 16px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            margin-bottom: 8px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .nav-item:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            transform: translateX(5px);
        }

        .nav-item.active {
            background: #66bb6a;
            color: white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .nav-icon {
            width: 24px;
            height: 24px;
            margin-right: 12px;
            border-radius: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            image-rendering: pixelated;
            image-rendering: -moz-crisp-edges;
            image-rendering: crisp-edges;
            filter: contrast(1.2) brightness(1.1);
            box-shadow: 
                inset 1px 1px 0px rgba(255,255,255,0.3),
                inset -1px -1px 0px rgba(0,0,0,0.2);
        }

        /* Top Navigation - Based on uploaded code */
        .floating-icons {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            z-index: 1000;
        }

        .floating-icon {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.9);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 
                inset 2px 2px 0px rgba(255,255,255,0.8),
                inset -2px -2px 0px rgba(0,0,0,0.3),
                0px 4px 12px rgba(0,0,0,0.15);
            cursor: pointer;
            transition: all 0.3s ease;
            image-rendering: pixelated;
            image-rendering: -moz-crisp-edges;
            image-rendering: crisp-edges;
            filter: contrast(1.2) brightness(1.1);
        }

        .floating-icon:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        }

        /* Main Content */
        .main-content {
            margin-left: 200px;
            flex: 1;
            padding: 20px;
        }

        /* Profile Header */
        .profile-header {
            background: linear-gradient(135deg, #66bb6a 0%, #4caf50 100%);
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        }

        .profile-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="3" fill="white" opacity="0.1"/><circle cx="80" cy="40" r="2" fill="white" opacity="0.1"/><circle cx="40" cy="70" r="2.5" fill="white" opacity="0.1"/></svg>');
            animation: float 20s infinite linear;
        }

        @keyframes float {
            0% { transform: translateX(-100%) translateY(-100%) rotate(0deg); }
            100% { transform: translateX(0%) translateY(0%) rotate(360deg); }
        }

        .profile-content {
            display: flex;
            align-items: center;
            gap: 30px;
            position: relative;
            z-index: 1;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            background: #2d5a3d;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: white;
            border: 5px solid rgba(255,255,255,0.3);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }

        .profile-info {
            flex: 1;
        }

        .profile-name {
            font-size: 36px;
            font-weight: bold;
            color: white;
            margin-bottom: 8px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .profile-title {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 20px;
        }

        .edit-profile-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 2px solid rgba(255,255,255,0.3);
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .edit-profile-btn:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        }

        /* Content Layout */
        .content-layout {
            display: flex;
            gap: 30px;
        }

        .left-content {
            flex: 1;
        }

        .right-content {
            flex: 1;
        }

        /* Lifestyle Info Card */
        .lifestyle-card {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }

        .lifestyle-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, #66bb6a, #4caf50);
        }

        .card-header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }

        .card-icon {
            font-size: 24px;
            margin-right: 12px;
        }

        .card-title {
            font-size: 20px;
            font-weight: bold;
            color: #2d5016;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .lifestyle-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 15px;
            background: rgba(255,255,255,0.6);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .lifestyle-item:hover {
            background: rgba(255,255,255,0.8);
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .lifestyle-icon {
            font-size: 20px;
            margin-right: 15px;
            width: 30px;
            text-align: center;
        }

        .lifestyle-text {
            flex: 1;
        }

        .lifestyle-label {
            font-weight: bold;
            color: #2d5016;
            margin-bottom: 2px;
        }

        .lifestyle-value {
            color: #666;
            font-size: 14px;
        }

        /* Carbon Stats Card */
        .carbon-card {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }

        .carbon-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, #64b5f6, #2196f3);
        }

        .carbon-main {
            text-align: center;
            margin-bottom: 25px;
        }

        .carbon-total {
            font-size: 28px;
            font-weight: bold;
            color: #1976d2;
            margin-bottom: 5px;
        }

        .carbon-subtitle {
            color: #666;
            font-size: 14px;
        }

        .carbon-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid rgba(0,0,0,0.1);
        }

        .carbon-item:last-child {
            border-bottom: none;
        }

        .carbon-label {
            display: flex;
            align-items: center;
            color: #333;
        }

        .carbon-value {
            font-weight: bold;
            color: #1976d2;
        }

        .status-icon {
            margin-left: 10px;
            font-size: 18px;
        }

        /* Impact Card */
        .impact-card {
            background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }

        .impact-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, #ba68c8, #9c27b0);
        }

        .impact-main {
            text-align: center;
            margin-bottom: 25px;
        }

        .impact-title {
            font-size: 18px;
            font-weight: bold;
            color: #7b1fa2;
            margin-bottom: 10px;
        }

        .impact-value {
            font-size: 32px;
            font-weight: bold;
            color: #7b1fa2;
            margin-bottom: 10px;
        }

        .progress-bar {
            width: 100%;
            height: 12px;
            background: rgba(255,255,255,0.6);
            border-radius: 6px;
            overflow: hidden;
            margin-bottom: 10px;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #66bb6a, #4caf50);
            border-radius: 6px;
            width: 75%;
            transition: width 0.3s ease;
        }

        .progress-text {
            color: #7b1fa2;
            font-weight: bold;
            font-size: 14px;
        }

        /* Achievements Card */
        .achievements-card {
            background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }

        .achievements-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, #ffb74d, #ff9800);
        }

        .achievement-badges {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        .badge {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #ffd54f, #ffc107);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }

        .badge:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }

        .achievement-level {
            margin-bottom: 15px;
        }

        .level-stars {
            display: flex;
            gap: 5px;
            margin-bottom: 10px;
        }

        .star {
            font-size: 20px;
            color: #ffd54f;
        }

        .star.empty {
            color: #ddd;
        }

        .level-text {
            color: #f57c00;
            font-weight: bold;
        }

        .challenges-joined {
            color: #666;
            font-size: 14px;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .content-layout {
                flex-direction: column;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 60px;
            }
            
            .main-content {
                margin-left: 60px;
                padding: 15px;
            }
            
            .logo-text, .nav-item span {
                display: none;
            }
            
            .profile-content {
                flex-direction: column;
                text-align: center;
                gap: 20px;
            }
            
            .profile-name {
                font-size: 28px;
            }
            
            .profile-avatar {
                width: 100px;
                height: 100px;
                font-size: 40px;
            }
        }

        @media (max-width: 480px) {
            .profile-header {
                padding: 25px;
            }
            
            .lifestyle-card, .carbon-card, .impact-card, .achievements-card {
                padding: 20px;
            }
            
            .profile-name {
                font-size: 24px;
            }
            
            .profile-avatar {
                width: 80px;
                height: 80px;
                font-size: 32px;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
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
                    <h1 class="profile-name">Halbert & Colarina [Ecosaver]</h1>
                    <div class="profile-title">ECO CHAMPION</div>
                    <button class="edit-profile-btn">Edit Profile</button>
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
    </script>
</body>
</html>