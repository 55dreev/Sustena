<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recycling & Waste - SUSTENA</title>
  <link rel="stylesheet" href="{{ asset('css/learningmod.css') }}">
  <link rel="stylesheet" href="{{ asset('css/recycling.css') }}">
  <link rel="stylesheet" href="{{ asset('css/recyclingresponsive.css') }}">
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background: linear-gradient(135deg, #a8e6cf 0%, #88d8c0 100%);
      min-height: 100vh;
      margin: 0;
      padding: 20px;
    }

    .recycle-container {
      max-width: 1000px;
      margin: 0 auto;
      padding: 20px;
    }

    /* Animated Header */
    .recycle-header {
      background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
      border-radius: 25px;
      padding: 50px 40px;
      text-align: center;
      margin-bottom: 40px;
      position: relative;
      overflow: hidden;
      box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    }

    .recycle-header::before {
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

    .recycle-icons {
      position: absolute;
      font-size: 35px;
      opacity: 0.7;
      animation: spin 8s infinite linear;
    }

    .icon-1 { top: 20px; left: 10%; animation-delay: 0s; }
    .icon-2 { top: 30px; right: 10%; animation-delay: 2s; }
    .icon-3 { bottom: 30px; left: 15%; animation-delay: 4s; }
    .icon-4 { bottom: 40px; right: 15%; animation-delay: 6s; }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    .header-title {
      font-size: 52px;
      font-weight: bold;
      color: white;
      text-shadow: 3px 3px 6px rgba(0,0,0,0.3);
      z-index: 1;
      position: relative;
      margin-bottom: 15px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 15px;
    }

    .header-subtitle {
      background: rgba(255,255,255,0.95);
      color: #1565C0;
      padding: 18px 35px;
      border-radius: 30px;
      font-size: 18px;
      font-weight: 600;
      display: inline-block;
      box-shadow: 0 6px 20px rgba(0,0,0,0.15);
      z-index: 1;
      position: relative;
    }

    .xp-badge {
      position: absolute;
      top: 25px;
      right: 25px;
      background: linear-gradient(135deg, #ffd54f, #ffb300);
      color: #333;
      padding: 12px 20px;
      border-radius: 25px;
      font-size: 16px;
      font-weight: bold;
      box-shadow: 0 4px 15px rgba(255,213,79,0.4);
      z-index: 2;
    }

    /* Info Cards Grid */
    .info-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 25px;
      margin-bottom: 40px;
    }

    .info-card {
      background: rgba(255,255,255,0.95);
      border-radius: 20px;
      padding: 30px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.12);
      transition: all 0.3s ease;
      border: 3px solid transparent;
      position: relative;
      overflow: hidden;
    }

    .info-card::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: linear-gradient(45deg, transparent, rgba(33,150,243,0.1), transparent);
      transform: rotate(45deg);
      transition: all 0.5s ease;
      opacity: 0;
    }

    .info-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 15px 40px rgba(0,0,0,0.2);
      border-color: #2196F3;
    }

    .info-card:hover::before {
      opacity: 1;
      transform: rotate(45deg) translate(50%, 50%);
    }

    .card-icon {
      width: 70px;
      height: 70px;
      background: linear-gradient(135deg, #2196F3, #1976D2);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 35px;
      margin-bottom: 20px;
      box-shadow: 0 4px 15px rgba(33,150,243,0.3);
    }

    .info-card h2 {
      font-size: 24px;
      color: #1565C0;
      margin-bottom: 15px;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .info-card p {
      color: #555;
      line-height: 1.7;
      font-size: 15px;
    }

    .info-card ul {
      list-style: none;
      padding: 0;
      margin: 15px 0;
    }

    .info-card ul li {
      color: #555;
      padding: 10px 0;
      padding-left: 30px;
      position: relative;
      line-height: 1.6;
    }

    .info-card ul li::before {
      content: '♻️';
      position: absolute;
      left: 0;
      font-size: 18px;
    }

    /* Video Section */
    .video-section {
      background: rgba(255,255,255,0.95);
      border-radius: 20px;
      padding: 35px;
      margin-bottom: 30px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.12);
      text-align: center;
    }

    .video-section h2 {
      font-size: 28px;
      color: #1565C0;
      margin-bottom: 25px;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .video-container {
      position: relative;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }

    .video-container iframe {
      width: 100%;
      max-width: 800px;
      height: 450px;
      border: none;
      border-radius: 15px;
    }

    /* Game Challenge Card */
    .game-challenge {
      background: linear-gradient(135deg, #42A5F5 0%, #1E88E5 100%);
      border-radius: 25px;
      padding: 40px;
      text-align: center;
      margin-bottom: 30px;
      position: relative;
      overflow: hidden;
      box-shadow: 0 10px 35px rgba(0,0,0,0.2);
      border: 4px solid rgba(255,255,255,0.3);
    }

    .game-challenge h2 {
      font-size: 32px;
      color: white;
      margin-bottom: 20px;
      font-weight: bold;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }

    .play-button {
      background: linear-gradient(135deg, #66bb6a, #4caf50);
      color: white;
      border: none;
      padding: 18px 45px;
      border-radius: 30px;
      font-size: 20px;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.3s ease;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      box-shadow: 0 6px 20px rgba(102,187,106,0.4);
      display: inline-flex;
      align-items: center;
      gap: 10px;
    }

    .play-button:hover {
      transform: translateY(-4px) scale(1.05);
      box-shadow: 0 10px 30px rgba(102,187,106,0.5);
    }

    .play-button:active {
      transform: translateY(-2px) scale(1.02);
    }

    .back-section {
      text-align: center;
      margin-top: 30px;
    }

    .back-button {
      background: linear-gradient(135deg, #1976D2, #1565C0);
      color: white;
      padding: 15px 35px;
      border-radius: 25px;
      text-decoration: none;
      font-weight: 600;
      font-size: 16px;
      transition: all 0.3s ease;
      display: inline-block;
      box-shadow: 0 4px 15px rgba(25,118,210,0.3);
      text-transform: uppercase;
      letter-spacing: 1px;
      border: none;
      cursor: pointer;
    }

    .back-button:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 20px rgba(25,118,210,0.4);
    }

    .progress-dots {
      display: flex;
      justify-content: center;
      gap: 8px;
      margin-top: 20px;
    }

    .dot {
      width: 12px;
      height: 12px;
      border-radius: 50%;
      background: rgba(255,255,255,0.4);
      transition: all 0.3s ease;
    }

    .dot.active {
      background: #66bb6a;
      box-shadow: 0 0 10px rgba(102,187,106,0.6);
    }

    @media (max-width: 768px) {
      .header-title {
        font-size: 36px;
      }

      .header-subtitle {
        font-size: 16px;
        padding: 14px 25px;
      }

      .xp-badge {
        font-size: 14px;
        padding: 10px 16px;
        top: 15px;
        right: 15px;
      }

      .info-grid {
        grid-template-columns: 1fr;
        gap: 20px;
      }

      .video-container iframe {
        height: 250px;
      }

      .game-challenge h2 {
        font-size: 24px;
      }

      .play-button {
        font-size: 16px;
        padding: 14px 30px;
      }
    }
  </style>
</head>
<body>
  <div class="recycle-container">
    <div class="recycle-header">
      <div class="recycle-icons icon-1">♻️</div>
      <div class="recycle-icons icon-2">♻️</div>
      <div class="recycle-icons icon-3">♻️</div>
      <div class="recycle-icons icon-4">♻️</div>

      <div class="xp-badge">+60 XP</div>

      <h1 class="header-title">♻️ Recycling & Waste</h1>
      <p class="header-subtitle">Understand the importance of recycling and how to properly manage waste to protect our planet.</p>

      <div class="progress-dots">
        <div class="dot active"></div>
        <div class="dot active"></div>
        <div class="dot active"></div>
        <div class="dot"></div>
      </div>
    </div>

    <div class="info-grid">
      <div class="info-card">
        <div class="card-icon">🌍</div>
        <h2>Why Recycling Matters</h2>
        <p>
          Recycling is the process of collecting, processing, and repurposing materials that would otherwise be discarded as waste.
          It helps conserve natural resources, reduce landfill waste, and minimize pollution caused by raw material extraction and manufacturing.
        </p>
      </div>

      <div class="info-card">
        <div class="card-icon">✅</div>
        <h2>How to Recycle Properly</h2>
        <p>Follow these steps to ensure proper recycling:</p>
        <ul>
          <li>Separate recyclable items from general waste.</li>
          <li>Clean and dry containers like bottles and cans before recycling.</li>
          <li>Know which materials are accepted in your local recycling program.</li>
          <li>Reuse items like jars, boxes, and bags whenever possible.</li>
          <li>Compost organic waste such as food scraps and garden trimmings.</li>
        </ul>
      </div>

      <div class="info-card">
        <div class="card-icon">💡</div>
        <h2>Simple Ways to Reduce Waste</h2>
        <ul>
          <li>Carry reusable shopping bags and water bottles.</li>
          <li>Avoid single-use plastics whenever possible.</li>
          <li>Buy in bulk to reduce packaging waste.</li>
          <li>Support companies that use eco-friendly packaging.</li>
          <li>Donate old clothes and electronics instead of throwing them away.</li>
        </ul>
      </div>
    </div>

    <div class="video-section">
      <h2>🎥 Watch: Recycling Explained</h2>
      <div class="video-container">
        <iframe
          src="https://www.youtube.com/embed/_6xlNyWPpB8"
          title="Recycling and Waste Management"
          allowfullscreen>
        </iframe>
      </div>
    </div>

    <div class="game-challenge">
      <h2>🎮 Try the Recycling Game!</h2>
      <button id="startGameBtn" class="play-button">
        🗑️ Start Recycling Game
      </button>
      <div class="progress-dots" style="margin-top: 25px;">
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot active"></div>
      </div>
    </div>

    <div class="back-section">
      <a href="{{ url('/learning-modules') }}" class="back-button">← Back to Learning Modules</a>
    </div>
  </div>

  <div class="floating-icons">
    <a href="{{ url('/analytics') }}" class="floating-icon" title="Analytics">🔥</a>
    <a href="{{ url('/streaks') }}" class="floating-icon" title="Streaks">📅</a>
    <a href="{{ url('/learning-modules') }}" class="floating-icon" title="Learning Modules">🌱</a>
    <a href="{{ url('/leaderboard') }}" class="floating-icon" title="Leaderboard">🏆</a>
    <a href="{{ url('/badges') }}" class="floating-icon" title="Badges">🥇</a>
    <a href="{{ url('/settings') }}" class="floating-icon" title="Settings">⚙️</a>
  </div>



  <!-- Instruction Modal -->
  <div id="instructionModal" class="modal">
    <div class="modal-content">
      <button class="close-game-btn" onclick="closeGame()">×</button>
      <h2>How to Play</h2>
      <p>Drag each item into the correct bin before the timer runs out!</p>
      <ul style="text-align:left;">
        <li><strong>Recycling Bin:</strong> bottles, cans, newspapers, cardboard, glass</li>
        <li><strong>Trash Bin:</strong> food waste, tissues, plastic utensils, dirty containers</li>
        <li>+1 point for correct, -1 point for wrong</li>
        <li>You have 60 seconds!</li>
      </ul>
      <div style="display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
        <button class="game-control-btn restart-btn" onclick="startGame()">🚀 Start Game</button>
        <button class="game-control-btn exit-btn" onclick="closeGame()">❌ Cancel</button>
      </div>
    </div>
  </div>

  <!-- Game Modal -->
  <div id="gameModal" class="modal">
    <div class="modal-content game-area">
      <button class="close-game-btn" onclick="closeGame()">×</button>
      <div class="game-header">
        <div>⏱️ Time: <span id="time">60</span>s</div>
        <div>⭐ Score: <span id="score">0</span></div>
      </div>

      <div class="bins">
        <div class="bin-area">
          <img src="{{ asset('assets/recbin.png') }}" class="bin" data-type="recycle">
          <p>Recycling Bin</p>
        </div>
        <div class="bin-area">
          <img src="{{ asset('assets/bin.png') }}" class="bin" data-type="trash">
          <p>Trash Bin</p>
        </div>
      </div>

      <div id="itemsArea" class="items-area"></div>

      <div class="game-controls">
        <button class="game-control-btn exit-btn" onclick="closeGame()">❌ Exit Game</button>
      </div>
    </div>
  </div>

  <!-- Game Over Modal -->
  <div id="gameOverModal" class="modal">
    <div class="modal-content game-over-content">
      <h2>🎉 Game Over!</h2>
      <p class="final-score-text">Your Score: <span id="finalScore" class="score-number">0</span></p>
      <div class="game-over-buttons">
        <button class="game-control-btn restart-btn" onclick="restartGame()">🔄 Play Again</button>
        <button class="game-control-btn exit-btn" onclick="closeGame()">❌ Exit</button>
      </div>
    </div>
  </div>

  <style>
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.8);
      justify-content: center;
      align-items: center;
      backdrop-filter: blur(5px);
    }

    .modal-content {
      background: linear-gradient(135deg, #ffffff 0%, #f0f8ff 100%);
      padding: 30px;
      border-radius: 25px;
      text-align: center;
      position: relative;
      box-shadow: 0 15px 50px rgba(0,0,0,0.3);
      border: 4px solid #2196F3;
      max-width: 90vw;
      max-height: 90vh;
      overflow-y: auto;
    }

    .modal-content h2 {
      color: #1565C0;
      margin-bottom: 20px;
      font-size: 28px;
    }

    .close-game-btn {
      position: absolute;
      top: 10px;
      right: 15px;
      background: rgba(255,255,255,0.9);
      border: none;
      width: 35px;
      height: 35px;
      border-radius: 50%;
      font-size: 28px;
      font-weight: bold;
      color: #1565C0;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 2px 8px rgba(0,0,0,0.2);
      z-index: 10;
    }

    .close-game-btn:hover {
      background: #ff5252;
      color: white;
      transform: rotate(90deg) scale(1.1);
    }

    .game-area {
      min-width: 320px;
      min-height: 500px;
    }

    .game-header {
      display: flex;
      justify-content: space-around;
      margin-bottom: 20px;
      font-size: 18px;
      font-weight: bold;
      color: #1565C0;
    }

    .bins {
      display: flex;
      justify-content: space-around;
      margin: 20px 0;
      gap: 15px;
    }

    .bin-area {
      text-align: center;
    }

    .bin-area p {
      font-size: 14px;
      font-weight: bold;
      color: #1565C0;
      margin-top: 10px;
    }

    .bin {
      width: 120px;
      height: 120px;
      cursor: pointer;
      transition: transform 0.3s ease;
    }

    .bin:hover {
      transform: scale(1.1);
    }

    .items-area {
      display: flex;
      justify-content: center;
      gap: 15px;
      flex-wrap: wrap;
      min-height: 150px;
      padding: 20px;
      background: rgba(33,150,243,0.1);
      border-radius: 15px;
      margin-bottom: 15px;
    }

    .draggable-item {
      width: 80px;
      height: 80px;
      cursor: grab;
      cursor: -webkit-grab;
      transition: transform 0.2s ease;
      touch-action: none;
    }

    .draggable-item:active {
      cursor: grabbing;
      cursor: -webkit-grabbing;
    }

    .draggable-item:hover {
      transform: scale(1.1);
    }

    .game-controls {
      margin-top: 15px;
      display: flex;
      justify-content: center;
      gap: 10px;
    }

    .game-control-btn {
      background: linear-gradient(135deg, #2196F3, #1976D2);
      color: white;
      border: none;
      padding: 12px 25px;
      border-radius: 20px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(33,150,243,0.3);
    }

    .game-control-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(33,150,243,0.4);
    }

    .exit-btn {
      background: linear-gradient(135deg, #ff5252, #d32f2f) !important;
      box-shadow: 0 4px 15px rgba(255,82,82,0.3) !important;
    }

    .exit-btn:hover {
      box-shadow: 0 6px 20px rgba(255,82,82,0.4) !important;
    }

    .restart-btn {
      background: linear-gradient(135deg, #66bb6a, #4caf50) !important;
      box-shadow: 0 4px 15px rgba(102,187,106,0.3) !important;
    }

    .restart-btn:hover {
      box-shadow: 0 6px 20px rgba(102,187,106,0.4) !important;
    }

    .game-over-content {
      padding: 40px;
    }

    .game-over-content h2 {
      font-size: 36px;
      margin-bottom: 25px;
    }

    .final-score-text {
      font-size: 24px;
      color: #555;
      margin-bottom: 30px;
    }

    .score-number {
      font-size: 36px;
      font-weight: bold;
      color: #2196F3;
    }

    .game-over-buttons {
      display: flex;
      justify-content: center;
      gap: 15px;
      flex-wrap: wrap;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
      .modal-content {
        padding: 20px;
      }

      .game-area {
        min-width: 280px;
      }

      .game-header {
        font-size: 16px;
      }

      .bins {
        flex-direction: column;
        align-items: center;
        gap: 20px;
      }

      .bin {
        width: 100px;
        height: 100px;
      }

      .items-area {
        min-height: 120px;
        padding: 15px;
        gap: 10px;
      }

      .draggable-item {
        width: 60px;
        height: 60px;
      }

      .game-control-btn {
        padding: 10px 20px;
        font-size: 14px;
      }

      .game-over-content {
        padding: 25px;
      }

      .game-over-content h2 {
        font-size: 28px;
      }

      .final-score-text {
        font-size: 20px;
      }

      .score-number {
        font-size: 28px;
      }
    }

    @media (max-width: 480px) {
      .bin {
        width: 80px;
        height: 80px;
      }

      .draggable-item {
        width: 50px;
        height: 50px;
      }
    }

    /* Floating Icons */
    .floating-icons {
      position: fixed;
      top: 20px;
      right: 20px;
      display: flex;
      gap: 10px;
      z-index: 1000;
    }

    .floating-icon {
      width: 40px;
      height: 40px;
      background: rgba(255,255,255,0.9);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: inset 2px 2px 0px rgba(255,255,255,0.8),
                  inset -2px -2px 0px rgba(0,0,0,0.3),
                  0px 4px 12px rgba(0,0,0,0.15);
      cursor: pointer;
      transition: all 0.3s ease;
      filter: contrast(1.2) brightness(1.1);
      text-decoration: none;
      color: inherit;
    }

    .floating-icon:hover {
      transform: scale(1.1);
      box-shadow: 0 6px 20px rgba(0,0,0,0.2);
    }

    @media (max-width: 768px) {
      .floating-icons {
        top: 10px;
        right: 10px;
        gap: 8px;
      }

      .floating-icon {
        width: 35px;
        height: 35px;
      }
    }
  </style>

  <script>
    const items = [
      // Recyclables
      { name: 'Plastic Bottle', img: '{{ asset('assets/pbottle.png') }}', type: 'recycle' },
      { name: 'Can', img: '{{ asset('assets/can.png') }}', type: 'recycle' },
      { name: 'Box', img: '{{ asset('assets/box.png') }}', type: 'recycle' },
      { name: 'Glass', img: '{{ asset('assets/glass.png') }}', type: 'recycle' },
      { name: 'Newspaper', img: '{{ asset('assets/newspaper.png') }}', type: 'recycle' },

      // Trash
      { name: 'Old Food', img: '{{ asset('assets/oldfood.png') }}', type: 'trash' },
      { name: 'Tissue', img: '{{ asset('assets/tissue.png') }}', type: 'trash' },
      { name: 'Plastic Cup', img: '{{ asset('assets/plasticup.png') }}', type: 'trash' },
      { name: 'Plastic Utensils', img: '{{ asset('assets/plasticutens.png') }}', type: 'trash' }
    ];

    let score = 0;
    let timeLeft = 60;
    let timer;

    const instructionModal = document.getElementById('instructionModal');
    const gameModal = document.getElementById('gameModal');
    const gameOverModal = document.getElementById('gameOverModal');
    const itemsArea = document.getElementById('itemsArea');

    // Open Instructions
    document.getElementById('startGameBtn').addEventListener('click', () => {
      instructionModal.style.display = 'flex';
    });

    function startGame() {
      instructionModal.style.display = 'none';
      gameModal.style.display = 'flex';
      score = 0;
      timeLeft = 60;
      document.getElementById('score').textContent = score;
      document.getElementById('time').textContent = timeLeft;
      spawnItems();
      timer = setInterval(updateTimer, 1000);
    }

    function updateTimer() {
      if (timeLeft > 0) {
        timeLeft--;
        document.getElementById('time').textContent = timeLeft;
      } else {
        endGame();
      }
    }

    function enableTouchDrag(img) {
  img.addEventListener("touchstart", (e) => {
    touchItem = img;
    img.classList.add("dragging");
  });

  img.addEventListener("touchmove", (e) => {
    if (!touchItem) return;

    const touch = e.touches[0];
    img.style.position = "fixed";
    img.style.left = (touch.clientX - img.width / 2) + "px";
    img.style.top = (touch.clientY - img.height / 2) + "px";

    e.preventDefault();
  });

  img.addEventListener("touchend", (e) => {
    if (!touchItem) return;

    const touch = e.changedTouches[0];
    const dropX = touch.clientX;
    const dropY = touch.clientY;

    img.classList.remove("dragging");

    // Check bin collision
    document.querySelectorAll(".bin").forEach(bin => {
      const rect = bin.getBoundingClientRect();

      if (
        dropX > rect.left &&
        dropX < rect.right &&
        dropY > rect.top &&
        dropY < rect.bottom
      ) {
        // Drop successful
        if (img.dataset.type === bin.dataset.type) {
          score++;
        } else {
          score--;
        }
        document.getElementById("score").textContent = score;
        spawnItems();
      }
    });

    touchItem = null;
    img.style.position = "";
    img.style.left = "";
    img.style.top = "";
  });
}

    function spawnItems() {
  itemsArea.innerHTML = '';

  // Pick 1 random item
  const randomItem = items[Math.floor(Math.random() * items.length)];

  const img = document.createElement('img');
  img.src = randomItem.img;
  img.classList.add('draggable-item');
  img.draggable = true;
  img.dataset.type = randomItem.type;

  // Desktop drag
  img.addEventListener('dragstart', function(ev) {
    ev.dataTransfer.setData("type", ev.target.dataset.type);
  });

  // Mobile touch drag
  enableTouchDrag(img);

  itemsArea.appendChild(img);
}



    document.querySelectorAll('.bin').forEach(bin => {
      bin.addEventListener('dragover', (ev) => ev.preventDefault());

      bin.addEventListener('drop', (ev) => {
        ev.preventDefault();
        const droppedType = ev.dataTransfer.getData("type");

        if (droppedType === bin.dataset.type) {
          score++;
        } else {
          score--;
        }

        document.getElementById('score').textContent = score;
        spawnItems();
      });
    });

    function endGame() {
      clearInterval(timer);
      gameModal.style.display = 'none';
      document.getElementById('finalScore').textContent = score;
      gameOverModal.style.display = 'flex';
    }

    function restartGame() {
      gameOverModal.style.display = 'none';
      startGame();
    }

    function closeGame() {
      clearInterval(timer);
      instructionModal.style.display = 'none';
      gameModal.style.display = 'none';
      gameOverModal.style.display = 'none';
      itemsArea.innerHTML = '';
      score = 0;
      timeLeft = 60;
    }

    // Floating icons animation
    document.querySelectorAll('.floating-icon').forEach((icon, index) => {
      icon.style.animationDelay = `${index * 0.2}s`;
      icon.addEventListener('click', function() {
        this.style.transform = 'scale(1.2) rotate(360deg)';
        setTimeout(() => {
          this.style.transform = 'scale(1.1)';
        }, 300);
      });
    });
  </script>
</body>
</html>
