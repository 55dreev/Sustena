<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUSTENA - Leaderboards</title>
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
            background: url('sustena2.jpg') center/cover no-repeat;
            opacity: 0.25;
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
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 20px;
        }
        .floating-icons a {
    text-decoration: none; /* removes underline */
    color: inherit;        /* keeps the emoji color instead of blue */
     }

        .floating-icon:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
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

        .header-content {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .welcome-text {
            color: #2d5a3d;
            font-size: 18px;
            font-weight: 600;
            margin: 0;
        }

        .trophy-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(145deg, #ffd700, #ffb300);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            box-shadow: 0 6px 20px rgba(255, 183, 0, 0.3);
            animation: shine 2s ease-in-out infinite;
        }

        @keyframes shine {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .leaderboard-title {
            font-size: 32px;
            font-weight: 800;
            color: #4CAF50;
            text-align: center;
            margin: 30px 0;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
            letter-spacing: 2px;
        }

        .leaderboard-container {
            background: rgba(255,255,255,0.95);
            border-radius: 20px;
            padding: 40px 30px;
            box-shadow: 0 15px 50px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
        }

        .leaderboard-entry {
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #66bb6a 0%, #4caf50 100%);
            margin-bottom: 15px;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 8px 25px rgba(76, 175, 80, 0.2);
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .leaderboard-entry:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(76, 175, 80, 0.3);
        }

        .leaderboard-entry::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            transform: translate(30px, -30px);
        }

        .rank-number {
            width: 60px;
            height: 60px;
            background: rgba(0,0,0,0.8);
            color: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: 800;
            margin-right: 20px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.3);
            position: relative;
            z-index: 2;
        }

        .rank-number.gold {
            background: linear-gradient(145deg, #ffd700, #ffb300);
            color: #333;
            animation: goldGlow 2s ease-in-out infinite;
        }

        .rank-number.silver {
            background: linear-gradient(145deg, #c0c0c0, #a0a0a0);
            color: #333;
        }

        .rank-number.bronze {
            background: linear-gradient(145deg, #cd7f32, #b8860b);
            color: white;
        }

        @keyframes goldGlow {
            0%, 100% { box-shadow: 0 6px 15px rgba(255, 215, 0, 0.3); }
            50% { box-shadow: 0 6px 25px rgba(255, 215, 0, 0.6); }
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            background: rgba(255,255,255,0.9);
            border-radius: 50%;
            margin-right: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            position: relative;
            z-index: 2;
        }

        .user-info {
            flex: 1;
            position: relative;
            z-index: 2;
        }

        .user-name {
            font-size: 20px;
            font-weight: 700;
            color: white;
            margin-bottom: 2px;
        }

        .user-score {
            background: rgba(255,255,255,0.9);
            color: #4CAF50;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 16px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            position: relative;
            z-index: 2;
        }

        .score-icon {
            font-size: 14px;
        }

        .podium-section {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }

        .podium-entry {
            background: linear-gradient(145deg, #ffffff, #f5f5f5);
            border-radius: 20px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            min-width: 180px;
            position: relative;
            overflow: hidden;
        }

        .podium-entry:hover {
            transform: translateY(-5px);
        }

        .podium-entry.first {
            border: 3px solid #ffd700;
            background: linear-gradient(145deg, #fffbf0, #fff8e1);
        }

        .podium-entry.second {
            border: 3px solid #c0c0c0;
            background: linear-gradient(145deg, #f8f8f8, #f0f0f0);
        }

        .podium-entry.third {
            border: 3px solid #cd7f32;
            background: linear-gradient(145deg, #fdf5e6, #f4e4bc);
        }

        .podium-rank {
            width: 50px;
            height: 50px;
            margin: 0 auto 15px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 800;
        }

        .podium-rank.first {
            background: linear-gradient(145deg, #ffd700, #ffb300);
            color: #333;
        }

        .podium-rank.second {
            background: linear-gradient(145deg, #c0c0c0, #a0a0a0);
            color: #333;
        }

        .podium-rank.third {
            background: linear-gradient(145deg, #cd7f32, #b8860b);
            color: white;
        }

        .podium-name {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }

        .podium-score {
            font-size: 18px;
            font-weight: 700;
            color: #4CAF50;
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
            
            .leaderboard-entry {
                padding: 15px;
            }
            
            .rank-number {
                width: 50px;
                height: 50px;
                font-size: 18px;
                margin-right: 15px;
            }
            
            .user-name {
                font-size: 16px;
            }
            
            .podium-section {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>
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

    <div class="main-content">
        <div class="header">
            <div class="header-content">
                <div class="trophy-icon">🏆</div>
                <div class="welcome-text">Compete with eco-warriors worldwide and climb the sustainability rankings! 🌍</div>
            </div>
        </div>

        <h1 class="leaderboard-title">LEADERBOARDS</h1>

        <div class="leaderboard-container">
            <div class="podium-section">
                <div class="podium-entry first">
                    <div class="podium-rank first">1</div>
                    <div class="podium-name">Lance David</div>
                    <div class="podium-score">1,030 🌱</div>
                </div>
                <div class="podium-entry second">
                    <div class="podium-rank second">2</div>
                    <div class="podium-name">Jahred Myldol</div>
                    <div class="podium-score">1,023 🌱</div>
                </div>
                <div class="podium-entry third">
                    <div class="podium-rank third">3</div>
                    <div class="podium-name">Carl San Jose</div>
                    <div class="podium-score">1,010 🌱</div>
                </div>
            </div>

            <div class="leaderboard-entry">
                <div class="rank-number gold">1</div>
                <div class="user-avatar">👤</div>
                <div class="user-info">
                    <div class="user-name">Lance David</div>
                </div>
                <div class="user-score">
                    1,030 <span class="score-icon">🌱</span>
                </div>
            </div>

            <div class="leaderboard-entry">
                <div class="rank-number silver">2</div>
                <div class="user-avatar">👤</div>
                <div class="user-info">
                    <div class="user-name">Jahred Myldol</div>
                </div>
                <div class="user-score">
                    1,023 <span class="score-icon">🌱</span>
                </div>
            </div>

            <div class="leaderboard-entry">
                <div class="rank-number bronze">3</div>
                <div class="user-avatar">👤</div>
                <div class="user-info">
                    <div class="user-name">Carl San Jose</div>
                </div>
                <div class="user-score">
                    1,010 <span class="score-icon">🌱</span>
                </div>
            </div>

            <div class="leaderboard-entry">
                <div class="rank-number">4</div>
                <div class="user-avatar">👤</div>
                <div class="user-info">
                    <div class="user-name">Cyacinth Cotara</div>
                </div>
                <div class="user-score">
                    984 <span class="score-icon">🌱</span>
                </div>
            </div>

            <div class="leaderboard-entry">
                <div class="rank-number">5</div>
                <div class="user-avatar">👤</div>
                <div class="user-info">
                    <div class="user-name">Richard Bilan</div>
                </div>
                <div class="user-score">
                    800 <span class="score-icon">🌱</span>
                </div>
            </div>

            <div class="leaderboard-entry">
                <div class="rank-number">6</div>
                <div class="user-avatar">👤</div>
                <div class="user-info">
                    <div class="user-name">Maibert Colarina</div>
                </div>
                <div class="user-score">
                    785 <span class="score-icon">🌱</span>
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
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function(e) {
                
                document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
                this.classList.add('active');
            });
        });

        document.querySelectorAll('.leaderboard-entry').forEach((entry, index) => {
            entry.addEventListener('click', function() {
                this.style.transform = 'translateY(-6px) scale(1.02)';
                setTimeout(() => {
                    this.style.transform = 'translateY(-3px)';
                }, 200);
            });
            
            // Stagger animation on load
            entry.style.opacity = '0';
            entry.style.transform = 'translateY(30px)';
            setTimeout(() => {
                entry.style.transition = 'all 0.6s ease';
                entry.style.opacity = '1';
                entry.style.transform = 'translateY(0)';
            }, index * 100);
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

        document.querySelectorAll('.podium-entry').forEach((entry, index) => {
            entry.style.opacity = '0';
            entry.style.transform = 'translateY(-20px)';
            setTimeout(() => {
                entry.style.transition = 'all 0.6s ease';
                entry.style.opacity = '1';
                entry.style.transform = 'translateY(0)';
            }, 200 + index * 150);
        });
    </script>
</body>
</html>