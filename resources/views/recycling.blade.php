<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recycling & Waste - SUSTENA</title>
  <link rel="stylesheet" href="{{ asset('css/learningmod.css') }}">
  <style>
    /* Page layout */
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f6f8fa;
      margin: 0;
      padding: 0;
      color: #333;
    }

    /* Modals */
    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.6);
      justify-content: center;
      align-items: center;
      z-index: 1000;
    }

    .modal-content {
      background: white;
      padding: 20px;
      border-radius: 10px;
      max-width: 500px;
      width: 90%;
      text-align: center;
      animation: fadeIn 0.3s ease-in-out;
      box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: scale(0.9); }
      to { opacity: 1; transform: scale(1); }
    }

    /* Game Layout */
    .game-area {
      max-width: 800px;
    }

    .game-header {
      display: flex;
      justify-content: space-between;
      margin-bottom: 15px;
      font-weight: bold;
    }

    .bins {
      display: flex;
      justify-content: space-around;
      margin-bottom: 20px;
    }

    .bin {
      width: 100px;
      cursor: pointer;
    }

    .items-area {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 15px;
    }

    .draggable-item {
      width: 80px;
      cursor: grab;
    }

    .module-container {
      max-width: 1000px;
      margin: 20px auto;
      background: #fff;
      border-radius: 15px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      padding: 30px;
    }

    .module-header {
      text-align: center;
      margin-bottom: 30px;
    }

    .module-header h1 {
      font-size: 2.5rem;
      color: #2e7d32;
      margin-bottom: 10px;
    }

    .module-header p {
      font-size: 1rem;
      color: #555;
    }

    /* Section styling */
    .module-section {
      margin-bottom: 40px;
    }

    .module-section h2 {
      font-size: 1.5rem;
      color: #2e7d32;
      margin-bottom: 10px;
    }

    .module-section p {
      line-height: 1.8;
      color: #444;
      font-size: 1rem;
    }

    .module-section ul {
      margin-left: 20px;
      color: #444;
      line-height: 1.8;
    }

    /* Video styling */
    .video-container {
      text-align: center;
      margin: 20px 0;
    }

    .video-container iframe {
      width: 100%;
      max-width: 800px;
      height: 450px;
      border: none;
      border-radius: 10px;
    }

    /* Back button */
    .back-button {
      display: inline-block;
      background-color: #2e7d32;
      color: white;
      padding: 10px 20px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      transition: background 0.3s;
      border: none;
      cursor: pointer;
    }

    .back-button:hover {
      background-color: #256528;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .module-container {
        padding: 20px;
      }

      .module-header h1 {
        font-size: 2rem;
      }

      .video-container iframe {
        height: 250px;
      }
    }
  </style>
</head>
<body>

  <div class="module-container">
    <div class="module-header">
      <h1>Recycling & Waste</h1>
      <p>Understand the importance of recycling and how to properly manage waste to protect our planet.</p>
    </div>

    <!-- Section 1: Introduction -->
    <div class="module-section">
      <h2>Why Recycling Matters</h2>
      <p>
        Recycling is the process of collecting, processing, and repurposing materials that would otherwise be discarded as waste.
        It helps conserve natural resources, reduce landfill waste, and minimize pollution caused by raw material extraction and manufacturing.
      </p>
    </div>

    <!-- Section 2: History of Recycling -->
    <div class="module-section">
      <h2>A Brief History of Recycling</h2>
      <p>
        Recycling has been around for centuries. Ancient civilizations reused bronze, glass, and other materials to conserve resources.
        In modern times, recycling gained global importance during the 1970s environmental movement, with the iconic recycling symbol being created in 1970.
      </p>
    </div>

    <!-- Section 3: Steps to Effective Recycling -->
    <div class="module-section">
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

    <!-- Section 4: Embedded Video -->
    <div class="module-section">
      <h2>Watch: Recycling Explained</h2>
      <div class="video-container">
        <iframe 
          src="https://www.youtube.com/embed/_6xlNyWPpB8" 
          title="Recycling and Waste Management"
          allowfullscreen>
        </iframe>
      </div>
    </div>

    <!-- Section 5: Tips for Waste Reduction -->
    <div class="module-section">
      <h2>Simple Ways to Reduce Waste</h2>
      <ul>
        <li>Carry reusable shopping bags and water bottles.</li>
        <li>Avoid single-use plastics whenever possible.</li>
        <li>Buy in bulk to reduce packaging waste.</li>
        <li>Support companies that use eco-friendly packaging.</li>
        <li>Donate old clothes and electronics instead of throwing them away.</li>
      </ul>
    </div>

    <!-- Back button -->
    <div class="module-footer" style="text-align:center;">
        <!-- Start Game Button -->
  <div style="text-align:center; margin-top: 30px;">
    <button id="startGameBtn" class="back-button">Start Recycling Game</button> <br><br><br>
  </div>
      <a href="{{ url('/learning-modules') }}" class="back-button">← Back to Learning Modules</a>
    </div>
  </div>



  <!-- Instruction Modal -->
  <div id="instructionModal" class="modal">
    <div class="modal-content">
      <h2>How to Play</h2>
      <p>Drag each item into the correct bin before the timer runs out!</p>
      <ul style="text-align:left;">
        <li><strong>Recycling Bin:</strong> bottles, cans, newspapers, cardboard, glass</li>
        <li><strong>Trash Bin:</strong> food waste, tissues, plastic utensils, dirty containers</li>
        <li>+1 point for correct, -1 point for wrong</li>
        <li>You have 60 seconds!</li>
      </ul>
      <button class="back-button" onclick="startGame()">Start</button>
    </div>
  </div>

  <!-- Game Modal -->
  <div id="gameModal" class="modal">
    <div class="modal-content game-area">
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
    </div>
  </div>

  <!-- Game Over Modal -->
  <div id="gameOverModal" class="modal">
    <div class="modal-content">
      <h2>Game Over!</h2>
      <p>Your Score: <span id="finalScore">0</span></p>
      <button class="back-button" onclick="restartGame()">Play Again</button>
      <button class="back-button" onclick="closeGame()">Close</button>
    </div>
  </div>

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

    function spawnItems() {
      itemsArea.innerHTML = '';
      const randomItems = [...items].sort(() => 0.5 - Math.random()).slice(0, 5);
      randomItems.forEach(item => {
        const img = document.createElement('img');
        img.src = item.img;
        img.classList.add('draggable-item');
        img.draggable = true;
        img.dataset.type = item.type;
        
        img.addEventListener('dragstart', function(ev) {
          ev.dataTransfer.setData("type", ev.target.dataset.type);
        });

        itemsArea.appendChild(img);
      });
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
      gameOverModal.style.display = 'none';
    }
  </script>
</body>
</html>
