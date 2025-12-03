<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recycling & Waste - SUSTENA</title>
  <link rel="stylesheet" href="{{ asset('css/learningmod.css') }}">
  <link rel="stylesheet" href="{{ asset('css/recycling.css') }}">
  <link rel="stylesheet" href="{{ asset('css/recyclingresponsive.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">

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

    <audio id="gameMusic" loop>
    <source src="{{ asset('sounds/TrashSortGame.mp3') }}" type="audio/mpeg">
      Your browser does not support the audio element.
    </audio>


    <div class="back-section">
      <a href="{{ url('/learning-modules') }}" class="back-button">← Back to Learning Modules</a>
    </div>
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

  <script>
const items = [
  { name: 'Plastic Bottle', img: '{{ asset("assets/pbottle.png") }}', type: 'recycle' },
  { name: 'Can', img: '{{ asset("assets/can.png") }}', type: 'recycle' },
  { name: 'Box', img: '{{ asset("assets/box.png") }}', type: 'recycle' },
  { name: 'Glass', img: '{{ asset("assets/glass.png") }}', type: 'recycle' },
  { name: 'Newspaper', img: '{{ asset("assets/newspaper.png") }}', type: 'recycle' },
  { name: 'Old Food', img: '{{ asset("assets/oldfood.png") }}', type: 'trash' },
  { name: 'Tissue', img: '{{ asset("assets/tissue.png") }}', type: 'trash' },
  { name: 'Plastic Cup', img: '{{ asset("assets/plasticup.png") }}', type: 'trash' },
  { name: 'Plastic Utensils', img: '{{ asset("assets/plasticutens.png") }}', type: 'trash' }
];

let score = 0;
let timeLeft = 60;
let timer;
let usedItems = [];
let currentItem = null; // <-- track currently dragged/touched item

const instructionModal = document.getElementById('instructionModal');
const gameModal = document.getElementById('gameModal');
const gameOverModal = document.getElementById('gameOverModal');
const itemsArea = document.getElementById('itemsArea');
const audio = new Audio('{{ asset("sounds/TrashSortGame.mp3") }}');

audio.volume = 0.4; // 40% volume
audio.loop = true;

document.getElementById('startGameBtn').addEventListener('click', () => {
  instructionModal.style.display = 'flex';
  audio.play();
});

