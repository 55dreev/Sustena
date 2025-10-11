<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Energy Saving - SUSTENA</title>
  <link rel="stylesheet" href="{{ asset('css/learningmod.css') }}">
  <style>
    body { font-family: 'Poppins', sans-serif; background-color: #f6f8fa; margin:0; padding:0; color:#333; }
    .module-container { max-width:1000px; margin:20px auto; background:#fff; border-radius:15px; box-shadow:0 4px 10px rgba(0,0,0,0.1); padding:30px; }
    .module-header { text-align:center; margin-bottom:30px; }
    .module-header h1 { font-size:2.5rem; color:#2e7d32; margin-bottom:10px; }
    .module-header p { font-size:1rem; color:#555; }
    .module-section { margin-bottom:40px; }
    .module-section h2 { font-size:1.5rem; color:#2e7d32; margin-bottom:10px; }
    .module-section p { line-height:1.8; color:#444; font-size:1rem; }
    .video-container { text-align:center; margin:20px 0; }
    .video-container iframe { width:100%; max-width:800px; height:450px; border:none; border-radius:10px; }
    ul { padding-left:20px; }
    ul li { margin-bottom:8px; }
    .back-button { display:inline-block; background-color:#2e7d32; color:white; padding:10px 20px; border-radius:8px; text-decoration:none; font-weight:600; transition:background 0.3s; cursor:pointer; }
    .back-button:hover { background-color:#256528; }
    .game-section { text-align:center; }
    .modal { display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.7); justify-content:center; align-items:center; }
    .modal-content { background:#fff; padding:20px; border-radius:12px; text-align:center; position:relative; }
    .modal-content canvas { display:block; margin:0 auto; background:#fffbe6; border-radius:10px; border:2px solid #2e7d32; }
    .close { position:absolute; top:10px; right:20px; font-size:24px; font-weight:bold; color:#333; cursor:pointer; }
    @media (max-width:768px) { .module-container { padding:20px; } .module-header h1 { font-size:2rem; } .video-container iframe { height:250px; } }
  </style>
</head>
<body>

<div class="module-container">
  <div class="module-header">
    <h1>Energy Saving and Conservation</h1>
    <p>Learn how to save energy, lower costs, and protect the environment through smart energy practices.</p>
  </div>

  <div class="module-section">
    <h2>Why Energy Saving Matters</h2>
    <p>Energy saving is the practice of using less energy by eliminating unnecessary usage and improving efficiency. By conserving energy, we reduce greenhouse gas emissions, slow climate change, and save money on electricity bills. Simple habits, like turning off unused devices and using renewable energy, can make a big difference.</p>
  </div>

  <div class="module-section">
    <h2>Sources of Energy</h2>
    <p>Our energy comes from different sources, and some are more sustainable than others:</p>
    <ul>
      <li><strong>Renewable Energy</strong> – Solar, wind, hydro, and geothermal are clean and sustainable.</li>
      <li><strong>Non-Renewable Energy</strong> – Fossil fuels like coal, oil, and natural gas are limited and harmful to the environment.</li>
    </ul>
    <p>Shifting to renewable sources is key to reducing pollution and ensuring a sustainable future.</p>
  </div>

  <div class="module-section">
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

  <div class="module-section">
    <h2>Watch: Easy Energy Saving Hacks</h2>
    <div class="video-container">
      <iframe src="https://www.youtube.com/embed/D11iFUw_ImU" title="Energy Saving Tips" allowfullscreen></iframe>
    </div>
  </div>

  <div class="module-section game-section">
    <h2>Try the Light Bulb Rush Game!</h2>
    <button onclick="openLightGame()" class="back-button">💡 Play Light Bulb Rush</button>
  </div>

  <div class="module-section">
    <h2>Summary</h2>
    <p>Saving energy not only lowers your electricity bill but also helps combat climate change. Through simple steps like reducing waste, switching to renewables, and making energy-smart decisions, we can create a cleaner, more sustainable future for everyone.</p>
  </div>

  <div class="module-footer" style="text-align:center;">
    <a href="{{ url('/learning-modules') }}" class="back-button">← Back to Learning Modules</a>
  </div>
</div>

<!-- Modal -->
<div id="lightModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeLightGame()">&times;</span>
    <canvas id="lightCanvas" width="400" height="500"></canvas>
  </div>
</div>

<!-- Audio -->
<audio id="bgMusic" src="{{ asset('sounds/lightbulbbg.mp3') }}" loop></audio>
<audio id="clickSound" src="{{ asset('sounds/Switch.mp3') }}"></audio>

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

function closeLightGame() {
  lightModal.style.display = "none";
  gameRunning = false;
  bulbs = [];

  // Stop music
  bgMusic.pause();
}

function startLightGame() {
  bulbs = [];
  score = 0;
  gameStart = Date.now();
  lightInterval = 1000;
  gameRunning = true;
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
  lctx.fillStyle = "rgba(0,0,0,0.7)";
  lctx.fillRect(0,0,lightCanvas.width,lightCanvas.height);

  lctx.fillStyle = "#fff";
  lctx.font = "24px Poppins";
  lctx.fillText("GAME OVER", 100, 200);
  lctx.font = "18px Poppins";
  lctx.fillText("Score: " + score, 150, 250);
  lctx.fillText("Time's up!", 130, 280);

  // Stop background music
  bgMusic.pause();
}
</script>

</body>
</html>
