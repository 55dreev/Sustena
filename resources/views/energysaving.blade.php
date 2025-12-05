<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Energy Saving - SUSTENA</title>
  <link rel="stylesheet" href="{{ asset('css/learningmod.css') }}">
  <link rel="stylesheet" href="{{ asset('css/learnmodules.css') }}">
  <link rel="stylesheet" href="{{ asset('css/learnmodresponsive.css') }}">
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background: linear-gradient(135deg, #a8e6cf 0%, #88d8c0 100%);
      min-height: 100vh;
      margin: 0;
      padding: 20px;
    }

    .energy-container {
      max-width: 1000px;
      margin: 0 auto;
      padding: 20px;
    }

    /* Animated Header */
    .energy-header {
      background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);
      border-radius: 25px;
      padding: 50px 40px;
      text-align: center;
      margin-bottom: 40px;
      position: relative;
      overflow: hidden;
      box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    }

    .energy-header::before {
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

    .bulb-icons {
      position: absolute;
      font-size: 40px;
      opacity: 0.7;
      animation: blink 2s infinite ease-in-out;
    }

    .bulb-1 { top: 20px; left: 10%; animation-delay: 0s; }
    .bulb-2 { top: 30px; right: 10%; animation-delay: 0.5s; }
    .bulb-3 { bottom: 30px; left: 15%; animation-delay: 1s; }
    .bulb-4 { bottom: 40px; right: 15%; animation-delay: 1.5s; }

    @keyframes blink {
      0%, 100% { opacity: 0.7; transform: scale(1); }
      50% { opacity: 1; transform: scale(1.1); }
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
      color: #E65100;
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
      background: linear-gradient(45deg, transparent, rgba(255,152,0,0.1), transparent);
      transform: rotate(45deg);
      transition: all 0.5s ease;
      opacity: 0;
    }

    .info-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 15px 40px rgba(0,0,0,0.2);
      border-color: #FF9800;
    }

    .info-card:hover::before {
      opacity: 1;
      transform: rotate(45deg) translate(50%, 50%);
    }

    .card-icon {
      width: 70px;
      height: 70px;
      background: linear-gradient(135deg, #FF9800, #F57C00);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 35px;
      margin-bottom: 20px;
      box-shadow: 0 4px 15px rgba(255,152,0,0.3);
    }

    .info-card h2 {
      font-size: 24px;
      color: #E65100;
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
      content: '⚡';
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
      color: #E65100;
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
      background: linear-gradient(135deg, #FFB74D 0%, #FF9800 100%);
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
      background: linear-gradient(135deg, #F57C00, #E65100);
      color: white;
      padding: 15px 35px;
      border-radius: 25px;
      text-decoration: none;
      font-weight: 600;
      font-size: 16px;
      transition: all 0.3s ease;
      display: inline-block;
      box-shadow: 0 4px 15px rgba(245,124,0,0.3);
      text-transform: uppercase;
      letter-spacing: 1px;
      border: none;
      cursor: pointer;
    }

    .back-button:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 20px rgba(245,124,0,0.4);
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
  <div class="energy-container">
    <div class="energy-header">
      <div class="bulb-icons bulb-1">💡</div>
      <div class="bulb-icons bulb-2">⚡</div>
      <div class="bulb-icons bulb-3">💡</div>
      <div class="bulb-icons bulb-4">⚡</div>

      <div class="xp-badge">+55 XP</div>

      <h1 class="header-title">⚡ Energy Saving</h1>
      <p class="header-subtitle">Learn how to save energy, lower costs, and protect the environment through smart energy practices.</p>

      <div class="progress-dots">
        <div class="dot active"></div>
        <div class="dot active"></div>
        <div class="dot active"></div>
        <div class="dot"></div>
      </div>
    </div>

    <div class="info-grid">
      <div class="info-card">
        <div class="card-icon">🔋</div>
        <h2>Why Energy Saving Matters</h2>
        <p>
          Energy saving is the practice of using less energy by eliminating unnecessary usage and improving efficiency. By conserving energy, we reduce greenhouse gas emissions, slow climate change, and save money on electricity bills. Simple habits, like turning off unused devices and using renewable energy, can make a big difference.
        </p>
      </div>

      <div class="info-card">
        <div class="card-icon">☀️</div>
        <h2>Sources of Energy</h2>
        <p>Our energy comes from different sources, and some are more sustainable than others:</p>
        <ul>
          <li><strong>Renewable Energy</strong> – Solar, wind, hydro, and geothermal are clean and sustainable.</li>
          <li><strong>Non-Renewable Energy</strong> – Fossil fuels like coal, oil, and natural gas are limited and harmful to the environment.</li>
        </ul>
        <p>Shifting to renewable sources is key to reducing pollution and ensuring a sustainable future.</p>
      </div>

      <div class="info-card">
        <div class="card-icon">💡</div>
        <h2>Practical Energy Saving Tips</h2>
        <ul>
          <li>Switch to LED bulbs instead of incandescent lights.</li>
          <li>Unplug chargers and electronics when not in use.</li>
          <li>Use natural light during the day to reduce electricity use.</li>
          <li>Invest in energy-efficient appliances with a high energy rating.</li>
          <li>Limit air conditioning and set thermostats to optimal temperatures.</li>
          <li>Consider installing solar panels for sustainable energy.</li>
        </ul>
      </div>

      
    </div>

    <div class="video-section">
      <h2>🎥 Watch: Easy Energy Saving Hacks</h2>
      <div class="video-container">
        <iframe
          src="https://www.youtube.com/embed/D11iFUw_ImU"
          title="Energy Saving Tips"
          allowfullscreen>
        </iframe>
      </div>
    </div>

    <div class="game-challenge">
      <h2>🎮 Try the Light Bulb Rush Game!</h2>
      <button onclick="openLightGame()" class="play-button">
        💡 Play Light Bulb Rush
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
<div id="lightInstructionModal" class="modal">
  <div class="modal-content light-game-modal">
    <button class="close" onclick="closeAllLightModals()">×</button>
    <h2 style="color: #E65100; font-size: 32px; margin-bottom: 20px;">💡 Light Bulb Rush Game</h2>
    <p style="font-size: 18px; color: #555; margin-bottom: 20px;">Click the light bulbs as fast as you can to save energy!</p>
    <ul style="text-align:left; margin: 0 auto; max-width: 400px; line-height: 1.8; color: #555;">
      <li><strong>Goal:</strong> Click as many light bulbs as possible before time runs out</li>
      <li><strong>Controls:</strong> Click/tap on the light bulbs (💡 or 🔆)</li>
      <li><strong>Score:</strong> Each bulb clicked = +1 point</li>
      <li><strong>Speed:</strong> Bulbs appear faster as you progress</li>
      <li><strong>Duration:</strong> Game lasts 30 seconds</li>
    </ul>
    <div style="display: flex; justify-content: center; gap: 15px; flex-wrap: wrap; margin-top: 30px;">
      <button class="light-game-btn restart-btn" onclick="startLightGameFromInstructions()">🎮 Start Game</button>
      <button class="light-game-btn exit-btn" onclick="closeAllLightModals()">❌ Cancel</button>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="lightModal" class="modal">
  <div class="modal-content light-game-modal">
    <span class="close" onclick="closeLightGame()">&times;</span>
    <div class="light-game-container">
      <canvas id="lightCanvas" width="400" height="500"></canvas>
      <div class="light-game-controls">
        <button class="light-game-btn exit-btn" onclick="closeLightGame()">❌ Exit</button>
      </div>
      <div id="lightGameOverScreen" class="light-game-over-screen">
        <h2>🎉 Game Over!</h2>
        <p class="light-game-over-stats">
          <span style="font-size: 36px; font-weight: bold; color: #FF9800;" id="finalLightScore">0</span><br>
          <span style="font-size: 20px; color: #666;">Bulbs Clicked!</span>
        </p>
        <div class="light-game-buttons">
          <button class="light-game-btn restart-btn" onclick="restartLightGameDirectly()">🔄 Play Again</button>
          <button class="light-game-btn exit-btn" onclick="closeLightGame()">❌ Exit</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Audio -->
<audio id="bgMusic" src="{{ asset('sounds/lightbulbbg.mp3') }}" loop></audio>
<audio id="clickSound" src="{{ asset('sounds/Switch.mp3') }}"></audio>

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

  .light-game-modal {
    background: linear-gradient(135deg, #ffffff 0%, #fff9e6 100%);
    padding: 20px;
    border-radius: 25px;
    text-align: center;
    position: relative;
    box-shadow: 0 15px 50px rgba(0,0,0,0.3);
    border: 4px solid #FF9800;
    max-width: 90vw;
    max-height: 90vh;
    overflow: auto;
  }

  .light-game-container {
    position: relative;
  }

  .light-game-container canvas {
    display: block;
    margin: 0 auto;
    background: linear-gradient(180deg, #fff9e6 0%, #ffe082 100%);
    border-radius: 15px;
    box-shadow: inset 0 4px 10px rgba(0,0,0,0.1);
    max-width: 100%;
    height: auto;
    touch-action: none;
  }

  .light-game-controls {
    margin-top: 15px;
    display: flex;
    justify-content: center;
    gap: 10px;
  }

  .light-game-btn {
    background: linear-gradient(135deg, #FF9800, #F57C00);
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 20px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(255,152,0,0.3);
  }

  .light-game-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255,152,0,0.4);
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

  .light-game-over-screen {
    display: none;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(255,255,255,0.98);
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    border: 3px solid #FF9800;
    z-index: 100;
    min-width: 280px;
  }

  .light-game-over-screen h2 {
    color: #E65100;
    margin-bottom: 20px;
    font-size: 32px;
  }

  .light-game-over-stats {
    color: #555;
    line-height: 1.8;
    margin-bottom: 25px;
  }

  .light-game-buttons {
    display: flex;
    justify-content: center;
    gap: 15px;
    flex-wrap: wrap;
  }

  .close {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 28px;
    font-weight: bold;
    color: #E65100;
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
    .light-game-modal {
      padding: 15px;
    }

    #lightCanvas {
      max-width: 100%;
      height: auto;
    }

    .light-game-btn {
      padding: 10px 20px;
      font-size: 14px;
    }

    .light-game-over-screen {
      padding: 20px;
      min-width: 250px;
    }

    .light-game-over-screen h2 {
      font-size: 24px;
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
const lightModal = document.getElementById("lightModal");
const lightCanvas = document.getElementById("lightCanvas");
const lctx = lightCanvas.getContext("2d");

let bulbs = [];
let score = 0;
let gameStart = 0;
const gameTime = 30;
let lightInterval = 1000;
let gameRunning = false;

const bgMusic = document.getElementById('bgMusic');
bgMusic.volume = 0.2; 
const clickSound = document.getElementById('clickSound');

// Load the bulb image
const onBulb = new Image();
onBulb.src = "{{ asset('assets/lightbulb.png') }}";

function openLightGame() {
  // Show instruction modal first
  document.getElementById("lightInstructionModal").style.display = "flex";
}

function startLightGameFromInstructions() {
  // Close instruction modal and open game
  document.getElementById("lightInstructionModal").style.display = "none";
  if(gameRunning) return;
  lightModal.style.display = "flex";

  // Set and play background music at 50%
  bgMusic.currentTime = 0;
  bgMusic.volume = 0.2;
  bgMusic.play();

  // Start game after image loads
  if(onBulb.complete) startLightGame();
  else onBulb.onload = () => startLightGame();
}

function closeAllLightModals() {
  document.getElementById("lightInstructionModal").style.display = "none";
  lightModal.style.display = "none";
  gameRunning = false;
  bulbs = [];
  document.getElementById('lightGameOverScreen').style.display = 'none';
  bgMusic.pause();
}

function restartLightGameDirectly() {
  // Hide game over screen and restart game directly without showing instructions
  document.getElementById('lightGameOverScreen').style.display = 'none';
  bulbs = [];
  score = 0;
  gameStart = Date.now();
  lightInterval = 1000;
  gameRunning = true;

  // Restart music
  bgMusic.currentTime = 0;
  bgMusic.play();

  spawnBulb();
  requestAnimationFrame(updateLightGame);
}


function spawnBulb() {
  if(!gameRunning) return;

  let x = Math.random() * 350;
  let y = Math.random() * 450;
  let bulb = { x, y, width: 80, height: 80, alpha: 1, shrink: false };

  // Auto-shrink if not clicked in 1.5s
  setTimeout(() => { bulb.shrink = true; }, 1500);

  bulbs.push(bulb);

  lightInterval *= 0.97;
  setTimeout(spawnBulb, lightInterval);
}

// Click handler
lightCanvas.addEventListener("click", (e) => {
  if(!gameRunning) return;
  const rect = lightCanvas.getBoundingClientRect();
  const mx = e.clientX - rect.left;
  const my = e.clientY - rect.top;

  bulbs.forEach(b => {
    if(!b.shrink && mx > b.x && mx < b.x + b.width && my > b.y && my < b.y + b.height) {
      b.shrink = true;
      score++;
      clickSound.currentTime = 0;
      clickSound.play();
    }
  });
});

function updateLightGame() {
  if(!gameRunning) return;
  const elapsed = (Date.now() - gameStart) / 1000;
  if(elapsed >= gameTime) return lightGameOver();

  lctx.clearRect(0,0,lightCanvas.width,lightCanvas.height);

  bulbs.forEach((b, i) => {
    if(b.shrink) {
      b.width *= 0.85;
      b.height *= 0.85;
      b.alpha *= 0.85;
      b.x += 25*0.15;
      b.y += 25*0.15;
      if(b.width < 5) bulbs.splice(i,1);
    }
    lctx.globalAlpha = b.alpha;
    lctx.drawImage(onBulb, b.x, b.y, b.width, b.height);
    lctx.globalAlpha = 1;
  });

  lctx.fillStyle = "#2e7d32";
  lctx.font = "18px Poppins";
  lctx.fillText("Score: " + score, 10, 20);
  lctx.fillText("Time: " + Math.floor(gameTime - elapsed) + "s", 10, 40);

  requestAnimationFrame(updateLightGame);
}

function lightGameOver() {
  gameRunning = false;
  bgMusic.pause();

  // Show game over screen
  document.getElementById('finalLightScore').textContent = score;
  document.getElementById('lightGameOverScreen').style.display = 'block';
}

function closeLightGame() {
  lightModal.style.display = "none";
  gameRunning = false;
  bulbs = [];
  document.getElementById('lightGameOverScreen').style.display = 'none';
  bgMusic.pause();
}

function startLightGame() {
  bulbs = [];
  score = 0;
  gameStart = Date.now();
  lightInterval = 1000;
  gameRunning = true;
  document.getElementById('lightGameOverScreen').style.display = 'none';
  spawnBulb();
  requestAnimationFrame(updateLightGame);
}

// Touch support for mobile
lightCanvas.addEventListener("touchstart", (e) => {
  if(!gameRunning) return;
  e.preventDefault();
  const rect = lightCanvas.getBoundingClientRect();
  const scaleX = lightCanvas.width / rect.width;
  const scaleY = lightCanvas.height / rect.height;
  const touch = e.touches[0];
  const mx = (touch.clientX - rect.left) * scaleX;
  const my = (touch.clientY - rect.top) * scaleY;

  bulbs.forEach(b => {
    if(!b.shrink && mx > b.x && mx < b.x + b.width && my > b.y && my < b.y + b.height) {
      b.shrink = true;
      score++;
      clickSound.currentTime = 0;
      clickSound.play();
    }
  });
});

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
