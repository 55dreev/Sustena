<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sustena - Admin Dashboard</title>
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<style>
  .proof-modal__backdrop{
    position:fixed; inset:0; background:rgba(0,0,0,.7);
    display:none; align-items:center; justify-content:center; z-index:2000;
  }
  .proof-modal__dialog{
    background:#111; border-radius:12px; max-width:90vw; max-height:90vh;
    padding:12px; position:relative;
  }
  .proof-modal__img{ max-width:88vw; max-height:80vh; display:block; margin:auto; }
  .proof-modal__close{
    position:absolute; top:8px; right:10px; border:0; background:transparent;
    color:#fff; font-size:28px; cursor:pointer; line-height:1;
  }
</style>

<div id="proofModal" class="proof-modal__backdrop" role="dialog" aria-modal="true" aria-label="Proof viewer">
  <div class="proof-modal__dialog">
    <button class="proof-modal__close" type="button" aria-label="Close">×</button>
    <img id="proofModalImg" class="proof-modal__img" alt="Challenge proof">
  </div>
</div>

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
      <a href="{{ route('admin.moderation') }}" class="menu-item">
        <span class="menu-icon">💬</span>
        <span>Feedback & Moderation</span>
      </a>
        <a href="{{ route('admin.settings') }}" class="menu-item {{ request()->is('admin/settings') ? 'active' : '' }}">
      <span class="menu-icon">⚙️</span><span>Settings</span>
        </a>
       <a href="#" class="menu-item" onclick="openLogoutModal()">
    <span class="menu-icon">🚪</span>
    <span>Logout</span>
