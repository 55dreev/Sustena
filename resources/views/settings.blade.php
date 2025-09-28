<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUSTENA - Settings</title>
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
    text-decoration: none;
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

        .settings-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .settings-section {
            background: linear-gradient(135deg, #66bb6a 0%, #4caf50 100%);
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 40px rgba(76, 175, 80, 0.2);
            position: relative;
            overflow: hidden;
        }

        .settings-section::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 100px;
            height: 100px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .section-title {
            color: white;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }

        .setting-group {
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }

        .setting-group:last-child {
            margin-bottom: 0;
        }

        .setting-label {
            color: white;
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
        }

        .setting-sublabel {
            color: rgba(255,255,255,0.8);
            font-size: 14px;
            margin-bottom: 12px;
            display: block;
        }

        .toggle-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .toggle-switch {
            position: relative;
            width: 60px;
            height: 30px;
            background: rgba(255,255,255,0.3);
            border-radius: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .toggle-switch.active {
            background: rgba(255,255,255,0.5);
        }

        .toggle-slider {
            position: absolute;
            top: 3px;
            left: 3px;
            width: 24px;
            height: 24px;
            background: white;
            border-radius: 50%;
            transition: all 0.3s ease;
            box-shadow: 0 2px 6px rgba(0,0,0,0.3);
        }

        .toggle-switch.active .toggle-slider {
            transform: translateX(30px);
            background: #2d5a3d;
        }

        .bottom-section {
            background: linear-gradient(135deg, #66bb6a 0%, #4caf50 100%);
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 40px rgba(76, 175, 80, 0.2);
            position: relative;
            overflow: hidden;
            margin-top: 20px;
        }

        .bottom-section::before {
            content: '';
            position: absolute;
            top: -30px;
            right: -30px;
            width: 80px;
            height: 80px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .support-links {
            display: flex;
            flex-direction: column;
            gap: 12px;
            position: relative;
            z-index: 1;
        }

        .support-link {
            color: white;
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
            padding: 12px 16px;
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .support-link:hover {
            background: rgba(255,255,255,0.2);
            transform: translateX(5px);
        }

        .support-link::after {
            content: '→';
            font-size: 18px;
            transition: transform 0.3s ease;
        }

        .support-link:hover::after {
            transform: translateX(5px);
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
            
            .settings-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Enhanced animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .settings-section, .bottom-section {
            animation: fadeInUp 0.6s ease forwards;
        }

        .settings-section:nth-child(2) {
            animation-delay: 0.1s;
        }

        .bottom-section {
            animation-delay: 0.2s;
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
                <div class="welcome-text">Customize your SUSTENA experience and preferences 🛠️</div>
            </div>
        </div>

        <div class="settings-grid">
            <div class="settings-section">
                <h2 class="section-title">Preferences</h2>
                
                <div class="setting-group">
                    <div class="setting-label">Appearance</div>
                </div>
                
                <div class="toggle-container">
                    <div>
                        <span class="setting-sublabel">Dark mode</span>
                    </div>
                    <div class="toggle-switch" onclick="toggleSwitch(this)">
                        <div class="toggle-slider"></div>
                    </div>
                </div>
                
                <div class="toggle-container">
                    <div>
                        <span class="setting-sublabel">Eco mode</span>
                    </div>
                    <div class="toggle-switch active" onclick="toggleSwitch(this)">
                        <div class="toggle-slider"></div>
                    </div>
                </div>
                
                <div class="toggle-container">
                    <div>
                        <span class="setting-sublabel">Light mode</span>
                    </div>
                    <div class="toggle-switch" onclick="toggleSwitch(this)">
                        <div class="toggle-slider"></div>
                    </div>
                </div>
                
                <div class="setting-group">
                    <div class="setting-label">Tracking Experience</div>
                </div>
                
                <div class="toggle-container">
                    <div>
                        <span class="setting-sublabel">Animations</span>
                    </div>
                    <div class="toggle-switch active" onclick="toggleSwitch(this)">
                        <div class="toggle-slider"></div>
                    </div>
                </div>
                
                <div class="toggle-container">
                    <div>
                        <span class="setting-sublabel">Sound effects</span>
                    </div>
                    <div class="toggle-switch active" onclick="toggleSwitch(this)">
                        <div class="toggle-slider"></div>
                    </div>
                </div>
            </div>

            <div class="settings-section">
                <h2 class="section-title">Notification Settings</h2>
                
                <div class="toggle-container">
                    <div>
                        <span class="setting-sublabel">Mission reminders</span>
                    </div>
                    <div class="toggle-switch active" onclick="toggleSwitch(this)">
                        <div class="toggle-slider"></div>
                    </div>
                </div>
                
                <div class="toggle-container">
                    <div>
                        <span class="setting-sublabel">Streak alerts</span>
                    </div>
                    <div class="toggle-switch active" onclick="toggleSwitch(this)">
                        <div class="toggle-slider"></div>
                    </div>
                </div>
                
                <div class="toggle-container">
                    <div>
                        <span class="setting-sublabel">New badges unlock</span>
                    </div>
                    <div class="toggle-switch active" onclick="toggleSwitch(this)">
                        <div class="toggle-slider"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bottom-section">
            <h2 class="section-title">Support and Legal</h2>
            <div class="support-links">
                <a href="#" class="support-link">Help Center / FAQ</a>
                <a href="#" class="support-link">Privacy Policy</a>
                <a href="#" class="support-link">Contact and Support</a>
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
        // Navigation functionality
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function(e) {
                document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Toggle switch functionality
        function toggleSwitch(element) {
            element.classList.toggle('active');
            
            // Add a smooth animation effect
            element.style.transform = 'scale(0.95)';
            setTimeout(() => {
                element.style.transform = 'scale(1)';
            }, 150);
        }

        // Floating icons animation
        document.querySelectorAll('.floating-icon').forEach((icon, index) => {
            icon.style.animationDelay = `${index * 0.2}s`;
            icon.addEventListener('click', function() {
                this.style.transform = 'scale(1.2) rotate(360deg)';
                setTimeout(() => {
                    this.style.transform = 'scale(1.1)';
                }, 300);
            });
        });

        // Support links interaction
        document.querySelectorAll('.support-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                this.style.transform = 'translateX(10px) scale(0.98)';
                setTimeout(() => {
                    this.style.transform = 'translateX(5px)';
                }, 200);
            });
        });

        // Add subtle hover effects to settings sections
        document.querySelectorAll('.settings-section, .bottom-section').forEach(section => {
            section.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 15px 50px rgba(76, 175, 80, 0.3)';
            });
            
            section.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 10px 40px rgba(76, 175, 80, 0.2)';
            });
        });
    </script>
</body>
</html>