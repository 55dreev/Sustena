<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUSTENA - MicroForum</title>
   <link rel="stylesheet" href="{{ asset('css/forum.css') }}">
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