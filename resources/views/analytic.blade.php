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
              <div id="headlinePctLeft"  class="stat-value">--</div>
              <div id="headlineUnitLeft" class="stat-sublabel">—</div>
              <div id="headlineBadgeLeft"  class="warning-badge">--</div>
          </div>

          <!-- RIGHT: Last attempt (or Target est.) -->
          <div class="stat-card">
              <div id="headlineTitleRight" class="stat-label">—</div>
              <div id="headlinePctRight" class="stat-value">--</div>
              <div id="headlineUnitRight" class="stat-sublabel">—</div>
              <div id="headlineBadgeRight" class="warning-badge">--</div>
          </div>
        </div>

        <div class="footprint-display">
            <div class="footprint-visual">
                <div class="footprint">
                    <div class="toes">
                        <div class="toe"></div><div class="toe"></div><div class="toe"></div><div class="toe"></div><div class="toe"></div>
                    </div>
                    <div class="footprint-face"></div>
                </div>
                <div class="footprint">
                    <div class="toes">
                        <div class="toe"></div><div class="toe"></div><div class="toe"></div><div class="toe"></div><div class="toe"></div>
                    </div>
                    <div class="footprint-face"></div>
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
                  <button class="action-button">Reduce this score</button>
                </div>

                <div class="category-card food" data-key="Food">
                  <div class="category-icon">🍎</div>
                  <div class="category-name">Food</div>
                  <div class="category-percentage card-percent">--%</div>
                  <div class="category-subtitle card-delta"></div>
                  <button class="action-button">Reduce this score</button>
                </div>

                <div class="category-card travel" data-key="Travel">
                  <div class="category-icon">🚗</div>
                  <div class="category-name">Travel</div>
                  <div class="category-percentage card-percent">--%</div>
                  <div class="category-subtitle card-delta"></div>
                  <button class="action-button">Reduce this score</button>
                </div>

                <div class="category-card waste" data-key="Waste">
                  <div class="category-icon">🗑️</div>
                  <div class="category-name">Waste</div>
                  <div class="category-percentage card-percent">--%</div>
                  <div class="category-subtitle card-delta"></div>
                  <button class="action-button">Reduce this score</button>
                </div>

                <div class="category-card electricity" data-key="Electricity">
                  <div class="category-icon">⚡</div>
                  <div class="category-name">Electricity</div>
                  <div class="category-percentage card-percent">--%</div>
                  <div class="category-subtitle card-delta"></div>
                  <button class="action-button">Reduce this score</button>
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
      <a href="{{ url('/analytics') }}" class="floating-icon" title="Analytics">🔥</a>
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
      // ----- Nav + micro interactions (unchanged) -----
      document.querySelectorAll('.nav-item').forEach(item => {
        item.addEventListener('click', function() {
          document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
          this.classList.add('active');
        });
      });

      document.querySelectorAll('.category-card').forEach(card => {
        card.addEventListener('click', function() {
          this.style.transform = 'translateY(-8px) scale(1.02)';
          setTimeout(() => { this.style.transform = 'translateY(-5px)'; }, 200);
        });
      });

      document.querySelectorAll('.floating-icon').forEach((icon, index) => {
        icon.style.animationDelay = `${index * 0.2}s`;
        icon.addEventListener('click', function() {
          this.style.transform = 'scale(1.2) rotate(360deg)';
          setTimeout(() => { this.style.transform = 'scale(1.1)'; }, 300);
        });
      });

      document.querySelectorAll('.action-button').forEach(button => {
        if (button.id === 'openDetails') return; // handled separately
        button.addEventListener('click', function(e) {
          e.stopPropagation();
          this.style.transform = 'scale(0.95)';
          this.innerHTML = 'Loading...';
          setTimeout(() => { this.innerHTML = 'Reduce this score'; this.style.transform = 'scale(1.05)'; }, 1000);
        });
      });

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
        } else if (d.headline.target_pct !== null) {
          const t = d.headline.target_pct;
          // Interpret as "target is t% of current"
          const estTargetWeekly = weeklyTotal * (t / 100);
          rightValue     = fmt(weeklyToBasis(estTargetWeekly, VIEW_BASIS));
          rightTitle     = d.headline.target_label ? `Target • ${d.headline.target_label}` : 'Target (est.)';
          const src = d.headline.target_source === 'user'
            ? 'target'
            : (d.headline.target_source === 'rolling_avg' ? 'avg (last 5)' : '');
          rightBadgeText = src ? `${t}% • ${src}` : `${t}%`;
          rightBadgeType = t <= 100 ? 'good' : 'warn';
        }

        if (rightValue === null) {
          rightTitle = 'Comparison';
          rightValue = '--';
        }

        document.querySelector('#headlineTitleRight').textContent = rightTitle;
        document.querySelector('#headlinePctRight').textContent   = String(rightValue);
        document.querySelector('#headlineUnitRight').textContent  = rightUnit;
        setBadge(document.querySelector('#headlineBadgeRight'), rightBadgeText, rightBadgeType);

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
            const sign = c.delta.abs > 0 ? '+' : '';
            deltaEl.textContent = `${sign}${c.delta.abs} kg (${c.delta.pct >= 0 ? '+' : ''}${c.delta.pct}%) vs last`;
            deltaEl.style.opacity = 0.9;
          } else {
            deltaEl.textContent = 'First run';
            deltaEl.style.opacity = 0.6;
          }
        });

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
      document.getElementById('openDetails').onclick = () => {
        renderDetails(lastSummary);
        modal.style.display = 'flex';
      };
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

          // Goal band (if provided): prefer absolute weekly target; else interpret target_pct as % of *current* weekly
          let targetWeekly = null;
          if (typeof d.headline.target_abs_weekly === 'number') {
            targetWeekly = d.headline.target_abs_weekly;
          } else if (typeof d.headline.target_pct === 'number') {
            targetWeekly = wkTotal * (d.headline.target_pct / 100);
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
                  tension: 0.25,
                  pointRadius: 2
                }]
              },
              options: {
                scales: {
                  y: { ticks: { callback: (v) => fmtShort(v) }, beginAtZero: true }
                },
                plugins: {
                  legend: { display: false },
                  tooltip: { callbacks: { label: (ctx) => `${ctx.dataset.label}: ${fmt(ctx.parsed.y)}` } },
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

      document.querySelectorAll('.nav-item').forEach(item => {
        item.addEventListener('click', function() {
          document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
          this.classList.add('active');
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
