<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Water Conservation - SUSTENA</title>
  <link rel="stylesheet" href="{{ asset('css/waterconservation.css') }}">
  <link rel="stylesheet" href="{{ asset('css/waterconservationresponsive.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
 
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

      <h1 class="header-title"> Water Conservation </h1>
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
            src="https://www.youtube.com/embed/8tA3GnlaX18" 
            title="Energy Saving Video" 
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

// Game variables
let bucket = { x: 170, y: 420, width: 50, height: 50, speed: 400 }; // faster bucket speed px/sec
let drops = [];
let litersSaved = 0,
    litersWasted = 0,
    startTime,
    elapsedTime = 0;
let poopChance = 0.3;
let gameRunning = false;
let gameOverState = false;

// Input tracking
let keys = { left: false, right: false };
let lastFrameTime = 0;

function openGame() {
  document.getElementById("waterInstructionModal").style.display = "flex";
}

function startWaterGame() {
  document.getElementById("waterInstructionModal").style.display = "none";
  if (gameRunning) return;
  modal.style.display = "flex";
  resetGame();
}

function closeWaterGame() {
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
  lastFrameTime = performance.now();

  document.getElementById('gameOverScreen').style.display = 'none';

  bgMusic.currentTime = 0;
  bgMusic.play().catch(err => console.log("Autoplay blocked", err));

  requestAnimationFrame(update);
}

function spawnDrop() {
  const type = Math.random() < poopChance ? "poop" : "water";
  drops.push({ x: Math.random() * (canvas.width - 40), y: 0, width: 40, height: 40, type });
}

// Main game loop
function update(timestamp) {
  if (!gameRunning) return;

  const delta = (timestamp - lastFrameTime) / 1000;
  lastFrameTime = timestamp;

  elapsedTime = Math.floor((Date.now() - startTime) / 1000);

  // Aggressive spawn rate: more drops and scaling with time
  if (Math.random() < (1.5 + elapsedTime * 0.05) * delta) {
    spawnDrop();
  }

  // Move bucket
  const moveDistance = bucket.speed * delta;
  if (keys.left && bucket.x > 0) bucket.x -= moveDistance;
  if (keys.right && bucket.x + bucket.width < canvas.width) bucket.x += moveDistance;

  // Clear canvas
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  ctx.drawImage(bucketImg, bucket.x, bucket.y, bucket.width, bucket.height);

  // Update drops
  for (let i = drops.length - 1; i >= 0; i--) {
    let d = drops[i];
    d.y += (200 + elapsedTime * 8) * delta; // faster initial fall and scales faster

    ctx.drawImage(d.type === "water" ? dropImg : dirtyImg, d.x, d.y, d.width, d.height);

    // Collision detection
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
    // Drop missed
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

  document.getElementById('finalSaved').textContent = litersSaved;
  document.getElementById('finalWasted').textContent = litersWasted;
  document.getElementById('finalTime').textContent = elapsedTime;
  document.getElementById('gameOverScreen').style.display = 'block';
}

// Keyboard controls
document.addEventListener("keydown", (e) => {
  if (!gameRunning) return;
  if (e.key === "ArrowLeft") keys.left = true;
  if (e.key === "ArrowRight") keys.right = true;
});
document.addEventListener("keyup", (e) => {
  if (!gameRunning) return;
  if (e.key === "ArrowLeft") keys.left = false;
  if (e.key === "ArrowRight") keys.right = false;
});

// Mobile touch controls
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
</script>


</body>
</html>
