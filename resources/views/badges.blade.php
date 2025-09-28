<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUSTENA - Achievements & Badges</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #a8e6cf 0%, #7fcdcd 50%, #88d8c0 100%);
            min-height: 100vh;
            display: flex;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background: radial-gradient(circle at 20% 80%, rgba(120, 200, 150, 0.2) 0%, transparent 50%),
                        radial-gradient(circle at 80% 20%, rgba(100, 180, 200, 0.2) 0%, transparent 50%);
            z-index: -1;
            pointer-events: none;
        }

        /* Sidebar Styles - Consistent with original */
        .sidebar {
            width: 200px;
            background: linear-gradient(180deg, #4a7c59 0%, #2d5a3d 100%);
            padding: 20px;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
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
            font-size: 18px;
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
        }

        /* Top Navigation Bar */
        .top-nav {
            position: fixed;
            top: 0;
            left: 200px;
            right: 0;
            height: 70px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 0 30px;
            z-index: 999;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }

        .user-info {
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #66bb6a, #4caf50);
            padding: 8px 20px;
            border-radius: 25px;
            color: white;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(102, 187, 106, 0.3);
        }

        .floating-icons {
            display: flex;
            gap: 10px;
        }

        .floating-icons a {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .floating-icon {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.9);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 20px;
        }

        .floating-icon:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        }

        /* Main Content */
        .main-content {
            margin-left: 200px;
            margin-top: 70px;
            flex: 1;
            padding: 30px;
        }

        .header {
            background: linear-gradient(135deg, #87ceeb 0%, #b0e0e6 100%);
            padding: 20px;
            border-radius: 16px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }

        .welcome-text {
            color: #2d5a3d;
            font-size: 18px;
            font-weight: 600;
            position: relative;
            z-index: 1;
        }

        /* Current Badge Section */
        .current-badge-section {
            display: flex;
            gap: 30px;
            margin-bottom: 40px;
            align-items: stretch;
        }

        .badge-display {
            background: linear-gradient(135deg, #ffd54f 0%, #ffb300 100%);
            border-radius: 20px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(255, 179, 0, 0.3);
            position: relative;
            overflow: hidden;
            flex: 0 0 280px;
        }

        .badge-display::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .badge-circle {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #4caf50, #2e7d32);
            border-radius: 50%;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 25px rgba(76, 175, 80, 0.4);
            position: relative;
            z-index: 1;
        }

        .badge-icon {
            font-size: 48px;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
        }

        .badge-text {
            font-size: 14px;
            font-weight: 600;
            color: #2d5a3d;
            position: relative;
            z-index: 1;
        }

        .progress-points {
            font-size: 12px;
            color: #5d4037;
            margin-top: 5px;
            position: relative;
            z-index: 1;
        }

        .user-profile-section {
            flex: 1;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
        }

        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .profile-avatar {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #4caf50, #2e7d32);
            border-radius: 50%;
            margin-right: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
        }

        .profile-info h3 {
            color: #2d5a3d;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .profile-title {
            color: #66bb6a;
            font-size: 14px;
            font-weight: 600;
        }

        .stats-row {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }

        .stat-item {
            text-align: center;
            flex: 1;
        }

        .stat-icon {
            font-size: 24px;
            margin-bottom: 8px;
            display: block;
        }

        .stat-label {
            font-size: 12px;
            color: #666;
            font-weight: 600;
        }

        /* Achievements Grid */
        .achievements-section {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
        }

        .achievements-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-top: 20px;
        }

        .achievement-badge {
            background: linear-gradient(135deg, #e8f5e8, #c8e6c9);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .achievement-badge:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .achievement-badge.locked {
            background: linear-gradient(135deg, #f5f5f5, #eeeeee);
            opacity: 0.6;
        }

        .achievement-badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transition: left 0.5s;
        }

        .achievement-badge:hover::before {
            left: 100%;
        }

        .achievement-icon-wrapper {
            width: 60px;
            height: 60px;
            margin: 0 auto 15px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .achievement-badge.energy-saver .achievement-icon-wrapper {
            background: linear-gradient(135deg, #66bb6a, #4caf50);
        }

        .achievement-badge.water-warrior .achievement-icon-wrapper {
            background: linear-gradient(135deg, #42a5f5, #1976d2);
        }

        .achievement-badge.waste-reducer .achievement-icon-wrapper {
            background: linear-gradient(135deg, #ab47bc, #7b1fa2);
        }

        .achievement-badge.carbon-neutral .achievement-icon-wrapper {
            background: linear-gradient(135deg, #26a69a, #00695c);
        }

        .achievement-badge.tree-planter .achievement-icon-wrapper {
            background: linear-gradient(135deg, #8bc34a, #689f38);
        }

        .achievement-badge.eco-transport .achievement-icon-wrapper {
            background: linear-gradient(135deg, #ff7043, #d84315);
        }

        .achievement-badge.green-home .achievement-icon-wrapper {
            background: linear-gradient(135deg, #5c6bc0, #3949ab);
        }

        .achievement-badge.earth-guardian .achievement-icon-wrapper {
            background: linear-gradient(135deg, #ffa726, #f57c00);
        }

        .achievement-badge.locked .achievement-icon-wrapper {
            background: linear-gradient(135deg, #bdbdbd, #757575);
        }

        .achievement-icon {
            font-size: 28px;
            color: white;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
        }

        .achievement-name {
            font-size: 12px;
            font-weight: 600;
            color: #2d5a3d;
            margin-bottom: 8px;
        }

        .achievement-badge.locked .achievement-name {
            color: #999;
        }

        .achievement-status {
            font-size: 10px;
            color: #4caf50;
            font-weight: 600;
        }

        .achievement-badge.locked .achievement-status {
            color: #999;
        }

        /* Section Headers */
        .section-header {
            font-size: 24px;
            font-weight: 700;
            color: #2d5a3d;
            margin-bottom: 20px;
            text-align: left;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                width: 60px;
            }
            
            .main-content {
                margin-left: 60px;
            }

            .top-nav {
                left: 60px;
            }
            
            .logo-text, .nav-item span {
                display: none;
            }

            .current-badge-section {
                flex-direction: column;
                gap: 20px;
            }

            .badge-display {
                flex: none;
            }

            .achievements-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }

            .user-info {
                padding: 6px 15px;
                font-size: 14px;
            }

            .floating-icon {
                width: 35px;
                height: 35px;
                font-size: 18px;
            }
        }

        @media (max-width: 480px) {
            .achievements-grid {
                grid-template-columns: repeat(2, 1fr);
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

    <!-- Top Navigation Bar -->
    <div class="top-nav">                   
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

    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <div class="welcome-text">Your sustainability achievements and environmental impact badges! 🌍</div>
        </div>

        <!-- Current Badge and Profile Section -->
        <div class="current-badge-section">
            <div class="badge-display">
                <div class="badge-circle">
                    <div class="badge-icon">♻️</div>
                </div>
                <div class="badge-text">RECYCLE</div>
                <div class="progress-points">540 points/1000 points</div>
            </div>

            <div class="user-profile-section">
                <div class="profile-header">
                    <div class="profile-avatar">👤</div>
                    <div class="profile-info">
                        <h3>Malbert Q. Colarina (EcoSaver)</h3>
                        <div class="profile-title">Environmental Champion</div>
                    </div>
                </div>
                
                <div class="stats-row">
                    <div class="stat-item">
                        <div class="stat-icon">🏅</div>
                        <div class="stat-label">Completed Badge</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon">🌸</div>
                        <div class="stat-label">Lv 35</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon">🔥</div>
                        <div class="stat-label">5 Day Streak</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Achievements Section -->
        <div class="achievements-section">
            <h2 class="section-header">Environmental Achievements</h2>
            <div class="achievements-grid">
                <div class="achievement-badge energy-saver">
                    <div class="achievement-icon-wrapper">
                        <div class="achievement-icon">⚡</div>
                    </div>
                    <div class="achievement-name">Energy Saver</div>
                    <div class="achievement-status">Silver Badge</div>
                </div>

                <div class="achievement-badge water-warrior">
                    <div class="achievement-icon-wrapper">
                        <div class="achievement-icon">🌊</div>
                    </div>
                    <div class="achievement-name">Water Warrior</div>
                    <div class="achievement-status">Silver Badge</div>
                </div>

                <div class="achievement-badge waste-reducer">
                    <div class="achievement-icon-wrapper">
                        <div class="achievement-icon">🗑️</div>
                    </div>
                    <div class="achievement-name">Waste Reducer</div>
                    <div class="achievement-status">Silver Badge</div>
                </div>

                <div class="achievement-badge carbon-neutral">
                    <div class="achievement-icon-wrapper">
                        <div class="achievement-icon">✅</div>
                    </div>
                    <div class="achievement-name">Carbon Neutral</div>
                    <div class="achievement-status">Silver Badge</div>
                </div>

                <div class="achievement-badge tree-planter">
                    <div class="achievement-icon-wrapper">
                        <div class="achievement-icon">🌳</div>
                    </div>
                    <div class="achievement-name">Tree Planter</div>
                    <div class="achievement-status">Silver Badge</div>
                </div>

                <div class="achievement-badge eco-transport">
                    <div class="achievement-icon-wrapper">
                        <div class="achievement-icon">🚲</div>
                    </div>
                    <div class="achievement-name">Eco Transport</div>
                    <div class="achievement-status">Silver Badge</div>
                </div>

                <div class="achievement-badge green-home">
                    <div class="achievement-icon-wrapper">
                        <div class="achievement-icon">🏠</div>
                    </div>
                    <div class="achievement-name">Green Home</div>
                    <div class="achievement-status">Silver Badge</div>
                </div>

                <div class="achievement-badge earth-guardian">
                    <div class="achievement-icon-wrapper">
                        <div class="achievement-icon">🌍</div>
                    </div>
                    <div class="achievement-name">Earth Guardian</div>
                    <div class="achievement-status">Silver Badge</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Navigation active state management
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function(e) {
                
                document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Achievement badge interactions
        document.querySelectorAll('.achievement-badge').forEach(badge => {
            badge.addEventListener('click', function() {
                if (!this.classList.contains('locked')) {
                    this.style.transform = 'translateY(-8px) scale(1.05)';
                    setTimeout(() => {
                        this.style.transform = 'translateY(-5px)';
                    }, 200);
                }
            });
        });

        // Floating icons interactions
        document.querySelectorAll('.floating-icon').forEach((icon, index) => {
            icon.style.animationDelay = `${index * 0.1}s`;
            icon.addEventListener('click', function() {
                this.style.transform = 'scale(1.2) rotate(360deg)';
                setTimeout(() => {
                    this.style.transform = 'scale(1.1)';
                }, 400);
            });
        });

        // Badge display animation
        const badgeDisplay = document.querySelector('.badge-display');
        badgeDisplay.addEventListener('mouseenter', function() {
            this.querySelector('.badge-circle').style.transform = 'scale(1.1) rotate(5deg)';
        });

        badgeDisplay.addEventListener('mouseleave', function() {
            this.querySelector('.badge-circle').style.transform = 'scale(1) rotate(0deg)';
        });

        // Add entrance animation
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        });

        // Apply observer to achievement badges
        document.querySelectorAll('.achievement-badge').forEach((badge, index) => {
            badge.style.opacity = '0';
            badge.style.transform = 'translateY(20px)';
            badge.style.transition = `all 0.5s ease ${index * 0.1}s`;
            observer.observe(badge);
        });
    </script>
</body>
</html>