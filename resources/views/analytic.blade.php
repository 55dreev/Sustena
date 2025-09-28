<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUSTENA - Carbon Footprint Tracker</title>
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

        .floating-icon:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        }
        .floating-icons a {
    text-decoration: none; /* removes underline */
    color: inherit;        /* keeps the emoji color instead of blue */
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
        }

        .welcome-text {
            color: #2d5a3d;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .footprint-display {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 30px 0;
            gap: 40px;
        }

        .footprint-visual {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .footprint {
            width: 120px;
            height: 160px;
            background: linear-gradient(145deg, #66bb6a, #4caf50);
            border-radius: 60px 60px 40px 40px;
            position: relative;
            box-shadow: 0 10px 30px rgba(76, 175, 80, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            animation: float 3s ease-in-out infinite;
        }

        .footprint:nth-child(2) {
            animation-delay: -1.5s;
        }

        .footprint::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 100px;
            background: linear-gradient(145deg, #81c784, #66bb6a);
            border-radius: 40px 40px 30px 30px;
            box-shadow: inset 0 4px 8px rgba(0,0,0,0.1);
        }

        .footprint-face {
            position: relative;
            z-index: 2;
            font-size: 24px;
        }

        .toes {
            position: absolute;
            top: -10px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 4px;
        }

        .toe {
            width: 12px;
            height: 16px;
            background: linear-gradient(145deg, #81c784, #66bb6a);
            border-radius: 50% 50% 30% 30%;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .breakdown-section {
            background: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }

        .breakdown-title {
            font-size: 24px;
            font-weight: 700;
            color: #2d5a3d;
            margin-bottom: 25px;
            text-align: center;
        }

        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .category-card {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            border: 3px solid transparent;
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 50px rgba(0,0,0,0.15);
        }

        .category-card.housing {
            border-color: #2196F3;
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
        }

        .category-card.food {
            border-color: #4CAF50;
            background: linear-gradient(135deg, #e8f5e8, #c8e6c9);
        }

        .category-card.travel {
            border-color: #FF9800;
            background: linear-gradient(135deg, #fff3e0, #ffe0b2);
        }

        .category-card.waste {
            border-color: #E91E63;
            background: linear-gradient(135deg, #fce4ec, #f8bbd9);
        }

        .category-card.electricity {
            border-color: #FFC107;
            background: linear-gradient(135deg, #fffde7, #fff9c4);
        }

        .category-icon {
            font-size: 40px;
            margin-bottom: 15px;
            display: block;
        }

        .category-name {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }

        .category-percentage {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .housing .category-percentage { color: #2196F3; }
        .food .category-percentage { color: #4CAF50; }
        .travel .category-percentage { color: #FF9800; }
        .waste .category-percentage { color: #E91E63; }
        .electricity .category-percentage { color: #FFC107; }

        .category-subtitle {
            font-size: 12px;
            color: #666;
            margin-bottom: 15px;
        }

        .action-button {
            background: linear-gradient(135deg, #66bb6a, #4caf50);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .action-button:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: linear-gradient(135deg, #66bb6a 0%, #4caf50 100%);
            color: white;
            padding: 25px;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 8px 32px rgba(76, 175, 80, 0.3);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
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

        .stat-value {
            font-size: 36px;
            font-weight: 800;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }

        .stat-label {
            font-size: 16px;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .stat-sublabel {
            font-size: 14px;
            opacity: 0.8;
            margin-top: 4px;
            position: relative;
            z-index: 1;
        }

        .warning-badge {
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            color: white;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            margin-top: 8px;
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
            
            .footprint-display {
                flex-direction: column;
                gap: 20px;
            }
            
            .stats-grid, .category-grid {
                grid-template-columns: 1fr;
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
                <div class="welcome-text">Track your environmental impact and make every step count! 🌍</div>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">127%</div>
                <div class="stat-label">Your Footprint</div>
                <div class="stat-sublabel">Tonnes</div>
                <div class="warning-badge">⚠️ Above Average</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">127%</div>
                <div class="stat-label">Your Footprint</div>
                <div class="stat-sublabel">Tonnes</div>
                <div class="warning-badge">⚠️ Above Average</div>
            </div>
        </div>

        <div class="footprint-display">
            <div class="footprint-visual">
                <div class="footprint">
                    <div class="toes">
                        <div class="toe"></div>
                        <div class="toe"></div>
                        <div class="toe"></div>
                        <div class="toe"></div>
                        <div class="toe"></div>
                    </div>
                    <div class="footprint-face"></div>
                </div>
                <div class="footprint">
                    <div class="toes">
                        <div class="toe"></div>
                        <div class="toe"></div>
                        <div class="toe"></div>
                        <div class="toe"></div>
                        <div class="toe"></div>
                    </div>
                    <div class="footprint-face"></div>
                </div>
            </div>
        </div>

        <div class="breakdown-section">
            <h2 class="breakdown-title">Break Down</h2>
            <div class="category-grid">
                <div class="category-card housing">
                    <div class="category-icon">🏠</div>
                    <div class="category-name">Housing</div>
                    <div class="category-percentage">45%</div>
                    <div class="category-subtitle">10%</div>
                    <button class="action-button">Reduce this score</button>
                </div>
                
                <div class="category-card food">
                    <div class="category-icon">🍎</div>
                    <div class="category-name">Food</div>
                    <div class="category-percentage">27%</div>
                    <div class="category-subtitle"></div>
                    <button class="action-button">Reduce this score</button>
                </div>
                
                <div class="category-card travel">
                    <div class="category-icon">🚗</div>
                    <div class="category-name">Travel</div>
                    <div class="category-percentage">15%</div>
                    <div class="category-subtitle"></div>
                    <button class="action-button">Reduce this score</button>
                </div>
                
                <div class="category-card waste">
                    <div class="category-icon">🗑️</div>
                    <div class="category-name">Waste</div>
                    <div class="category-percentage">13%</div>
                    <div class="category-subtitle"></div>
                    <button class="action-button">Reduce this score</button>
                </div>
                
                <div class="category-card electricity">
                    <div class="category-icon">⚡</div>
                    <div class="category-name">Electricity</div>
                    <div class="category-percentage">13%</div>
                    <div class="category-subtitle"></div>
                    <button class="action-button">Reduce this score</button>
                </div>
            </div>
        </div>
    </div>

    <div class="floating-icons">
    <a href="{{ url('/streak') }}">
        <div class="floating-icon">🔥</div>
    </a>
    <a href="{{ url('/anal') }}">
        <div class="floating-icon">🌱</div>
    </a>
    <a href="{{ url('/leaderboard') }}">
        <div class="floating-icon">🏆</div>
    </a>
    <a href="{{ url('/badge') }}">
        <div class="floating-icon">🥇</div>
    </a>
    <a href="{{ url('/setting') }}">
        <div class="floating-icon">⚙️</div>
    </a>
</div>


    <script>
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function(e) {
                
                document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
                this.classList.add('active');
            });
        });

        document.querySelectorAll('.category-card').forEach(card => {
            card.addEventListener('click', function() {
                this.style.transform = 'translateY(-8px) scale(1.02)';
                setTimeout(() => {
                    this.style.transform = 'translateY(-5px)';
                }, 200);
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

        document.querySelectorAll('.action-button').forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                this.style.transform = 'scale(0.95)';
                this.innerHTML = 'Loading...';
                setTimeout(() => {
                    this.innerHTML = 'Reduce this score';
                    this.style.transform = 'scale(1.05)';
                }, 1000);
            });
        });
    </script>
</body>
</html>