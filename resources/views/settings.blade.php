<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUSTENA - Settings</title>
 <link rel="stylesheet" href="{{ asset('css/settings.css') }}">
 <link rel="stylesheet" href="{{ asset('css/settingsresponsive.css') }}">
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
    <a href="{{ url('/footprint-calculator') }}" class="nav-item active">
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
        // Toggle sidebar
  function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    sidebar.classList.toggle('collapsed');
    mainContent.classList.toggle('expanded');
  }
        // Navigation functionality
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function(e) {
                document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Toggle switch functionality
        // Toggle switch functionality
function toggleSwitch(element) {
    element.classList.toggle('active');

    // Animate press
    element.style.transform = 'scale(0.95)';
    setTimeout(() => {
        element.style.transform = 'scale(1)';
    }, 150);

    // If it's the Dark Mode toggle
    const label = element.previousElementSibling?.querySelector('.setting-sublabel');
    if (label && label.textContent.includes('Dark mode')) {
        const isActive = element.classList.contains('active');
        document.body.classList.toggle('dark-mode', isActive);
        localStorage.setItem('darkMode', isActive ? 'enabled' : 'disabled');
    }
}

// On page load, apply saved dark mode preference
document.addEventListener('DOMContentLoaded', () => {
    const darkMode = localStorage.getItem('darkMode');
    const darkModeSwitch = Array.from(document.querySelectorAll('.toggle-container'))
        .find(container => container.textContent.includes('Dark mode'))
        ?.querySelector('.toggle-switch');

    if (darkMode === 'enabled') {
        document.body.classList.add('dark-mode');
        if (darkModeSwitch) darkModeSwitch.classList.add('active');
    } else {
        document.body.classList.remove('dark-mode');
        if (darkModeSwitch) darkModeSwitch.classList.remove('active');
    }
});


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