</a>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

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

      <div class="card">
        <div class="card-header">
          <div class="card-icon">🎯</div>
          <div>
            <div class="card-title">Challenge Management</div>
            <div class="card-subtitle">{{ $challenges->count() }} total challenges</div>
          </div>
        </div>
        <button class="action-btn" onclick="openModal('challengeModal')">Manage Challenges</button>
      </div>

      <div class="card mt-5 shadow-sm border-0">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0 fw-bold">Challenge Moderation</h5>
        </div>
        <div class="card-body">
          <p class="text-muted">Review and approve or reject user-submitted challenge proofs.</p>
          <div class="table-responsive">
            <table class="table table-striped align-middle">
              <thead class="table-light">
                <tr>
                  <th>User</th>
                  <th>Challenge</th>
                  <th>Proof</th>
                  <th>Submitted At</th>
                  <th class="text-center">Actions</th>
                </tr>
              </thead>
              <tbody>
                @forelse($pendingChallenges as $submission)
                  <tr>
                    <td>{{ $submission->user->username ?? 'Unknown' }}</td>
                    <td>{{ $submission->challenge->title ?? 'N/A' }}</td>
                    <td>
                      @if ($submission->proof_path)
                        <button
                          type="button"
                          class="btn btn-link p-0 view-proof"
                          data-url="{{ route('admin.proofs.show', $submission->id) }}"
                          aria-label="View proof image"
                        >
                          📷 View Proof
                        </button>
                      @else
                        No proof
                      @endif
                    </td>
                    <td>{{ $submission->submitted_at?->format('Y-m-d H:i') ?? '-' }}</td>
                    <td class="text-center">
                      <button class="btn btn-success btn-sm approveChallengeBtn" data-id="{{ $submission->id }}">Approve</button>
                      <button class="btn btn-danger btn-sm rejectChallengeBtn" data-id="{{ $submission->id }}">Reject</button>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="text-center text-muted">No pending submissions.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
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
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h5 class="modal-title fw-bold">Manage Users</h5>
        <button type="button" onclick="closeModal('userModal')" 
                style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #888;">×</button>
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
                  style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #888;">×</button>
        </div>

        <div class="modal-body">
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

  <!-- CHALLENGE MODAL -->
  <div class="modal fade" id="challengeModal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center;">
          <h5 class="modal-title fw-bold">Manage Challenges</h5>
          <button type="button" onclick="closeModal('challengeModal')" 
                  style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #888;">×</button>
        </div>

        <div class="modal-body">
          <label>Title:</label>
          <input type="text" id="challengeTitle" class="form-control mb-2" placeholder="Challenge title">

          <label>Subtitle:</label>
          <input type="text" id="challengeSubtitle" class="form-control mb-2" placeholder="Optional subtitle">

          <label>Description:</label>
          <textarea id="challengeDescription" class="form-control mb-2" placeholder="What is the challenge about?"></textarea>

          <label>Difficulty:</label>
          <select id="challengeDifficulty" class="form-control mb-2">
            <option value="1">Easy</option>
            <option value="2">Medium</option>
            <option value="3">Hard</option>
          </select>

          <label>XP Points:</label>
          <input type="number" id="challengePoints" class="form-control mb-2" placeholder="e.g. 100">

          <label>Icon (emoji or filename):</label>
          <input type="text" id="challengeIcon" class="form-control mb-2" placeholder="e.g. 🏆 or trophy.png">

          <div class="form-check mb-3">
            <input type="checkbox" id="challengeActive" class="form-check-input" checked>
            <label for="challengeActive" class="form-check-label">Active</label>
          </div>

          <button class="btn btn-success w-100 mb-3" id="addChallengeBtn">Add Challenge</button>

          <hr>
          <h6 class="fw-bold">Existing Challenges</h6>
          <select id="deleteChallengeSelect" class="form-control mb-2">
            @foreach ($challenges as $challenge)
              <option value="{{ $challenge->id }}">
                {{ $challenge->title }} — {{ $challenge->points_xp }} XP (Diff {{ $challenge->difficulty }})
              </option>
            @endforeach
          </select>
          <button class="btn btn-danger w-100" id="deleteChallengeBtn">🗑️ Delete Selected Challenge</button>
        </div>
      </div>
    </div>
  </div>

  <!-- JS -->
  <script>
  document.addEventListener('DOMContentLoaded', () => {
    const token = document.querySelector('meta[name="csrf-token"]').content;

    // ---------- MODALS ----------
    window.openModal = (id) => document.getElementById(id).style.display = 'flex';
    window.closeModal = (id) => document.getElementById(id).style.display = 'none';

    // ---------- USER MANAGEMENT ----------
    const searchBtn = document.getElementById('searchUserBtn');
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

    const saveBtn = document.getElementById('saveUserBtn');
    if (saveBtn) {
      saveBtn.addEventListener('click', () => {
        const username = document.getElementById('editUserName').value.trim();
        const email = document.getElementById('editUserEmail').value.trim();
        fetch('/admin/users/update', {
          method: 'POST',
          headers: {'Content-Type':'application/json','X-CSRF-TOKEN':token},
          body: JSON.stringify({ username, email })
        })
        .then(res => res.json())
        .then(d => alert(d.message ?? 'User updated.'))
        .catch(err => alert('Update failed: ' + err.message));
      });
    }

    // ---------- BADGE MANAGEMENT ----------
    const updateRulePreview = () => {
      const fact = document.getElementById('ruleFact')?.value;
      const op = document.getElementById('ruleOp')?.value;
      const value = document.getElementById('ruleValue')?.value;
      const preview = document.getElementById('rulePreview');
      preview.textContent = (fact && op && value) ? `Rule: ${fact} ${op} ${value}` : '';
    }
    ['ruleFact','ruleOp','ruleValue'].forEach(id => {
      const el = document.getElementById(id);
      if(el) el.addEventListener('input', updateRulePreview);
    });

    const addBadgeBtn = document.getElementById('addBadgeBtn');
    if(addBadgeBtn) {
      addBadgeBtn.addEventListener('click', () => {
        const name = document.getElementById('badgeName').value.trim();
        const category = document.getElementById('badgeCategory').value.trim();
        const points = Number(document.getElementById('badgePoints').value.trim()) || 0;
        const rule = { op: document.getElementById('ruleOp')?.value || '<', fact: document.getElementById('ruleFact')?.value || 'weekly_kg', type:'threshold', value:Number(document.getElementById('ruleValue')?.value) || 0 };

        if(!name) return alert('Enter badge name.');
        if(!rule.value) return alert('Please specify a rule value.');

        fetch('/admin/badges/add', {
          method:'POST',
          headers:{'Content-Type':'application/json','X-CSRF-TOKEN':token,'Accept':'application/json'},
          body:JSON.stringify({ name, category, points, rule })
        })
        .then(res=>res.json())
        .then(d=>{
          if(!d.success) throw new Error(d.message || 'Failed to add badge.');
          alert(d.message ?? '✅ Badge added.');
          const select = document.getElementById('deleteBadgeSelect');
          if(select && d.badge){
            const opt = document.createElement('option');
            opt.value = d.badge.id;
            opt.textContent = `${d.badge.name} — ${d.badge.points_reward} pts (${d.badge.category})`;
            select.appendChild(opt);
          }
        })
        .catch(err=>alert(err.message));
      });
    }

    const deleteBadgeBtn = document.getElementById('deleteBadgeBtn');
    if(deleteBadgeBtn) {
      deleteBadgeBtn.addEventListener('click', () => {
        const select = document.getElementById('deleteBadgeSelect');
        if(!select.value) return alert('Select a badge.');
        if(!confirm('Delete this badge?')) return;
        fetch('/admin/badges/delete', {
          method:'POST',
          headers:{'Content-Type':'application/json','X-CSRF-TOKEN':token,'Accept':'application/json'},
          body:JSON.stringify({ id: select.value })
        })
        .then(res=>res.json())
        .then(d=>{
          if(!d.success) throw new Error(d.message || 'Failed.');
          alert('Badge deleted.');
          select.querySelector(`option[value="${select.value}"]`)?.remove();
        })
        .catch(err=>alert(err.message));
      });
    }

    // ---------- CHALLENGE MANAGEMENT ----------
    const addChallengeBtn = document.getElementById('addChallengeBtn');
    if(addChallengeBtn){
      addChallengeBtn.addEventListener('click', () => {
        const title = document.getElementById('challengeTitle').value.trim();
        if(!title) return alert('Enter challenge title.');
        const challenge = {
          title,
          subtitle: document.getElementById('challengeSubtitle').value.trim(),
          description: document.getElementById('challengeDescription').value.trim(),
          difficulty: Number(document.getElementById('challengeDifficulty').value) || 1,
          points_xp: Number(document.getElementById('challengePoints').value) || 0,
          icon: document.getElementById('challengeIcon').value.trim() || '🏆',
          active: document.getElementById('challengeActive').checked
        };

        fetch('/admin/challenges',{
          method:'POST',
          headers:{'Content-Type':'application/json','X-CSRF-TOKEN':token},
          body:JSON.stringify(challenge)
        })
        .then(res=>res.json())
        .then(d=>{ if(d.success) alert('Challenge added!'); else throw new Error(d.message || 'Failed'); })
        .catch(err=>alert(err.message));
      });
    }
const deleteChallengeBtn = document.getElementById('deleteChallengeBtn');
if (deleteChallengeBtn) {
  deleteChallengeBtn.addEventListener('click', () => {
    const select = document.getElementById('deleteChallengeSelect');
    if (!select.value) return alert('Select a challenge.');
    if (!confirm('Delete this challenge?')) return;

    fetch('/admin/challenges/delete', {   // changed to POST route
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': token,
        'Accept': 'application/json'
      },
      body: JSON.stringify({ id: select.value })  // send ID in body
    })
    .then(res => res.json())
    .then(d => {
      if (!d.success) throw new Error(d.message || 'Failed');
      alert('🗑️ Challenge deleted');
      select.querySelector(`option[value="${select.value}"]`)?.remove();
    })
    .catch(err => alert(err.message));
  });
}

// ---------- CHALLENGE MODERATION ----------
document.querySelectorAll('.approveChallengeBtn').forEach(btn => {
  btn.addEventListener('click', () => {
    const id = btn.dataset.id;
    if(!confirm('Approve this submission?')) return;

    fetch(`/admin/challengemoderation/${id}/approve`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': token,
        'Accept': 'application/json'
      }
    })
    .then(res => res.json())
    .then(d => {
      if(!d.success) throw new Error(d.message || 'Failed to approve');
      alert('✅ Submission approved');
      btn.closest('tr')?.remove();
    })
    .catch(err => alert(err.message));
  });
});

