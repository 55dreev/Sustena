<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUSTENA - Streak & Achievements</title>
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

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="40" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="40" cy="80" r="1.5" fill="rgba(255,255,255,0.1)"/></svg>') center/200px no-repeat;
            opacity: 0.3;
            z-index: -1;
            pointer-events: none;
        }

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
        }

        .main-content {
            margin-left: 200px;
            flex: 1;
            padding: 20px;
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

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 20"><path d="M0 20L100 20L100 0C80 5 60 15 40 10C20 5 0 15 0 20Z" fill="rgba(255,255,255,0.1)"/></svg>');
            background-size: 200px 40px;
            animation: wave 3s ease-in-out infinite;
        }

        @keyframes wave {
            0%, 100% { transform: translateX(0); }
            50% { transform: translateX(-50px); }
        }

        .header-content {
            position: relative;
            z-index: 1;
        }

        .welcome-text {
            color: #2d5a3d;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
        }

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
            border-radius: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 
                inset 2px 2px 0px rgba(255,255,255,0.8),
                inset -2px -2px 0px rgba(0,0,0,0.3),
                0px 4px 12px rgba(0,0,0,0.15);
            cursor: pointer;
            transition: all 0.3s ease;
            filter: contrast(1.2) brightness(1.1);
        }

        .floating-icon:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        }
        .floating-icons a {
    text-decoration: none; /* removes underline */
    color: inherit;        /* keeps the emoji color instead of blue */
     }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            align-items: start;
        }

        .streak-section {
            background: linear-gradient(135deg, #66bb6a 0%, #4caf50 100%);
            border-radius: 20px;
            padding: 40px;
            color: white;
            text-align: center;
            box-shadow: 0 12px 40px rgba(76, 175, 80, 0.3);
            position: relative;
            overflow: hidden;
        }

        .streak-section::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 150px;
            height: 150px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .streak-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }

        .robot-mascot {
            width: 200px;
            height: 200px;
            margin: 0 auto 20px;
            position: relative;
            z-index: 1;
        }

        .robot-body {
            width: 120px;
            height: 140px;
            background: linear-gradient(135deg, #42a5f5 0%, #1976d2 100%);
            border-radius: 15px;
            position: absolute;
            left: 50%;
            top: 60px;
            transform: translateX(-50%);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }

        .robot-head {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #78909c 0%, #546e7a 100%);
            border-radius: 10px;
            position: absolute;
            left: 50%;
            top: 20px;
            transform: translateX(-50%);
            box-shadow: 0 6px 15px rgba(0,0,0,0.2);
        }

        .robot-eyes {
            display: flex;
            gap: 15px;
            position: absolute;
            left: 50%;
            top: 25px;
            transform: translateX(-50%);
        }

        .robot-eye {
            width: 12px;
            height: 12px;
            background: #2d5a3d;
            border-radius: 50%;
            animation: blink 3s infinite;
        }

        .robot-mouth {
            width: 30px;
            height: 15px;
            background: #2d5a3d;
            border-radius: 0 0 15px 15px;
            position: absolute;
            left: 50%;
            top: 45px;
            transform: translateX(-50%);
        }

        .robot-antenna {
            width: 4px;
            height: 20px;
            background: #37474f;
            position: absolute;
            left: 50%;
            top: 0;
            transform: translateX(-50%);
        }

        .robot-antenna::after {
            content: '';
            width: 8px;
            height: 8px;
            background: #ff5722;
            border-radius: 50%;
            position: absolute;
            top: -4px;
            left: 50%;
            transform: translateX(-50%);
            animation: pulse 2s infinite;
        }

        .robot-arms {
            position: absolute;
            width: 100%;
            top: 80px;
        }

        .robot-arm {
            width: 35px;
            height: 80px;
            background: linear-gradient(135deg, #42a5f5 0%, #1976d2 100%);
            border-radius: 17px;
            position: absolute;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        .robot-arm.left {
            left: -25px;
            transform: rotate(-10deg);
            animation: wave-left 2s ease-in-out infinite;
        }

        .robot-arm.right {
            right: -25px;
            transform: rotate(10deg);
            animation: wave-right 2s ease-in-out infinite;
        }

        .trophy {
            position: absolute;
            right: 15px;
            top: 20px;
            font-size: 40px;
            animation: bounce 2s infinite;
        }

        .streak-flame {
            font-size: 60px;
            margin-bottom: 10px;
            animation: flicker 1.5s ease-in-out infinite;
        }

        .achievements-section {
            background: rgba(255,255,255,0.95);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.1);
        }

        .achievements-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .achievements-title {
            font-size: 24px;
            font-weight: bold;
            color: #2d5a3d;
        }

        .badge-row {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            justify-content: center;
        }

        .achievement-badge {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .achievement-badge:hover {
            transform: scale(1.1);
        }

        .badge-10-day { background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%); }
        .badge-20-day { background: linear-gradient(135deg, #8bc34a 0%, #689f38 100%); }
        .badge-30-day { background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%); }

        .checklist {
            margin-top: 30px;
        }

        .checklist-title {
            font-size: 20px;
            font-weight: bold;
            color: #2d5a3d;
            margin-bottom: 20px;
            text-align: center;
        }

        .task-item {
            display: flex;
            align-items: center;
            padding: 15px;
            margin-bottom: 12px;
            background: rgba(76, 175, 80, 0.1);
            border-radius: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
            border-left: 4px solid #4caf50;
        }

        .task-item:hover {
            background: rgba(76, 175, 80, 0.15);
            transform: translateX(5px);
        }

        .task-checkbox {
            width: 24px;
            height: 24px;
            border: 2px solid #4caf50;
            border-radius: 4px;
            margin-right: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #4caf50;
            color: white;
            font-size: 16px;
        }

        .task-text {
            font-size: 16px;
            font-weight: 500;
            color: #2d5a3d;
        }

        @keyframes blink {
            0%, 90%, 100% { transform: scaleY(1); }
            95% { transform: scaleY(0.1); }
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        @keyframes wave-left {
            0%, 100% { transform: rotate(-10deg); }
            50% { transform: rotate(-20deg); }
        }

        @keyframes wave-right {
            0%, 100% { transform: rotate(10deg); }
            50% { transform: rotate(20deg); }
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }

        @keyframes flicker {
            0%, 100% { transform: scale(1) rotate(0deg); }
            25% { transform: scale(1.1) rotate(-2deg); }
            50% { transform: scale(0.95) rotate(2deg); }
            75% { transform: scale(1.05) rotate(-1deg); }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 60px;
            }
            
            .main-content {
                margin-left: 60px;
            }
            
            .logo-text, .nav-item span {
                display: none;
            }
            
            .content-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .robot-mascot {
                width: 150px;
                height: 150px;
            }
            
            .robot-body {
                width: 90px;
                height: 105px;
            }
            
            .robot-head {
                width: 60px;
                height: 60px;
            }
        }
    </style>
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