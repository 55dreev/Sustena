<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Water Conservation - SUSTENA</title>
  <link rel="stylesheet" href="{{ asset('css/learningmod.css') }}">
  <link rel="stylesheet" href="{{ asset('css/waterconservation.css') }}">
  <link rel="stylesheet" href="{{ asset('css/waterconservationresponsive.css') }}">
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background: linear-gradient(135deg, #a8e6cf 0%, #7fcdcd 100%);
      min-height: 100vh;
      margin: 0;
      padding: 20px;
    }

    .water-container {
      max-width: 1000px;
      margin: 0 auto;
      padding: 20px;
    }

    /* Animated Header */
    .water-header {
      background: linear-gradient(135deg, #00BCD4 0%, #0097A7 100%);
      border-radius: 25px;
      padding: 50px 40px;
      text-align: center;
      margin-bottom: 40px;
      position: relative;
      overflow: hidden;
      box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    }

    .water-header::before {
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

    .water-drops {
      position: absolute;
      font-size: 30px;
      opacity: 0.6;
      animation: dropFall 3s infinite ease-in;
    }

    .drop-1 { top: -30px; left: 10%; animation-delay: 0s; }
    .drop-2 { top: -30px; left: 30%; animation-delay: 0.5s; }
    .drop-3 { top: -30px; right: 30%; animation-delay: 1s; }
    .drop-4 { top: -30px; right: 10%; animation-delay: 1.5s; }

    @keyframes dropFall {
      0% { transform: translateY(0px); opacity: 0.6; }
      50% { opacity: 0.8; }
      100% { transform: translateY(400px); opacity: 0; }
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
      color: #00695C;
      padding: 18px 35px;
      border-radius: 30px;
      font-size: 18px;
      font-weight: 600;
      display: inline-block;
      box-shadow: 0 6px 20px rgba(0,0,0,0.15);
      z-index: 1;
      position: relative;
    }

    /* XP Badge */
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
      background: linear-gradient(45deg, transparent, rgba(0,188,212,0.1), transparent);
      transform: rotate(45deg);
      transition: all 0.5s ease;
      opacity: 0;
    }

    .info-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 15px 40px rgba(0,0,0,0.2);
      border-color: #00BCD4;
    }

    .info-card:hover::before {
      opacity: 1;
      transform: rotate(45deg) translate(50%, 50%);
    }

    .card-icon {
      width: 70px;
      height: 70px;
      background: linear-gradient(135deg, #00BCD4, #0097A7);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 35px;
      margin-bottom: 20px;
      box-shadow: 0 4px 15px rgba(0,188,212,0.3);
    }

    .info-card h2 {
      font-size: 24px;
      color: #00695C;
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
      content: '💧';
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
      color: #00695C;
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
      background: linear-gradient(135deg, #26C6DA 0%, #00ACC1 100%);
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

    /* Back Button */
    .back-section {
      text-align: center;
      margin-top: 30px;
    }

    .back-button {
      background: linear-gradient(135deg, #00897B, #00695C);
      color: white;
      padding: 15px 35px;
      border-radius: 25px;
      text-decoration: none;
      font-weight: 600;
      font-size: 16px;
      transition: all 0.3s ease;
      display: inline-block;
      box-shadow: 0 4px 15px rgba(0,137,123,0.3);
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .back-button:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 20px rgba(0,137,123,0.4);
    }

    /* Progress Dots */
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

    /* Responsive Design */
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
  <div class="water-container">
    <!-- Animated Header -->
    <div class="water-header">
      <div class="water-drops drop-1">💧</div>
      <div class="water-drops drop-2">💧</div>
      <div class="water-drops drop-3">💧</div>
      <div class="water-drops drop-4">💧</div>

      <div class="xp-badge">+50 XP</div>

      <h1 class="header-title">💧 Water Conservation</h1>
      <p class="header-subtitle">Learn about the importance of saving water and how you can make a difference in protecting this precious resource.</p>

      <div class="progress-dots">
        <div class="dot active"></div>
        <div class="dot active"></div>
        <div class="dot active"></div>
        <div class="dot"></div>
      </div>
    </div>

    <!-- Info Cards Grid -->
    <div class="info-grid">
      <div class="info-card">
        <div class="card-icon">🌊</div>
        <h2>What is Water Conservation?</h2>
        <p>
          Water conservation involves the sustainable management of freshwater resources to ensure a balance between human consumption and natural ecosystems.
          With climate change and population growth, the need to conserve water has become more critical than ever.
        </p>
      </div>

      <div class="info-card">
        <div class="card-icon">🌍</div>
        <h2>Why Water Conservation Matters</h2>
        <p>Clean water is essential for life. Conserving water helps:</p>
        <ul>
          <li>Ensure safe drinking water for future generations.</li>
          <li>Protect natural ecosystems and wildlife.</li>
          <li>Reduce energy costs linked to water treatment and delivery.</li>
          <li>Mitigate the effects of droughts and water shortages.</li>
        </ul>
      </div>
    </div>

    <!-- Video Section -->
    <div class="video-section">
      <h2>🎥 Watch: Simple Water Saving Tips</h2>
      <div class="video-container">
        <iframe
          src="https://www.youtube.com/embed/gJmY3dzg3Gk"
          title="Water Conservation Video"
          allowfullscreen>
        </iframe>
      </div>
    </div>

    <!-- Game Challenge Card -->
    <div class="game-challenge">
      <h2>🎮 Try the Water Catcher Game!</h2>
      <button onclick="openGame()" class="play-button">
        🎮 Play Water Catcher
      </button>
      <div class="progress-dots" style="margin-top: 25px;">
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot active"></div>
      </div>
    </div>

    <!-- Back Button -->
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
  <div id="waterInstructionModal" class="modal">
    <div class="modal-content game-modal-content">
      <button class="close" onclick="closeWaterGame()">×</button>
      <h2 style="color: #00695C; font-size: 32px; margin-bottom: 20px;">💧 Water Catcher Game</h2>
      <p style="font-size: 18px; color: #555; margin-bottom: 20px;">Catch falling water drops to save water!</p>
      <ul style="text-align:left; margin: 0 auto; max-width: 400px; line-height: 1.8; color: #555;">
        <li><strong>Goal:</strong> Catch as many water drops as possible with your bucket</li>
        <li><strong>Controls:</strong> Use ← → arrow keys or touch/drag the bucket on mobile</li>
        <li><strong>Score:</strong> Each drop caught = +1 Liter saved</li>
        <li><strong>Penalty:</strong> Each drop missed = +1 Liter wasted</li>
        <li><strong>Duration:</strong> Game lasts 30 seconds</li>
      </ul>
      <div style="display: flex; justify-content: center; gap: 15px; flex-wrap: wrap; margin-top: 30px;">
        <button class="game-btn restart-btn" onclick="startWaterGame()">🎮 Start Game</button>
        <button class="game-btn exit-btn" onclick="closeWaterGame()">❌ Cancel</button>
      </div>
    </div>
  </div>

  <!-- Modal for game -->
  <div id="gameModal" class="modal">
    <div class="modal-content game-modal-content">
      <span class="close" onclick="closeGame()">&times;</span>
      <div class="game-container">
        <canvas id="gameCanvas" width="400" height="500"></canvas>
        <div class="game-controls">
          <button id="exitGameBtn" class="game-btn exit-btn" onclick="closeGame()">❌ Exit</button>
        </div>
        <div id="gameOverScreen" class="game-over-screen">
          <h2>Game Over!</h2>
          <p class="game-over-stats">
            <span>💧 Saved: <strong id="finalSaved">0</strong> L</span><br>
            <span>💦 Wasted: <strong id="finalWasted">0</strong> L</span><br>
            <span>⏱ Time: <strong id="finalTime">0</strong>s</span>
          </p>
          <button class="game-btn restart-btn" onclick="resetGame()">🔄 Play Again</button>
          <button class="game-btn exit-btn" onclick="closeGame()">❌ Exit</button>
        </div>
      </div>
    </div>
  </div>

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
      background: rgba(0,0,0,0.8);
      justify-content: center;
      align-items: center;
      backdrop-filter: blur(5px);
    }

    .game-modal-content {
      background: linear-gradient(135deg, #ffffff 0%, #f0f8ff 100%);
      padding: 20px;
      border-radius: 25px;
      text-align: center;
      position: relative;
      box-shadow: 0 15px 50px rgba(0,0,0,0.3);
      border: 4px solid #00BCD4;
      max-width: 500px;
      width: 90%;
      max-height: 90vh;
      overflow-y: auto;
    }

    .game-container {
      position: relative;
      width: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .game-container canvas {
      display: block;
      margin: 0 auto;
      background: linear-gradient(180deg, #e0f7fa 0%, #b2ebf2 100%);
      border-radius: 15px;
      box-shadow: inset 0 4px 10px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 400px;
      height: auto;
      touch-action: none;
    }

    .game-controls {
      margin-top: 15px;
      display: flex;
      justify-content: center;
      gap: 10px;
    }

    .game-btn {
      background: linear-gradient(135deg, #00BCD4, #0097A7);
      color: white;
      border: none;
      padding: 12px 25px;
      border-radius: 20px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(0,188,212,0.3);
    }

    .game-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(0,188,212,0.4);
    }

    .exit-btn {
      background: linear-gradient(135deg, #ff5252, #d32f2f);
      box-shadow: 0 4px 15px rgba(255,82,82,0.3);
    }

    .exit-btn:hover {
      box-shadow: 0 6px 20px rgba(255,82,82,0.4);
    }

    .restart-btn {
      background: linear-gradient(135deg, #66bb6a, #4caf50);
      box-shadow: 0 4px 15px rgba(102,187,106,0.3);
    }

    .restart-btn:hover {
      box-shadow: 0 6px 20px rgba(102,187,106,0.4);
    }

    .game-over-screen {
      display: none;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: rgba(255,255,255,0.98);
      padding: 30px;
      border-radius: 20px;
      box-shadow: 0 10px 40px rgba(0,0,0,0.3);
      border: 3px solid #00BCD4;
      z-index: 100;
      min-width: 280px;
    }

    .game-over-screen h2 {
      color: #00695C;
      margin-bottom: 20px;
      font-size: 32px;
    }

    .game-over-stats {
      color: #555;
      font-size: 18px;
      line-height: 2;
      margin-bottom: 25px;
    }

    .game-over-stats strong {
      color: #00BCD4;
    }

    .close {
      position: absolute;
      top: 10px;
      right: 15px;
      font-size: 28px;
      font-weight: bold;
      color: #00695C;
      cursor: pointer;
      transition: all 0.3s ease;
      z-index: 10;
      width: 35px;
      height: 35px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: rgba(255,255,255,0.9);
      border-radius: 50%;
      box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }

    .close:hover {
      transform: rotate(90deg) scale(1.1);
      background: #ff5252;
      color: white;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
      .game-modal-content {
        padding: 15px;
        max-width: 95%;
      }

      .game-container canvas {
        max-width: 350px;
      }

      .game-btn {
        padding: 10px 20px;
        font-size: 14px;
      }

      .game-over-screen {
        padding: 20px;
        min-width: 250px;
        max-width: 90%;
      }

      .game-over-screen h2 {
        font-size: 24px;
      }

      .game-over-stats {
        font-size: 16px;
      }
    }

    @media (max-width: 480px) {
      .game-modal-content {
        max-width: 98%;
        padding: 10px;
      }

      .game-container canvas {
        max-width: 300px;
      }

      .game-over-screen {
        padding: 15px;
        min-width: 200px;
      }

      .game-over-screen h2 {
        font-size: 20px;
      }

      .game-over-stats {
        font-size: 14px;
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
const modal = document.getElementById("gameModal");
const canvas = document.getElementById("gameCanvas");
const ctx = canvas.getContext("2d");

// Images
const bucketImg = new Image();
bucketImg.src = "https://img.icons8.com/emoji/96/bucket-emoji.png";
const dropImg = new Image();
dropImg.src = "https://img.icons8.com/fluency/96/water.png";
const dirtyImg = new Image();
dirtyImg.src = "https://img.icons8.com/emoji/96/pile-of-poo.png";

// Audio
const waterSound = new Audio("{{ asset('sounds/water.mp3') }}");
const poopSound = new Audio("{{ asset('sounds/poop.m4a') }}");
const bgMusic = new Audio("{{ asset('sounds/WaterGame.mp3') }}");
bgMusic.loop = true;

let bucket = { x: 170, y: 420, width: 50, height: 50, speed: 5 };
let drops = [];
let litersSaved = 0, litersWasted = 0, startTime, elapsedTime = 0;
let poopChance = 0.3;
let gameRunning = false;
let gameOverState = false;

// Track key states for smooth movement
let keys = { left: false, right: false };

function openGame() {
  // Show instruction modal first
  document.getElementById("waterInstructionModal").style.display = "flex";
}

function startWaterGame() {
  // Close instruction modal and start game
  document.getElementById("waterInstructionModal").style.display = "none";
  if (gameRunning) return;
  modal.style.display = "flex";
  resetGame();
}

function closeWaterGame() {
  // Close all modals
  document.getElementById("waterInstructionModal").style.display = "none";
  modal.style.display = "none";
  bgMusic.pause();
  bgMusic.currentTime = 0;
  gameRunning = false;
}

function closeGame() {
  modal.style.display = "none";
  bgMusic.pause();
  bgMusic.currentTime = 0;
  gameRunning = false;
}

function resetGame() {
  litersSaved = 0;
  litersWasted = 0;
  drops = [];
  bucket.x = 170;
  startTime = Date.now();
  elapsedTime = 0;
  poopChance = 0.3;
  gameRunning = true;
  gameOverState = false;

  // Hide game over screen
  document.getElementById('gameOverScreen').style.display = 'none';

  bgMusic.currentTime = 0;
  bgMusic.play().catch(err => console.log("Autoplay blocked", err));

  requestAnimationFrame(update);
}

function spawnDrop() {
  const type = Math.random() < poopChance ? "poop" : "water";
  drops.push({ x: Math.random() * (canvas.width - 40), y: 0, width: 40, height: 40, type });
}

function update() {
  if (!gameRunning) return;

  elapsedTime = Math.floor((Date.now() - startTime) / 1000);

  // Increase spawn rate over time
  if (Math.random() < 0.05 + elapsedTime * 0.001) {
    spawnDrop();
  }

  // Smooth bucket movement
  if (keys.left && bucket.x > 0) bucket.x -= bucket.speed;
  if (keys.right && bucket.x + bucket.width < canvas.width) bucket.x += bucket.speed;

  // Draw game
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  ctx.drawImage(bucketImg, bucket.x, bucket.y, bucket.width, bucket.height);

  for (let i = drops.length - 1; i >= 0; i--) {
    let d = drops[i];
    d.y += 3 + elapsedTime * 0.05;
    ctx.drawImage(d.type === "water" ? dropImg : dirtyImg, d.x, d.y, d.width, d.height);

    // Collision with bucket
    if (
        d.y + d.height > bucket.y + 5 &&
        d.y < bucket.y + bucket.height - 5 &&
        d.x + d.width > bucket.x + 10 &&
        d.x < bucket.x + bucket.width - 10
      ) {
      if (d.type === "water") {
        litersSaved++;
        waterSound.cloneNode().play();
      } else {
        poopSound.cloneNode().play();
        gameOver();
      }
      drops.splice(i, 1);
    }
    // Drop falls past bottom
    else if (d.y > canvas.height) {
      if (d.type === "water") litersWasted++;
      drops.splice(i, 1);
    }
  }

  // Display stats
  ctx.fillStyle = "#2e7d32";
  ctx.font = "16px Poppins";
  ctx.fillText("💧 Saved: " + litersSaved + " L", 10, 20);
  ctx.fillText("💦 Wasted: " + litersWasted + " L", 10, 40);
  ctx.fillText("⏱ Time: " + elapsedTime + "s", 10, 60);

  if (!gameOverState) requestAnimationFrame(update);
}

function gameOver() {
  bgMusic.pause();
  bgMusic.currentTime = 0;
  gameRunning = false;
  gameOverState = true;

  // Update game over screen
  document.getElementById('finalSaved').textContent = litersSaved;
  document.getElementById('finalWasted').textContent = litersWasted;
  document.getElementById('finalTime').textContent = elapsedTime;
  document.getElementById('gameOverScreen').style.display = 'block';
}

// Smooth key handling
document.addEventListener("keydown", (e) => {
  if (!gameRunning) return;
  if (e.key === "ArrowLeft") keys.left = true;
  if (e.key === "ArrowRight") keys.right = true;
});
document.addEventListener("keyup", (e) => {
  if (e.key === "ArrowLeft") keys.left = false;
  if (e.key === "ArrowRight") keys.right = false;
});

// Touch controls for mobile
let touchStartX = 0;
canvas.addEventListener("touchstart", (e) => {
  if (!gameRunning) return;
  touchStartX = e.touches[0].clientX;
});

canvas.addEventListener("touchmove", (e) => {
  if (!gameRunning) return;
  e.preventDefault();
  const touchX = e.touches[0].clientX;
  const rect = canvas.getBoundingClientRect();
  const scaleX = canvas.width / rect.width;
  bucket.x = (touchX - rect.left) * scaleX - bucket.width / 2;
  bucket.x = Math.max(0, Math.min(bucket.x, canvas.width - bucket.width));
});

// Hide game over screen when resetting
function hideGameOver() {
  document.getElementById('gameOverScreen').style.display = 'none';
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
