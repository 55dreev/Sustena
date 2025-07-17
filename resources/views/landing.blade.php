<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoSteps - Environmental Tracking</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
             font-family: 'Arial', sans-serif;
             /* keep a fallback gradient behind the image */
             background: linear-gradient(135deg, #a8e6cf 0%, #7fcdcd 100%);
             min-height: 100vh;
             display: flex;
             position: relative;          /* needed for the overlay */
             overflow-x: hidden;          /* keeps pseudo‑element tidy */
           
        }
        body::before {
    content: "";
    position: fixed;             /* pin it to the viewport */
    inset: 0;                    /* shorthand for top/ right/ bottom/ left: 0 */
    background: url'sustena2.jpg' center/cover no-repeat;
    opacity: 0.25;               /* <<< tweak this (0–1) for lighter/darker */
    z-index: -1;                 /* push behind all page content */
    pointer-events: none;        /* so it never blocks clicks */
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
            image-rendering: pixelated;
            image-rendering: -moz-crisp-edges;
            image-rendering: crisp-edges;
            filter: contrast(1.2) brightness(1.1);
            box-shadow: 
                inset 1px 1px 0px rgba(255,255,255,0.3),
                inset -1px -1px 0px rgba(0,0,0,0.2);
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

        

        

        .mission-section {
            background: linear-gradient(135deg, #66bb6a 0%, #4caf50 100%);
            padding: 24px;
            border-radius: 16px;
            margin-bottom: 20px;
            color: white;
            box-shadow: 0 8px 32px rgba(76, 175, 80, 0.3);
            transform: translateY(0);
            transition: transform 0.3s ease;
        }

        .mission-section:hover {
            transform: translateY(-2px);
        }

        .mission-header {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
        }

        .mission-icon {
            width: 32px;
            height: 32px;
            background: rgba(255,255,255,0.2);
            border-radius: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 18px;
            image-rendering: pixelated;
            image-rendering: -moz-crisp-edges;
            image-rendering: crisp-edges;
            filter: contrast(1.2) brightness(1.1);
            box-shadow: 
                inset 1px 1px 0px rgba(255,255,255,0.3),
                inset -1px -1px 0px rgba(0,0,0,0.2);
        }

        .mission-title {
            font-size: 20px;
            font-weight: 600;
        }

        .mission-text {
            font-size: 16px;
            line-height: 1.4;
        }

        .eco-tip {
            background: linear-gradient(135deg, #66bb6a 0%, #4caf50  100%);
            padding: 24px;
            border-radius: 16px;
            margin-bottom: 30px;
            color: white;
            box-shadow: 0 8px 32px rgba(129, 199, 132, 0.3);
        }

        .eco-tip-header {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
        }

        .eco-tip-icon {
            width: 32px;
            height: 32px;
            background: rgba(255,255,255,0.2);
            border-radius: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 18px;
            image-rendering: pixelated;
            image-rendering: -moz-crisp-edges;
            image-rendering: crisp-edges;
            filter: contrast(1.2) brightness(1.1);
            box-shadow: 
                inset 1px 1px 0px rgba(255,255,255,0.3),
                inset -1px -1px 0px rgba(0,0,0,0.2);
        }

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            padding: 24px;
            border-radius: 16px;
            color: white;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 48px rgba(0,0,0,0.2);
        }

        .card::before {
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

        .streak-card {
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            box-shadow: 0 8px 32px rgba(255, 107, 53, 0.3);
        }

        .badges-card {
            background: linear-gradient(135deg, #ffd54f 0%, #ffb300 100%);
            box-shadow: 0 8px 32px rgba(255, 213, 79, 0.3);
        }

        .co2-card {
            background: linear-gradient(135deg, #4caf50 0%, #2e7d32 100%);
            box-shadow: 0 8px 32px rgba(76, 175, 80, 0.3);
        }

        .progress-card {
            background: linear-gradient(135deg, #66bb6a 0%, #388e3c 100%);
            box-shadow: 0 8px 32px rgba(102, 187, 106, 0.3);
        }

        .water-saver-card {
            background: linear-gradient(135deg, #42a5f5 0%, #1976d2 100%);
            box-shadow: 0 8px 32px rgba(66, 165, 245, 0.3);
        }

        .energy-card {
            background: linear-gradient(135deg, #ffa726 0%, #fb8c00 100%);
            box-shadow: 0 8px 32px rgba(255, 167, 38, 0.3);
        }

        .leaderboard-card {
            background: linear-gradient(135deg, #ab47bc 0%, #8e24aa 100%);
            box-shadow: 0 8px 32px rgba(171, 71, 188, 0.3);
        }

        .weekly-goal-card {
            background: linear-gradient(135deg, #26c6da 0%, #0097a7 100%);
            box-shadow: 0 8px 32px rgba(38, 198, 218, 0.3);
        }

        .impact-card {
            background: linear-gradient(135deg, #5c6bc0 0%, #3949ab 100%);
            box-shadow: 0 8px 32px rgba(92, 107, 192, 0.3);
        }

        .rank-change {
            font-size: 12px;
            opacity: 0.9;
            margin-top: 4px;
            font-weight: 600;
        }

        .card-icon {
            width: 48px;
            height: 48px;
            background: rgba(255,255,255,0.2);
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            font-size: 24px;
            image-rendering: pixelated;
            image-rendering: -moz-crisp-edges;
            image-rendering: crisp-edges;
            filter: contrast(1.2) brightness(1.1);
            box-shadow: 
                inset 2px 2px 0px rgba(255,255,255,0.3),
                inset -2px -2px 0px rgba(0,0,0,0.2),
                0px 0px 0px 1px rgba(0,0,0,0.1);
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }

        .card-subtitle {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 4px;
            position: relative;
            z-index: 1;
        }

        .card-text {
            font-size: 14px;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .view-all-btn {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-top: 16px;
        }

        .view-all-btn:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-1px);
        }

        .badge-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-top: 12px;
        }

        .badge {
            width: 32px;
            height: 32px;
            background: rgba(255,255,255,0.2);
            border-radius: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            image-rendering: pixelated;
            image-rendering: -moz-crisp-edges;
            image-rendering: crisp-edges;
            filter: contrast(1.2) brightness(1.1);
            box-shadow: 
                inset 1px 1px 0px rgba(255,255,255,0.3),
                inset -1px -1px 0px rgba(0,0,0,0.2);
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            background: rgba(255,255,255,0.2);
            border-radius: 4px;
            overflow: hidden;
            margin-top: 12px;
        }

        .progress-fill {
            height: 100%;
            background: rgba(255,255,255,0.8);
            border-radius: 4px;
            width: 60%;
            transition: width 0.3s ease;
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
            
            .cards-grid {
                grid-template-columns: 1fr;
            }
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
            image-rendering: pixelated;
            image-rendering: -moz-crisp-edges;
            image-rendering: crisp-edges;
            filter: contrast(1.2) brightness(1.1);
        }

        .floating-icon:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <<div class="sidebar">
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
        <div class="floating-icon">🔥</div>
        <div class="floating-icon">🌱</div>
        <div class="floating-icon">🏆</div>
        <div class="floating-icon">🥇</div>
        <div class="floating-icon">⚙️</div>
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
    </script>
</body>
</html>