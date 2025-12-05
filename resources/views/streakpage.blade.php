<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SUSTENA - Streak & Achievements</title>
    <link rel="stylesheet" href="{{ asset('css/streakpage.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
</head>
<body>
    <!-- Sidebar Backdrop -->
    <div class="sidebar-backdrop" id="sidebar-backdrop" onclick="closeSidebar()"></div>

    <!-- Sidebar Toggle Button -->
    <button class="mobile-hamburger" id="hamburger-btn" onclick="toggleMobileSidebar()" aria-label="Toggle navigation menu" aria-expanded="false">☰</button>

    <div class="sidebar" id="sidebar">
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
        <a href="{{ url('/challenge') }}" class="nav-item">
            <div class="nav-icon">🏆</div>
            <span>Challenges</span>
        </a>
        <a href="{{ url('/forum') }}" class="nav-item">
            <div class="nav-icon">💬</div>
            <span>MicroForum</span>
        </a>
        <a href="{{ url('/visual-progress') }}" class="nav-item">
            <div class="nav-icon">🌍</div>
            <span>Your Planet</span>
        </a>
        <a href="{{ url('/profile') }}" class="nav-item">
            <div class="nav-icon">👤</div>
            <span>Profile</span>
        </a>
    </div>

    <div class="main-content">
        <div class="header">
            <div class="header-content">
                <div class="welcome-text" id="motivationalText">Amazing work! You're on fire with your eco-friendly habits! 🔥</div>
                <div class="header-stats">
                    <div class="stat-pill">
                        <span class="stat-label">🎯 Streak</span>
                        <span class="stat-value" id="headerStreakDays">{{ $streak_days ?? 0 }}</span>
                    </div>
                    <div class="stat-pill">
                        <span class="stat-label">⏰ Next Reset</span>
                        <span class="stat-value" id="countdown">--:--:--</span>
                    </div>
                    <div class="stat-pill">
                        <span class="stat-label">✓ Completed</span>
                        <span class="stat-value" id="headerTasksCompleted">{{ count($tasks ?? []) > 0 ? collect($tasks)->where('is_completed', true)->count() : 0 }}/{{ count($tasks ?? []) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-grid">
            <div class="streak-section">
                <br><br>
                <div class="streak-flame">🔥</div>
                <div class="streak-title">You're in a<br><span id="streakDays">{{ $streak_days ?? 0 }}</span> Day Streak</div>
                <div class="trophy">🏆</div>

                <!-- Milestone Progress -->
                <div class="milestone-tracker">
                    <div class="milestone-text" id="milestoneText">
                        @php
                            $currentStreak = $streak_days ?? 0;
                            $nextMilestone = $currentStreak < 10 ? 10 : ($currentStreak < 20 ? 20 : ($currentStreak < 30 ? 30 : 50));
                            $remaining = $nextMilestone - $currentStreak;
                        @endphp
                        {{ $remaining > 0 ? "$remaining days to $nextMilestone day badge!" : "You're amazing! Keep going!" }}
                    </div>
                    <div class="milestone-progress-bar">
                        @php
                            $prevMilestone = $currentStreak < 10 ? 0 : ($currentStreak < 20 ? 10 : ($currentStreak < 30 ? 20 : 30));
                            $progress = $nextMilestone > $prevMilestone ? (($currentStreak - $prevMilestone) / ($nextMilestone - $prevMilestone)) * 100 : 0;
                        @endphp
                        <div class="milestone-fill" style="width: {{ min($progress, 100) }}%"></div>
                    </div>
                </div>
                <br><br>
                <div class="robot-mascot" id="robotMascot"
                     data-head-color="{{ $equipped_skin->head_color ?? '#ffffff' }}"
                     data-body-color="{{ $equipped_skin->body_color ?? '#ffffff' }}"
                     data-accent-color="{{ $equipped_skin->accent_color ?? '#2196f3' }}"
                     data-special-effect="{{ $equipped_skin->special_effect ?? '' }}">
                    <div class="robot-head" style="background: linear-gradient(135deg, {{ $equipped_skin->head_color ?? '#ffffff' }} 0%, {{ $equipped_skin->head_color ?? '#f5f5f5' }} 100%);">
                        <div class="robot-antenna"></div>
                        <div class="robot-eyes">
                            <div class="robot-eye"></div>
                            <div class="robot-eye"></div>
                        </div>
                        <div class="robot-mouth"></div>
                    </div>
                    <div class="robot-body" style="background: linear-gradient(135deg, {{ $equipped_skin->body_color ?? '#ffffff' }} 0%, {{ $equipped_skin->body_color ?? '#e3f2fd' }} 50%, {{ $equipped_skin->body_color ?? '#bbdefb' }} 100%);">
                        <div class="robot-arms">
                            <div class="robot-arm left" style="background: linear-gradient(135deg, {{ $equipped_skin->body_color ?? '#ffffff' }} 0%, {{ $equipped_skin->body_color ?? '#e3f2fd' }} 100%);"></div>
                            <div class="robot-arm right" style="background: linear-gradient(135deg, {{ $equipped_skin->body_color ?? '#ffffff' }} 0%, {{ $equipped_skin->body_color ?? '#e3f2fd' }} 100%);"></div>
                        </div>
                    </div>
                </div>

                <!-- Shop Button -->
                <button class="robot-shop-btn" onclick="openShopModal()" title="Customize your robot!">
                    🛍️ Robot Shop
                </button>

                <br>
                <!-- Quick Stats -->
                <div class="quick-streak-stats">
                    <div class="quick-stat">
                        <div class="quick-stat-icon">📊</div>
                        <div class="quick-stat-info">
                            <div class="quick-stat-label">Best Streak</div>
                            <div class="quick-stat-value">{{ $streak_days ?? 0 }} days</div>
                        </div>
                    </div>
                    <div class="quick-stat">
                        <div class="quick-stat-icon">🌟</div>
                        <div class="quick-stat-info">
                            <div class="quick-stat-label">Total XP</div>
                            <div class="quick-stat-value" id="totalXP">0 XP</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="achievements-section">
                <div class="achievements-header">
                    <div class="achievements-title">Achievement Badges</div>
                    <div class="badge-count" id="badgeCount">{{ collect($badges ?? [])->where('earned', true)->count() }}/3</div>
                </div>

                <div class="badge-row">
                    <div class="achievement-badge badge-10-day {{ $badges[0]['earned'] ?? false ? 'earned' : '' }}"
                         data-badge-name="10 Day Warrior"
                         data-badge-desc="Complete 10 consecutive days"
                         data-earned="{{ $badges[0]['earned'] ?? false ? 'true' : 'false' }}">
                        🏅
                        <div class="badge-tooltip">
                            <div class="tooltip-title">10 Day Warrior</div>
                            <div class="tooltip-desc">Complete 10 consecutive days of eco-tasks</div>
                            <div class="tooltip-status {{ $badges[0]['earned'] ?? false ? 'earned' : '' }}">
                                {{ $badges[0]['earned'] ?? false ? '✓ Earned!' : '🔒 Locked' }}
                            </div>
                        </div>
                    </div>
                    <div class="achievement-badge badge-20-day {{ $badges[1]['earned'] ?? false ? 'earned' : '' }}"
                         data-badge-name="20 Day Champion"
                         data-badge-desc="Complete 20 consecutive days"
                         data-earned="{{ $badges[1]['earned'] ?? false ? 'true' : 'false' }}">
                        🏅
                        <div class="badge-tooltip">
                            <div class="tooltip-title">20 Day Champion</div>
                            <div class="tooltip-desc">Complete 20 consecutive days of eco-tasks</div>
                            <div class="tooltip-status {{ $badges[1]['earned'] ?? false ? 'earned' : '' }}">
                                {{ $badges[1]['earned'] ?? false ? '✓ Earned!' : '🔒 Locked' }}
                            </div>
                        </div>
                    </div>
                    <div class="achievement-badge badge-30-day {{ $badges[2]['earned'] ?? false ? 'earned' : '' }}"
                         data-badge-name="30 Day Legend"
                         data-badge-desc="Complete 30 consecutive days"
                         data-earned="{{ $badges[2]['earned'] ?? false ? 'true' : 'false' }}">
                        🏅
                        <div class="badge-tooltip">
                            <div class="tooltip-title">30 Day Legend</div>
                            <div class="tooltip-desc">Complete 30 consecutive days of eco-tasks</div>
                            <div class="tooltip-status {{ $badges[2]['earned'] ?? false ? 'earned' : '' }}">
                                {{ $badges[2]['earned'] ?? false ? '✓ Earned!' : '🔒 Locked' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="badge-row badge-labels">
                    <span class="badge-label">10 DAY<br>STREAK</span>
                    <span class="badge-label">20 DAY<br>STREAK</span>
                    <span class="badge-label">30 DAY<br>STREAK</span>
                </div>

                <div class="checklist">
                    <div class="checklist-header">
                        <div class="checklist-title">Daily Eco-Tasks</div>
                        <div class="tasks-summary">
                            <span id="tasksCompleted">{{ count($tasks ?? []) > 0 ? collect($tasks)->where('is_completed', true)->count() : 0 }}</span>/<span id="tasksTotal">{{ count($tasks ?? []) }}</span> completed
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="progress-container">
                        <div class="progress-bar" id="taskProgress" style="width: {{ count($tasks ?? []) > 0 ? (collect($tasks)->where('is_completed', true)->count() / count($tasks)) * 100 : 0 }}%">
                            <span class="progress-text">{{ count($tasks ?? []) > 0 ? round((collect($tasks)->where('is_completed', true)->count() / count($tasks)) * 100) : 0 }}%</span>
                        </div>
                    </div>

                    <div class="tasks-container" id="tasksContainer">
                        @foreach($tasks ?? [] as $task)
                        <div class="task-item {{ $task->is_completed ? 'completed' : '' }}" data-task-id="{{ $task->id }}">
                            <div class="task-checkbox">{{ $task->is_completed ? '✓' : '' }}</div>
                            <div class="task-icon">{{ $task->task_icon }}</div>
                            <div class="task-content">
                                <div class="task-text">{{ $task->task_name }}</div>
                                <div class="task-xp">+{{ $task->xp_reward }} XP</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <br>
                    <!-- Motivational Message -->   
                    <div class="motivation-box" id="motivationBox">
                        @php
                            $completedCount = count($tasks ?? []) > 0 ? collect($tasks)->where('is_completed', true)->count() : 0;
                            $totalCount = count($tasks ?? []);
                            $percentage = $totalCount > 0 ? ($completedCount / $totalCount) * 100 : 0;
                        @endphp
                        @if($percentage == 100)
                            🎉 Perfect! All tasks completed! You're a sustainability superstar!
                        @elseif($percentage >= 75)
                            🌟 Almost there! Just a few more tasks to go!
                        @elseif($percentage >= 50)
                            💪 Great progress! Keep up the momentum!
                        @elseif($percentage > 0)
                            🌱 Good start! Every action counts!
                        @else
                            🚀 Ready to make a difference today?
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Task Confirmation Modal -->
    <div id="taskConfirmModal" class="task-confirm-modal">
        <div class="task-confirm-content">
            <div class="task-confirm-header">
                <h3>🌱 Confirm Task Completion</h3>
                <button class="task-confirm-close" onclick="closeTaskConfirmModal()">&times;</button>
            </div>
            <div class="task-confirm-body">
                <div class="task-confirm-icon" id="confirmTaskIcon">🌍</div>
                <div class="task-confirm-name" id="confirmTaskName">Task Name</div>
                <div class="task-confirm-question">
                    Did you really complete this eco-friendly task?
                </div>
                <div class="task-confirm-reminder">
                    ⚠️ Please be honest! The streak system works on trust and helps build real sustainable habits.
                </div>
                <div class="task-confirm-timer" id="confirmTimer" style="display: none;">
                    Please wait <span id="confirmTimerSeconds">30</span>s before confirming...
                </div>
            </div>
            <div class="task-confirm-actions">
                <button class="task-confirm-btn cancel" onclick="closeTaskConfirmModal()">
                    Cancel
                </button>
                <button class="task-confirm-btn confirm" id="confirmTaskBtn" onclick="confirmTaskCompletion()" disabled>
                    Yes, I Completed It
                </button>
            </div>
        </div>
    </div>

    <!-- Robot Shop Modal -->
    <div id="shopModal" class="shop-modal">
        <div class="shop-modal-content">
            <div class="shop-modal-header">
                <h2>🛍️ Robot Customization Shop</h2>
                <div class="user-points-display">
                    Your Points: <span id="userPointsDisplay">{{ $user->points_total ?? 0 }}</span>
                </div>
                <button class="shop-close-btn" onclick="closeShopModal()">&times;</button>
            </div>

            <div class="shop-tabs">
                <button class="shop-tab active" onclick="filterShopSkins('all')">All Skins</button>
                <button class="shop-tab" onclick="filterShopSkins('available')">Available</button>
                <button class="shop-tab" onclick="filterShopSkins('locked')">Locked</button>
                <button class="shop-tab" onclick="filterShopSkins('owned')">Owned</button>
            </div>

            <div class="shop-skins-grid" id="shopSkinsGrid">
                <!-- Skins will be loaded here dynamically -->
                <div class="shop-loading">Loading skins...</div>
            </div>
        </div>
    </div>

    <div class="floating-icons">
      <a href="{{ url('/analytics') }}" class="floating-icon" title="Analytics">📅</a>
      <a href="{{ url('/streaks') }}" class="floating-icon" title="Streaks">🔥</a>
      <a href="{{ url('/learning-modules') }}" class="floating-icon" title="Learning Modules">🌱</a>
      <a href="{{ url('/leaderboard') }}" class="floating-icon" title="Leaderboard">🏆</a>
      <a href="{{ url('/badges') }}" class="floating-icon" title="Badges">🥇</a>
      <a href="{{ url('/settings') }}" class="floating-icon" title="Settings">⚙️</a>
    </div>



    <script>
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Motivational messages array
        const motivationalMessages = [
            "Amazing work! You're on fire with your eco-friendly habits! 🔥",
            "Keep going! Every small action makes a big difference! 🌍",
            "You're building a better future, one day at a time! 🌱",
            "Incredible dedication! The planet thanks you! 💚",
            "Your consistency is inspiring! Keep it up! ⭐",
            "Making sustainability a habit - you rock! 🎸",
            "One streak at a time, changing the world! 🌟"
        ];

        // Rotate motivational messages
        let messageIndex = 0;
        function rotateMotivationalMessage() {
            const textElement = document.getElementById('motivationalText');
            if (textElement) {
                messageIndex = (messageIndex + 1) % motivationalMessages.length;
                textElement.style.opacity = '0';
                setTimeout(() => {
                    textElement.textContent = motivationalMessages[messageIndex];
                    textElement.style.opacity = '1';
                }, 300);
            }
        }

        // Rotate message every 8 seconds
        setInterval(rotateMotivationalMessage, 8000);

        // Countdown timer to midnight
        function updateCountdown() {
            const now = new Date();
            const tomorrow = new Date(now);
            tomorrow.setHours(24, 0, 0, 0);

            const diff = tomorrow - now;
            const hours = Math.floor(diff / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);

            const countdownEl = document.getElementById('countdown');
            if (countdownEl) {
                countdownEl.textContent = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;

                // Urgency indication
                if (hours === 0 && minutes < 30) {
                    countdownEl.style.color = '#ff5722';
                    countdownEl.style.fontWeight = 'bold';
                } else {
                    countdownEl.style.color = 'inherit';
                    countdownEl.style.fontWeight = '600';
                }
            }
        }

        // Update countdown every second
        setInterval(updateCountdown, 1000);
        updateCountdown();

        // Calculate and display total XP
        function calculateTotalXP() {
            const tasks = document.querySelectorAll('.task-item');
            let totalXP = 0;
            tasks.forEach(task => {
                if (task.classList.contains('completed')) {
                    const xpText = task.querySelector('.task-xp').textContent;
                    const xpValue = parseInt(xpText.match(/\d+/)[0]);
                    totalXP += xpValue;
                }
            });

            const xpElement = document.getElementById('totalXP');
            if (xpElement) {
                animateNumber(xpElement, 0, totalXP, 1000, 'XP');
            }
        }

        // Animate number counting
        function animateNumber(element, start, end, duration, suffix = '') {
            const range = end - start;
            const increment = range / (duration / 16);
            let current = start;

            const timer = setInterval(() => {
                current += increment;
                if ((increment > 0 && current >= end) || (increment < 0 && current <= end)) {
                    current = end;
                    clearInterval(timer);
                }
                element.textContent = Math.round(current) + ' ' + suffix;
            }, 16);
        }

        // Initialize XP display
        setTimeout(calculateTotalXP, 500);

        // Robot mascot interactions
        const robotMascot = document.getElementById('robotMascot');
        if (robotMascot) {
            // Click to celebrate
            robotMascot.addEventListener('click', function() {
                this.style.animation = 'none';
                setTimeout(() => {
                    this.style.animation = '';
                }, 10);

                const eyes = document.querySelectorAll('.robot-eye');
                eyes.forEach(eye => {
                    eye.style.animation = 'none';
                    eye.style.transform = 'scaleY(0.1)';
                    setTimeout(() => {
                        eye.style.animation = 'blink 3s infinite';
                        eye.style.transform = 'scaleY(1)';
                    }, 200);
                });

                // Show encouraging message
                showFloatingMessage('Keep going! 🤖', robotMascot);
            });

            // Random robot reactions
            setInterval(() => {
                if (Math.random() > 0.7) {
                    const antenna = robotMascot.querySelector('.robot-antenna::after');
                    // Antenna will pulse automatically via CSS
                }
            }, 5000);
        }

        // Show floating message
        function showFloatingMessage(message, targetElement) {
            const floatingMsg = document.createElement('div');
            floatingMsg.className = 'floating-message';
            floatingMsg.textContent = message;
            floatingMsg.style.cssText = `
                position: absolute;
                top: -30px;
                left: 50%;
                transform: translateX(-50%);
                background: rgba(76, 175, 80, 0.95);
                color: white;
                padding: 8px 16px;
                border-radius: 20px;
                font-size: 14px;
                font-weight: 600;
                box-shadow: 0 4px 12px rgba(0,0,0,0.2);
                z-index: 1000;
                animation: floatUp 2s ease-out forwards;
                pointer-events: none;
            `;

            targetElement.style.position = 'relative';
            targetElement.appendChild(floatingMsg);

            setTimeout(() => floatingMsg.remove(), 2000);
        }

        // Toggle sidebar
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        }

        function toggleMobileSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const body = document.body;
            const hamburger = document.getElementById('hamburger-btn');
            const isOpen = sidebar.classList.contains('open');

            sidebar.classList.toggle('open');
            body.classList.toggle('sidebar-open');

            // Update aria-expanded for accessibility
            hamburger.setAttribute('aria-expanded', !isOpen);
        }

        function closeSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const body = document.body;
            const hamburger = document.getElementById('hamburger-btn');

            if (sidebar.classList.contains('open')) {
                sidebar.classList.remove('open');
                body.classList.remove('sidebar-open');
                hamburger.setAttribute('aria-expanded', 'false');
            }
        }

        // Navigation interaction
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function(e) {
                document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
                this.classList.add('active');
                closeSidebar();
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

        // Task completion validation variables
        let pendingTaskId = null;
        let pendingTaskElement = null;
        let confirmationTimer = null;
        let confirmationTimeLeft = 30; // seconds
        let lastTaskCompletionTime = 0;
        const TASK_COOLDOWN = 5000; // 5 seconds between task completions
        const CONFIRMATION_DELAY = 30; // 30 seconds before allowing confirmation

        // Show task confirmation modal
        function showTaskConfirmModal(taskId, taskElement) {
            const modal = document.getElementById('taskConfirmModal');
            const taskIcon = taskElement.querySelector('.task-icon').textContent;
            const taskName = taskElement.querySelector('.task-text').textContent;

            // Set modal content
            document.getElementById('confirmTaskIcon').textContent = taskIcon;
            document.getElementById('confirmTaskName').textContent = taskName;

            // Store pending task info
            pendingTaskId = taskId;
            pendingTaskElement = taskElement;

            // Reset and start confirmation timer
            confirmationTimeLeft = CONFIRMATION_DELAY;
            document.getElementById('confirmTimerSeconds').textContent = confirmationTimeLeft;
            document.getElementById('confirmTimer').style.display = 'block';
            document.getElementById('confirmTaskBtn').disabled = true;

            // Show modal
            modal.classList.add('active');

            // Start countdown
            if (confirmationTimer) clearInterval(confirmationTimer);
            confirmationTimer = setInterval(() => {
                confirmationTimeLeft--;
                document.getElementById('confirmTimerSeconds').textContent = confirmationTimeLeft;

                if (confirmationTimeLeft <= 0) {
                    clearInterval(confirmationTimer);
                    document.getElementById('confirmTimer').style.display = 'none';
                    document.getElementById('confirmTaskBtn').disabled = false;
                }
            }, 1000);
        }

        // Close task confirmation modal
        function closeTaskConfirmModal() {
            const modal = document.getElementById('taskConfirmModal');
            modal.classList.remove('active');

            // Clear timer
            if (confirmationTimer) {
                clearInterval(confirmationTimer);
                confirmationTimer = null;
            }

            // Reset state
            pendingTaskId = null;
            pendingTaskElement = null;
            confirmationTimeLeft = CONFIRMATION_DELAY;
        }

        // Confirm task completion
        async function confirmTaskCompletion() {
            if (!pendingTaskId || !pendingTaskElement) {
                return;
            }

            // IMPORTANT: Save values to local variables BEFORE closing modal
            // because closeTaskConfirmModal() resets these to null!
            const taskId = pendingTaskId;
            const taskElement = pendingTaskElement;

            // Update last completion time
            lastTaskCompletionTime = Date.now();

            // Close modal (this will reset pendingTaskId and pendingTaskElement to null)
            closeTaskConfirmModal();

            // Complete the task using the saved local variables
            await toggleTaskCompletion(taskId, taskElement);
        }

        // Task items interaction with completion toggle
        document.querySelectorAll('.task-item').forEach(task => {
            task.addEventListener('click', function() {
                const taskId = this.getAttribute('data-task-id');
                const isCompleted = this.classList.contains('completed');

                // Allow uncompleting without confirmation
                if (isCompleted) {
                    toggleTaskCompletion(taskId, this);
                } else {
                    // Check cooldown
                    const now = Date.now();
                    if (now - lastTaskCompletionTime < TASK_COOLDOWN) {
                        const remainingSeconds = Math.ceil((TASK_COOLDOWN - (now - lastTaskCompletionTime)) / 1000);
                        showNotification(`Please wait ${remainingSeconds}s before completing another task`, 'error');
                        return;
                    }

                    // Show confirmation modal for completing
                    showTaskConfirmModal(taskId, this);
                }
            });
        });

        // Function to toggle task completion
        async function toggleTaskCompletion(taskId, taskElement) {
            try {
                const response = await fetch('/api/streaks/toggle-task', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ task_id: taskId })
                });

                // Check if response is ok (status 200-299)
                if (!response.ok) {
                    let errorData;
                    try {
                        errorData = await response.json();
                    } catch (e) {
                        errorData = { error: 'Server error: ' + response.statusText };
                    }
                    showNotification(errorData.error || 'Failed to update task', 'error');
                    return;
                }

                const data = await response.json();

                if (data.success) {
                    // Update task UI
                    if (data.completed) {
                        taskElement.classList.add('completed');
                        taskElement.querySelector('.task-checkbox').textContent = '✓';

                        // Show XP earned notification with animation
                        showNotification(`Task completed! +${data.xp_earned} XP`, 'success');
                        showFloatingXP(taskElement, data.xp_earned);

                        // Animate task completion
                        taskElement.style.transform = 'scale(1.05)';
                        setTimeout(() => {
                            taskElement.style.transform = 'scale(1)';
                        }, 300);

                        // Recalculate XP
                        setTimeout(calculateTotalXP, 300);

                        // Check if all tasks completed
                        if (data.all_completed) {
                            setTimeout(() => {
                                showCelebration();
                                showNotification(`🎉 All tasks completed! +1 Streak Day!`, 'success');

                                // Animate the streak flame
                                const flame = document.querySelector('.streak-flame');
                                if (flame) {
                                    flame.style.animation = 'none';
                                    setTimeout(() => {
                                        flame.style.animation = 'flicker 1.5s ease-in-out infinite, celebrate 0.8s ease';
                                    }, 10);
                                }

                                // Robot celebrates
                                if (robotMascot) {
                                    robotMascot.style.animation = 'mascot-float 3s ease-in-out infinite, celebrate 0.8s ease';
                                    setTimeout(() => {
                                        robotMascot.style.animation = 'mascot-float 3s ease-in-out infinite';
                                    }, 800);
                                }
                            }, 500);
                        }
                    } else {
                        taskElement.classList.remove('completed');
                        taskElement.querySelector('.task-checkbox').textContent = '';

                        // Recalculate XP
                        setTimeout(calculateTotalXP, 300);
                    }

                    // Update streak display
                    if (data.streak_days !== undefined) {
                        const streakElement = document.getElementById('streakDays');
                        const headerStreakElement = document.getElementById('headerStreakDays');

                        animateNumber(streakElement, parseInt(streakElement.textContent), data.streak_days, 500);
                        if (headerStreakElement) {
                            headerStreakElement.textContent = data.streak_days;
                        }
                    }

                    // Update progress bar and counters
                    updateProgressBar();
                    updateTaskCounters();
                    updateMotivationBox();
                } else {
                    showNotification('Failed to update task', 'error');
                }
            } catch (error) {
                console.error('Error toggling task:', error);
                showNotification('An error occurred', 'error');
            }
        }

        // Show floating XP animation
        function showFloatingXP(taskElement, xpValue) {
            const xpFloat = document.createElement('div');
            xpFloat.className = 'floating-xp';
            xpFloat.textContent = `+${xpValue} XP`;
            xpFloat.style.cssText = `
                position: absolute;
                right: 10px;
                top: 50%;
                transform: translateY(-50%);
                color: #4caf50;
                font-weight: bold;
                font-size: 18px;
                pointer-events: none;
                animation: floatUpXP 1.5s ease-out forwards;
                z-index: 100;
            `;

            taskElement.style.position = 'relative';
            taskElement.appendChild(xpFloat);

            setTimeout(() => xpFloat.remove(), 1500);
        }

        // Update task counters
        function updateTaskCounters() {
            const totalTasks = document.querySelectorAll('.task-item').length;
            const completedTasks = document.querySelectorAll('.task-item.completed').length;

            const completedEl = document.getElementById('tasksCompleted');
            const totalEl = document.getElementById('tasksTotal');
            const headerCompletedEl = document.getElementById('headerTasksCompleted');

            if (completedEl) completedEl.textContent = completedTasks;
            if (totalEl) totalEl.textContent = totalTasks;
            if (headerCompletedEl) headerCompletedEl.textContent = `${completedTasks}/${totalTasks}`;
        }

        // Update motivation box
        function updateMotivationBox() {
            const totalTasks = document.querySelectorAll('.task-item').length;
            const completedTasks = document.querySelectorAll('.task-item.completed').length;
            const percentage = totalTasks > 0 ? (completedTasks / totalTasks) * 100 : 0;

            const motivationBox = document.getElementById('motivationBox');
            if (!motivationBox) return;

            let message = '';
            if (percentage === 100) {
                message = '🎉 Perfect! All tasks completed! You\'re a sustainability superstar!';
            } else if (percentage >= 75) {
                message = '🌟 Almost there! Just a few more tasks to go!';
            } else if (percentage >= 50) {
                message = '💪 Great progress! Keep up the momentum!';
            } else if (percentage > 0) {
                message = '🌱 Good start! Every action counts!';
            } else {
                message = '🚀 Ready to make a difference today?';
            }

            motivationBox.style.opacity = '0';
            setTimeout(() => {
                motivationBox.textContent = message;
                motivationBox.style.opacity = '1';
            }, 200);
        }

        // Notification function
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.textContent = message;
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 15px 25px;
                background: ${type === 'success' ? '#4caf50' : '#f44336'};
                color: white;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.2);
                z-index: 10000;
                animation: slideIn 0.3s ease;
                font-weight: 600;
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Update progress bar
        function updateProgressBar() {
            const totalTasks = document.querySelectorAll('.task-item').length;
            const completedTasks = document.querySelectorAll('.task-item.completed').length;
            const percentage = totalTasks > 0 ? (completedTasks / totalTasks) * 100 : 0;

            const progressBar = document.getElementById('taskProgress');
            if (progressBar) {
                progressBar.style.width = percentage + '%';

                // Update percentage text
                const progressText = progressBar.querySelector('.progress-text');
                if (progressText) {
                    progressText.textContent = Math.round(percentage) + '%';
                }

                // Change color based on completion
                if (percentage === 100) {
                    progressBar.style.background = 'linear-gradient(90deg, #4caf50 0%, #66bb6a 100%)';
                } else if (percentage >= 50) {
                    progressBar.style.background = 'linear-gradient(90deg, #ffc107 0%, #ffb300 100%)';
                } else {
                    progressBar.style.background = 'linear-gradient(90deg, #ff9800 0%, #ff5722 100%)';
                }
            }
        }

        // Celebration confetti effect
        function showCelebration() {
            const colors = ['#ff9800', '#4caf50', '#2196f3', '#ffeb3b', '#e91e63', '#9c27b0'];
            const confettiCount = 50;

            for (let i = 0; i < confettiCount; i++) {
                setTimeout(() => {
                    const confetti = document.createElement('div');
                    confetti.className = 'confetti';
                    confetti.style.cssText = `
                        position: fixed;
                        width: ${Math.random() * 10 + 5}px;
                        height: ${Math.random() * 10 + 5}px;
                        background: ${colors[Math.floor(Math.random() * colors.length)]};
                        left: ${Math.random() * 100}vw;
                        top: -20px;
                        border-radius: ${Math.random() > 0.5 ? '50%' : '0'};
                        z-index: 9999;
                        animation: confetti-fall ${Math.random() * 3 + 2}s linear;
                        transform: rotate(${Math.random() * 360}deg);
                        opacity: ${Math.random() * 0.5 + 0.5};
                    `;

                    document.body.appendChild(confetti);

                    confetti.addEventListener('animationend', () => {
                        confetti.remove();
                    });
                }, i * 30);
            }
        }

        // Add CSS animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(400px); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(400px); opacity: 0; }
            }
            .task-item {
                cursor: pointer;
                transition: all 0.3s ease;
            }
            .task-item:hover {
                transform: translateX(5px);
                background: rgba(76, 175, 80, 0.1);
            }
            .task-item.completed {
                opacity: 0.7;
                background: rgba(76, 175, 80, 0.2);
            }
            .task-icon {
                margin: 0 8px;
                font-size: 1.2em;
            }
            .task-xp {
                margin-left: auto;
                color: #4caf50;
                font-weight: 600;
                font-size: 0.9em;
            }
            .achievement-badge.earned {
                filter: brightness(1.2) saturate(1.5);
                transform: scale(1.1);
            }
            .achievement-badge:not(.earned) {
                filter: grayscale(1) brightness(0.6);
            }
            @keyframes confetti-fall {
                0% {
                    transform: translateY(-20px) rotate(0deg);
                    opacity: 1;
                }
                100% {
                    transform: translateY(100vh) rotate(720deg);
                    opacity: 0;
                }
            }
            @keyframes celebrate {
                0%, 100% { transform: scale(1) rotate(0deg); }
                25% { transform: scale(1.3) rotate(-15deg); }
                50% { transform: scale(1.5) rotate(15deg); }
                75% { transform: scale(1.3) rotate(-10deg); }
            }

            /* Task Confirmation Modal Styles */
            .task-confirm-modal {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                backdrop-filter: blur(5px);
                z-index: 10001;
                justify-content: center;
                align-items: center;
            }

            .task-confirm-modal.active {
                display: flex;
            }

            .task-confirm-content {
                background: white;
                border-radius: 20px;
                padding: 30px;
                max-width: 500px;
                width: 90%;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
                animation: modalSlideIn 0.3s ease;
            }

            @keyframes modalSlideIn {
                from {
                    transform: translateY(-50px);
                    opacity: 0;
                }
                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }

            .task-confirm-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 20px;
            }

            .task-confirm-header h3 {
                margin: 0;
                color: #2c3e50;
                font-size: 1.5em;
            }

            .task-confirm-close {
                background: none;
                border: none;
                font-size: 2em;
                color: #95a5a6;
                cursor: pointer;
                transition: color 0.3s;
                padding: 0;
                width: 40px;
                height: 40px;
            }

            .task-confirm-close:hover {
                color: #e74c3c;
            }

            .task-confirm-body {
                text-align: center;
                padding: 20px 0;
            }

            .task-confirm-icon {
                font-size: 4em;
                margin-bottom: 15px;
            }

            .task-confirm-name {
                font-size: 1.3em;
                font-weight: 600;
                color: #2c3e50;
                margin-bottom: 20px;
            }

            .task-confirm-question {
                font-size: 1.1em;
                color: #34495e;
                margin-bottom: 15px;
            }

            .task-confirm-reminder {
                background: #fff3cd;
                border: 2px solid #ffc107;
                border-radius: 10px;
                padding: 15px;
                color: #856404;
                font-size: 0.95em;
                margin: 20px 0;
                line-height: 1.5;
            }

            .task-confirm-timer {
                background: #e3f2fd;
                border: 2px solid #2196f3;
                border-radius: 10px;
                padding: 15px;
                color: #1565c0;
                font-weight: 600;
                font-size: 1em;
                margin: 20px 0;
            }

            .task-confirm-actions {
                display: flex;
                gap: 15px;
                margin-top: 25px;
            }

            .task-confirm-btn {
                flex: 1;
                padding: 15px 25px;
                border: none;
                border-radius: 10px;
                font-size: 1em;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .task-confirm-btn.cancel {
                background: #ecf0f1;
                color: #7f8c8d;
            }

            .task-confirm-btn.cancel:hover {
                background: #bdc3c7;
                color: #2c3e50;
            }

            .task-confirm-btn.confirm {
                background: linear-gradient(135deg, #4caf50 0%, #45a049 100%);
                color: white;
            }

            .task-confirm-btn.confirm:hover:not(:disabled) {
                background: linear-gradient(135deg, #45a049 0%, #3d8b40 100%);
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
            }

            .task-confirm-btn.confirm:disabled {
                background: #bdc3c7;
                color: #7f8c8d;
                cursor: not-allowed;
                opacity: 0.6;
            }
        `;
        document.head.appendChild(style);

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

        // ============================================
        // ROBOT SHOP FUNCTIONALITY
        // ============================================

        let allSkins = [];
        let currentFilter = 'all';

        // Open shop modal
        function openShopModal() {
            const modal = document.getElementById('shopModal');
            modal.classList.add('active');
            loadShopSkins();
        }

        // Close shop modal
        function closeShopModal() {
            const modal = document.getElementById('shopModal');
            modal.classList.remove('active');
        }

        // Load shop skins from API
        async function loadShopSkins() {
            const grid = document.getElementById('shopSkinsGrid');
            grid.innerHTML = '<div class="shop-loading">Loading skins...</div>';

            try {
                const response = await fetch('/api/streaks/shop/skins', {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    allSkins = data.skins;
                    document.getElementById('userPointsDisplay').textContent = data.user_points;
                    renderShopSkins();
                } else {
                    grid.innerHTML = '<div class="shop-loading">Failed to load skins</div>';
                }
            } catch (error) {
                console.error('Error loading skins:', error);
                grid.innerHTML = '<div class="shop-loading">Error loading skins</div>';
            }
        }

        // Filter shop skins
        function filterShopSkins(filter) {
            currentFilter = filter;

            // Update active tab
            document.querySelectorAll('.shop-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            event.target.classList.add('active');

            renderShopSkins();
        }

        // Render shop skins based on filter
        function renderShopSkins() {
            const grid = document.getElementById('shopSkinsGrid');

            let filteredSkins = allSkins;

            switch (currentFilter) {
                case 'available':
                    filteredSkins = allSkins.filter(s => s.is_unlocked && !s.is_purchased);
                    break;
                case 'locked':
                    filteredSkins = allSkins.filter(s => !s.is_unlocked);
                    break;
                case 'owned':
                    filteredSkins = allSkins.filter(s => s.is_purchased);
                    break;
            }

            if (filteredSkins.length === 0) {
                grid.innerHTML = '<div class="shop-loading">No skins found in this category</div>';
                return;
            }

            grid.innerHTML = filteredSkins.map(skin => createSkinCard(skin)).join('');
        }

        // Create skin card HTML
        function createSkinCard(skin) {
            const isLocked = !skin.is_unlocked;
            const isPurchased = skin.is_purchased;
            const isEquipped = skin.is_equipped;

            let actionButtons = '';

            if (isLocked) {
                actionButtons = `<div class="skin-lock-overlay">🔒</div>`;
            } else if (!isPurchased) {
                const canAfford = skin.can_afford;
                actionButtons = `
                    <div class="skin-actions">
                        <button class="skin-btn purchase"
                                onclick="purchaseSkin(${skin.id})"
                                ${!canAfford ? 'disabled' : ''}>
                            ${canAfford ? 'Purchase' : 'Not enough Points'}
                        </button>
                    </div>
                `;
            } else if (!isEquipped) {
                actionButtons = `
                    <div class="skin-actions">
                        <button class="skin-btn equip" onclick="equipSkin(${skin.id})">
                            Equip
                        </button>
                    </div>
                `;
            } else {
                actionButtons = `
                    <div class="skin-actions">
                        <button class="skin-btn equip" disabled>
                            Currently Equipped
                        </button>
                    </div>
                `;
            }

            return `
                <div class="shop-skin-card ${isLocked ? 'locked' : ''} ${isEquipped ? 'equipped' : ''}" data-skin-id="${skin.id}">
                    <div class="skin-preview">
                        <div class="skin-preview-robot">
                            <div class="skin-preview-head" style="background: linear-gradient(135deg, ${skin.head_color}, ${adjustBrightness(skin.head_color, -10)});"></div>
                            <div class="skin-preview-body" style="background: linear-gradient(135deg, ${skin.body_color}, ${adjustBrightness(skin.body_color, -15)});"></div>
                        </div>
                    </div>
                    <div class="skin-name">${skin.name}</div>
                    <div class="skin-description">${skin.description}</div>
                    <div class="skin-stats">
                        ${skin.price > 0 ? `<span class="skin-stat price">${skin.price} Points</span>` : '<span class="skin-stat">FREE</span>'}
                        ${skin.required_streak > 0 ? `<span class="skin-stat streak-req">${skin.required_streak} Day Streak</span>` : ''}
                        ${skin.special_effect ? `<span class="skin-stat">✨ ${skin.special_effect}</span>` : ''}
                    </div>
                    ${actionButtons}
                </div>
            `;
        }

        // Helper function to adjust color brightness
        function adjustBrightness(color, percent) {
            const num = parseInt(color.replace('#', ''), 16);
            const amt = Math.round(2.55 * percent);
            const R = (num >> 16) + amt;
            const G = (num >> 8 & 0x00FF) + amt;
            const B = (num & 0x0000FF) + amt;
            return '#' + (0x1000000 + (R < 255 ? R < 1 ? 0 : R : 255) * 0x10000 +
                (G < 255 ? G < 1 ? 0 : G : 255) * 0x100 +
                (B < 255 ? B < 1 ? 0 : B : 255))
                .toString(16).slice(1);
        }

        // Purchase a skin
        async function purchaseSkin(skinId) {
            try {
                const response = await fetch('/api/streaks/shop/purchase', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ skin_id: skinId })
                });

                const data = await response.json();

                if (data.success) {
                    showNotification(data.message, 'success');
                    // Update Points display
                    document.getElementById('userPointsDisplay').textContent = data.remaining_points;
                    // Reload skins
                    loadShopSkins();
                } else {
                    showNotification(data.error || 'Failed to purchase skin', 'error');
                }
            } catch (error) {
                console.error('Error purchasing skin:', error);
                showNotification('An error occurred while purchasing', 'error');
            }
        }

        // Equip a skin
        async function equipSkin(skinId) {
            try {
                const response = await fetch('/api/streaks/shop/equip', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ skin_id: skinId })
                });

                const data = await response.json();

                if (data.success) {
                    showNotification(data.message, 'success');
                    // Update robot appearance
                    updateRobotSkin(data.skin);
                    // Reload skins
                    loadShopSkins();
                } else {
                    showNotification(data.error || 'Failed to equip skin', 'error');
                }
            } catch (error) {
                console.error('Error equipping skin:', error);
                showNotification('An error occurred while equipping', 'error');
            }
        }

        // Update robot skin appearance
        function updateRobotSkin(skin) {
            const robotMascot = document.getElementById('robotMascot');
            const robotHead = robotMascot.querySelector('.robot-head');
            const robotBody = robotMascot.querySelector('.robot-body');
            const robotArms = robotMascot.querySelectorAll('.robot-arm');

            // Update colors
            robotHead.style.background = `linear-gradient(135deg, ${skin.head_color} 0%, ${adjustBrightness(skin.head_color, -10)} 100%)`;
            robotBody.style.background = `linear-gradient(135deg, ${skin.body_color} 0%, ${adjustBrightness(skin.body_color, -10)} 50%, ${adjustBrightness(skin.body_color, -20)} 100%)`;

            robotArms.forEach(arm => {
                arm.style.background = `linear-gradient(135deg, ${skin.body_color} 0%, ${adjustBrightness(skin.body_color, -10)} 100%)`;
            });

            // Store in data attributes
            robotMascot.dataset.headColor = skin.head_color;
            robotMascot.dataset.bodyColor = skin.body_color;
            robotMascot.dataset.accentColor = skin.accent_color;
            robotMascot.dataset.specialEffect = skin.special_effect || '';

            // Show celebrate animation
            robotMascot.style.animation = 'none';
            setTimeout(() => {
                robotMascot.style.animation = 'mascot-float 3s ease-in-out infinite, celebrate 0.8s ease';
                setTimeout(() => {
                    robotMascot.style.animation = 'mascot-float 3s ease-in-out infinite';
                }, 800);
            }, 10);
        }

        // Close modal when clicking outside
        document.getElementById('shopModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeShopModal();
            }
        });

        // Close task confirm modal when clicking outside
        document.getElementById('taskConfirmModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeTaskConfirmModal();
            }
        });

        // Dark mode support
        document.addEventListener('DOMContentLoaded', () => {
            if (localStorage.getItem('darkMode') === 'enabled') {
                document.body.classList.add('dark-mode');
            }
        });
    </script>
</body>
</html>