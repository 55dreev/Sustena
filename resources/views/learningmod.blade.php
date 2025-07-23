<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>SUSTENA - Environmental Learning App</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Arial', sans-serif;
      background: linear-gradient(135deg, #a8e6cf 0%, #88d8c0 100%);
      min-height: 100vh;
      display: flex;
    }

    /* Sidebar */
    .sidebar {
      width: 200px;
      background: linear-gradient(180deg, #4a7c59 0%, #2d5a3d 100%);
      padding: 20px;
      box-shadow: 2px 0 10px rgba(0,0,0,0.1);
      position: fixed;
      height: 100vh;
      overflow-y: auto;
      z-index: 100;
    }

    .logo {
      display: flex;
      align-items: center;
      margin-bottom: 30px;
      padding-bottom: 20px;
      border-bottom: 1px solid rgba(255,255,255,0.2);
      padding-left: 10px;
    }

    .logo-icon {
      width: 40px;
      height: 40px;
      background: #66bb6a;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      font-size: 20px;
    }

    .logo-text {
      color: white;
      font-weight: bold;
      font-size: 16px;
    }

    .nav-item {
      display: flex;
      align-items: center;
      padding: 12px 16px;
      margin-bottom: 8px;
      color: rgba(255,255,255,0.8);
      text-decoration: none;
      border-radius: 8px;
      transition: all 0.3s ease;
      cursor: pointer;
    }

    .nav-item:hover {
      background: rgba(255,255,255,0.1);
      color: white;
      transform: translateX(5px);
    }

    .nav-item.active {
      background: #66bb6a;
      color: white;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    .nav-icon {
      width: 24px;
      height: 24px;
      margin-right: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
    }

    /* Main Content */
    .main-content {
      margin-left: 200px;
      flex: 1;
      padding: 20px;
    }

    /* Top Navigation */
    .top-nav {
      background: rgba(255,255,255,0.3);
      backdrop-filter: blur(10px);
      border-radius: 15px;
      padding: 15px 25px;
      display: flex;
      justify-content: flex-end;
      align-items: center;
      gap: 15px;
      margin-bottom: 30px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    .nav-icon-top {
      width: 45px;
      height: 45px;
      background: white;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .nav-icon-top:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    }

    /* Header Section */
    .header-section {
      background: linear-gradient(135deg, #87ceeb 0%, #b0e0e6 100%);
      border-radius: 20px;
      padding: 40px;
      text-align: center;
      margin-bottom: 40px;
      position: relative;
      overflow: hidden;
      box-shadow: 0 8px 30px rgba(0,0,0,0.1);
    }

    .header-section::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="3" fill="white" opacity="0.3"/><circle cx="80" cy="40" r="2" fill="white" opacity="0.2"/><circle cx="40" cy="70" r="2.5" fill="white" opacity="0.25"/></svg>');
      animation: float 20s infinite linear;
    }

    @keyframes float {
      0% { transform: translateX(-100%) translateY(-100%) rotate(0deg); }
      100% { transform: translateX(0%) translateY(0%) rotate(360deg); }
    }

    .cloud {
      position: absolute;
      color: white;
      font-size: 24px;
      opacity: 0.8;
    }

    .cloud-1 { top: 20px; left: 15%; animation: drift 15s infinite ease-in-out; }
    .cloud-2 { top: 30px; right: 15%; animation: drift 20s infinite ease-in-out reverse; }
    .cloud-3 { bottom: 40px; left: 25%; animation: drift 18s infinite ease-in-out; }
    .cloud-4 { bottom: 50px; right: 25%; animation: drift 22s infinite ease-in-out reverse; }

    @keyframes drift {
      0%, 100% { transform: translateX(0px); }
      50% { transform: translateX(20px); }
    }

    .header-title {
      font-size: 48px;
      font-weight: bold;
      color: white;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
      z-index: 1;
      position: relative;
      margin-bottom: 20px;
    }

    .header-subtitle {
      background: rgba(255,255,255,0.9);
      color: #2d5016;
      padding: 15px 30px;
      border-radius: 25px;
      font-size: 18px;
      font-weight: 500;
      display: inline-block;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      z-index: 1;
      position: relative;
    }

    .learning-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 30px;
      max-width: 800px;
      margin: 0 auto;
    }

    .learning-card {
      background: rgba(255,255,255,0.9);
      border-radius: 20px;
      padding: 30px;
      text-align: center;
      cursor: pointer;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
      box-shadow: 0 6px 25px rgba(0,0,0,0.1);
    }

    .learning-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 12px 40px rgba(0,0,0,0.2);
    }

    .card-icon {
      width: 80px;
      height: 80px;
      margin: 0 auto 20px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 35px;
    }

    .climate-change .card-icon {
      background: linear-gradient(135deg, #4CAF50, #45a049);
    }

    .recycling .card-icon {
      background: linear-gradient(135deg, #2196F3, #1976D2);
    }

    .energy-saving .card-icon {
      background: linear-gradient(135deg, #FF9800, #F57C00);
    }

    .water-conservation .card-icon {
      background: linear-gradient(135deg, #00BCD4, #0097A7);
    }

    .card-title {
      font-size: 18px;
      font-weight: bold;
      color: #2d5016;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    @media (max-width: 768px) {
      .sidebar {
        width: 80px;
      }

      .main-content {
        margin-left: 80px;
      }

      .logo-text, .nav-item span {
        display: none;
      }

      .learning-grid {
        grid-template-columns: 1fr;
        gap: 20px;
      }

      .header-title {
        font-size: 36px;
      }
    }
  </style>
</head>
<body>
<div class="sidebar">
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
  <a href="{{ url('/learning-modules') }}" class="nav-item active">
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
  <a href="{{ url('/profile') }}" class="nav-item">
    <div class="nav-icon">👤</div>
    <span>Profile</span>
  </a>
</div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="top-nav">
      <div class="nav-icon-top">🔥</div>
      <div class="nav-icon-top">🌱</div>
      <div class="nav-icon-top">🏆</div>
      <div class="nav-icon-top">💰</div>
      <div class="nav-icon-top">⚙️</div>
    </div>

    <div class="header-section">
      <div class="cloud cloud-1">☁️</div>
      <div class="cloud cloud-2">☁️</div>
      <div class="cloud cloud-3">☁️</div>
      <div class="cloud cloud-4">☁️</div>

      <h1 class="header-title">Learn</h1>
      <div class="header-subtitle">How to reduce your carbon footprint?</div>
    </div>

    <div class="learning-grid">
      <div class="learning-card climate-change">
        <div class="card-icon">🌍</div>
        <div class="card-title">Climate Change</div>
      </div>

      <div class="learning-card recycling">
        <div class="card-icon">♻️</div>
        <div class="card-title">Recycling & Waste</div>
      </div>

      <div class="learning-card energy-saving">
        <div class="card-icon">💡</div>
        <div class="card-title">Energy Saving</div>
      </div>

      <div class="learning-card water-conservation">
        <div class="card-icon">💧</div>
        <div class="card-title">Water Conservation</div>
      </div>
    </div>
  </div>

  <script>
    // Sidebar active click highlight
    document.querySelectorAll('.nav-item').forEach(item => {
      item.addEventListener('click', function () {
        document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
        this.classList.add('active');
      });
    });

    // Learning card click alert
    document.querySelectorAll('.learning-card').forEach(card => {
      card.addEventListener('click', function () {
        const title = this.querySelector('.card-title').textContent;
        alert(`Opening ${title} learning module...`);
      });
    });
  </script>
</body>
</html>
