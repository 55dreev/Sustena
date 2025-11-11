<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sustena - Admin Settings</title>
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
  <link rel="stylesheet" href="{{ asset('css/adminsettings.css') }}">
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
  <a href="{{ route('admin.dashboard') }}"
     class="menu-item {{ request()->is('admin') ? 'active' : '' }}">
    <span class="menu-icon">📊</span><span>Dashboard</span>
  </a>

  <a href="{{ route('admin.moderation') }}"
     class="menu-item {{ request()->is('admin/moderation') ? 'active' : '' }}">
    <span class="menu-icon">💬</span><span>Feedback & Moderation</span>
  </a>

  <a href="{{ route('admin.settings') }}"
     class="menu-item {{ request()->is('admin/settings') ? 'active' : '' }}">
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
      <h2 class="section-title">Admin Settings</h2>

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
    </div>
  </div>

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
