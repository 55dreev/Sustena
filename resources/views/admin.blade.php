  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Sustena - Admin Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
              <div class="card-icon">🏅</div>
              <div>
                <div class="card-title">Badge Management</div>
                <div class="card-subtitle">{{ $badges->count() }} available badges</div>
              </div>
            </div>
            <button onclick="openModal('badgeModal')" class="action-btn">Manage Badges</button>
          </div>
        </div>

        <div class="recent-activity-section">
          <h2 class="section-title">Recent User Activity</h2>
          <table class="activity-table">
            <thead>
              <tr><th>User</th><th>Email</th><th>Action</th><th>Timestamp</th></tr>
            </thead>
            <tbody>
              @foreach ($users as $user)
                <tr>
                  <td>{{ $user->username }}</td>
                  <td>{{ $user->email }}</td>
                  <td><span class="status-badge status-login">Login</span></td>
                  <td>{{ $user->created_at ? $user->created_at->diffForHumans() : '—' }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- USER MODAL -->
    <div class="modal fade" id="userModal">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header"><h5 class="modal-title fw-bold">Manage Users</h5></div>
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
                <label>Name:</label>
                <input type="text" id="editUserName" class="form-control" value="" readonly>
                <label>Email:</label>
                <input type="email" id="editUserEmail" class="form-control" value="" readonly>
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

    <!-- BADGE MODAL -->
    <div class="modal fade" id="badgeModal">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center;">
          <h5 class="modal-title fw-bold">Manage Badges</h5>
          <button type="button" onclick="closeModal('badgeModal')" 
                  style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #888;">
            ×
          </button>
        </div>

            <label>Badge Name:</label>
            <input type="text" id="badgeName" class="form-control mb-2" placeholder="Enter badge name">

            <label>Category:</label>
            <input type="text" id="badgeCategory" class="form-control mb-2" placeholder="e.g. Environment">

            <hr>
            <h6 class="fw-bold mt-3">Rule Settings</h6>

            <div class="mb-2">
              <label>Condition Type:</label>
              <select id="ruleType" class="form-control">
                <option value="threshold">Threshold (Default)</option>
              </select>
            </div>

            <div class="mb-2">
              <label>Fact:</label>
              <select id="ruleFact" class="form-control">
                <option value="weekly_kg">Weekly CO₂ (kg)</option>
                <option value="missions_completed">Missions Completed</option>
                <option value="login_days">Login Days</option>
              </select>
            </div>

            <div class="mb-2">
              <label>Operator:</label>
              <select id="ruleOp" class="form-control">
                <option value="<">Less Than</option>
                <option value=">">Greater Than</option>
                <option value="=">Equal To</option>
              </select>
            </div>

            <div class="mb-2">
              <label>Value:</label>
              <input type="number" id="ruleValue" class="form-control" placeholder="e.g. 100">
            </div>

            <div class="mb-2">
              <label>Points Reward:</label>
              <input type="number" id="badgePoints" class="form-control" placeholder="e.g. 50">
            </div>

            <p id="rulePreview" style="font-size:0.9rem; color:#4CAF50; margin-top:5px;"></p>

            <button class="btn btn-success w-100 mt-3" id="addBadgeBtn">Add Badge</button>

            <hr>
            <h6 class="fw-bold">Existing Badges</h6>
            <select id="deleteBadgeSelect" class="form-control mb-2">
              @foreach ($badges as $badge)
                <option value="{{ $badge->id }}">
                  {{ $badge->name }} — {{ $badge->points_reward }} pts ({{ $badge->category }})
                </option>
              @endforeach
            </select>
            <button class="btn btn-danger w-100" id="deleteBadgeBtn">🗑️ Delete Selected Badge</button>


          </div>
        </div>
      </div>
    </div>

    <!-- ✅ SCRIPT SECTION -->
   <script>
document.addEventListener('DOMContentLoaded', () => {
  const token = document.querySelector('meta[name="csrf-token"]').content;

  /* ---------------- MODAL HANDLERS ---------------- */
  window.openModal = (id) => document.getElementById(id).style.display = 'flex';
  window.closeModal = (id) => document.getElementById(id).style.display = 'none';

  /* ---------------- USER MANAGEMENT ---------------- */
  const searchBtn = document.getElementById('searchUserBtn');
  const saveBtn = document.getElementById('saveUserBtn');

  if (searchBtn) {
    searchBtn.addEventListener('click', () => {
      const q = document.getElementById('searchUser').value.trim();
      if (!q) return alert('Enter username or email.');

      fetch(`/admin/users/search?q=${encodeURIComponent(q)}`)
        .then(res => res.json())
        .then(user => {
          if (!user) return alert('User not found.');
          document.getElementById('userResult').style.display = 'block';
          document.getElementById('editUserName').value = user.username;
          document.getElementById('editUserEmail').value = user.email;
        })
        .catch(err => alert('Search failed: ' + err.message));
    });
  }

  if (saveBtn) {
    saveBtn.addEventListener('click', () => {
      const username = document.getElementById('editUserName').value.trim();
      const email = document.getElementById('editUserEmail').value.trim();

      fetch('/admin/users/update', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({ username, email })
      })
      .then(res => res.json())
      .then(d => alert(d.message ?? 'User updated.'))
      .catch(err => alert('Update failed: ' + err.message));
    });
  }

  /* ---------------- BADGE MANAGEMENT ---------------- */

  // Auto-update rule preview text
  function updateRulePreview() {
    const fact = document.getElementById('ruleFact')?.value;
    const op = document.getElementById('ruleOp')?.value;
    const value = document.getElementById('ruleValue')?.value;
    const preview = document.getElementById('rulePreview');
    preview.textContent = (fact && op && value)
      ? `Rule: ${fact} ${op} ${value}`
      : '';
  }

  ['ruleFact', 'ruleOp', 'ruleValue'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.addEventListener('input', updateRulePreview);
  });

  // ADD BADGE
  const addBadgeBtn = document.getElementById('addBadgeBtn');
  if (addBadgeBtn) {
    addBadgeBtn.addEventListener('click', () => {
      const name = document.getElementById('badgeName').value.trim();
      const category = document.getElementById('badgeCategory').value.trim();
      const points = Number(document.getElementById('badgePoints').value.trim()) || 0;

      const rule = {
        op: document.getElementById('ruleOp')?.value || '<',
        fact: document.getElementById('ruleFact')?.value || 'weekly_kg',
        type: 'threshold',
        value: Number(document.getElementById('ruleValue')?.value) || 0
      };

      if (!name) return alert('Enter badge name.');
      if (!rule.value) return alert('Please specify a rule value.');

      fetch('/admin/badges/add', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': token,
          'Accept': 'application/json'
        },
        body: JSON.stringify({ name, category, rule, points })
      })
      .then(res => res.json())
      .then(d => {
        if (!d.success) throw new Error(d.message || 'Failed to add badge.');
        alert(d.message ?? '✅ Badge added.');

        // ✅ Add new badge to dropdown
        const select = document.getElementById('deleteBadgeSelect');
        if (select && d.badge) {
          const opt = document.createElement('option');
          opt.value = d.badge.id;
          opt.textContent = `${d.badge.name} — ${d.badge.points_reward} pts (${d.badge.category})`;
          select.appendChild(opt);
          select.value = opt.value; // select the newly added one
        }

        // Clear form
        document.getElementById('badgeName').value = '';
        document.getElementById('badgeCategory').value = '';
        document.getElementById('badgePoints').value = '';
        document.getElementById('ruleValue').value = '';
        updateRulePreview();
      })
      .catch(err => alert('Add failed: ' + err.message));
    });
  }

  // DELETE BADGE
  const deleteBtn = document.getElementById('deleteBadgeBtn');
  if (deleteBtn) {
    deleteBtn.addEventListener('click', () => {
      const select = document.getElementById('deleteBadgeSelect');
      const id = select?.value;
      if (!id) return alert('Please select a badge to delete.');
      if (!confirm('Are you sure you want to delete this badge?')) return;

      fetch(`/admin/badges/${id}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': token,
          'Accept': 'application/json'
        }
      })
      .then(res => res.json())
      .then(d => {
        alert(d.message);
        if (d.success) {
          select.querySelector(`option[value="${id}"]`)?.remove();
          if (select.options.length > 0) select.selectedIndex = 0;
        }
      })
      .catch(err => alert('Delete failed: ' + err.message));
    });
  }
});
</script>


  </body>
  </html>
