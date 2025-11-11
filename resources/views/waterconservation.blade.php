<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Water Conservation - SUSTENA</title>
  <link rel="stylesheet" href="{{ asset('css/learningmod.css') }}">
<link rel="stylesheet" href="{{ asset('css/waterconservation.css') }}">
<link rel="stylesheet" href="{{ asset('css/waterconservationresponsive.css') }}">
</head>
<body>
  <div class="module-container">
    <div class="module-header">
      <h1>Water Conservation</h1>
      <p>Learn about the importance of saving water and how you can make a difference in protecting this precious resource.</p>
    </div>

    <div class="module-section">
      <h2>What is Water Conservation?</h2>
      <p>
        Water conservation involves the sustainable management of freshwater resources to ensure a balance between human consumption and natural ecosystems. 
        With climate change and population growth, the need to conserve water has become more critical than ever.
      </p>
    </div>

    <div class="module-section">
      <h2>Why Water Conservation Matters</h2>
      <p>Clean water is essential for life. Conserving water helps:</p>
      <ul>
        <li>Ensure safe drinking water for future generations.</li>
        <li>Protect natural ecosystems and wildlife.</li>
        <li>Reduce energy costs linked to water treatment and delivery.</li>
        <li>Mitigate the effects of droughts and water shortages.</li>
      </ul>
    </div>

    <div class="module-section">
      <h2>Watch: Simple Water Saving Tips</h2>
      <div class="video-container">
        <iframe 
          src="https://www.youtube.com/embed/gJmY3dzg3Gk" 
          title="Water Conservation Video" 
          allowfullscreen>
        </iframe>
      </div>
    </div>

    <div class="module-section" style="text-align: center;">
      <h2>Try the Water Catcher Game!</h2>
      <button onclick="openGame()" class="back-button">🎮 Play Water Catcher</button>
      </div>

    <div class="module-footer" style="text-align:center;">
      <a href="{{ url('/learning-modules') }}" class="back-button">← Back to Learning Modules</a>
    </div>
  </div>

  <!-- Modal for game -->
  <div id="gameModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeGame()">&times;</span>
      <canvas id="gameCanvas" width="400" height="500"></canvas>
    </div>
  </div>

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
  if (gameRunning) return; 
  modal.style.display = "flex";
  resetGame();
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

  ctx.fillStyle = "rgba(0,0,0,0.7)";
  ctx.fillRect(0, 0, canvas.width, canvas.height);

  ctx.fillStyle = "#fff";
  ctx.font = "24px Poppins";
  ctx.fillText("GAME OVER", canvas.width / 2 - 70, canvas.height / 2 - 40);
  ctx.font = "18px Poppins";
  ctx.fillText("💧 Saved: " + litersSaved + " L", canvas.width / 2 - 70, canvas.height / 2);
  ctx.fillText("💦 Wasted: " + litersWasted + " L", canvas.width / 2 - 70, canvas.height / 2 + 30);
  ctx.fillText("⏱ Time: " + elapsedTime + "s", canvas.width / 2 - 70, canvas.height / 2 + 60);
}

// Smooth key handling
document.addEventListener("keydown", (e) => {
  if (e.key === "ArrowLeft") keys.left = true;
  if (e.key === "ArrowRight") keys.right = true;
});
document.addEventListener("keyup", (e) => {
  if (e.key === "ArrowLeft") keys.left = false;
  if (e.key === "ArrowRight") keys.right = false;
});
</script>
</body>
</html>
