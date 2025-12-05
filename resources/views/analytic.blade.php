<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SUSTENA - Carbon Footprint Tracker</title>
    <link rel="stylesheet" href="{{ asset('css/analytics.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
  
    <!-- Chart.js + Annotation plugin -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@3.0.1/dist/chartjs-plugin-annotation.min.js"></script>
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
    <a href="{{ url('/footprint-calculator') }}" class="nav-item active">
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
                <div class="welcome-text">Track your environmental impact and make every step count! 🌍</div>

                <!-- View-as + Details -->
                <div class="basis-switcher">
                  <label for="basisSelect" style="font-size:.9rem; opacity:.8;">View as:</label>
                  <select id="basisSelect">
                    <option value="daily">Daily</option>
                    <option value="weekly" selected>Weekly</option>
                    <option value="monthly">Monthly</option>
                    <option value="yearly">Yearly</option>
                  </select>
                  <button id="openDetails" class="action-button">Details</button>
                </div>
            </div>
        </div>

        <!-- Top stats -->
        <div class="stats-grid">
          <!-- LEFT: This attempt -->
          <div class="stat-card">
              <div id="headlineTitleLeft" class="stat-label">Your Footprint</div>
              <br><br>
              <div id="headlinePctLeft"  class="stat-value">--</div>
              <div id="headlineUnitLeft" class="stat-sublabel">—</div>
              <div id="headlineBadgeLeft"  class="warning-badge">--</div>
              <div class="progress-bar-container">
                  <div id="progressBarLeft" class="progress-bar"></div>
              </div>
          </div>

          <!-- RIGHT: Last attempt (or Target est.) -->
          <div class="stat-card">
              <div id="headlineTitleRight" class="stat-label">—</div>
              <br><br>
              <div id="headlinePctRight" class="stat-value">--</div>
              <div id="headlineUnitRight" class="stat-sublabel">—</div>
              <div id="headlineBadgeRight" class="warning-badge">--</div>
              <div class="progress-bar-container">
                  <div id="progressBarRight" class="progress-bar"></div>
              </div>
          </div>

          <!-- NEW: Carbon Impact Score -->
          <div class="stat-card impact-score-card">
              <div class="stat-label">Carbon Impact Score</div>
              <div id="impactScoreGrade" class="impact-grade">--</div>
              <div id="impactScoreText" class="stat-sublabel">Calculating...</div>
              <canvas id="impactGauge" width="200" height="100"></canvas>
          </div>

          <!-- NEW: Real-world Impact -->
          <div class="stat-card impact-equivalents">
              <div class="stat-label">Your Impact Equals</div>
              <div class="equivalents-grid" id="equivalentsGrid">
                  <div class="equivalent-item">
                      <span class="equivalent-icon">🌳</span>
                      <span id="treesEquiv" class="equivalent-value">--</span>
                      <span class="equivalent-label">trees needed</span>
                  </div>
                  <div class="equivalent-item">
                      <span class="equivalent-icon">🚗</span>
                      <span id="milesEquiv" class="equivalent-value">--</span>
                      <span class="equivalent-label">miles driven</span>
                  </div>
                  <div class="equivalent-item">
                      <span class="equivalent-icon">💡</span>
                      <span id="lightsEquiv" class="equivalent-value">--</span>
                      <span class="equivalent-label">lightbulbs/year</span>
                  </div>
                  <div class="equivalent-item">
                      <span class="equivalent-icon">✈️</span>
                      <span id="flightsEquiv" class="equivalent-value">--</span>
                      <span class="equivalent-label">flight hours</span>
                  </div>
              </div>
          </div>
        </div>

        <!-- NEW: Insights Section -->
        <div class="insights-section" id="insightsSection" style="display:none;">
            <div class="insight-card">
                <span class="insight-icon">💡</span>
                <div class="insight-content">
                    <div class="insight-title">Personalized Insight</div>
                    <div id="insightText" class="insight-text">Loading your personalized insights...</div>
                </div>
            </div>
        </div>

        <!-- Enhanced Quick Stats Section -->
        <div class="quick-stats-section">
            <div class="quick-stat-card">
                <div class="quick-stat-icon">📊</div>
                <div class="quick-stat-content">
                    <div class="quick-stat-label">This Period</div>
                    <div id="quickStatCurrent" class="quick-stat-value">-- kg</div>
                    <div id="quickStatCurrentChange" class="quick-stat-change">--</div>
                </div>
            </div>

            <div class="quick-stat-card">
                <div class="quick-stat-icon">📈</div>
                <div class="quick-stat-content">
                    <div class="quick-stat-label">Trend</div>
                    <div id="quickStatTrend" class="quick-stat-value">--</div>
                    <div id="quickStatTrendInfo" class="quick-stat-change">Analyzing...</div>
                </div>
            </div>

            <div class="quick-stat-card">
                <div class="quick-stat-icon">🎯</div>
                <div class="quick-stat-content">
                    <div class="quick-stat-label">Target Progress</div>
                    <div id="quickStatTarget" class="quick-stat-value">--%</div>
                    <div id="quickStatTargetInfo" class="quick-stat-change">--</div>
                </div>
            </div>

            <div class="quick-stat-card">
                <div class="quick-stat-icon">🏅</div>
                <div class="quick-stat-content">
                    <div class="quick-stat-label">Rank</div>
                    <div id="quickStatRank" class="quick-stat-value">--</div>
                    <div id="quickStatRankInfo" class="quick-stat-change">vs average</div>
                </div>
            </div>
        </div>

        <div class="breakdown-section">
            <h2 class="breakdown-title">Break Down</h2>
            <div class="category-grid">
                <div class="category-card housing" data-key="Housing">
                  <div class="category-icon">🏠</div>
                  <div class="category-name">Housing</div>
                  <div class="category-percentage card-percent">--%</div>
                  <div class="category-subtitle card-delta"></div>
                  <canvas class="category-sparkline" data-category="Housing" width="150" height="40"></canvas>
                  <div class="category-progress-container">
                      <div class="category-progress" data-category="Housing"></div>
                  </div>
                  
                </div>

                <div class="category-card food" data-key="Food">
                  <div class="category-icon">🍎</div>
                  <div class="category-name">Food</div>
                  <div class="category-percentage card-percent">--%</div>
                  <div class="category-subtitle card-delta"></div>
                  <canvas class="category-sparkline" data-category="Food" width="150" height="40"></canvas>
                  <div class="category-progress-container">
                      <div class="category-progress" data-category="Food"></div>
                  </div>
                  
                </div>

                <div class="category-card travel" data-key="Travel">
                  <div class="category-icon">🚗</div>
                  <div class="category-name">Travel</div>
                  <div class="category-percentage card-percent">--%</div>
                  <div class="category-subtitle card-delta"></div>
                  <canvas class="category-sparkline" data-category="Travel" width="150" height="40"></canvas>
                  <div class="category-progress-container">
                      <div class="category-progress" data-category="Travel"></div>
                  </div>
                  
                </div>

                <div class="category-card waste" data-key="Waste">
                  <div class="category-icon">🗑️</div>
                  <div class="category-name">Waste</div>
                  <div class="category-percentage card-percent">--%</div>
                  <div class="category-subtitle card-delta"></div>
                  <canvas class="category-sparkline" data-category="Waste" width="150" height="40"></canvas>
                  <div class="category-progress-container">
                      <div class="category-progress" data-category="Waste"></div>
                  </div>
                  
                </div>

                <div class="category-card electricity" data-key="Electricity">
                  <div class="category-icon">⚡</div>
                  <div class="category-name">Electricity</div>
                  <div class="category-percentage card-percent">--%</div>
                  <div class="category-subtitle card-delta"></div>
                  <canvas class="category-sparkline" data-category="Electricity" width="150" height="40"></canvas>
                  <div class="category-progress-container">
                      <div class="category-progress" data-category="Electricity"></div>
                  </div>
                  
                </div>
            </div>
        </div>


    <!-- Details Modal -->
    <div id="detailsModal">
      <div class="sheet">
        <div class="sheet-header">
          <div style="font-weight:700;">Footprint details</div>
          <div class="sheet-actions">
            <button id="btnDownloadCSV">Download CSV</button>
            <button id="btnDownloadPNG">Download Chart PNG</button>
            <button id="detailsClose">✕</button>
          </div>
        </div>
        <div id="detailsBody" class="sheet-body">
          <!-- Filled by JS -->
          <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px;">
            <div style="background:#f9fafb;border-radius:12px;padding:12px 14px;">
              <div style="opacity:.7;font-size:12px;">Total (<span id="detailsBasisLabel">weekly</span>)</div>
              <div id="detailsTotal" style="font-size:28px;font-weight:800;">--</div>
              <div id="detailsUnit" style="opacity:.7;font-size:12px;">kg CO₂ / wk</div>
            </div>
            <div style="background:#f9fafb;border-radius:12px;padding:12px 14px;">
              <div style="opacity:.7;font-size:12px;">Change vs last</div>
              <div id="detailsDelta" style="font-size:28px;font-weight:800;">—</div>
              <div style="opacity:.7;font-size:12px;">(percentage, unit-invariant)</div>
            </div>
          </div>

          <h4 style="margin:10px 0 6px;">Share by category</h4>
          <canvas id="chartShare" height="160"></canvas>

          <h4 style="margin:18px 0 6px;">Amount by category</h4>
          <canvas id="chartCategories" height="200"></canvas>

          <!-- Category Breakdown Summary -->
          <div class="category-breakdown-summary">
            <div class="breakdown-header">
              <h5>Category Insights</h5>
              <p>Focus on your highest impact areas for maximum reduction</p>
            </div>
            <div class="breakdown-grid" id="breakdownGrid">
              <!-- Will be populated by JavaScript -->
            </div>
          </div>

          <h4 style="margin:18px 0 6px;">Performance Comparison</h4>
          <canvas id="chartComparison" height="180"></canvas>

          <div id="trendBlock" style="margin-top:18px; display:none;">
            <div style="display:flex; gap:8px; align-items:center; margin:8px 0;">
              <h4 style="margin:0;">Trend</h4>
              <select id="trendCategory" style="margin-left:auto; padding:6px 8px; border-radius:8px; border:1px solid #e5e7eb;">
                <option value="__total__" selected>Total</option>
              </select>
            </div>
            <canvas id="chartTrend" height="220"></canvas>
            <div id="trendLegend" style="font-size:12px;opacity:.7;margin-top:6px;display:none;">
              Target band shows your goal range.
            </div>
          </div>

          <h4 style="margin:18px 0 6px;">By category</h4>
          <table id="detailsTable" style="width:100%; border-collapse:collapse; font-size:14px;">
            <thead>
              <tr>
                <th style="text-align:left;padding:8px 10px;">Category</th>
                <th style="text-align:right;padding:8px 10px;">Share</th>
                <th style="text-align:right;padding:8px 10px;">Amount</th>
              </tr>
            </thead>
            <tbody><!-- rows via JS --></tbody>
          </table>

          <div style="margin-top:14px; font-size:12px; opacity:.7;">
            Numbers are scaled from weekly for display. Factors are averages; your actual footprint may vary.
          </div>
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
      // Toggle sidebar
  function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    sidebar.classList.toggle('collapsed');
    mainContent.classList.toggle('expanded');
  }

      // ----- Helpers for basis switching -----
      let VIEW_BASIS = 'weekly';
      let lastSummary = null;

      function weeklyToBasis(kgPerWeek, basis) {
        switch (basis) {
          case 'daily':   return kgPerWeek / 7;
          case 'weekly':  return kgPerWeek;
          case 'monthly': return kgPerWeek * 4.345;
          case 'yearly':  return kgPerWeek * 52.1429;
          default:        return kgPerWeek;
        }
      }
      function unitFor(basis) {
        return basis === 'daily'   ? 'kg CO₂ / day'
             : basis === 'weekly'  ? 'kg CO₂ / wk'
             : basis === 'monthly' ? 'kg CO₂ / mo'
             :                       'kg CO₂ / yr';
      }
      function fmt(n) {
        if (n === null || n === undefined || Number.isNaN(n)) return '--';
        const abs = Math.abs(n);
        return abs >= 1000 ? n.toFixed(0) : n.toFixed(1);
      }
      function fmtShort(n) {
        if (n === null || n === undefined || Number.isNaN(n)) return '--';
        const abs = Math.abs(n);
        if (abs >= 1e6) return (n/1e6).toFixed(1) + 'M';
        if (abs >= 1e3) return (n/1e3).toFixed(1) + 'k';
        return n.toFixed(1);
      }
      function setBadge(el, text, type = 'info') {
        el.textContent = text;
        el.style.background =
          type === 'good' ? '#dcfce7' :
          type === 'warn' ? '#ffedd5' :
          '#e5e7eb';
        el.style.color = '#1f2937';
      }

      // ====== CHART HELPERS ======
      const charts = {};
      function upsertChart(canvasId, config) {
        const el = document.getElementById(canvasId);
        if (!el) return;
        if (charts[canvasId]) charts[canvasId].destroy();
        charts[canvasId] = new Chart(el, config);
      }

      // ====== NEW HELPER FUNCTIONS ======

      // Calculate Carbon Impact Score (A-F grading)
      function calculateImpactScore(weeklyKg) {
        // Based on average person: ~400 kg CO2/week = D grade
        // Excellent: <200 = A, Good: 200-300 = B, Average: 300-400 = C, High: 400-500 = D, Very High: 500+ = E/F
        if (weeklyKg < 200) return { grade: 'A', color: '#4caf50', text: 'Excellent! Well below average', percent: 25 };
        if (weeklyKg < 300) return { grade: 'B', color: '#8bc34a', text: 'Good! Below average', percent: 45 };
        if (weeklyKg < 400) return { grade: 'C', color: '#ffc107', text: 'Average footprint', percent: 65 };
        if (weeklyKg < 500) return { grade: 'D', color: '#ff9800', text: 'Above average - room to improve', percent: 80 };
        if (weeklyKg < 600) return { grade: 'E', color: '#ff5722', text: 'High impact - action needed', percent: 90 };
        return { grade: 'F', color: '#f44336', text: 'Very high impact - urgent action needed', percent: 100 };
      }

      // Calculate real-world equivalents
      function calculateEquivalents(weeklyKg) {
        // Convert weekly to yearly for some calculations
        const yearlyKg = weeklyKg * 52.1429;

        // 1 tree absorbs ~21 kg CO2/year = 0.4 kg/week
        const treesNeeded = (weeklyKg / 0.4).toFixed(1);
        // Average car emits ~0.41 kg CO2/mile
        const milesDriven = (weeklyKg / 0.41).toFixed(0);
        // 60W lightbulb for 1 year = ~300 kg CO2
        const lightbulbs = (yearlyKg / 300).toFixed(1);
        // 1 hour of flight = ~90 kg CO2
        const flightHours = (yearlyKg / 90).toFixed(1);

        return { trees: treesNeeded, miles: milesDriven, lightbulbs: lightbulbs, flights: flightHours };
      }

      // Draw gauge chart
      function drawGauge(canvasId, percent, color) {
        const canvas = document.getElementById(canvasId);
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        const centerX = canvas.width / 2;
        const centerY = canvas.height - 10;
        const radius = 70;

        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Background arc
        ctx.beginPath();
        ctx.arc(centerX, centerY, radius, Math.PI, 2 * Math.PI);
        ctx.lineWidth = 15;
        ctx.strokeStyle = '#e0e0e0';
        ctx.stroke();

        // Progress arc
        const endAngle = Math.PI + (Math.PI * percent / 100);
        ctx.beginPath();
        ctx.arc(centerX, centerY, radius, Math.PI, endAngle);
        ctx.lineWidth = 15;
        ctx.strokeStyle = color;
        ctx.lineCap = 'round';
        ctx.stroke();

        // Center text
        ctx.fillStyle = color;
        ctx.font = 'bold 20px Arial';
        ctx.textAlign = 'center';
        ctx.fillText(percent + '%', centerX, centerY - 20);
      }

      // Draw sparkline
      function drawSparkline(canvas, data, color) {
        if (!canvas || !data || data.length < 2) return;
        const ctx = canvas.getContext('2d');
        const width = canvas.width;
        const height = canvas.height;
        const padding = 5;

        ctx.clearRect(0, 0, width, height);

        const max = Math.max(...data);
        const min = Math.min(...data);
        const range = max - min || 1;

        ctx.beginPath();
        ctx.strokeStyle = color;
        ctx.lineWidth = 2;

        data.forEach((val, i) => {
          const x = padding + (i / (data.length - 1)) * (width - 2 * padding);
          const y = height - padding - ((val - min) / range) * (height - 2 * padding);
          if (i === 0) ctx.moveTo(x, y);
          else ctx.lineTo(x, y);
        });

        ctx.stroke();

        // Fill area under line
        ctx.lineTo(width - padding, height - padding);
        ctx.lineTo(padding, height - padding);
        ctx.closePath();
        ctx.fillStyle = color + '20';
        ctx.fill();
      }

      // Generate personalized insight
      function generateInsight(d) {
        if (!d?.has_data) return null;

        const delta = d.headline.delta_pct;
        const weeklyTotal = d.headline.kg_per_week ?? d.headline.total ?? 0;
        const score = calculateImpactScore(weeklyTotal);

        // Find highest category
        let highestCat = null;
        let highestPct = 0;
        (d.cards || []).forEach(c => {
          if (c.percent > highestPct) {
            highestPct = c.percent;
            highestCat = c.title;
          }
        });

        if (delta !== null && delta < 0) {
          return `Great progress! You've reduced your footprint by ${Math.abs(delta)}%. Your ${highestCat} (${highestPct}%) is your largest contributor - focus here for maximum impact.`;
        } else if (delta !== null && delta > 0) {
          return `Your footprint increased by ${delta}% this period. Don't worry - small changes in ${highestCat} (${highestPct}% of total) can make a big difference.`;
        } else if (score.grade === 'A' || score.grade === 'B') {
          return `You're performing ${score.text.toLowerCase()}! Keep it up. Consider sharing your eco-friendly habits with others.`;
        } else {
          return `${highestCat} accounts for ${highestPct}% of your footprint. Small changes here could significantly reduce your overall impact.`;
        }
      }

      // Show milestone celebration
      function checkMilestones(d) {
        if (!d?.has_data) return;

        const delta = d.headline.delta_pct;
        const weeklyTotal = d.headline.kg_per_week ?? d.headline.total ?? 0;
        const score = calculateImpactScore(weeklyTotal);

        // Check for achievements
        if (delta !== null && delta <= -10) {
          showCelebration('🎉 Amazing! 10%+ Reduction!', 'You\'re making a real difference!');
        } else if (delta !== null && delta <= -5) {
          showCelebration('🌟 Great Job! 5%+ Reduction!', 'Keep up the momentum!');
        } else if (score.grade === 'A') {
          showCelebration('🏆 A-Grade Achievement!', 'You\'re an eco-champion!');
        }
      }

      function showCelebration(title, message) {
        const existing = document.querySelector('.celebration-toast');
        if (existing) existing.remove();

        const toast = document.createElement('div');
        toast.className = 'celebration-toast';
        toast.innerHTML = `
          <div class="celebration-content">
            <div class="celebration-title">${title}</div>
            <div class="celebration-message">${message}</div>
          </div>
        `;
        document.body.appendChild(toast);

        setTimeout(() => toast.classList.add('show'), 100);
        setTimeout(() => {
          toast.classList.remove('show');
          setTimeout(() => toast.remove(), 300);
        }, 5000);
      }

      // Populate breakdown summary with actionable insights
      function populateBreakdownSummary(cards, weeklyTotal) {
        const grid = document.getElementById('breakdownGrid');
        if (!grid || !cards || cards.length === 0) return;

        // Sort cards by percentage (highest first)
        const sortedCards = [...cards].sort((a, b) => b.percent - a.percent);

        // Get category icons
        const categoryIcons = {
          'Housing': '🏠',
          'Food': '🍽️',
          'Travel': '🚗',
          'Waste': '🗑️',
          'Electricity': '💡'
        };

        // Get category colors
        const categoryColors = {
          'Housing': '#2196F3',
          'Food': '#4CAF50',
          'Travel': '#FF9800',
          'Waste': '#E91E63',
          'Electricity': '#FFC107'
        };

        // Generate recommendations based on category
        const recommendations = {
          'Housing': 'Consider energy-efficient appliances and better insulation',
          'Food': 'Reduce meat consumption and buy local produce',
          'Travel': 'Use public transport or carpool when possible',
          'Waste': 'Recycle more and reduce single-use plastics',
          'Electricity': 'Switch to LED bulbs and unplug unused devices'
        };

        let html = '';
        sortedCards.forEach((card, index) => {
          const icon = categoryIcons[card.title] || '📊';
          const color = categoryColors[card.title] || '#666';
          const recommendation = recommendations[card.title] || 'Find ways to reduce impact';

          // Calculate weekly kg for this category
          const categoryWeeklyKg = (weeklyTotal * card.percent / 100).toFixed(1);

          // Determine status
          let status = '';
          let statusClass = '';
          if (card.delta && card.delta.pct !== null) {
            if (card.delta.pct < 0) {
              status = `↓ ${Math.abs(card.delta.pct)}% improvement`;
              statusClass = 'status-good';
            } else if (card.delta.pct > 0) {
              status = `↑ ${card.delta.pct}% increase`;
              statusClass = 'status-warn';
            } else {
              status = '→ No change';
              statusClass = 'status-neutral';
            }
          } else {
            status = 'First measurement';
            statusClass = 'status-info';
          }

          html += `
            <div class="breakdown-item" style="border-left: 4px solid ${color};">
              <div class="breakdown-item-header">
                <span class="breakdown-icon">${icon}</span>
                <div class="breakdown-info">
                  <div class="breakdown-title">${card.title}</div>
                  <div class="breakdown-stats">
                    <span class="breakdown-percent">${card.percent}%</span>
                    <span class="breakdown-amount">${categoryWeeklyKg} kg CO₂/wk</span>
                  </div>
                </div>
                <div class="breakdown-rank">#${index + 1}</div>
              </div>
              <div class="breakdown-status ${statusClass}">${status}</div>
              <div class="breakdown-recommendation">
                💡 ${recommendation}
              </div>
            </div>
          `;
        });

        grid.innerHTML = html;
      }

      // ----- Renderer (uses VIEW_BASIS + weekly values) -----
      function renderSummary(d) {
        if (!d || !d.has_data) {
          document.querySelector('#headlinePctLeft').textContent  = '--';
          document.querySelector('#headlinePctRight').textContent = '--';
          document.querySelector('#headlineBadgeLeft').textContent  = 'No data yet';
          document.querySelector('#headlineBadgeRight').textContent = 'No data yet';
          return;
        }

        // HEADLINE
        const weeklyTotal = (d.headline.kg_per_week ?? d.headline.total ?? 0);
        const displayTotal = weeklyToBasis(weeklyTotal, VIEW_BASIS);
        const unit = unitFor(VIEW_BASIS);

        document.querySelector('#headlineTitleLeft').textContent = 'Your Footprint';
        document.querySelector('#headlinePctLeft').textContent    = fmt(displayTotal);
        document.querySelector('#headlineUnitLeft').textContent   = unit;

        // Left badge (delta vs last; % is unit-invariant)
        const delta = d.headline.delta_pct; // may be null
        const deltaText = delta === null ? 'First run' : (delta > 0 ? `+${delta}% vs last` : `${delta}% vs last`);
        setBadge(document.querySelector('#headlineBadgeLeft'), deltaText,
          delta === null ? 'info' : (delta <= 0 ? 'good' : 'warn'));

        // RIGHT: last attempt or target
        let rightValue = null, rightTitle = '', rightUnit = unit, rightBadgeType = 'info', rightBadgeText = '—';

        if (delta !== null) {
          // lastWeekly = currentWeekly / (1 + delta/100)
          const lastWeekly = (weeklyTotal * 100) / (100 + delta);
          rightValue     = fmt(weeklyToBasis(lastWeekly, VIEW_BASIS));
          rightTitle     = 'Last attempt';
          rightBadgeText = `${delta > 0 ? '+' : ''}${delta}% vs last`;
          rightBadgeType = delta <= 0 ? 'good' : 'warn';
        } else if (d.headline.target_abs_weekly !== null && d.headline.target_abs_weekly !== undefined) {
          const targetWeekly = d.headline.target_abs_weekly;
          rightValue     = fmt(weeklyToBasis(targetWeekly, VIEW_BASIS));
          rightTitle     = 'Target';
          const progressPct = ((targetWeekly / weeklyTotal) * 100).toFixed(0);
          const src = d.headline.target_source === 'user'
            ? 'user target'
            : (d.headline.target_source === 'rolling_avg' ? 'avg (last 5)' : '');
          rightBadgeText = src ? `${progressPct}% • ${src}` : `${progressPct}%`;
          rightBadgeType = weeklyTotal <= targetWeekly ? 'good' : 'warn';
        }

        if (rightValue === null) {
          rightTitle = 'Comparison';
          rightValue = '--';
        }

        document.querySelector('#headlineTitleRight').textContent = rightTitle;
        document.querySelector('#headlinePctRight').textContent   = String(rightValue);
        document.querySelector('#headlineUnitRight').textContent  = rightUnit;
        setBadge(document.querySelector('#headlineBadgeRight'), rightBadgeText, rightBadgeType);

        // NEW: Update progress bars with animation
        const progressLeft = document.getElementById('progressBarLeft');
        const progressRight = document.getElementById('progressBarRight');
        if (progressLeft) {
          setTimeout(() => {
            progressLeft.style.width = '100%';
          }, 100);
        }
        if (progressRight && rightValue !== '--') {
          let targetPercent = 100;
          if (d.headline.target_abs_weekly !== null && d.headline.target_abs_weekly !== undefined) {
            targetPercent = Math.min(100, (d.headline.target_abs_weekly / weeklyTotal) * 100);
          }
          setTimeout(() => {
            progressRight.style.width = targetPercent + '%';
          }, 100);
        }

        // NEW: Carbon Impact Score
        const impactScore = calculateImpactScore(weeklyTotal);
        document.getElementById('impactScoreGrade').textContent = impactScore.grade;
        document.getElementById('impactScoreGrade').style.color = impactScore.color;
        document.getElementById('impactScoreText').textContent = impactScore.text;
        drawGauge('impactGauge', impactScore.percent, impactScore.color);

        // NEW: Real-world equivalents
        const equivalents = calculateEquivalents(weeklyTotal);
        document.getElementById('treesEquiv').textContent = equivalents.trees;
        document.getElementById('milesEquiv').textContent = equivalents.miles;
        document.getElementById('lightsEquiv').textContent = equivalents.lightbulbs;
        document.getElementById('flightsEquiv').textContent = equivalents.flights;

        // NEW: Quick Stats Section
        document.getElementById('quickStatCurrent').textContent = fmt(displayTotal) + ' ' + unit.split(' ')[1];

        if (delta !== null) {
          const changeIcon = delta <= 0 ? '↓' : '↑';
          const changeColor = delta <= 0 ? '#4caf50' : '#ff5722';
          const changeText = `${changeIcon} ${Math.abs(delta)}% from last`;
          document.getElementById('quickStatCurrentChange').innerHTML = `<span style="color: ${changeColor};">${changeText}</span>`;
        } else {
          document.getElementById('quickStatCurrentChange').textContent = 'First measurement';
        }

        // Trend analysis (based on timeseries if available)
        if (d.timeseries && d.timeseries.length >= 3) {
          const recent = d.timeseries.slice(-3).map(p => p.total_weekly || 0);
          const trend = recent[2] < recent[0] ? '↓ Improving' : recent[2] > recent[0] ? '↑ Increasing' : '→ Stable';
          const trendColor = recent[2] < recent[0] ? '#4caf50' : recent[2] > recent[0] ? '#ff5722' : '#ff9800';
          document.getElementById('quickStatTrend').innerHTML = `<span style="color: ${trendColor};">${trend}</span>`;
          const avgChange = ((recent[2] - recent[0]) / recent[0] * 100).toFixed(1);
          document.getElementById('quickStatTrendInfo').textContent = `${avgChange}% over ${d.timeseries.length} periods`;
        } else {
          document.getElementById('quickStatTrend').textContent = '→ Stable';
          document.getElementById('quickStatTrendInfo').textContent = 'Need more data';
        }

        // Target progress
        if (d.headline.target_abs_weekly !== null && d.headline.target_abs_weekly !== undefined) {
          // Calculate progress: how close are we to target? (lower is better for carbon footprint)
          // progress = (target / current) * 100, then display as achievement percentage
          const targetWeekly = d.headline.target_abs_weekly;
          const progress = Math.min(100, (targetWeekly / weeklyTotal * 100)).toFixed(0);
          const targetColor = progress >= 100 ? '#4caf50' : progress >= 70 ? '#ff9800' : '#ff5722';
          document.getElementById('quickStatTarget').innerHTML = `<span style="color: ${targetColor};">${progress}%</span>`;
          document.getElementById('quickStatTargetInfo').textContent = progress >= 100 ? 'Target achieved!' : `${(100 - progress).toFixed(0)}% to goal`;
        } else {
          document.getElementById('quickStatTarget').textContent = 'N/A';
          document.getElementById('quickStatTargetInfo').textContent = 'Set a target';
        }

        // Rank (vs average 400kg/week)
        const avgWeekly = 400;
        const percentileRank = weeklyTotal <= avgWeekly ? 'Top 50%' : 'Bottom 50%';
        const rankColor = weeklyTotal <= avgWeekly ? '#4caf50' : '#ff9800';
        const percentBetter = ((avgWeekly - weeklyTotal) / avgWeekly * 100).toFixed(0);
        document.getElementById('quickStatRank').innerHTML = `<span style="color: ${rankColor};">${percentileRank}</span>`;
        if (weeklyTotal <= avgWeekly) {
          document.getElementById('quickStatRankInfo').textContent = `${Math.abs(percentBetter)}% better`;
        } else {
          document.getElementById('quickStatRankInfo').textContent = `${Math.abs(percentBetter)}% above avg`;
        }

        // NEW: Personalized insights
        const insight = generateInsight(d);
        if (insight) {
          document.getElementById('insightText').textContent = insight;
          document.getElementById('insightsSection').style.display = 'block';
        }

        // NEW: Check for milestones
        checkMilestones(d);

        // CARDS
        d.cards.forEach(c => {
          const root = document.querySelector(`.category-card[data-key="${c.title}"]`);
          if (!root) return;

          const pctEl   = root.querySelector('.card-percent');
          const deltaEl = root.querySelector('.card-delta');

          pctEl.textContent = `${c.percent}%`;

          const catWeekly = (c.kg_per_week ?? c.total ?? 0);
          const catDisplay = weeklyToBasis(catWeekly, VIEW_BASIS);
          root.setAttribute('title', `${fmt(catDisplay)} ${unit}`);

          if (c.delta && c.delta.pct !== null) {
            const sign = c.delta.pct > 0 ? '↑' : '↓';
            const deltaColor = c.delta.pct <= 0 ? 'green' : 'red';
            deltaEl.innerHTML = `${sign} ${Math.abs(c.delta.pct)}% vs last <span style="color: ${deltaColor}; font-weight: 600;">(${c.delta.pct >= 0 ? '+' : ''}${c.delta.abs} kg)</span>`;
            deltaEl.style.opacity = 0.9;
          } else {
            deltaEl.textContent = 'First run';
            deltaEl.style.opacity = 0.6;
          }

          // NEW: Update category progress bar
          const progressBar = root.querySelector('.category-progress');
          if (progressBar) {
            setTimeout(() => {
              progressBar.style.width = c.percent + '%';
            }, 100 + Math.random() * 200);
          }
        });

        // NEW: Draw sparklines for categories (if timeseries data exists)
        if (d.timeseries && Array.isArray(d.timeseries) && d.timeseries.length >= 2) {
          const categoryColors = {
            'Housing': '#2196F3',
            'Food': '#4CAF50',
            'Travel': '#FF9800',
            'Waste': '#E91E63',
            'Electricity': '#FFC107'
          };

          d.cards.forEach(c => {
            const canvas = document.querySelector(`.category-sparkline[data-category="${c.title}"]`);
            if (canvas) {
              const sparkData = d.timeseries.map(p => p.categories?.[c.title] ?? 0);
              drawSparkline(canvas, sparkData, categoryColors[c.title] || '#666');
            }
          });
        }

        // Wire “Reduce this score” buttons → tips
        document.querySelectorAll('.category-card .action-button').forEach(btn => {
          const wrapper = btn.closest('.category-card');
          const uiCat = wrapper?.getAttribute('data-key') || '';
          btn.onclick = (e) => {
            e.stopPropagation();
            btn.disabled = true;
            btn.textContent = 'Loading...';
            const attempt = encodeURIComponent(d.attempt.id);
            const cat = encodeURIComponent(uiCat);
            window.location.href = `/tips?cat=${cat}&attempt=${attempt}`;
          };
        });
      }

      // ----- Details modal -----
      const modal = document.getElementById('detailsModal');
      const bodyEl = document.getElementById('detailsBody');

      // Function to open modal
      function openModal() {
        if (lastSummary && lastSummary.has_data) {
          renderDetails(lastSummary);
          modal.style.display = 'flex';
        } else {
          console.log('No data available yet');
        }
      }

      // Both buttons open the same modal
      const openDetailsBtn = document.getElementById('openDetails');
      const seeBreakdownBtn = document.getElementById('seeBreakdownBtn');

      if (openDetailsBtn) {
        openDetailsBtn.onclick = openModal;
      }

      if (seeBreakdownBtn) {
        seeBreakdownBtn.onclick = openModal;
      }

      document.getElementById('detailsClose').onclick = () => modal.style.display = 'none';
      modal.addEventListener('click', (e) => { if (e.target === modal) modal.style.display = 'none'; });

      // Download buttons
      document.getElementById('btnDownloadPNG').onclick = () => {
        const c = document.getElementById('chartCategories');
        if (!c) return;
        const a = document.createElement('a');
        a.href = c.toDataURL('image/png');
        a.download = 'sustena-categories.png';
        a.click();
      };
      document.getElementById('btnDownloadCSV').onclick = () => downloadCSV();

      function downloadCSV() {
        if (!lastSummary?.cards?.length) return;
        const unit = unitFor(VIEW_BASIS);
        const rows = [['Category','Share (%)',`Amount (${unit})`]];
        lastSummary.cards.forEach(c => {
          const wk = c.kg_per_week ?? c.total ?? 0;
          const v = weeklyToBasis(wk, VIEW_BASIS);
          rows.push([c.title, c.percent, Number(fmt(v))]);
        });
        const csv = rows.map(r=>r.join(',')).join('\n');
        const blob = new Blob([csv], {type:'text/csv;charset=utf-8;'});
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url; a.download = 'sustena-details.csv'; a.click();
        URL.revokeObjectURL(url);
      }

      function renderDetails(d) {
        if (!d?.has_data) {
          document.querySelector('#detailsTotal').textContent = '--';
          document.querySelector('#detailsDelta').textContent = '—';
          return;
        }

        const unit = unitFor(VIEW_BASIS);
        const wkTotal = d.headline.kg_per_week ?? d.headline.total ?? 0;
        const dispTotal = weeklyToBasis(wkTotal, VIEW_BASIS);

        document.getElementById('detailsBasisLabel').textContent = VIEW_BASIS;
        document.getElementById('detailsTotal').textContent = fmt(dispTotal);
        document.getElementById('detailsUnit').textContent  = unit;
        document.getElementById('detailsDelta').textContent =
          d.headline.delta_pct === null ? '—' : (d.headline.delta_pct > 0 ? '+' : '') + d.headline.delta_pct + '%';

        // Table rows
        const tbody = document.querySelector('#detailsTable tbody');
        tbody.innerHTML = '';
        (d.cards || []).forEach(c => {
          const wk = c.kg_per_week ?? c.total ?? 0;
          const val = weeklyToBasis(wk, VIEW_BASIS);
          const tr = document.createElement('tr');
          tr.innerHTML = `
            <td style="padding:8px 10px;">${c.title}</td>
            <td style="padding:8px 10px; text-align:right;">${c.percent}%</td>
            <td style="padding:8px 10px; text-align:right;">${fmt(val)} ${unit}</td>
          `;
          tbody.appendChild(tr);
        });

        // ----- CHARTS -----
        const labels = (d.cards || []).map(c => c.title);
        const wkValues = (d.cards || []).map(c => (c.kg_per_week ?? c.total ?? 0));
        const basisValues = wkValues.map(v => weeklyToBasis(v, VIEW_BASIS));
        const percents = (d.cards || []).map(c => c.percent);

        // Doughnut: share by category
        upsertChart('chartShare', {
          type: 'doughnut',
          data: { labels, datasets: [{ data: percents, borderWidth: 1 }] },
          options: {
            plugins: {
              legend: { position: 'bottom' },
              tooltip: { callbacks: { label: (ctx) => `${ctx.label}: ${ctx.parsed}%` } }
            },
            cutout: '55%'
          }
        });

        // Bar: absolute amounts in selected basis
        upsertChart('chartCategories', {
          type: 'bar',
          data: { labels, datasets: [{ label: `Amount (${unit})`, data: basisValues, borderWidth: 1 }] },
          options: {
            scales: { y: { beginAtZero: true, ticks: { callback: (v) => fmtShort(v) } } },
            plugins: {
              legend: { display: false },
              tooltip: { callbacks: { label: (ctx) => `${ctx.dataset.label}: ${fmt(ctx.parsed.y)}` } }
            }
          }
        });

        // NEW: Populate Category Breakdown Summary
        populateBreakdownSummary(d.cards, wkTotal);

        // NEW: Comparison chart (You vs Average vs Target)
        const avgWeekly = 400; // Average person's weekly CO2
        const avgDisplay = weeklyToBasis(avgWeekly, VIEW_BASIS);
        const yourValue = dispTotal;
        let targetValue = avgDisplay * 0.7; // Default target: 30% below average

        if (d.headline.target_abs_weekly !== null && d.headline.target_abs_weekly !== undefined) {
          targetValue = weeklyToBasis(d.headline.target_abs_weekly, VIEW_BASIS);
        }

        upsertChart('chartComparison', {
          type: 'bar',
          data: {
            labels: ['Your Footprint', 'Average Person', 'Your Target'],
            datasets: [{
              label: unit,
              data: [yourValue, avgDisplay, targetValue],
              backgroundColor: [
                yourValue <= targetValue ? '#4caf50' : '#ff9800',
                '#9e9e9e',
                '#2196F3'
              ],
              borderWidth: 1
            }]
          },
          options: {
            indexAxis: 'y',
            scales: {
              x: { beginAtZero: true, ticks: { callback: (v) => fmtShort(v) } }
            },
            plugins: {
              legend: { display: false },
              tooltip: {
                callbacks: {
                  label: (ctx) => `${ctx.label}: ${fmt(ctx.parsed.x)} ${unit}`
                }
              }
            }
          }
        });

        // Trend: optional time series
        const ts = d.timeseries || null;
        const trendBlock = document.getElementById('trendBlock');
        const trendSelect = document.getElementById('trendCategory');
        const trendLegend = document.getElementById('trendLegend');

        if (ts && Array.isArray(ts) && ts.length) {
          trendBlock.style.display = 'block';
          trendLegend.style.display = 'none';

          // Build category options once
          if (trendSelect.options.length <= 1 && labels.length) {
            labels.forEach(name => {
              const opt = document.createElement('option');
              opt.value = name;
              opt.textContent = name;
              trendSelect.appendChild(opt);
            });
          }

          const dates = ts.map(p => p.date);

          // Choose series picker
          const pickSeries = (key) => {
            if (key === '__total__') {
              return ts.map(p => weeklyToBasis(p.total_weekly ?? 0, VIEW_BASIS));
            }
            return ts.map(p => weeklyToBasis((p.categories?.[key] ?? 0), VIEW_BASIS));
          };

          // Goal band (if provided): use absolute weekly target
          let targetWeekly = null;
          if (typeof d.headline.target_abs_weekly === 'number') {
            targetWeekly = d.headline.target_abs_weekly;
          }

          function renderTrend() {
            const chosen = trendSelect.value || '__total__';
            const series = pickSeries(chosen);

            const annotations = {};
            if (targetWeekly !== null && chosen === '__total__') {
              const tgt = weeklyToBasis(targetWeekly, VIEW_BASIS);
              annotations['targetBand'] = {
                type: 'box',
                yMin: tgt * 0.95,
                yMax: tgt * 1.05,
                backgroundColor: 'rgba(76,175,80,0.08)',
                borderWidth: 0
              };
              trendLegend.style.display = 'block';
            }

            upsertChart('chartTrend', {
              type: 'line',
              data: {
                labels: dates,
                datasets: [{
                  label: (chosen === '__total__' ? `Total` : chosen) + ` (${unit})`,
                  data: series,
                  tension: 0.3,
                  pointRadius: 4,
                  pointHoverRadius: 6,
                  borderWidth: 3,
                  borderColor: '#4caf50',
                  backgroundColor: 'rgba(76, 175, 80, 0.1)',
                  fill: true
                }]
              },
              options: {
                interaction: {
                  mode: 'index',
                  intersect: false
                },
                scales: {
                  y: { ticks: { callback: (v) => fmtShort(v) }, beginAtZero: true }
                },
                plugins: {
                  legend: { display: false },
                  tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 },
                    callbacks: {
                      label: (ctx) => {
                        const value = fmt(ctx.parsed.y);
                        const index = ctx.dataIndex;
                        let change = '';
                        if (index > 0) {
                          const prev = series[index - 1];
                          const diff = ((ctx.parsed.y - prev) / prev * 100).toFixed(1);
                          const arrow = diff >= 0 ? '↑' : '↓';
                          change = ` ${arrow} ${Math.abs(diff)}% vs prev`;
                        }
                        return `${ctx.dataset.label}: ${value}${change}`;
                      }
                    }
                  },
                  annotation: { annotations }
                }
              }
            });
          }

          trendSelect.onchange = renderTrend;
          renderTrend();
        } else {
          trendBlock.style.display = 'none';
          if (charts['chartTrend']) { charts['chartTrend'].destroy(); charts['chartTrend'] = null; }
        }
      }

      // ----- Fetch + cache then render -----
      function loadSummary() {
        fetch('/analytics/summary?limit=5', { headers: { 'Accept': 'application/json' } })
          .then(r => r.json())
          .then(d => { lastSummary = d; renderSummary(d); })
          .catch(err => console.error(err));
      }

      // ----- Basis switch listener -----
      document.getElementById('basisSelect')?.addEventListener('change', (e) => {
        VIEW_BASIS = e.target.value;  // 'daily' | 'weekly' | 'monthly' | 'yearly'
        if (lastSummary) {
          renderSummary(lastSummary);
          if (document.getElementById('detailsModal').style.display === 'flex') {
            renderDetails(lastSummary);
          }
        }
      });

      // Initial load
      loadSummary();

      // Existing card click animation (keep)
      document.querySelectorAll('.category-card').forEach(card => {
        card.addEventListener('click', function () {
          this.style.transform = 'translateY(-8px) scale(1.02)';
          setTimeout(() => { this.style.transform = 'translateY(-5px)'; }, 200);
        });
      });
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

// Close sidebar when clicking on nav items
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.nav-item').forEach(item => {
        item.addEventListener('click', closeSidebar);
    });
});

    </script>
</body>
</html>
