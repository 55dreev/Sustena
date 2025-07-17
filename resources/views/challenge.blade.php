<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUSTENA - Challenges</title>
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

        /* Main Content */
        .main-content {
            margin-left: 200px;
            flex: 1;
            padding: 20px;
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

        /* Header Section */
        .header-section {
            background: linear-gradient(135deg, #87ceeb 0%, #b0e0e6 100%);
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            margin-bottom: 40px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        }

        .header-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="3" fill="white" opacity="0.3"/><circle cx="80" cy="40" r="2" fill="white" opacity="0.2"/><circle cx="40" cy="70" r="2.5" fill="white" opacity="0.25"/></svg>');
            animation: float 20s infinite linear;
        }

        @keyframes float {
            0% { transform: translateX(-100%) translateY(-100%) rotate(0deg); }
            100% { transform: translateX(0%) translateY(0%) rotate(360deg); }
        }

        .cloud {
            position: absolute;
            color: white;
            font-size: 24px;
            opacity: 0.8;
        }

        .cloud-1 { top: 20px; left: 15%; animation: drift 15s infinite ease-in-out; }
        .cloud-2 { top: 30px; right: 15%; animation: drift 20s infinite ease-in-out reverse; }
        .cloud-3 { bottom: 40px; left: 25%; animation: drift 18s infinite ease-in-out; }
        .cloud-4 { bottom: 50px; right: 25%; animation: drift 22s infinite ease-in-out reverse; }

        @keyframes drift {
            0%, 100% { transform: translateX(0px); }
            50% { transform: translateX(20px); }
        }

        .header-title {
            font-size: 48px;
            font-weight: bold;
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            z-index: 1;
            position: relative;
            margin-bottom: 20px;
        }

        .header-subtitle {
            background: rgba(255,255,255,0.9);
            color: #2d5016;
            padding: 15px 30px;
            border-radius: 25px;
            font-size: 18px;
            font-weight: 500;
            display: inline-block;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            z-index: 1;
            position: relative;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Challenge Cards Grid */
        .challenges-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
            max-width: 900px;
            margin: 0 auto;
        }

        .challenge-card {
            background: rgba(255,255,255,0.95);
            border-radius: 20px;
            padding: 30px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            border: 3px solid transparent;
        }

        .challenge-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 45px rgba(0,0,0,0.2);
            border-color: #66bb6a;
        }

        .challenge-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(102,187,106,0.1), transparent);
            transform: rotate(45deg);
            transition: all 0.5s ease;
            opacity: 0;
        }

        .challenge-card:hover::before {
            opacity: 1;
            transform: rotate(45deg) translate(50%, 50%);
        }

        .challenge-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            position: relative;
            z-index: 1;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .meatless .challenge-icon {
            background: linear-gradient(135deg, #e57373, #d32f2f);
        }

        .energy .challenge-icon {
            background: linear-gradient(135deg, #ffb74d, #ff9800);
        }

        .bike .challenge-icon {
            background: linear-gradient(135deg, #81c784, #4caf50);
        }

        .waste .challenge-icon {
            background: linear-gradient(135deg, #64b5f6, #2196f3);
        }

        .challenge-content {
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .challenge-title {
            font-size: 22px;
            font-weight: bold;
            color: #2d5016;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .challenge-subtitle {
            font-size: 16px;
            color: #666;
            margin-bottom: 15px;
            font-weight: 500;
        }

        .challenge-description {
            font-size: 14px;
            color: #777;
            line-height: 1.4;
            margin-bottom: 20px;
        }

        .challenge-button {
            background: linear-gradient(135deg, #66bb6a, #4caf50);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(102,187,106,0.3);
        }

        .challenge-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102,187,106,0.4);
        }

        .challenge-button:active {
            transform: translateY(0);
        }

        .challenge-points {
            position: absolute;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #ffd54f, #ffb300);
            color: #333;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            box-shadow: 0 2px 10px rgba(255,213,79,0.3);
        }

        /* Difficulty Indicators */
        .difficulty {
            display: flex;
            justify-content: center;
            gap: 3px;
            margin-bottom: 15px;
        }

        .difficulty-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #ddd;
        }

        .difficulty-dot.active {
            background: #66bb6a;
        }

        /* Responsive Design */
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
            
            .challenges-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .header-title {
                font-size: 36px;
            }
            
            .header-subtitle {
                font-size: 16px;
                padding: 12px 20px;
            }
        }

        @media (max-width: 480px) {
            .challenge-card {
                padding: 20px;
            }
            
            .challenge-icon {
                width: 60px;
                height: 60px;
                font-size: 30px;
            }
            
            .challenge-title {
                font-size: 18px;
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
    </script>
</body>
</html>