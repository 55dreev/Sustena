<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SUSTENA - Challenges</title>
  <link rel="stylesheet" href="{{ asset('css/challenges.css') }}">
  <style>
    /* Modal Overlay */
    .modal {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.7);
      justify-content: center;
      align-items: center;
      z-index: 1000;
      padding: 20px;
    }

    /* Modal Content */
    .modal-content {
      background: #fff;
      border-radius: 16px;
      padding: 30px;
      width: 95%;
      max-width: 800px;
      text-align: left;
      position: relative;
      max-height: 90%;
      overflow-y: auto;
      box-shadow: 0 10px 30px rgba(0,0,0,0.25);
      animation: fadeInUp 0.3s ease;
      margin-left: 250px;
    }

    .close-modal {
      position: absolute;
      right: 20px;
      top: 15px;
      font-size: 28px;
      cursor: pointer;
      color: #444;
      font-weight: bold;
    }

    .upload-box {
      margin-top: 20px;
      padding: 20px;
      border: 2px dashed #aaa;
      border-radius: 10px;
      text-align: center;
      transition: 0.2s ease-in-out;
    }
    .upload-box:hover { border-color: #4CAF50; }

    #previewImage {
      margin-top: 15px;
      max-width: 100%;
      max-height: 300px;
      display: none;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    /* Status Badge */
    .status-badge {
      display: inline-block;
      padding: 3px 8px;
      border-radius: 8px;
      font-size: 12px;
      margin-top: 5px;
      font-weight: bold;
    }
    .not-started { background: #aaa; color: white; }
    .pending { background: #ffeb3b; color: #444; }
    .completed { background: #4caf50; color: white; }

    /* Difficulty circles */
    .difficulty-circles {
      margin-bottom: 5px;
    }
    .difficulty {
      display: inline-block;
      width: 12px;
      height: 12px;
      border-radius: 50%;
      margin-right: 3px;
      border: 1px solid #aaa;
      background-color: transparent;
      vertical-align: middle;
    }
    .difficulty.filled.easy { background-color: #4caf50; border-color: #4caf50; }    /* green */
    .difficulty.filled.medium { background-color: #ffeb3b; border-color: #ffeb3b; } /* yellow */
    .difficulty.filled.hard { background-color: #f44336; border-color: #f44336; }   /* red */

    @keyframes fadeInUp {
      from { transform: translateY(50px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <div class="sidebar-toggle" onclick="toggleSidebar()">☰</div>
    <div class="logo">
      <div class="logo-icon">🌱</div>
      <div class="logo-text">SUSTENA</div>
    </div>
    <a href="{{ url('/landing-page') }}" class="nav-item"><div class="nav-icon">🏠</div><span>Home</span></a>
    <a href="{{ url('/footprint-calculator') }}" class="nav-item"><div class="nav-icon">👣</div><span>Footprint Tracker</span></a>
    <a href="{{ url('/learning-modules') }}" class="nav-item"><div class="nav-icon">📚</div><span>Learn</span></a>
    <a href="{{ url('/challenge') }}" class="nav-item active"><div class="nav-icon">🏆</div><span>Challenges</span></a>
    <a href="{{ url('/forum') }}" class="nav-item"><div class="nav-icon">💬</div><span>MicroForum</span></a>
    <a href="{{ url('/profile') }}" class="nav-item"><div class="nav-icon">👤</div><span>Profile</span></a>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="header-section">
      <h1 class="header-title">Challenges</h1>
      <div class="header-subtitle">Pick a challenge to complete</div>
      <div id="challenge-timer" style="margin-top:5px; font-size:14px; color:#444;">Refreshes in: --</div>
    </div>

    <button id="refreshChallenges" style="margin: 20px; padding: 10px; cursor: pointer;">🔄 Refresh Challenges (Test)</button>

    <div class="challenges-grid"></div>
  </div>

  <!-- Modal -->
  <div id="challengeModal" class="modal">
    <div class="modal-content">
      <span class="close-modal" onclick="closeModal()">&times;</span>
      <h2 id="modalTitle"></h2>
      <p id="modalDescription"></p>
      <p><strong>Instructions:</strong> Complete the challenge thoroughly, then upload a picture proof.</p>
      
      <div class="upload-box">
        <input type="file" id="proofUpload" accept="image/*">
        <img id="previewImage" alt="Preview">
      </div>

      <button id="submitProof" style="margin-top:20px; padding:10px; cursor:pointer;">✅ Submit Proof</button>
    </div>
  </div>

  <script>
    const challengePool = [
                  { title: "Go Meatless", subtitle: "Eat Vegan Meals", points: "+50 XP", icon: "🥗", description: "Choose plant-based meals for the day.", difficulty: 1 },
            { title: "Turn Off Lights", subtitle: "Save Electricity", points: "+30 XP", icon: "💡", description: "Switch off lights when leaving a room.", difficulty: 1 },
            { title: "Use a Reusable Bottle", subtitle: "Avoid Plastic", points: "+40 XP", icon: "🥤", description: "Carry a reusable water bottle today.", difficulty: 1 },
            { title: "Pick Up Trash", subtitle: "Clean Your Area", points: "+45 XP", icon: "🗑️", description: "Collect litter from your surroundings.", difficulty: 1 },
            { title: "Short Shower", subtitle: "Save Water", points: "+35 XP", icon: "🚿", description: "Keep your shower under 5 minutes.", difficulty: 1 },
            { title: "Skip the Straw", subtitle: "Plastic-Free Drink", points: "+20 XP", icon: "🥛", description: "Say no to plastic straws today.", difficulty: 1 },
            { title: "Reuse a Bag", subtitle: "Ditch Plastic", points: "+25 XP", icon: "🛍️", description: "Bring your own bag when shopping.", difficulty: 1 },
            { title: "Unplug Devices", subtitle: "Save Power", points: "+30 XP", icon: "🔌", description: "Unplug electronics not in use.", difficulty: 1 },
            { title: "Recycle Correctly", subtitle: "Sort Waste", points: "+40 XP", icon: "♻️", description: "Properly sort recyclables today.", difficulty: 1 },
            { title: "Open the Window", subtitle: "Fresh Air", points: "+20 XP", icon: "🌬️", description: "Use natural ventilation instead of AC.", difficulty: 1 },
            { title: "Use Cloth Napkin", subtitle: "Avoid Paper", points: "+25 XP", icon: "🧻", description: "Replace tissues with a cloth napkin.", difficulty: 1 },
            { title: "Print Double-Sided", subtitle: "Save Paper", points: "+30 XP", icon: "🖨️", description: "If you must print, use both sides.", difficulty: 1 },
            { title: "Eat Leftovers", subtitle: "No Food Waste", points: "+35 XP", icon: "🍛", description: "Eat your leftovers instead of wasting.", difficulty: 1 },
            { title: "Turn Off Tap", subtitle: "Save Water", points: "+20 XP", icon: "🚰", description: "Turn off water while brushing teeth.", difficulty: 1 },
            { title: "Share a Ride", subtitle: "Carpool", points: "+40 XP", icon: "🚗", description: "Share your ride with someone.", difficulty: 1 },

            // Medium ⚡
            { title: "Bike Instead of Drive", subtitle: "Use Your Bicycle", points: "+60 XP", icon: "🚲", description: "Bike for short trips instead of driving.", difficulty: 2 },
            { title: "Public Transport", subtitle: "Eco Travel", points: "+70 XP", icon: "🚌", description: "Use public transportation today.", difficulty: 2 },
            { title: "Meal Prep", subtitle: "Reduce Waste", points: "+65 XP", icon: "🍱", description: "Cook meals at home to avoid packaging waste.", difficulty: 2 },
            { title: "Digital Declutter", subtitle: "Save Energy", points: "+55 XP", icon: "💻", description: "Delete unnecessary files and emails.", difficulty: 2 },
            { title: "Bring Your Mug", subtitle: "Skip Disposable Cups", points: "+50 XP", icon: "☕", description: "Use your own mug at cafes.", difficulty: 2 },
            { title: "Cold Wash Laundry", subtitle: "Save Energy", points: "+75 XP", icon: "👕", description: "Wash clothes in cold water.", difficulty: 2 },
            { title: "Compost Food", subtitle: "Reduce Waste", points: "+70 XP", icon: "🍂", description: "Start composting kitchen scraps.", difficulty: 2 },
            { title: "Switch to E-Docs", subtitle: "Paperless Day", points: "+60 XP", icon: "📄", description: "Avoid printing, go fully digital today.", difficulty: 2 },
            { title: "DIY Repair", subtitle: "Fix, Don’t Replace", points: "+80 XP", icon: "🔧", description: "Repair something instead of buying new.", difficulty: 2 },
            { title: "Support Local", subtitle: "Shop Nearby", points: "+65 XP", icon: "🏪", description: "Buy from local stores or markets.", difficulty: 2 },
            { title: "Buy Seasonal Fruits", subtitle: "Local Food", points: "+70 XP", icon: "🍎", description: "Only buy seasonal, local fruits.", difficulty: 2 },
            { title: "Donate Clothes", subtitle: "Give Away", points: "+85 XP", icon: "👕", description: "Donate old clothes to those in need.", difficulty: 2 },
            { title: "Turn Down AC", subtitle: "Save Power", points: "+65 XP", icon: "❄️", description: "Raise your AC temp by 2 degrees.", difficulty: 2 },
            { title: "Eco-Friendly Gift", subtitle: "Green Giving", points: "+75 XP", icon: "🎁", description: "Give a sustainable gift today.", difficulty: 2 },
            { title: "Batch Cooking", subtitle: "Efficient Meals", points: "+70 XP", icon: "🍲", description: "Cook in bulk to save energy.", difficulty: 2 },

            // Hard 🔥
            { title: "Plant a Tree", subtitle: "Contribute to Nature", points: "+100 XP", icon: "🌳", description: "Plant and care for a tree.", difficulty: 3 },
            { title: "Go Car-Free Day", subtitle: "No Driving", points: "+120 XP", icon: "🚶", description: "Avoid using a car for the whole day.", difficulty: 3 },
            { title: "Plastic-Free Day", subtitle: "Zero Plastic Use", points: "+150 XP", icon: "🚯", description: "Don’t use any single-use plastic today.", difficulty: 3 },
            { title: "Volunteer Cleanup", subtitle: "Help the Community", points: "+140 XP", icon: "🧹", description: "Join or organize a cleanup drive.", difficulty: 3 },
            { title: "No Meat Day", subtitle: "Plant-Based Only", points: "+130 XP", icon: "🥦", description: "Eat no meat products all day.", difficulty: 3 },
            { title: "Energy-Free Evening", subtitle: "No Electricity", points: "+125 XP", icon: "🕯️", description: "Spend the evening without electricity.", difficulty: 3 },
            { title: "Walk 10,000 Steps", subtitle: "Skip Vehicles", points: "+110 XP", icon: "👟", description: "Walk at least 10,000 steps today.", difficulty: 3 },
            { title: "Eco Shopping", subtitle: "Sustainable Products", points: "+135 XP", icon: "🛒", description: "Buy only eco-friendly items today.", difficulty: 3 },
            { title: "Cook for Friends", subtitle: "Sustainable Meal", points: "+150 XP", icon: "🍲", description: "Cook a zero-waste meal for others.", difficulty: 3 },
            { title: "Minimalist Day", subtitle: "Buy Nothing", points: "+120 XP", icon: "🚫", description: "Don’t buy anything non-essential today.", difficulty: 3 },
            { title: "Zero-Waste Meal", subtitle: "No Packaged Food", points: "+140 XP", icon: "🥘", description: "Cook only unpackaged ingredients.", difficulty: 3 },
            { title: "Plastic-Free Groceries", subtitle: "Eco Shopping", points: "+150 XP", icon: "🛍️", description: "Buy all groceries without plastic.", difficulty: 3 },
            { title: "DIY Cleaning Product", subtitle: "Eco-Friendly", points: "+135 XP", icon: "🧴", description: "Make your own natural cleaner.", difficulty: 3 },
            { title: "Community Gardening", subtitle: "Grow Together", points: "+145 XP", icon: "🌱", description: "Help plant in a community garden.", difficulty: 3 },
            { title: "Thrift Shopping", subtitle: "Second-Hand", points: "+130 XP", icon: "👗", description: "Buy second-hand instead of new.", difficulty: 3 }
    ];

    let currentChallenge = null;
    let displayedChallenges = [];

    function getShuffledQueue(pool) {
      return [...pool].sort(() => 0.5 - Math.random());
    }

    function loadChallenges() {
      const today = new Date().toDateString();
      const lastDate = localStorage.getItem("lastRefreshDate");

      if (lastDate !== today) {
        localStorage.removeItem("challengeQueue");
        localStorage.removeItem("challengeStatus");
        localStorage.setItem("lastRefreshDate", today);
      }

      let savedData = JSON.parse(localStorage.getItem("challengeQueue"));
      if (!savedData || savedData.queue.length < 4) {
        savedData = { queue: getShuffledQueue(challengePool) };
      }

      displayedChallenges = savedData.queue.splice(0, 4);
      localStorage.setItem("challengeQueue", JSON.stringify(savedData));
      renderChallenges(displayedChallenges);
    }

    function getChallengeStatus(title) {
      const statusData = JSON.parse(localStorage.getItem("challengeStatus")) || {};
      const entry = statusData[title];
      const today = new Date().toDateString();
      if (!entry || entry.date !== today) return "not-started";
      return entry.status;
    }

    function setChallengeStatus(title, status) {
      const statusData = JSON.parse(localStorage.getItem("challengeStatus")) || {};
      const today = new Date().toDateString();
      statusData[title] = { status: status, date: today };
      localStorage.setItem("challengeStatus", JSON.stringify(statusData));
      renderChallenges(displayedChallenges);
    }

    function renderChallenges(challenges) {
      const grid = document.querySelector(".challenges-grid");
      grid.innerHTML = "";

      challenges.forEach(ch => {
        const status = getChallengeStatus(ch.title);
        const badgeText = status === "pending" ? "Pending" : status === "completed" ? "Completed" : "Not Started";

        // Difficulty circles
        let diffHTML = '';
        for (let i = 1; i <= 3; i++) {
          if (i <= ch.difficulty) diffHTML += `<span class="difficulty filled ${ch.difficulty===1?'easy':ch.difficulty===2?'medium':'hard'}"></span>`;
          else diffHTML += '<span class="difficulty"></span>';
        }

        const card = document.createElement("div");
        card.classList.add("challenge-card");
        card.innerHTML = `
          <div class="challenge-points">${ch.points}</div>
          <div class="challenge-icon">${ch.icon}</div>
          <div class="challenge-content">
            <div class="difficulty-circles">${diffHTML}</div>
            <h3 class="challenge-title">${ch.title}</h3>
            <p class="challenge-subtitle">${ch.subtitle}</p>
            <p class="challenge-description">${ch.description}</p>
            ${status !== "completed" ? '<button class="challenge-button">Open</button>' : ''}
            <div class="status-badge ${status}">${badgeText}</div>
          </div>
        `;
        grid.appendChild(card);
        if (status !== "completed") {
          card.querySelector(".challenge-button").addEventListener("click", () => openModal(ch));
        }
      });
    }

    function openModal(challenge) {
      currentChallenge = challenge;
      document.getElementById("modalTitle").textContent = challenge.title;
      document.getElementById("modalDescription").textContent = challenge.description;
      document.getElementById("proofUpload").value = "";
      document.getElementById("previewImage").style.display = "none";
      document.getElementById("challengeModal").style.display = "flex";

      const status = getChallengeStatus(challenge.title);
      const btn = document.getElementById("submitProof");
      btn.textContent = status === "pending" ? "✔ Mark as Completed" : "✅ Submit Proof";
    }

    function closeModal() {
      document.getElementById("challengeModal").style.display = "none";
    }

    document.getElementById("proofUpload").addEventListener("change", function() {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          const preview = document.getElementById("previewImage");
          preview.src = e.target.result;
          preview.style.display = "block";
        };
        reader.readAsDataURL(file);
      }
    });

    document.getElementById("submitProof").addEventListener("click", function() {
      if (!currentChallenge) return;
      const status = getChallengeStatus(currentChallenge.title);
      if (status === "not-started") setChallengeStatus(currentChallenge.title, "pending");
      else if (status === "pending") setChallengeStatus(currentChallenge.title, "completed");
      closeModal();
    });

    function startCountdown() {
      const timerEl = document.getElementById("challenge-timer");
      function updateTimer() {
        const now = new Date();
        const midnight = new Date();
        midnight.setHours(24, 0, 0, 0);
        const diff = midnight - now;
        const hours = String(Math.floor(diff / (1000 * 60 * 60))).padStart(2, "0");
        const minutes = String(Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, "0");
        const seconds = String(Math.floor((diff % (1000 * 60)) / 1000)).padStart(2, "0");
        timerEl.textContent = `Refreshes in: ${hours}:${minutes}:${seconds}`;
      }
      updateTimer();
      setInterval(updateTimer, 1000);
    }

    document.getElementById("refreshChallenges").addEventListener("click", loadChallenges);

    loadChallenges();
    startCountdown();
  </script>
</body>
</html>
