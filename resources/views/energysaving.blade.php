<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Energy Saving - SUSTENA</title>
  <link rel="stylesheet" href="{{ asset('css/energysaving.css') }}">
  <link rel="stylesheet" href="{{ asset('css/learnmodresponsive.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">

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
          <span style="font-size: 20px; color: #666;">Lights Switched Off</span>
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
</style>

<script>
const lightModal = document.getElementById("lightModal");
const lightCanvas = document.getElementById("lightCanvas");
const lctx = lightCanvas.getContext("2d");

let bulbs = [];
let score = 0;
let misses = 0;
const maxMisses = 3;
let gameRunning = false;
let lightInterval = 1000;

// Audio
const bgMusic = document.getElementById('bgMusic');
bgMusic.volume = 0.2;
const clickSound = document.getElementById('clickSound');

// Bulb image
const onBulb = new Image();
onBulb.src = "{{ asset('assets/lightbulb.png') }}";

// Open game modal
function openLightGame() {
  document.getElementById("lightInstructionModal").style.display = "flex";
}

// Start game from instructions
function startLightGameFromInstructions() {
  document.getElementById("lightInstructionModal").style.display = "none";
  if(gameRunning) return;
  lightModal.style.display = "flex";

  // Reset variables
  bulbs = [];
  score = 0;
  misses = 0;
  lightInterval = 1000;
  gameRunning = true;

  // Start music
  bgMusic.currentTime = 0;
  bgMusic.play();

  // Start game after bulb image loads
  if(onBulb.complete) startLightGame();
  else onBulb.onload = () => startLightGame();
}

// Close all modals
function closeAllLightModals() {
  document.getElementById("lightInstructionModal").style.display = "none";
  lightModal.style.display = "none";
  bulbs = [];
  score = 0;
  misses = 0;
  gameRunning = false;
  document.getElementById('lightGameOverScreen').style.display = 'none';
  bgMusic.pause();
}

// Restart game directly
function restartLightGameDirectly() {
  document.getElementById('lightGameOverScreen').style.display = 'none';
  bulbs = [];
  score = 0;
  misses = 0;
  lightInterval = 1000;
  gameRunning = true;

  bgMusic.currentTime = 0;
  bgMusic.play();

  spawnBulb();
  requestAnimationFrame(updateLightGame);
}

// Close game
function closeLightGame() {
  lightModal.style.display = "none";
  bulbs = [];
  score = 0;
  misses = 0;
  gameRunning = false;
  document.getElementById('lightGameOverScreen').style.display = 'none';
  bgMusic.pause();
}

// Start the actual game loop
function startLightGame() {
  spawnBulb();
  requestAnimationFrame(updateLightGame);
}

// Spawn a new bulb
function spawnBulb() {
  if(!gameRunning) return;

  let x = Math.random() * (lightCanvas.width - 80);
  let y = Math.random() * (lightCanvas.height - 80);
  let bulb = { x, y, width: 80, height: 80, alpha: 1, shrink: false };

  // Auto-shrink if not clicked
  setTimeout(() => {
    if(!bulb.shrink) {
      bulb.shrink = true;
      misses++;
      if(misses >= maxMisses) return lightGameOver();
    }
  }, 1500);

  bulbs.push(bulb);

  lightInterval *= 1;
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

// Touch support
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

// Game update loop
function updateLightGame() {
  if(!gameRunning) return;
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
  lctx.font = "18px 'Press Start 2P'";
  lctx.fillText("Score: " + score, 10, 20);
  lctx.fillText("Misses: " + misses + "/" + maxMisses, 10, 40);

  requestAnimationFrame(updateLightGame);
}

// Game over
function lightGameOver() {
  gameRunning = false;
  bgMusic.pause();
  document.getElementById('finalLightScore').textContent = score;
  document.getElementById('lightGameOverScreen').style.display = 'block';
}
</script>

</body>
</html>
