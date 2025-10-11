<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SUSTENA - Profile</title>
  @php
    use Illuminate\Support\Facades\Auth;
    $user = Auth::user();
    $xpTotal = $user->xp_total ?? null;
    $level   = $user->level ?? null;
    $username = session('username') ?? ($user->name ?? 'Guest');
  @endphp
  <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
  <style>
    /* Modal Styling */
    .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background: rgba(0,0,0,0.5); justify-content: center; align-items: center; }
    .modal-content { background: #fff; padding: 20px; width: 400px; max-width: 90%; border-radius: 10px; animation: fadeIn 0.3s ease-in-out; }
    @keyframes fadeIn { from { opacity: 0; transform: scale(0.9); } to { opacity: 1; transform: scale(1); } }
    .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
    .modal-header h2 { font-size: 1.2rem; margin: 0; }
    .close-btn { background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #333; }
    .modal form { display: flex; flex-direction: column; gap: 10px; }
    .modal form label { font-weight: bold; font-size: 0.9rem; }
    .modal form input, .modal form select { padding: 8px; border: 1px solid #ccc; border-radius: 5px; font-size: 0.9rem; }
    .modal form button { background-color: #2ecc71; color: white; border: none; padding: 10px; border-radius: 5px; cursor: pointer; transition: background 0.3s ease; }
    .modal form button:hover { background-color: #27ae60; }

    .get-score-btn { background-color: #3498db; color: white; border: none; padding: 10px 15px; margin-top: 15px; border-radius: 5px; cursor: pointer; transition: background 0.3s ease; font-size: 0.9rem; }
    .get-score-btn:hover { background-color: #2980b9; }

    /* Simple progress bars */
    .progress-bar { width: 100%; height: 8px; background: #e5e7eb; border-radius: 999px; overflow: hidden; margin: 10px 0; }
    .progress-fill { height: 100%; background: linear-gradient(90deg, #66bb6a, #4caf50); width: 0%; transition: width .4s ease; }

    .xp-row { display:flex; gap:8px; align-items:center; flex-wrap:wrap; }
    .xp-pill { background:#eef6ee; padding:4px 8px; border-radius:999px; font-size:.85rem; }
    .muted { opacity:.7; font-size:.9rem; }

    .status-icon { margin-left: 6px; }
  </style>
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
  <a href="{{ url('/forum') }}" class="nav-item">
    <div class="nav-icon">💬</div>
    <span>MicroForum</span>
  </a>
  <a href="{{ url('/profile') }}" class="nav-item active">
    <div class="nav-icon">👤</div>
    <span>Profile</span>
  </a>
</div>

<!-- Top Navigation -->
<div class="floating-icons">
    <a href="{{ route('analytics') }}" class="floating-icon" title="Analytics">🔥</a>
    <a href="{{ route('learning-modules') }}" class="floating-icon" title="Learning Modules">🌱</a>
    <a href="{{ route('leaderboard') }}" class="floating-icon" title="Leaderboard">🏆</a>
    <a href="{{ route('badges') }}" class="floating-icon" title="Badges">🥇</a>
    <a href="{{ route('settings') }}" class="floating-icon" title="Settings">⚙️</a>
</div>

<!-- Main Content -->
<div class="main-content"
     data-xp="{{ $xpTotal !== null ? (int)$xpTotal : '' }}"
     data-level="{{ $level !== null ? (int)$level : '' }}"
>
  <!-- Profile Header -->
  <div class="profile-header">
    <div class="profile-content">
      <div class="profile-avatar">👤</div>
      <div class="profile-info">
        <h1 class="profile-name">{{ $username }} [Ecosaver]</h1>

        <div class="xp-row" id="xpSummary">
          <span class="xp-pill">Level: <strong id="levelText">{{ $level ?? '—' }}</strong></span>
          <span class="xp-pill">XP: <strong id="xpText">{{ $xpTotal ?? '—' }}</strong></span>
          <span class="muted" id="xpNextLabel">—</span>
        </div>
        <div class="progress-bar" title="XP towards next level">
          <div class="progress-fill" id="xpProgress" style="width:0%"></div>
        </div>

        <div style="display: flex; gap: 10px; align-items: center; margin-top:8px;">
          <button class="edit-profile-btn" id="openModalBtn">Edit Profile</button>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="edit-profile-btn" style="background-color: #e74c3c;">Logout</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Profile Modal -->
  <div class="modal" id="editProfileModal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Edit Profile</h2>
        <button class="close-btn" id="closeModalBtn">&times;</button>
      </div>
      <form method="POST" action="{{ route('update-profile') }}">
        @csrf
        <label for="username">Username</label>
        <input type="text" id="username" name="username" value="{{ session('username') ?? '' }}" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ $user->email ?? '' }}" required>

        <label for="diet">Diet</label>
        <select id="diet" name="diet">
          <option value="Vegan">Vegan</option>
          <option value="Vegetarian">Vegetarian</option>
          <option value="Omnivore">Omnivore</option>
        </select>

        <label for="transport">Transport</label>
        <select id="transport" name="transport">
          <option value="Bike + Public Transport">Bike + Public Transport</option>
          <option value="Car">Car</option>
          <option value="Walking">Walking</option>
        </select>

        <button type="submit">Save Changes</button>
      </form>
    </div>
  </div>

  <!-- Content Layout -->
  <div class="content-layout">
    <!-- Left Content -->
    <div class="left-content">
      <div class="lifestyle-card">
        <div class="card-header">
          <div class="card-icon">🌿</div>
          <div class="card-title">Lifestyle Info</div>
        </div>
        <div class="lifestyle-item">
          <div class="lifestyle-icon">🥗</div>
          <div class="lifestyle-text">
            <div class="lifestyle-label">Diet</div>
            <div class="lifestyle-value" id="dietValue">Vegan</div>
          </div>
        </div>
        <div class="lifestyle-item">
          <div class="lifestyle-icon">🚲</div>
          <div class="lifestyle-text">
            <div class="lifestyle-label">Transport</div>
            <div class="lifestyle-value" id="transportValue">Bike + Public Transport</div>
          </div>
        </div>
        <div class="lifestyle-item">
          <div class="lifestyle-icon">🏠</div>
          <div class="lifestyle-text">
            <div class="lifestyle-label">Home</div>
            <div class="lifestyle-value" id="homeValue">Apartment</div>
          </div>
        </div>
        <div class="lifestyle-item">
          <div class="lifestyle-icon">⚡</div>
          <div class="lifestyle-text">
            <div class="lifestyle-label">Energy</div>
            <div class="lifestyle-value" id="energyValue">Renewable</div>
          </div>
        </div>
      </div>

      <!-- Carbon Stats (dynamic) -->
      <div class="carbon-card">
        <div class="card-header">
          <div class="card-icon">📊</div>
          <div class="card-title">Carbon Stats</div>
        </div>
        <div class="carbon-main">
          <div class="carbon-total" id="totalYearly">Total CO₂: —</div>
          <div class="carbon-subtitle" id="totalDelta">(—)</div>
        </div>
        <div class="carbon-item" id="bestCat">
          <div class="carbon-label">
            🏆 Best:
            <span class="status-icon">✅</span>
          </div>
          <div class="carbon-value"></div>
        </div>
        <div class="carbon-item" id="improveCat">
          <div class="carbon-label">
            ⚠️ Improve:
            <span class="status-icon">🔄</span>
          </div>
          <div class="carbon-value"></div>
        </div>
        <div class="carbon-item" id="goalRow" style="display:none;">
          <div class="carbon-label">
            🎯 Goal
            <span class="status-icon">⏳</span>
          </div>
          <div class="carbon-value" id="goalValue"></div>
        </div>
      </div>
    </div>

    <!-- Right Content -->
    <div class="right-content">
      <!-- Your Impact -->
      <div class="impact-card">
        <div class="card-header">
          <div class="card-icon">🌍</div>
          <div class="card-title">Your Impact</div>
        </div>

        <div class="impact-main">
          <div class="impact-title">Your Footprint Score</div>

          <div class="impact-value" id="impactValue">
            {{ session('footprint_score') ?? 'No score yet' }}
          </div>

          <div class="progress-bar">
            <div class="progress-fill" id="impactProgress" style="width: {{ session('footprint_score') ? min(session('footprint_score'), 100) . '%' : '0%' }};"></div>
          </div>

          <div class="progress-text" id="impactText">
            @if(session('footprint_score'))
              {{ session('footprint_score') }} kg CO₂ — your current impact level
            @else
              Start by taking the footprint calculator!
            @endif
          </div>

          <!-- Button to go to calculator -->
          <form method="GET" action="{{ url('/footprint-calculator') }}">
            <button type="submit" class="get-score-btn">Get a New Score</button>
          </form>
        </div>
      </div>

      <div class="achievements-card">
        <div class="card-header">
          <div class="card-icon">🏅</div>
          <div class="card-title">Achievements</div>
        </div>
        <div class="achievement-badges">
          <div class="badge">🌱</div>
          <div class="badge">🚲</div>
          <div class="badge">♻️</div>
        </div>
        <div class="achievement-level">
          <div class="level-stars" id="levelStars">
            <!-- stars will be updated to reflect level bracket -->
            <span class="star">⭐</span>
            <span class="star">⭐</span>
            <span class="star">⭐</span>
            <span class="star">⭐</span>
            <span class="star empty">⭐</span>
          </div>
          <div class="level-text" id="levelLabel">Level — Eco Champion</div>
          <div class="challenges-joined">Joined: 3 Challenges</div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  // ------------ Modal ------------
  const modal = document.getElementById('editProfileModal');
  const openModalBtn = document.getElementById('openModalBtn');
  const closeModalBtn = document.getElementById('closeModalBtn');

  if (openModalBtn) openModalBtn.addEventListener('click', () => modal.style.display = 'flex');
  if (closeModalBtn) closeModalBtn.addEventListener('click', () => modal.style.display = 'none');
  window.addEventListener('click', (e) => { if (e.target === modal) modal.style.display = 'none'; });

  function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    sidebar.classList.toggle('collapsed');
    mainContent.classList.toggle('expanded');
  }

  // ------------ XP / Level UI (client-side thresholds identical to server) ------------
  function levelThresholdTotal(level) {
    if (level <= 1) return 0;
    let sum = 0;
    for (let k = 1; k < level; k++) sum += Math.round(100 * Math.pow(k, 1.15));
    return sum;
  }
  function renderXp() {
    const root = document.querySelector('.main-content');
    const xp = root.getAttribute('data-xp');
    const lvl = root.getAttribute('data-level');

    const xpText = document.getElementById('xpText');
    const levelText = document.getElementById('levelText');
    const xpNextLabel = document.getElementById('xpNextLabel');
    const xpProgress = document.getElementById('xpProgress');
    const levelLabel = document.getElementById('levelLabel');
    const levelStars = document.getElementById('levelStars');

    if (!xp || !lvl) {
      xpText.textContent = '—';
      levelText.textContent = '—';
      xpNextLabel.textContent = 'Log an official attempt to earn XP!';
      xpProgress.style.width = '0%';
      levelLabel.textContent = 'Level — Eco Champion';
      return;
    }

    const xpTotal = parseInt(xp, 10);
    const level = parseInt(lvl, 10);

    xpText.textContent = xpTotal;
    levelText.textContent = level;

    const currMin = levelThresholdTotal(level);
    const nextMin = levelThresholdTotal(level + 1);
    const span = Math.max(1, nextMin - currMin);
    const into = Math.max(0, xpTotal - currMin);
    const pct = Math.max(0, Math.min(100, (into / span) * 100));
    xpProgress.style.width = pct.toFixed(0) + '%';
    xpNextLabel.textContent = `Next level in ${Math.max(0, nextMin - xpTotal)} XP`;

    // Update level label + simple star bucket (every 5 levels)
    const bucket = Math.min(5, Math.max(1, Math.ceil((level % 25 || 25) / 5)));
    if (levelStars) {
      const stars = levelStars.querySelectorAll('.star');
      stars.forEach((s, i) => s.classList.toggle('empty', i >= bucket));
    }
    levelLabel.textContent = `Level ${level} Eco Champion`;
  }

  // ------------ Analytics summary → fills Carbon cards + Impact ------------
  function fmt(n, decimals = 1) {
    if (n === null || n === undefined || isNaN(n)) return '—';
    const abs = Math.abs(n);
    if (abs >= 1000) return n.toFixed(0);
    return n.toFixed(decimals);
  }
  function loadFootprint() {
    fetch('/analytics/summary?limit=5', { headers: { 'Accept': 'application/json' } })
      .then(r => r.json())
      .then(d => {
        const totalYearlyEl = document.getElementById('totalYearly');
        const totalDeltaEl = document.getElementById('totalDelta');
        const bestCatEl = document.getElementById('bestCat');
        const improveCatEl = document.getElementById('improveCat');
        const goalRow = document.getElementById('goalRow');
        const goalValue = document.getElementById('goalValue');

        const impactValue = document.getElementById('impactValue');
        const impactProgress = document.getElementById('impactProgress');
        const impactText = document.getElementById('impactText');

        if (!d || !d.has_data) {
          totalYearlyEl.textContent = 'Total CO₂: —';
          totalDeltaEl.textContent = '(—)';
          bestCatEl.querySelector('.carbon-label').childNodes[0].nodeValue = '🏆 Best: ';
          bestCatEl.querySelector('.carbon-value').textContent = '(—)';
          improveCatEl.querySelector('.carbon-label').childNodes[0].nodeValue = '⚠️ Improve: ';
          improveCatEl.querySelector('.carbon-value').textContent = '(—)';
          goalRow.style.display = 'none';
          // Impact: keep session fallback already shown
          return;
        }

        // Weekly → Yearly
        const weekly = d.headline.kg_per_week ?? d.headline.total ?? 0;
        const yearly = weekly * 52.1429;
        totalYearlyEl.textContent = `Total CO₂: ${fmt(yearly, 1)} kg/year`;
        const delta = d.headline.delta_pct;
        totalDeltaEl.textContent = `(${delta > 0 ? '+' : ''}${delta ?? 0}%)`;

        // Best/Improve categories from kg_per_week
        if (Array.isArray(d.cards) && d.cards.length) {
          // Ensure kg value
          const withKg = d.cards.map(c => ({
            title: c.title,
            kg: c.kg_per_week ?? c.total ?? 0,
            pct: c.percent ?? null
          }));
          withKg.sort((a, b) => a.kg - b.kg);
          const best = withKg[0];
          const worst = withKg[withKg.length - 1];

          bestCatEl.querySelector('.carbon-label').childNodes[0].nodeValue = '🏆 Best: ' + best.title + ' ';
          bestCatEl.querySelector('.carbon-value').textContent = `(${fmt(best.kg)} kg/wk)`;

          improveCatEl.querySelector('.carbon-label').childNodes[0].nodeValue = '⚠️ Improve: ' + worst.title + ' ';
          improveCatEl.querySelector('.carbon-value').textContent = `(${fmt(worst.kg)} kg/wk)`;
        }

        // Goal / target (from headline.target_pct)
        if (d.headline && d.headline.target_pct !== null && d.headline.target_pct !== undefined) {
          const t = d.headline.target_pct;
          goalRow.style.display = '';
          goalValue.textContent = `${t}% of your target`;
        } else {
          goalRow.style.display = 'none';
        }

        // Impact card: show weekly score prominently
        impactValue.textContent = fmt(weekly, 1) + ' kg CO₂ / wk';
        const pctWidth = Math.max(0, Math.min(100, (weekly / 100) * 100)); // naive scale to 100
        impactProgress.style.width = pctWidth + '%';
        impactText.textContent = `${fmt(weekly,1)} kg CO₂ per week — your current impact level`;
      })
      .catch(() => {
        // silent fail → keep placeholders/session fallback
      });
  }
fetch('/me/xp')
  .then(r => r.json())
  .then(d => {
    const root = document.querySelector('.main-content');
    root.setAttribute('data-xp', d.xp);
    root.setAttribute('data-level', d.level);
    if (typeof renderXp === 'function') renderXp();
  });

  // Init
  renderXp();
  loadFootprint();
</script>
</body>
</html>
