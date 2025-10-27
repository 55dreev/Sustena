<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sustena - Admin Dashboard</title>
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

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
      <div class="menu-item active"><span class="menu-icon">📊</span><span>Dashboard</span></div>
      <div class="menu-item"><span class="menu-icon">📦</span><span>Content Management</span></div>
      <div class="menu-item"><span class="menu-icon">💬</span><span>Feedback & Moderation</span></div>
      <div class="menu-item"><span class="menu-icon">⚙️</span><span>Settings</span></div>
    </div>

    <div class="main-content">
      <div class="dashboard-grid">
        <!-- User Management card with button -->
        <div class="card">
          <div class="card-header">
            <div class="card-icon">👥</div>
            <div>
              <div class="card-title">User Management</div>
              <div class="card-subtitle">Search, edit and moderate users</div>
            </div>
          </div>
          <button onclick="openModal('userModal')" class="action-btn">Manage Users</button>
        </div>

        <div class="card">
          <div class="card-header">
            <div class="card-icon">🏆</div>
            <div>
              <div class="card-title">Challenge Management</div>
              <div class="card-subtitle">12 active challenges</div>
            </div>
          </div>
          <button onclick="openModal('challengeModal')" class="action-btn">Manage Challenges</button>
        </div>

        <div class="card">
          <div class="card-header">
            <div class="card-icon">🏅</div>
            <div>
              <div class="card-title">Badge Management</div>
              <div class="card-subtitle">14 available badges</div>
            </div>
          </div>
          <button onclick="openModal('badgeModal')" class="action-btn">Manage Badges</button>
        </div>

        <div class="card">
          <div class="card-header">
            <div class="card-icon">📚</div>
            <div>
              <div class="card-title">Research Data</div>
              <div class="card-subtitle">Browse Data<br>Download .csv</div>
            </div>
          </div>
          <button onclick="openModal('dataModal')" class="action-btn">Download Data</button>
        </div>
      </div>

      <div class="recent-activity-section">
        <h2 class="section-title">Recent User Activity</h2>
        <table class="activity-table">
          <thead>
            <tr><th>User</th><th>Email</th><th>Action</th><th>Timestamp</th></tr>
          </thead>
          <tbody>
            <tr><td>Maria Santos</td><td>maria.santos@example.com</td><td><span class="status-badge status-login">Login</span></td><td>2 minutes ago</td></tr>
            <tr><td>Juan Dela Cruz</td><td>juan.delacruz@example.com</td><td><span class="status-badge status-signup">Sign Up</span></td><td>15 minutes ago</td></tr>
            <tr><td>Ana Reyes</td><td>ana.reyes@example.com</td><td><span class="status-badge status-login">Login</span></td><td>23 minutes ago</td></tr>
            <tr><td>Carlos Lopez</td><td>carlos.lopez@example.com</td><td><span class="status-badge status-login">Login</span></td><td>45 minutes ago</td></tr>
            <tr><td>Isabel Garcia</td><td>isabel.garcia@example.com</td><td><span class="status-badge status-signup">Sign Up</span></td><td>1 hour ago</td></tr>
          </tbody>
        </table>
      </div>

      <div class="dashboard-grid">
        <div class="card">
          <h3 class="section-title">Total CO2 Saved (All Categories)</h3>
          <div class="chart-container"><div class="donut"></div></div>
        </div>

        <div class="card">
          <h3 class="section-title">Top User Activity</h3>
          <table class="top-users-table">
            <thead><tr><th>User</th><th>Email</th><th>Missions</th><th>Streak</th></tr></thead>
            <tbody>
              <tr><td>AliceSmith</td><td>alice@example.com</td><td>5</td><td>8 🔥</td></tr>
              <tr><td>BobJohnson</td><td>bob@example.com</td><td>8</td><td>12 🔥</td></tr>
              <tr><td>CharlieBrown</td><td>charlie@example.com</td><td>3</td><td>2 🔥</td></tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>

  <!-- Manage Challenges Modal -->
  <div class="modal fade" id="challengeModal" tabindex="-1" aria-labelledby="challengeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fw-bold" id="challengeModalLabel">Manage Challenges</h5>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="challengeTitle" class="form-label">Challenge Title:</label>
            <input type="text" id="challengeTitle" class="form-control" placeholder="Enter challenge title">
          </div>
          <button class="btn btn-success w-100 mb-3" id="addChallengeBtn">Add Challenge</button>
          <hr>
          <h6 class="fw-bold">Existing Challenges</h6>
          <ul class="list-unstyled mt-2">
            <li class="mb-2">Zero Waste Week <button class="remove-btn">Remove</button></li>
            <li class="mb-2">Bike to Work <button class="remove-btn">Remove</button></li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <!-- Manage Badges Modal -->
  <div class="modal fade" id="badgeModal" tabindex="-1" aria-labelledby="badgeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fw-bold" id="badgeModalLabel">Manage Badges</h5>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="badgeName" class="form-label">Badge Name:</label>
            <input type="text" id="badgeName" class="form-control" placeholder="Enter badge name">
          </div>
          <button class="btn btn-success w-100 mb-3" id="addBadgeBtn">Add Badge</button>
          <hr>
          <h6 class="fw-bold">Existing Badges</h6>
          <ul class="list-unstyled mt-2">
            <li class="mb-2">Eco Warrior <button class="remove-btn">Remove</button></li>
            <li class="mb-2">Carbon Saver <button class="remove-btn">Remove</button></li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <!-- User Management Modal -->
  <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fw-bold" id="userModalLabel">Manage Users</h5>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="searchUser" class="form-label">Search User:</label>
            <input type="text" id="searchUser" class="form-control" placeholder="Enter username or email">
          </div>
          <button class="btn btn-success w-100 mb-3" id="searchUserBtn">Search</button>
          <hr>
          <div id="userResult" style="display:none;">
            <h6 class="fw-bold">User Details</h6>
            <div class="user-info mb-3">
              <label for="editUserName">Name:</label>
              <input type="text" id="editUserName" class="form-control" value="">
              <label for="editUserEmail">Email:</label>
              <input type="email" id="editUserEmail" class="form-control" value="">
            </div>
            <div class="d-flex mt-3">
              <button class="btn btn-primary w-100 me-2" id="saveUserBtn">💾 Save</button>
              <button class="btn btn-warning w-100 me-2" id="warnUserBtn">⚠️ Warn</button>
              <button class="btn btn-secondary w-100 me-2" id="restrictUserBtn">🚫 Restrict</button>
              <button class="remove-btn w-100" id="banUserBtn">❌ Ban</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Research Data Modal -->
  <div id="dataModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal('dataModal')">&times;</span>
      <h2>Download Research Data</h2>
      <form id="dataDownloadForm">
        <label for="dataRange">Select Time Range:</label>
        <select id="dataRange" name="dataRange">
          <option value="weekly">Weekly</option>
          <option value="monthly">Monthly</option>
          <option value="yearly">Yearly</option>
        </select>
        <button type="submit">Download CSV</button>
      </form>
    </div>
  </div>

  <script>
    /* Modal open/close helpers */
    function openModal(id) { document.getElementById(id).style.display = 'flex'; }
    function closeModal(id) { document.getElementById(id).style.display = 'none'; }
    window.onclick = function(event) {
      document.querySelectorAll('.modal').forEach(m => { if (event.target === m) m.style.display = 'none'; });
    };

    /* Safe event listeners (only attach if elements exist) */
    // Data download
    const dataForm = document.getElementById('dataDownloadForm');
    if (dataForm) {
      dataForm.addEventListener('submit', e => {
        e.preventDefault();
        const range = document.getElementById('dataRange').value;
        alert(`📊 Downloading ${range} CSV... (backend logic coming soon)`);
      });
    }

    // Add Challenge (mock)
    const addChallengeBtn = document.getElementById('addChallengeBtn');
    if (addChallengeBtn) {
      addChallengeBtn.addEventListener('click', () => {
        const title = document.getElementById('challengeTitle').value.trim();
        if (!title) return alert('Enter challenge title.');
        alert(`✅ '${title}' added (mock).`);
        document.getElementById('challengeTitle').value = '';
      });
    }

    // Add Badge (mock)
    const addBadgeBtn = document.getElementById('addBadgeBtn');
    if (addBadgeBtn) {
      addBadgeBtn.addEventListener('click', () => {
        const name = document.getElementById('badgeName').value.trim();
        if (!name) return alert('Enter badge name.');
        alert(`✅ '${name}' added (mock).`);
        document.getElementById('badgeName').value = '';
      });
    }

    /* USER MODAL - mock behavior */
    const searchBtn = document.getElementById('searchUserBtn');
    if (searchBtn) {
      searchBtn.addEventListener('click', () => {
        const q = document.getElementById('searchUser').value.trim();
        if (!q) return alert('Please enter a username or email to search.');
        // Mock: show a fake user result — replace this with fetch/AJAX to your backend
        document.getElementById('userResult').style.display = 'block';
        document.getElementById('editUserName').value = 'Maria Santos';
        document.getElementById('editUserEmail').value = 'maria.santos@example.com';
        alert(`✅ Found user: ${q} (mock)`);
      });
    }

    const saveUserBtn = document.getElementById('saveUserBtn');
    if (saveUserBtn) {
      saveUserBtn.addEventListener('click', () => {
        const name = document.getElementById('editUserName').value.trim();
        const email = document.getElementById('editUserEmail').value.trim();
        if (!name || !email) return alert('Name and email cannot be empty.');
        // TODO: call backend to save
        alert(`💾 User updated: ${name} — ${email} (mock).`);
      });
    }

    const warnUserBtn = document.getElementById('warnUserBtn');
    if (warnUserBtn) {
      warnUserBtn.addEventListener('click', () => {
        alert('⚠️ Warning issued to user (mock).');
      });
    }

    const restrictUserBtn = document.getElementById('restrictUserBtn');
    if (restrictUserBtn) {
      restrictUserBtn.addEventListener('click', () => {
        alert('🚫 User restricted (mock).');
      });
    }

    const banUserBtn = document.getElementById('banUserBtn');
    if (banUserBtn) {
      banUserBtn.addEventListener('click', () => {
        if (!confirm('Are you sure you want to ban this user?')) return;
        alert('❌ User banned (mock).');
      });
    }

    // Prevent console errors if remove-btns exist but no handler needed
    document.querySelectorAll('.remove-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        if (confirm('Remove this item?')) {
          // in real app, remove via backend then remove from DOM
          btn.parentElement && btn.parentElement.remove();
        }
      });
    });

  </script>
</body>
</html>
