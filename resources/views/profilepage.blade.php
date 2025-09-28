<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SUSTENA - Profile</title>
  @php
    use Illuminate\Support\Facades\Auth;
  @endphp
  <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
  <style>
    /* Modal Styling */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background: rgba(0,0,0,0.5);
      justify-content: center;
      align-items: center;
    }
    .get-score-btn {
    background-color: #3498db;
    color: white;
    border: none;
    padding: 10px 15px;
    margin-top: 15px;
    border-radius: 5px;
    cursor: pointer;
    transition: background 0.3s ease;
    font-size: 0.9rem;
}

.get-score-btn:hover {
    background-color: #2980b9;
}


    .modal-content {
      background: #fff;
      padding: 20px;
      width: 400px;
      max-width: 90%;
      border-radius: 10px;
      animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: scale(0.9); }
      to { opacity: 1; transform: scale(1); }
    }

    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
    }

    .modal-header h2 {
      font-size: 1.2rem;
      margin: 0;
    }

    .close-btn {
      background: none;
      border: none;
      font-size: 1.5rem;
      cursor: pointer;
      color: #333;
    }

    .modal form {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .modal form label {
      font-weight: bold;
      font-size: 0.9rem;
    }

    .modal form input,
    .modal form select {
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 0.9rem;
    }

    .modal form button {
      background-color: #2ecc71;
      color: white;
      border: none;
      padding: 10px;
      border-radius: 5px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .modal form button:hover {
      background-color: #27ae60;
    }
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
    <!-- Analytics -->
    <a href="{{ route('analytics') }}" class="floating-icon" title="Analytics">
        🔥
    </a>

    <!-- Learning Modules (or Sustainability Section) -->
    <a href="{{ route('learning-modules') }}" class="floating-icon" title="Learning Modules">
        🌱
    </a>

    <!-- Leaderboard -->
    <a href="{{ route('leaderboard') }}" class="floating-icon" title="Leaderboard">
        🏆
    </a>

    <!-- Badges / Achievements -->
    <a href="{{ route('badges') }}" class="floating-icon" title="Badges">
        🥇
    </a>

    <!-- Settings -->
    <a href="{{ route('settings') }}" class="floating-icon" title="Settings">
        ⚙️
    </a>
</div>


<!-- Main Content -->
<div class="main-content">
  <!-- Profile Header -->
  <div class="profile-header">
    <div class="profile-content">
      <div class="profile-avatar">👤</div>
      <div class="profile-info">
        @if(session()->has('username'))
          <h1 class="profile-name">{{ session('username') }} [Ecosaver]</h1>
        @else
          <h1 class="profile-name">Guest [Ecosaver]</h1>
        @endif
        <div class="profile-title">ECO CHAMPION</div>
        <div style="display: flex; gap: 10px; align-items: center;">
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
    <input type="email" id="email" name="email" value="{{ Auth::user()->email ?? '' }}" required>

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
            <div class="lifestyle-value">Vegan</div>
          </div>
        </div>
        <div class="lifestyle-item">
          <div class="lifestyle-icon">🚲</div>
          <div class="lifestyle-text">
            <div class="lifestyle-label">Transport</div>
            <div class="lifestyle-value">Bike + Public Transport</div>
          </div>
        </div>
        <div class="lifestyle-item">
          <div class="lifestyle-icon">🏠</div>
          <div class="lifestyle-text">
            <div class="lifestyle-label">Home</div>
            <div class="lifestyle-value">Apartment</div>
          </div>
        </div>
        <div class="lifestyle-item">
          <div class="lifestyle-icon">⚡</div>
          <div class="lifestyle-text">
            <div class="lifestyle-label">Energy</div>
            <div class="lifestyle-value">Renewable</div>
          </div>
        </div>
      </div>

      <!-- Carbon Stats -->
      <div class="carbon-card">
        <div class="card-header">
          <div class="card-icon">📊</div>
          <div class="card-title">Carbon Stats</div>
        </div>
        <div class="carbon-main">
          <div class="carbon-total">Total CO₂: 3.2 tons/year</div>
          <div class="carbon-subtitle">(↓ 10%)</div>
        </div>
        <div class="carbon-item">
          <div class="carbon-label">
            🏆 Best: Transport
            <span class="status-icon">✅</span>
          </div>
          <div class="carbon-value">(0.5 tons)</div>
        </div>
        <div class="carbon-item">
          <div class="carbon-label">
            ⚠️ Improve: Diet
            <span class="status-icon">🔄</span>
          </div>
          <div class="carbon-value">(1.8 tons)</div>
        </div>
        <div class="carbon-item">
          <div class="carbon-label">
            🎯 Goal: Reduce 15% by 2025
            <span class="status-icon">⏳</span>
          </div>
          <div class="carbon-value">[45% done]</div>
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

        <div class="impact-value">
            {{ session('footprint_score') ?? 'No score yet' }}
        </div>

        <div class="progress-bar">
            <div class="progress-fill" style="width: 
                {{ session('footprint_score') ? min(session('footprint_score'), 100) . '%' : '0%' }};
            "></div>
        </div>

        <div class="progress-text">
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
          <div class="level-stars">
            <span class="star">⭐</span>
            <span class="star">⭐</span>
            <span class="star">⭐</span>
            <span class="star">⭐</span>
            <span class="star empty">⭐</span>
          </div>
          <div class="level-text">Level 5 Eco Champion</div>
          <div class="challenges-joined">Joined: 3 Challenges</div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  // Modal Functionality
  const modal = document.getElementById('editProfileModal');
  const openModalBtn = document.getElementById('openModalBtn');
  const closeModalBtn = document.getElementById('closeModalBtn');

  openModalBtn.addEventListener('click', () => {
    modal.style.display = 'flex';
  });

  closeModalBtn.addEventListener('click', () => {
    modal.style.display = 'none';
  });

  window.addEventListener('click', (e) => {
    if (e.target === modal) {
      modal.style.display = 'none';
    }
  });

  function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    sidebar.classList.toggle('collapsed');
    mainContent.classList.toggle('expanded');
  }
</script>
</body>
</html>
