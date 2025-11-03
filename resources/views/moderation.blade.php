<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sustena - Feedback & Moderation</title>
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
  </style>
</head>
<body>
  <div class="header">
    <div class="logo">
      <svg class="leaf-icon" viewBox="0 0 24 24" fill="white">
        <path d="M17,8C8,10 5.9,16.17 3.82,21.34L5.71,22L6.66,19.7C7.14,19.87 7.64,20 8,20C19,20 22,3 22,3C21,5 14,5.25 9,6.25C4,7.25 2,11.5 2,13.5C2,15.5 3.75,17.25 3.75,17.25C7,8 17,8 17,8Z"/>
      </svg>
      <span>SUSTENA</span>
    </div>
    <div class="admin-profile">
      <span>ADMIN</span>
      <div class="profile-icon"></div>
    </div>
  </div>

  <div class="container">
      <div class="sidebar">
     <div class="menu-item" onclick="window.location='{{ route('admin.dashboard') }}'">
        <span class="menu-icon">📊</span>
        <span>Dashboard</span>
     </div>
       <a href="{{ url('/moderation') }}" class="menu-item {{ request()->is('moderation') ? 'active' : '' }}">
    <span class="menu-icon">💬</span><span>Feedback & Moderation</span>
      </a>
       <a href="{{ url('/adminsettings') }}" class="menu-item {{ request()->is('adminsettings') ? 'active' : '' }}">
    <span class="menu-icon">⚙️</span><span>Settings</span>
      </a>
    </div>

    <div class="main-content">
      <h2 class="section-title">Community Feedback Overview</h2>

      <!-- STATS CARDS -->
      <div class="stats-grid">
        <div class="stat-card"><h3>1,245</h3><p>Total Comments</p></div>
        <div class="stat-card"><h3>56</h3><p>Pending</p></div>
        <div class="stat-card"><h3>8</h3><p>Flagged</p></div>
        <div class="stat-card"><h3>1,181</h3><p>Approved</p></div>
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

      <!-- FILTER TOOLS -->
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
    </div>
  </div>

  <script>
    const commentsData = [
      { user: "Maria Santos", comment: "Loved the new recycling feature!", status: "Approved", time: "5 mins ago" },
      { user: "Juan Dela Cruz", comment: "App keeps lagging when submitting missions.", status: "Pending", time: "12 mins ago" },
      { user: "Carlos Lopez", comment: "This is a scam app!", status: "Flagged", time: "20 mins ago" },
      { user: "Anna Cruz", comment: "Nice work on the update!", status: "Approved", time: "30 mins ago" },
      { user: "Rico Tan", comment: "My report button doesn’t work.", status: "Pending", time: "45 mins ago" }
    ];

    const tableBody = document.getElementById("commentsTable");
    const filterButtons = document.querySelectorAll(".filter-tab");
    const searchInput = document.getElementById("searchInput");
    const noResults = document.getElementById("noResults");

    let currentFilter = "All";
    let searchQuery = "";

    function renderTable() {
      tableBody.innerHTML = "";
      const filtered = commentsData.filter(item => {
        const matchesFilter = currentFilter === "All" || item.status === currentFilter;
        const matchesSearch =
          item.user.toLowerCase().includes(searchQuery) ||
          item.comment.toLowerCase().includes(searchQuery);
        return matchesFilter && matchesSearch;
      });

      if (filtered.length === 0) {
        noResults.style.display = "block";
        return;
      } else {
        noResults.style.display = "none";
      }

      filtered.forEach(item => {
        const row = document.createElement("tr");
        row.innerHTML = `
          <td><input type="checkbox" class="rowCheck"></td>
          <td>${item.user}</td>
          <td>"${item.comment}"</td>
          <td><span class="status-badge status-${item.status.toLowerCase()}">${item.status}</span></td>
          <td>
            <button class="action-btn">Review</button>
            <button class="remove-btn">Delete</button>
          </td>
          <td>${item.time}</td>
        `;
        tableBody.appendChild(row);
      });
    }

    // Filter click
    filterButtons.forEach(btn => {
      btn.addEventListener("click", () => {
        filterButtons.forEach(b => b.classList.remove("active"));
        btn.classList.add("active");
        currentFilter = btn.dataset.filter;
        renderTable();
      });
    });

    // Search input
    searchInput.addEventListener("input", e => {
      searchQuery = e.target.value.toLowerCase();
      renderTable();
    });

    // Select all checkbox
    document.getElementById("selectAll").addEventListener("change", e => {
      document.querySelectorAll(".rowCheck").forEach(c => (c.checked = e.target.checked));
    });

    // Initial render
    renderTable();

    // Charts
    new Chart(document.getElementById("commentsTrend"), {
      type: "line",
      data: {
        labels: ["Mon","Tue","Wed","Thu","Fri","Sat","Sun"],
        datasets: [{
          label: "Comments per Day",
          data: [120,190,150,220,300,280,320],
          borderColor: "#10b981",
          backgroundColor: "rgba(16,185,129,0.2)",
          borderWidth: 2,
          fill: true,
          tension: 0.3
        }]
      },
      options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });

    new Chart(document.getElementById("statusChart"), {
      type: "doughnut",
      data: {
        labels: ["Approved","Pending","Flagged"],
        datasets: [{ data: [1181, 56, 8], backgroundColor: ["#10b981","#facc15","#ef4444"] }]
      },
      options: { plugins: { legend: { position: "bottom" } } }
    });
  </script>
</body>
</html>
