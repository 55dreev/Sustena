<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Dashboard - SUSTENA Admin</title>
    <link rel="stylesheet" href="{{ asset('css/admin-analytics.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="admin-logo">
                <div class="logo-icon">🌱</div>
                <h1>SUSTENA</h1>
                <span class="admin-badge">ADMIN</span>
            </div>

            <nav class="admin-nav">
                <a href="{{ route('admin.dashboard') }}" class="nav-link">
                    <span class="nav-icon">📊</span> Dashboard
                </a>
                <a href="{{ route('admin.analytics') }}" class="nav-link active">
                    <span class="nav-icon">📈</span> Analytics
                </a>
                <a href="{{ route('admin.moderation') }}" class="nav-link">
                    <span class="nav-icon">💬</span> Feedback & Moderation
                </a>
                <a href="{{ route('admin.settings') }}" class="nav-link">
                    <span class="nav-icon">⚙️</span> Settings
                </a>
                <a href="{{ route('landing-page') }}" class="nav-link">
                    <span class="nav-icon">🏠</span> Back to App
                </a>
                <a href="#" class="nav-link logout" onclick="openLogoutModal()">
          <span class="nav-icon">🚪</span> Logout
        </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <header class="page-header">
                <h1>📈 Analytics Dashboard</h1>
                <p>Comprehensive carbon footprint insights for research and analysis</p>
            </header>

            <!-- Overview Stats -->
            <section class="stats-grid">
                <div class="stat-card green">
                    <div class="stat-icon">👥</div>
                    <div class="stat-content">
                        <h3>Total Users</h3>
                        <div class="stat-value">{{ number_format($total_users) }}</div>
                        <div class="stat-meta">{{ number_format($active_users) }} active (30d)</div>
                    </div>
                </div>

                <div class="stat-card blue">
                    <div class="stat-icon">📋</div>
                    <div class="stat-content">
                        <h3>Total Attempts</h3>
                        <div class="stat-value">{{ number_format($total_attempts) }}</div>
                        <div class="stat-meta">{{ number_format($official_attempts) }} official</div>
                    </div>
                </div>

                <div class="stat-card orange">
                    <div class="stat-icon">📊</div>
                    <div class="stat-content">
                        <h3>Avg. Footprint</h3>
                        <div class="stat-value">
                            {{ $monthly_avg->isNotEmpty() ? number_format($monthly_avg->last()->avg_kg, 1) : '0' }} kg
                        </div>
                        <div class="stat-meta">per week (latest month)</div>
                    </div>
                </div>

                <div class="stat-card purple">
                    <div class="stat-icon">🔥</div>
                    <div class="stat-content">
                        <h3>Avg. Streak</h3>
                        <div class="stat-value">{{ number_format($streak_stats['avg_streak'], 1) }}</div>
                        <div class="stat-meta">weeks (max: {{ $streak_stats['max_streak'] }})</div>
                    </div>
                </div>
            </section>

            <!-- Charts Row 1 -->
            <section class="charts-row">
                <div class="chart-card">
                    <h2>📈 Carbon Footprint Trend (12 Months)</h2>
                    <canvas id="trendChart"></canvas>
                </div>

                <div class="chart-card">
                    <h2>📊 Emissions by Category (30 Days)</h2>
                    <canvas id="categoryChart"></canvas>
                </div>
            </section>

            <!-- Charts Row 2 -->
            <section class="charts-row">
                <div class="chart-card">
                    <h2>👥 Daily Active Users (30 Days)</h2>
                    <canvas id="activeUsersChart"></canvas>
                </div>

                <div class="chart-card">
                    <h2>📉 Distribution Analysis</h2>
                    <canvas id="distributionChart"></canvas>
                </div>
            </section>

            <!-- Improvement & Challenge Stats -->
            <section class="charts-row">
                <div class="chart-card half">
                    <h2>🎯 User Improvement</h2>
                    <div class="improvement-stats">
                        <div class="improvement-item good">
                            <div class="improvement-label">↓ Improved</div>
                            <div class="improvement-value">{{ $improvement_stats['improved'] }}</div>
                            <div class="improvement-percent">
                                {{ $improvement_stats['total_compared'] > 0 ? number_format(($improvement_stats['improved'] / $improvement_stats['total_compared']) * 100, 1) : 0 }}%
                            </div>
                        </div>
                        <div class="improvement-item bad">
                            <div class="improvement-label">↑ Worsened</div>
                            <div class="improvement-value">{{ $improvement_stats['worsened'] }}</div>
                            <div class="improvement-percent">
                                {{ $improvement_stats['total_compared'] > 0 ? number_format(($improvement_stats['worsened'] / $improvement_stats['total_compared']) * 100, 1) : 0 }}%
                            </div>
                        </div>
                        <div class="improvement-item neutral">
                            <div class="improvement-label">— Unchanged</div>
                            <div class="improvement-value">{{ $improvement_stats['unchanged'] }}</div>
                            <div class="improvement-percent">
                                {{ $improvement_stats['total_compared'] > 0 ? number_format(($improvement_stats['unchanged'] / $improvement_stats['total_compared']) * 100, 1) : 0 }}%
                            </div>
                        </div>
                    </div>
                </div>

                <div class="chart-card half">
                    <h2>🏆 Challenge Statistics</h2>
                    <div class="challenge-stats">
                        <div class="challenge-metric">
                            <span class="metric-label">Total Challenges</span>
                            <span class="metric-value">{{ $challenge_stats['total_challenges'] }}</span>
                        </div>
                        <div class="challenge-metric">
                            <span class="metric-label">Assignments</span>
                            <span class="metric-value">{{ number_format($challenge_stats['total_assignments']) }}</span>
                        </div>
                        <div class="challenge-metric">
                            <span class="metric-label">Completed</span>
                            <span class="metric-value green">{{ number_format($challenge_stats['completed']) }}</span>
                        </div>
                        <div class="challenge-metric">
                            <span class="metric-label">Completion Rate</span>
                            <span class="metric-value">{{ $completion_rate }}%</span>
                        </div>
                        <div class="progress-bar-container">
                            <div class="progress-bar" style="width: {{ $completion_rate }}%"></div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Top Performers -->
            <section class="table-section">
                <h2>🌟 Top Performers (by Points)</h2>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Level</th>
                            <th>XP Total</th>
                            <th>Points</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($top_reducers as $index => $user)
                        <tr>
                            <td class="rank">{{ $index + 1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td class="email">{{ $user->email }}</td>
                            <td>{{ $user->level }}</td>
                            <td>{{ number_format($user->xp_total) }}</td>
                            <td class="points">{{ number_format($user->points_total) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>

            <!-- Recent Activity -->
            <section class="table-section">
                <h2>⏱️ Recent Footprint Attempts</h2>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>kg/week</th>
                            <th>Score</th>
                            <th>Type</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recent_attempts as $attempt)
                        <tr>
                            <td>{{ $attempt->name }}</td>
                            <td class="carbon">{{ number_format($attempt->kg_per_week, 1) }} kg</td>
                            <td>{{ number_format($attempt->total_score, 1) }}</td>
                            <td>
                                <span class="badge {{ $attempt->is_official ? 'official' : 'practice' }}">
                                    {{ $attempt->is_official ? 'Official' : 'Practice' }}
                                </span>
                            </td>
                            <td class="date">{{ \Carbon\Carbon::parse($attempt->created_at)->format('M d, Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        </main>
    </div>

    <!-- LOGOUT MODAL -->
    <div id="logoutModal" class="modal">
        <div class="modal-content">
            <h3>Logout</h3>
            <p>Are you sure you want to logout?</p>
            <div class="modal-buttons">
                <button class="btn btn-cancel" onclick="closeLogoutModal()">Cancel</button>
                <button class="btn btn-logout" onclick="confirmLogout()">Logout</button>
            </div>
        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <script>
        // Logout modal functions
        function openLogoutModal() {
            document.getElementById('logoutModal').style.display = 'flex';
        }

        function closeLogoutModal() {
            document.getElementById('logoutModal').style.display = 'none';
        }

        function confirmLogout() {
            document.getElementById('logout-form').submit();
        }

        // Trend Chart
        const trendCtx = document.getElementById('trendChart').getContext('2d');
        const trendData = @json($monthly_avg);
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: trendData.map(d => d.month),
                datasets: [{
                    label: 'Avg kg/week',
                    data: trendData.map(d => parseFloat(d.avg_kg).toFixed(1)),
                    borderColor: '#4caf50',
                    backgroundColor: 'rgba(76, 175, 80, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: true } }
            }
        });

        // Category Chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        const categoryData = @json($category_avg);
        new Chart(categoryCtx, {
            type: 'bar',
            data: {
                labels: categoryData.map(d => d.category),
                datasets: [{
                    label: 'Avg kg/week',
                    data: categoryData.map(d => parseFloat(d.avg_kg).toFixed(1)),
                    backgroundColor: [
                        '#ff6b35', '#f7931e', '#ffd54f', '#66bb6a',
                        '#42a5f5', '#ab47bc', '#26c6da'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });

        // Active Users Chart
        const activeCtx = document.getElementById('activeUsersChart').getContext('2d');
        const activeData = @json($daily_active);
        new Chart(activeCtx, {
            type: 'line',
            data: {
                labels: activeData.map(d => d.date),
                datasets: [{
                    label: 'Active Users',
                    data: activeData.map(d => d.users),
                    borderColor: '#42a5f5',
                    backgroundColor: 'rgba(66, 165, 245, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: true } }
            }
        });

        // Distribution Chart
        const distCtx = document.getElementById('distributionChart').getContext('2d');
        const distData = @json($distribution);
        new Chart(distCtx, {
            type: 'doughnut',
            data: {
                labels: distData.map(d => d.bucket),
                datasets: [{
                    data: distData.map(d => d.count),
                    backgroundColor: [
                        '#4caf50', '#66bb6a', '#ffd54f', '#ff9800', '#f44336'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
        
    </script>
</body>
</html>
