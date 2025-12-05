<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Feedback & Moderation - SUSTENA Admin</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    /* ====== STATS CARDS ====== */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 15px;
      margin-bottom: 25px;
    }
    .stat-card {
      background: #1e293b;
      color: white;
      border-radius: 12px;
      text-align: center;
      padding: 20px 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.3);
    }
    .stat-card h3 { margin: 0; font-size: 24px; }
    .stat-card p { margin: 5px 0 0; font-size: 14px; color: #cbd5e1; }

    /* ====== FILTER TOOLS ====== */
    .tools-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin: 15px 0;
      flex-wrap: wrap;
      gap: 10px;
    }
    .tools-bar input {
      padding: 8px 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
      width: 250px;
    }
    .filter-tabs {
      display: flex;
      gap: 10px;
    }
    .filter-tab {
      background: #f1f5f9;
      border: none;
      padding: 8px 14px;
      border-radius: 8px;
      cursor: pointer;
      transition: 0.2s;
      font-weight: 600;
    }
    .filter-tab.active { background: #10b981; color: white; }
    .filter-tab:hover { background: #d1fae5; }

    /* ====== CHARTS ====== */
    .charts-grid {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      gap: 20px;
      margin-bottom: 30px;
    }
    .chart-card {
      background: white;
      border-radius: 12px;
      padding: 15px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      text-align: center;
    }
    canvas { max-height: 180px !important; }

    /* ====== LIVE COMMENTS ====== */
    .live-comments {
      background: white;
      border-radius: 12px;
      padding: 15px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      height: 260px;
      overflow-y: auto;
    }
    .live-comment { border-bottom: 1px solid #eee; padding: 8px 0; }
    .live-comment:last-child { border-bottom: none; }
    .live-comment strong { color: #1e293b; }
    .live-comment span { font-size: 13px; color: #6b7280; }

    /* ====== TABLE & BUTTONS ====== */
    .activity-table input[type="checkbox"] { transform: scale(1.2); }
    .bulk-actions { margin-top: 10px; }
    .bulk-btn {
      background: #334155;
      color: white;
      padding: 8px 14px;
      border-radius: 8px;
      margin-right: 5px;
      border: none;
      cursor: pointer;
    }
    .bulk-btn:hover { background: #475569; }

    .no-results {
      text-align: center;
      color: #aaa;
      padding: 15px;
      font-style: italic;
    }

    /* --- added: posts section visuals reuse same styles --- */
    .section-divider { height: 1px; background:#e5e7eb; margin: 28px 0; }
  </style>
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
        <a href="{{ route('admin.analytics') }}" class="nav-link">
          <span class="nav-icon">📈</span> Analytics
        </a>
        <a href="{{ route('admin.moderation') }}" class="nav-link active">
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
        <h1>💬 Feedback & Moderation</h1>
        <p>Manage forum posts, comments, and community feedback</p>
      </header>

      <h2 class="section-title">Community Feedback Overview</h2>

      <!-- STATS CARDS -->
      <div class="stats-grid">
        <div class="stat-card"><h3 id="cardTotal">0</h3><p>Total Comments</p></div>
        <div class="stat-card"><h3 id="cardPending">0</h3><p>Pending</p></div>
        <div class="stat-card"><h3 id="cardFlagged">0</h3><p>Flagged</p></div>
        <div class="stat-card"><h3 id="cardApproved">0</h3><p>Approved</p></div>
      </div>

      <!-- CHARTS + LIVE COMMENTS -->
      <div class="charts-grid">
        <div class="chart-card">
          <h4>Comments Over Time</h4>
          <canvas id="commentsTrend"></canvas>
        </div>
        <div class="chart-card">
          <h4>Status Breakdown</h4>
          <canvas id="statusChart"></canvas>
        </div>
        <div class="live-comments">
          <h4>🟢 Live Comments</h4>
          <div class="live-comment"><strong>Anna Cruz:</strong> “Just joined! Love the clean UI.” <span>• 1 min ago</span></div>
          <div class="live-comment"><strong>Mark Reyes:</strong> “Some features aren’t loading.” <span>• 3 mins ago</span></div>
          <div class="live-comment"><strong>Ella Flores:</strong> “Can we get dark mode soon?” <span>• 5 mins ago</span></div>
          <div class="live-comment"><strong>Jayson Lim:</strong> “This project’s amazing!” <span>• 10 mins ago</span></div>
        </div>
      </div>

      <!-- FILTER TOOLS (COMMENTS) -->
      <div class="tools-bar">
        <input type="text" id="searchInput" placeholder="Search comments...">
        <div class="filter-tabs">
          <button class="filter-tab active" data-filter="All">All</button>
          <button class="filter-tab" data-filter="Pending">Pending</button>
          <button class="filter-tab" data-filter="Flagged">Flagged</button>
          <button class="filter-tab" data-filter="Approved">Approved</button>
        </div>
      </div>

      <!-- COMMENTS TABLE -->
      <div class="card">
        <h3 class="section-title">User Comments & Feedback</h3>
        <table class="activity-table">
          <thead>
            <tr>
              <th><input type="checkbox" id="selectAll"></th>
              <th>User</th>
              <th>Comment</th>
              <th>Status</th>
              <th>Actions</th>
              <th>Time</th>
            </tr>
          </thead>
          <tbody id="commentsTable"></tbody>
        </table>
        <div class="no-results" id="noResults" style="display:none;">No results found</div>

        <div class="bulk-actions">
          <button class="bulk-btn">Approve Selected</button>
          <button class="bulk-btn">Delete Selected</button>
        </div>
      </div>

      <!-- ========================================================= -->
      <!-- ===================== ADDED: POSTS UI =================== -->
      <!-- ========================================================= -->

      <div class="section-divider"></div>

      <h2 class="section-title">Posts Moderation</h2>

      <!-- FILTER TOOLS (POSTS) -->
      <div class="tools-bar">
        <input type="text" id="postSearchInput" placeholder="Search posts...">
        <div class="filter-tabs" id="postFilterTabs">
          <button class="filter-tab active" data-post-filter="All">All</button>
          <button class="filter-tab" data-post-filter="Pending">Pending</button>
          <button class="filter-tab" data-post-filter="Flagged">Flagged</button>
          <button class="filter-tab" data-post-filter="Approved">Approved</button>
        </div>
      </div>

      <!-- POSTS TABLE -->
      <div class="card">
        <h3 class="section-title">User Posts</h3>
        <table class="activity-table">
          <thead>
            <tr>
              <th><input type="checkbox" id="postSelectAll"></th>
              <th>User</th>
              <th>Title</th>
              <th>Status</th>
              <th>Actions</th>
              <th>Time</th>
            </tr>
          </thead>
          <tbody id="postsTable"></tbody>
        </table>
        <div class="no-results" id="postNoResults" style="display:none;">No results found</div>

        <div class="bulk-actions">
          <button class="bulk-btn post-bulk-btn" data-post-action="approve">Approve Selected</button>
          <button class="bulk-btn post-bulk-btn" data-post-action="delete">Delete Selected</button>
        </div>
      </div>
      <!-- =================== END ADDED: POSTS UI ================= -->
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
  // Helpers
  const qs  = s => document.querySelector(s);
  const qsa = s => Array.from(document.querySelectorAll(s));
  const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  async function fetchJSON(url, opts = {}) {
    const res = await fetch(url, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': csrf,
        ...(opts.headers || {})
      },
      credentials: 'same-origin',
      ...opts
    });
    return res.json();
  }

  // ======== COMMENTS STATE & LOGIC (unchanged) ========
  let currentFilter = 'All';
  let searchQuery   = '';
  let page          = 1;
  const per         = 10;

  async function loadStats() {
    const j = await fetchJSON('{{ route('moderation.stats') }}');
    qs('#cardTotal').textContent    = j.cards.total;
    qs('#cardPending').textContent  = j.cards.pending;
    qs('#cardFlagged').textContent  = j.cards.flagged;
    qs('#cardApproved').textContent = j.cards.approved;
    renderCharts(j.trend, j.breakdown);
  }

  function renderCharts(trend, breakdown) {
    if (window._trendChart) { window._trendChart.destroy(); }
    if (window._statusChart) { window._statusChart.destroy(); }

    const trendCtx = document.getElementById('commentsTrend');
    const statusCtx = document.getElementById('statusChart');

    window._trendChart = new Chart(trendCtx, {
      type: 'line',
      data: {
        labels: trend.labels,
        datasets: [{
          label: 'Comments per Day',
          data: trend.data,
          borderColor: '#10b981',
          backgroundColor: 'rgba(16,185,129,0.2)',
          borderWidth: 2, fill: true, tension: 0.3
        }]
      },
      options: { plugins:{ legend:{ display:false } }, scales:{ y:{ beginAtZero:true } } }
    });

    window._statusChart = new Chart(statusCtx, {
      type: 'doughnut',
      data: {
        labels: breakdown.labels,
        datasets: [{ data: breakdown.data, backgroundColor: ['#10b981','#facc15','#ef4444'] }]
      },
      options: { plugins:{ legend:{ position:'bottom' } } }
    });
  }

  function escapeHtml(s) {
    return s.replace(/[&<>"]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]));
  }

  async function loadComments() {
    const url = new URL('{{ route('moderation.comments') }}', window.location.origin);
    url.searchParams.set('status', currentFilter);
    url.searchParams.set('q', searchQuery);
    url.searchParams.set('page', page);
    url.searchParams.set('per', per);

    const j = await fetchJSON(url.toString());
    const tbody = qs('#commentsTable');
    const noResults = qs('#noResults');
    tbody.innerHTML = '';

    if (!j.data || j.data.length === 0) {
      noResults.style.display = 'block';
      return;
    }
    noResults.style.display = 'none';

    j.data.forEach(row => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td><input type="checkbox" class="rowCheck" data-id="${row.id}"></td>
        <td>${row.user}</td>
        <td>"${escapeHtml(row.comment)}"</td>
        <td><span class="status-badge status-${row.status.toLowerCase()}">${row.status}</span></td>
        <td>
          <button class="action-btn" data-act="approve" data-id="${row.id}">Review</button>
          <button class="remove-btn" data-act="delete" data-id="${row.id}">Delete</button>
        </td>
        <td>${row.time_ago}</td>
      `;
      tbody.appendChild(tr);
    });
  }

  qsa('.filter-tab').forEach(btn => {
    btn.addEventListener('click', () => {
      qsa('.filter-tab').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      currentFilter = btn.dataset.filter;
      page = 1;
      loadComments();
      loadStats();
    });
  });

  const searchInput = qs('#searchInput');
  searchInput.addEventListener('input', e => {
    searchQuery = e.target.value.toLowerCase();
    page = 1;
    loadComments();
  });

  qs('#selectAll').addEventListener('change', e => {
    qsa('.rowCheck').forEach(c => c.checked = e.target.checked);
  });

  qs('#commentsTable').addEventListener('click', async (e) => {
    const btn = e.target.closest('button');
    if (!btn) return;
    const id  = btn.dataset.id;
    const act = btn.dataset.act;

    if (act === 'approve') {
      await fetchJSON('{{ route('moderation.approve') }}', { method: 'POST', body: new URLSearchParams({ id }) });
    } else if (act === 'delete') {
      if (!confirm('Delete this comment?')) return;
      await fetchJSON('{{ route('moderation.delete') }}', { method: 'POST', body: new URLSearchParams({ id }) });
    }
    await loadComments();
    await loadStats();
  });

  function getCheckedIds() {
    return qsa('.rowCheck:checked').map(c => c.dataset.id);
  }
  document.addEventListener('click', async (e) => {
    if (e.target.matches('.bulk-btn') && !e.target.classList.contains('post-bulk-btn')) {
      const label = e.target.textContent.trim().toLowerCase();
      const action = label.includes('approve') ? 'approve' : 'delete';
      const ids = getCheckedIds();
      if (ids.length === 0) return alert('No rows selected');

      if (action === 'delete' && !confirm('Delete selected comments?')) return;

      await fetchJSON('{{ route('moderation.bulk') }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
        body: JSON.stringify({ action, ids })
      });
      qs('#selectAll').checked = false;
      await loadComments();
      await loadStats();
    }
  });

  async function loadLive() {
    const j = await fetchJSON('{{ route('moderation.live') }}');
    const wrap = document.querySelector('.live-comments');
    const list = document.createElement('div');
    list.id = 'liveList';
    list.innerHTML = '';
    const old = wrap.querySelector('#liveList');
    if (old) old.remove();

    j.items.forEach(o => {
      const div = document.createElement('div');
      div.className = 'live-comment';
      div.innerHTML = `<strong>${o.user}:</strong> "${escapeHtml(o.text)}" <span>• ${o.ago}</span>`;
      list.appendChild(div);
    });
    wrap.appendChild(list);
  }
  setInterval(loadLive, 10000);

  // Initial load (comments)
  loadStats();
  loadComments();
  loadLive();

  // ============================================================
  // ===================== ADDED: POSTS LOGIC ====================
  // ============================================================

  // Separate state for posts
  let postCurrentFilter = 'All';
  let postSearchQuery   = '';
  let postPage          = 1;
  const postPer         = 10;

  async function loadPosts() {
    const url = new URL('{{ route('moderation.posts') }}', window.location.origin);
    url.searchParams.set('status', postCurrentFilter);
    url.searchParams.set('q', postSearchQuery);
    url.searchParams.set('page', postPage);
    url.searchParams.set('per', postPer);

    const j = await fetchJSON(url.toString());
    const tbody = qs('#postsTable');
    const noResults = qs('#postNoResults');
    tbody.innerHTML = '';

    if (!j.data || j.data.length === 0) {
      noResults.style.display = 'block';
      return;
    }
    noResults.style.display = 'none';

    j.data.forEach(row => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td><input type="checkbox" class="postRowCheck" data-id="${row.id}"></td>
        <td>${row.user}</td>
        <td>${escapeHtml(row.title)}</td>
        <td><span class="status-badge status-${row.status?.toLowerCase?.() || 'unknown'}">${row.status}</span></td>
        <td>
          <button class="action-btn" data-post-act="approve" data-id="${row.id}">Review</button>
          <button class="remove-btn" data-post-act="delete" data-id="${row.id}">Delete</button>
        </td>
        <td>${row.time_ago || ''}</td>
      `;
      tbody.appendChild(tr);
    });
  }

  // Posts filters
  qsa('#postFilterTabs .filter-tab').forEach(btn => {
    btn.addEventListener('click', () => {
      qsa('#postFilterTabs .filter-tab').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      postCurrentFilter = btn.dataset.postFilter;
      postPage = 1;
      loadPosts();
    });
  });

  // Posts search
  const postSearchInput = qs('#postSearchInput');
  postSearchInput.addEventListener('input', e => {
    postSearchQuery = e.target.value.toLowerCase();
    postPage = 1;
    loadPosts();
  });

  // Posts select all
  qs('#postSelectAll').addEventListener('change', e => {
    qsa('.postRowCheck').forEach(c => c.checked = e.target.checked);
  });

  // Posts row actions
  qs('#postsTable').addEventListener('click', async (e) => {
    const btn = e.target.closest('button');
    if (!btn) return;
    const id  = btn.dataset.id;
    const act = btn.dataset.postAct;

    if (act === 'approve') {
      await fetchJSON('{{ route('moderation.postApprove') }}', { method: 'POST', body: new URLSearchParams({ id }) });
    } else if (act === 'delete') {
      if (!confirm('Delete this post?')) return;
      await fetchJSON('{{ route('moderation.postDelete') }}', { method: 'POST', body: new URLSearchParams({ id }) });
    }
    await loadPosts();
  });

  // Posts bulk helpers
  function getPostCheckedIds() {
    return qsa('.postRowCheck:checked').map(c => c.dataset.id);
  }

  document.addEventListener('click', async (e) => {
    const btn = e.target.closest('.post-bulk-btn');
    if (!btn) return;

    const action = btn.dataset.postAction; // approve | delete
    const ids = getPostCheckedIds();
    if (ids.length === 0) return alert('No rows selected');

    if (action === 'delete' && !confirm('Delete selected posts?')) return;

    await fetchJSON('{{ route('moderation.postBulk') }}', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
      body: JSON.stringify({ action, ids })
    });
    qs('#postSelectAll').checked = false;
    await loadPosts();
  });

  // Initial load (posts)
  loadPosts();
  // =================== END ADDED: POSTS LOGIC ==================

  function openLogoutModal() {
    document.getElementById('logoutModal').style.display = 'flex';
  }

  function closeLogoutModal() {
    document.getElementById('logoutModal').style.display = 'none';
  }

  function confirmLogout() {
    document.getElementById('logout-form').submit();
  }
  </script>
</body>
</html>
