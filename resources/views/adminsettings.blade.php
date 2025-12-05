<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Settings - SUSTENA Admin</title>
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
  <link rel="stylesheet" href="{{ asset('css/adminsettings.css') }}">
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
        <a href="{{ route('admin.moderation') }}" class="nav-link">
          <span class="nav-icon">💬</span> Feedback & Moderation
        </a>
        <a href="{{ route('admin.settings') }}" class="nav-link active">
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
        <h1>⚙️ Admin Settings</h1>
        <p>Configure system settings and preferences</p>
      </header>


      <!-- ACCOUNT SETTINGS -->
      <div class="settings-section">
        <h3>Account Management</h3>
        <div class="setting-row">
          <span class="setting-label">Change Admin Username</span>
          <div class="setting-control">
            <input type="text" placeholder="Enter new username">
          </div>
        </div>
        <div class="setting-row">
          <span class="setting-label">Change Password</span>
          <div class="setting-control">
            <button class="bulk-btn">Update Password</button>
          </div>
        </div>
      </div>

      <!-- NOTIFICATION SETTINGS -->
      <div class="settings-section">
        <h3>Notifications</h3>
        <div class="setting-row">
          <span class="setting-label">Enable Email Alerts</span>
          <div class="setting-control">
            <input type="checkbox" checked>
          </div>
        </div>
        <div class="setting-row">
          <span class="setting-label">Push Notifications</span>
          <div class="setting-control">
            <input type="checkbox">
          </div>
        </div>
        <div class="setting-row">
          <span class="setting-label">Weekly Summary Reports</span>
          <div class="setting-control">
            <input type="checkbox" checked>
          </div>
        </div>
      </div>

      <!-- THEME SETTINGS -->
      <div class="settings-section">
        <h3>Theme Preferences</h3>
        <div class="setting-row">
          <span class="setting-label">Select Theme</span>
          <div class="setting-control theme-options">
            <div class="theme-preview active" style="background:#0f172a;" data-theme="dark"></div>
            <div class="theme-preview" style="background:#f8fafc;" data-theme="light"></div>
            <div class="theme-preview" style="background:#1e293b;" data-theme="slate"></div>
          </div>
        </div>
      </div>

      <!-- SYSTEM SETTINGS -->
      <div class="settings-section">
        <h3>System Preferences</h3>
        <div class="setting-row">
          <span class="setting-label">Auto Backup</span>
          <div class="setting-control">
            <input type="checkbox" checked>
          </div>
        </div>
        <div class="setting-row">
          <span class="setting-label">Data Retention (Days)</span>
          <div class="setting-control">
            <select>
              <option>7</option>
              <option>30</option>
              <option selected>90</option>
              <option>180</option>
              <option>365</option>
            </select>
          </div>
        </div>
      </div>

      <button class="save-btn">💾 Save Settings</button>
    </main>
  </div>

  <!-- Logout Modal -->
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
    // Theme Selection
    document.querySelectorAll('.theme-preview').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('.theme-preview').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        alert('Theme switched to: ' + btn.dataset.theme.charAt(0).toUpperCase() + btn.dataset.theme.slice(1));
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