document.querySelectorAll('.rejectChallengeBtn').forEach(btn => {
  btn.addEventListener('click', () => {
    const id = btn.dataset.id;
    if(!confirm('Reject this submission?')) return;

    fetch(`/admin/challengemoderation/${id}/reject`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': token,
        'Accept': 'application/json'
      }
    })
    .then(res => res.json())
    .then(d => {
      if(!d.success) throw new Error(d.message || 'Failed to reject');
      alert('❌ Submission rejected');
      btn.closest('tr')?.remove();
    })
    .catch(err => alert(err.message));
  });
});

  });

  document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('proofModal');
  const imgEl = document.getElementById('proofModalImg');
  const closeBtn = modal.querySelector('.proof-modal__close');

  function openProof(url){
    // Optional: show a temporary loading cursor
    modal.style.display = 'flex';
    imgEl.src = '';              // reset previous
    imgEl.alt = 'Loading...';
    // Set the image src (served by your admin.proofs.show route)
    imgEl.src = url + '?_=' + Date.now(); // cache-bust while moderating
  }

  function closeProof(){
    modal.style.display = 'none';
    imgEl.src = '';
  }

  // Open (event delegation so it works after table refreshes)
  document.body.addEventListener('click', (e) => {
    const btn = e.target.closest('.view-proof');
    if (!btn) return;
    e.preventDefault();
    openProof(btn.dataset.url);
  });

  // Close actions
  closeBtn.addEventListener('click', closeProof);
  modal.addEventListener('click', (e) => {
    if (e.target === modal) closeProof(); // click backdrop
  });
  window.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal.style.display === 'flex') closeProof();
  });
});
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