function startGame() {
  instructionModal.style.display = 'none';
  gameModal.style.display = 'flex';
  score = 0;
  timeLeft = 60;
  usedItems = [];
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

function spawnItems() {
  itemsArea.innerHTML = '';
  currentItem = null; // clear current tracking
  if (usedItems.length === items.length) usedItems = [];
  
  let randomItem;
  do {
    randomItem = items[Math.floor(Math.random() * items.length)];
  } while (usedItems.includes(randomItem));

  usedItems.push(randomItem);

  const img = document.createElement('img');
  img.src = randomItem.img;
  img.classList.add('draggable-item');
  img.style.width = '100px';
  img.style.height = '100px';
  img.draggable = true;
  img.dataset.type = randomItem.type;

  // track which item is being dragged (mouse)
  img.addEventListener('dragstart', (ev) => {
    currentItem = ev.target;
    ev.dataTransfer.setData("type", ev.target.dataset.type);
  });

  // Also track dragend to be safe
  img.addEventListener('dragend', () => {
    // do not immediately clear currentItem here; handlers will remove it when appropriate
  });

  enableTouchDrag(img);

  itemsArea.appendChild(img);
}

function enableTouchDrag(img) {
  let touchItem = null;

  img.addEventListener("touchstart", (e) => {
    touchItem = img;
    currentItem = img; // track for touch as well
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
    // pass the image explicitly (some older code calls checkDrop with 3 args)
    checkDrop(touch.clientX, touch.clientY, touchItem);
    touchItem = null;
    // don't immediately reset styles — checkDrop/drop handlers will handle removal/animation
  });
}

// checkDrop accepts optional passedItem (for touch); if not given uses currentItem
function checkDrop(x, y, passedItem = null) {
  const bins = document.querySelectorAll('.bin');
  const scoreDisplay = document.getElementById('score');
  const item = passedItem || currentItem;

  if (!item) {
    // nothing to process
    return;
  }

  for (let bin of bins) {
    const rect = bin.getBoundingClientRect();

    if (
      x > rect.left &&
      x < rect.right &&
      y > rect.top &&
      y < rect.bottom
    ) {
      const itemType = item.dataset.type;

      if (itemType === bin.dataset.type) {
        // CORRECT
        score++;
        dropItemToBin(item, bin);
        createStars(bin);
        shakeBin(bin);
        highlightBinGreen(bin);
      } else {
        // WRONG
        score--;
        // fling away instead of dropping inside
        flingWrongItem(item);
        moveBin(bin, 'right');
        highlightBinRed(bin);
      }

      scoreDisplay.textContent = score;
      // spawn next after animation finished
      setTimeout(() => {
        spawnItems();
      }, 650);
      currentItem = null;
      return;
    }
  }

  // If drop outside any bin -> just remove the item and spawn next
  if (item && item.parentNode) item.remove();
  currentItem = null;
  spawnItems();
}

// fling the wrong item away
function flingWrongItem(item) {
  const randomX = (Math.random() * 300 - 150); // -150 to +150 sideways
  const randomY = (Math.random() * -200 - 100); // upward arc

  // ensure we have a starting rect
  const startRect = item.getBoundingClientRect();
  item.style.position = 'fixed';
  item.style.left = `${startRect.left}px`;
  item.style.top = `${startRect.top}px`;
  item.style.transition = 'all 0.6s cubic-bezier(.17,.67,.83,.67)';
  item.style.pointerEvents = 'none';

  // tiny delay to allow the browser to apply fixed position before animating
  requestAnimationFrame(() => {
    requestAnimationFrame(() => {
      item.style.left = (startRect.left + randomX) + 'px';
      item.style.top = (startRect.top + randomY) + 'px';
      item.style.transform = 'rotate(' + (Math.random() * 180 - 90) + 'deg) scale(0.8)';
      item.style.opacity = '0';
    });
  });

  setTimeout(() => {
    if (item && item.parentNode) item.remove();
  }, 650);
}

function createStars(bin) {
  for (let i = 0; i < 6; i++) {
    const star = document.createElement('div');
    star.textContent = '⭐';
    star.style.position = 'fixed';
    const rect = bin.getBoundingClientRect();
    star.style.left = (rect.left + rect.width/2) + 'px';
    star.style.top = (rect.top + rect.height/2) + 'px';
    star.style.fontSize = `${14 + Math.random()*30}px`;
    star.style.opacity = '1';
    star.style.transition = 'all 0.9s ease-out';
    star.style.pointerEvents = 'none';
    document.body.appendChild(star);

    setTimeout(() => {
      star.style.left = (rect.left + rect.width/2 + (Math.random()*220-110)) + 'px';
      star.style.top = (rect.top + rect.height/2 - 160 - Math.random()*60) + 'px';
      star.style.opacity = '0';
      star.style.transform = `rotate(${Math.random()*720}deg) scale(${1 + Math.random()})`;
    }, 30);

    setTimeout(() => star.remove(), 950);
  }
}

function highlightBinRed(bin) {
  bin.style.transition = '0.35s';
  bin.style.filter = 'drop-shadow(0 0 18px red)';
  setTimeout(() => { if (bin) bin.style.filter = ''; }, 500);
}

function highlightBinGreen(bin) {
  bin.style.transition = '0.35s';
  bin.style.filter = 'drop-shadow(0 0 20px lime)';
  setTimeout(() => { if (bin) bin.style.filter = ''; }, 500);
}

function dropItemToBin(item, bin) {
  const rect = bin.getBoundingClientRect();
  const binCenterX = rect.left + rect.width / 2 - item.width / 2;
  const binCenterYTop = rect.top - 80; // Hover above bin
  const binCenterYInside = rect.top + rect.height / 2 - item.height / 2; // Inside bin

  // move the actual element smoothly
  const startRect = item.getBoundingClientRect();
  item.style.position = 'fixed';
  item.style.left = `${startRect.left}px`;
  item.style.top = `${startRect.top}px`;
  item.style.pointerEvents = 'none';
  item.style.transition = 'all 0.25s ease-out';
  item.style.transform = 'scale(1)';
  item.style.opacity = '1';

  // Stage 1: move above bin
  requestAnimationFrame(() => {
    item.style.left = `${binCenterX}px`;
    item.style.top = `${binCenterYTop}px`;
  });

  // Stage 2: drop into bin
  setTimeout(() => {
    item.style.transition = 'all 0.42s cubic-bezier(.25,.46,.45,.94)';
    item.style.top = `${binCenterYInside}px`;
    item.style.transform = 'scale(0.5)';
    item.style.opacity = '0';
  }, 260);

  // Remove after finish
  setTimeout(() => {
    if (item && item.parentNode) item.remove();
  }, 700);
}

function shakeBin(bin) {
  bin.style.transition = 'transform 0.1s';
  bin.style.transform = 'rotate(-12deg)';
  setTimeout(() => { if (bin) bin.style.transform = 'rotate(12deg)'; }, 100);
  setTimeout(() => { if (bin) bin.style.transform = 'rotate(0deg)'; }, 200);
}

function moveBin(bin, direction) {
  bin.style.transition = 'transform 0.32s';
  const dist = direction === 'right' ? 26 : -26;
  bin.style.transform = `translateX(${dist}px)`;
  setTimeout(() => { if (bin) bin.style.transform = 'translateX(0px)'; }, 330);
}

// Desktop drag/drop handler (mouse)
document.querySelectorAll('.bin').forEach(bin => {
  bin.addEventListener('dragover', (ev) => ev.preventDefault());
  bin.addEventListener('drop', (ev) => {
    ev.preventDefault();
    
    const droppedType = ev.dataTransfer.getData("type");
    const img = currentItem || document.querySelector('.draggable-item');
    if (!img) return;

    if (droppedType === bin.dataset.type) {
      score++;
      // Correct
      dropItemToBin(img, bin);
      createStars(bin);
      shakeBin(bin);
      highlightBinGreen(bin);
    } else {
      score--;
      // Wrong: fling away (do NOT drop in)
      flingWrongItem(img);
      moveBin(bin, 'right');
      highlightBinRed(bin);
    }

    document.getElementById('score').textContent = score;
    // spawn after animation completes
    setTimeout(() => spawnItems(), 700);
    currentItem = null;
  });
});

function endGame() {
  clearInterval(timer);
  gameModal.style.display = 'none';
  document.getElementById('finalScore').textContent = score;
  gameOverModal.style.display = 'flex';
  audio.pause();
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
  usedItems = [];
  currentItem = null;
  audio.pause();
  audio.currentTime = 0;
}
</script>

</body>
</html>
