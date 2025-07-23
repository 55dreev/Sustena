<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUSTENA - MicroForum</title>
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
            display: flex;
            gap: 20px;
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

        /* Content Layout */
        .content-layout {
            display: flex;
            gap: 30px;
            width: 100%;
        }

        .forum-content {
            flex: 2;
        }

        .sidebar-right {
            flex: 1;
            max-width: 300px;
            margin-top: 80px; 
        }

        /* New Post Button */
        .new-post-btn {
            background: linear-gradient(135deg, #66bb6a, #4caf50);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102,187,106,0.3);
            margin-bottom: 30px;
            width: 100%;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .new-post-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102,187,106,0.4);
        }

        /* Recent Posts Section */
        .recent-posts {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        }

        .section-title {
            font-size: 24px;
            font-weight: bold;
            color: #2d5016;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .post-card {
            background: rgba(255,255,255,0.9);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .post-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .post-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, #66bb6a, #4caf50);
        }

        .post-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .author-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #64b5f6, #2196f3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            margin-right: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .post-meta {
            flex: 1;
        }

        .author-name {
            font-weight: bold;
            color: #2d5016;
            font-size: 16px;
        }

        .post-topic {
            color: #666;
            font-size: 14px;
            margin-bottom: 2px;
        }

        .post-time {
            color: #999;
            font-size: 12px;
        }

        .post-content {
            color: #333;
            line-height: 1.5;
            margin-bottom: 15px;
        }

        .post-stats {
            display: flex;
            align-items: center;
            gap: 20px;
            font-size: 14px;
            color: #666;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .stat-icon {
            font-size: 16px;
        }

        /* Right Sidebar */
        .user-list {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        }

        .user-item {
            display: flex;
            align-items: center;
            padding: 15px;
            margin-bottom: 12px;
            background: rgba(255,255,255,0.8);
            border-radius: 12px;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .user-item:hover {
            background: rgba(255,255,255,0.95);
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #ffb74d, #ff9800);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            margin-right: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .user-info {
            flex: 1;
        }

        .user-name {
            font-weight: bold;
            color: #2d5016;
            font-size: 14px;
        }

        .user-status {
            color: #666;
            font-size: 12px;
        }

        .message-bubble {
            background: rgba(255,255,255,0.9);
            border-radius: 15px;
            padding: 15px;
            margin-left: 60px;
            margin-top: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-left: 3px solid #66bb6a;
        }

        .message-text {
            color: #333;
            font-size: 13px;
            line-height: 1.4;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .content-layout {
                flex-direction: column;
            }
            
            .sidebar-right {
                max-width: 100%;
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
            
            .header-title {
                font-size: 36px;
            }
            
            .content-layout {
                gap: 20px;
            }
        }

        @media (max-width: 480px) {
            .post-card {
                padding: 15px;
            }
            
            .author-avatar {
                width: 35px;
                height: 35px;
            }
            
            .user-avatar {
                width: 40px;
                height: 40px;
            }
            
            .header-title {
                font-size: 28px;
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
<a href="{{ url('/challenge') }}" class="nav-item">
  <div class="nav-icon">🏆</div>
  <span>Challenges</span>
</a>
<a href="{{ url('/forum') }}" class="nav-item active">
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
            
            <h1 class="header-title">...</h1>
        </div>

        <!-- Content Layout -->
        <div class="content-layout">
            <!-- Forum Content -->
            <div class="forum-content">
                <button class="new-post-btn">
                    + New Post
                </button>

                <!-- Recent Posts -->
                <div class="recent-posts">
                    <h2 class="section-title">Recent Posts</h2>
                    
                    <div class="post-card">
                        <div class="post-header">
                            <div class="author-avatar">R</div>
                            <div class="post-meta">
                                <div class="author-name">Richard</div>
                                <div class="post-topic">tips on reducing plastic use?</div>
                                <div class="post-time">1 hr ago</div>
                            </div>
                        </div>
                        <div class="post-content">
                            how do you minimize the use of plastic in daily lives? please share your tips and ideas!
                        </div>
                        <div class="post-stats">
                            <div class="stat-item">
                                <span class="stat-icon">👍</span>
                                <span>2 reacts</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-icon">💬</span>
                                <span>4 comments</span>
                            </div>
                        </div>
                    </div>

                    <div class="post-card">
                        <div class="post-header">
                            <div class="author-avatar">Z</div>
                            <div class="post-meta">
                                <div class="author-name">Zara</div>
                                <div class="post-topic">plant-based food recipes?</div>
                                <div class="post-time">5 hr ago</div>
                            </div>
                        </div>
                        <div class="post-content">
                            hi everyone! i'm looking for some vegan recipes can you suggest one? tyia!!
                        </div>
                        <div class="post-stats">
                            <div class="stat-item">
                                <span class="stat-icon">👍</span>
                                <span>8 reacts</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-icon">💬</span>
                                <span>5 comments</span>
                            </div>
                        </div>
                    </div>

                    <div class="post-card">
                        <div class="post-header">
                            <div class="author-avatar">M</div>
                            <div class="post-meta">
                                <div class="author-name">Maya</div>
                                <div class="post-topic">sustainable transportation</div>
                                <div class="post-time">8 hr ago</div>
                            </div>
                        </div>
                        <div class="post-content">
                            What are some eco-friendly transportation options in urban areas? Looking for alternatives to driving every day.
                        </div>
                        <div class="post-stats">
                            <div class="stat-item">
                                <span class="stat-icon">👍</span>
                                <span>12 reacts</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-icon">💬</span>
                                <span>7 comments</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar -->
            <div class="sidebar-right">
                <div class="user-list">
                    <h3 class="section-title">Active Users</h3>
                    
                    <div class="user-item">
                        <div class="user-avatar">👨</div>
                        <div class="user-info">
                            <div class="user-name">Alex</div>
                            <div class="user-status">Online</div>
                        </div>
                    </div>
                    <div class="message-bubble">
                        <div class="message-text">Great tips on composting! I started my own bin last week.</div>
                    </div>

                    <div class="user-item">
                        <div class="user-avatar">👩</div>
                        <div class="user-info">
                            <div class="user-name">Sarah</div>
                            <div class="user-status">Online</div>
                        </div>
                    </div>
                    <div class="message-bubble">
                        <div class="message-text">Anyone interested in carpooling for Earth Day events?</div>
                    </div>

                    <div class="user-item">
                        <div class="user-avatar">👨</div>
                        <div class="user-info">
                            <div class="user-name">Jake</div>
                            <div class="user-status">Online</div>
                        </div>
                    </div>
                    <div class="message-bubble">
                        <div class="message-text">Just completed the bike challenge! Feeling great!</div>
                    </div>

                    <div class="user-item">
                        <div class="user-avatar">👩</div>
                        <div class="user-info">
                            <div class="user-name">Emma</div>
                            <div class="user-status">Away</div>
                        </div>
                    </div>
                    <div class="message-bubble">
                        <div class="message-text">Check out my zero-waste kitchen setup!</div>
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

        // Post card interactions
        document.querySelectorAll('.post-card').forEach(card => {
            card.addEventListener('click', function() {
                this.style.transform = 'translateY(-5px)';
                setTimeout(() => {
                    this.style.transform = 'translateY(-3px)';
                }, 200);
            });
        });

        // New post button
        document.querySelector('.new-post-btn').addEventListener('click', function() {
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
                alert('New post composer would open here!');
            }, 150);
        });

        // User item interactions
        document.querySelectorAll('.user-item').forEach(item => {
            item.addEventListener('click', function() {
                this.style.transform = 'translateX(8px)';
                setTimeout(() => {
                    this.style.transform = 'translateX(5px)';
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

        // Add hover effects for post stats
        document.querySelectorAll('.stat-item').forEach(stat => {
            stat.addEventListener('mouseenter', function() {
                this.style.color = '#66bb6a';
                this.style.transform = 'scale(1.1)';
            });
            
            stat.addEventListener('mouseleave', function() {
                this.style.color = '#666';
                this.style.transform = 'scale(1)';
            });
        });

        // Simulate real-time updates
        setInterval(() => {
            const timeElements = document.querySelectorAll('.post-time');
            timeElements.forEach(element => {
                const currentTime = element.textContent;
                // Simple time increment simulation
                if (currentTime.includes('hr ago')) {
                    const hours = parseInt(currentTime);
                    if (hours < 24) {
                        // Randomly update some posts
                        if (Math.random() < 0.1) {
                            element.textContent = `${hours + 1} hr ago`;
                        }
                    }
                }
            });
        }, 30000); // Update every 30 seconds
    </script>
</body>
</html>