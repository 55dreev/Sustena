{{-- resources/views/challenge/index.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>SUSTENA - Challenges</title>

  {{-- Use a public CSS file. Place it at public/css/challenges.css --}}
  <link rel="stylesheet" href="{{ asset('css/challenges.css') }}"/>
   <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">

  <style>
    /* inline page-specific styles (your original block) */
    .modal{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.7);justify-content:center;align-items:center;z-index:1000;padding:20px}
    .modal-content{background:#fff;border-radius:16px;padding:30px;width:95%;max-width:800px;text-align:left;position:relative;max-height:90%;overflow-y:auto;box-shadow:0 10px 30px rgba(0,0,0,.25);animation:fadeInUp .3s ease;margin-left:250px}
    .close-modal{position:absolute;right:20px;top:15px;font-size:28px;cursor:pointer;color:#444;font-weight:bold}
    .upload-box{margin-top:20px;padding:20px;border:2px dashed #aaa;border-radius:10px;text-align:center;transition:.2s}
    .upload-box:hover{border-color:#4CAF50}
    #previewImage{margin-top:15px;max-width:100%;max-height:300px;display:none;border-radius:10px;box-shadow:0 5px 15px rgba(0,0,0,.2)}
    .status-badge{display:inline-block;padding:3px 8px;border-radius:8px;font-size:12px;margin-top:5px;font-weight:bold}
    .not-started{background:#aaa;color:#fff}.pending{background:#ffeb3b;color:#444}.completed{background:#4caf50;color:#fff}
    .difficulty-circles{margin-bottom:5px}
    .difficulty{display:inline-block;width:12px;height:12px;border-radius:50%;margin-right:3px;border:1px solid #aaa;background:transparent;vertical-align:middle}
    .difficulty.filled.easy{background:#4caf50;border-color:#4caf50}
    .difficulty.filled.medium{background:#ffeb3b;border-color:#ffeb3b}
    .difficulty.filled.hard{background:#f44336;border-color:#f44336}
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
            box-shadow: 
                inset 2px 2px 0px rgba(255,255,255,0.8),
                inset -2px -2px 0px rgba(0,0,0,0.3),
                0px 4px 12px rgba(0,0,0,0.15);
            cursor: pointer;
            transition: all 0.3s ease;
            image-rendering: pixelated;
            image-rendering: -moz-crisp-edges;
            image-rendering: crisp-edges;
            filter: contrast(1.2) brightness(1.1);
             text-decoration: none;  /* removes underline */
    color: inherit;         /* optional: inherit text color */
        }

        .floating-icon:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        }
    @keyframes fadeInUp{from{transform:translateY(50px);opacity:0}to{transform:translateY(0);opacity:1}}
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
      <div id="challenge-timer" style="margin-top:5px;font-size:14px;color:#444;">Refreshes in: --</div>
    </div>
    <div class="floating-icons">
      <a href="{{ url('/analytics') }}" class="floating-icon" title="Analytics">🔥</a>
      <a href="{{ url('/learning-modules') }}" class="floating-icon" title="Learning Modules">🌱</a>
      <a href="{{ url('/leaderboard') }}" class="floating-icon" title="Leaderboard">🏆</a>
      <a href="{{ url('/badges') }}" class="floating-icon" title="Badges">🥇</a>
      <a href="{{ url('/settings') }}" class="floating-icon" title="Settings">⚙️</a>
    </div>

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

      <button id="submitProof" style="margin-top:20px;padding:10px;cursor:pointer;">✅ Submit Proof</button>
    </div>
  </div>

<script>
  // ---- config ----
  const BASE_URL = "{{ url('') }}";         
  const CSRF     = document.querySelector('meta[name="csrf-token"]').content;

  // Sidebar toggle
  function toggleSidebar() {
    document.querySelector('.sidebar')?.classList.toggle('collapsed');
    document.querySelector('.main-content')?.classList.toggle('expanded');
  }

  let todayChallenges = [];
  let currentChallenge = null;

  function fetchToday() {
    fetch(`${BASE_URL}/api/challenges/today`, {
      headers: { 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(({ challenges, refresh_in_seconds, refresh_at, server_now }) => {
      todayChallenges = challenges || [];
      renderChallenges(todayChallenges);

      if (refresh_at && server_now) {
        startCountdownFromAbsolute(refresh_at, server_now);
      } else {
        startCountdown(Math.max(0, parseInt(refresh_in_seconds, 10) || 0));
      }
    })
    .catch(err => {
      console.error(err);
      startCountdown(0);
    });
  }

  function renderChallenges(list) {
  const grid = document.querySelector(".challenges-grid");
  grid.innerHTML = "";

  list.forEach(ch => {
    let diffHTML = '';
    for (let i = 1; i <= 3; i++) {
      diffHTML += (i <= ch.difficulty)
        ? `<span class="difficulty filled ${ch.difficulty===1?'easy':ch.difficulty===2?'medium':'hard'}"></span>`
        : '<span class="difficulty"></span>';
    }

    const badgeText =
      ch.status === "pending"   ? "Pending"   :
      ch.status === "completed" ? "Completed" : "Not Started";

    const wrap = document.createElement('div');
    wrap.classList.add('challenge-card');
    wrap.innerHTML = `
      <div class="challenge-points">${ch.points}</div>
      <div class="challenge-icon">${ch.icon}</div>
      <div class="challenge-content">
        <div class="difficulty-circles">${diffHTML}</div>
        <h3 class="challenge-title">${ch.title}</h3>
        <p class="challenge-subtitle">${ch.subtitle ?? ''}</p> 
        <p class="challenge-description">${ch.description ?? ''}</p>
        ${ch.status === 'not-started' ? '<button class="challenge-button">Open</button>' : ''}
        <div class="status-badge ${ch.status}">${badgeText}</div>
      </div>
    `;

    const btn = wrap.querySelector('.challenge-button');
    if (btn) {
      btn.addEventListener('click', () => openModal(ch));
    }

    grid.appendChild(wrap);
  });

  }

  function openModal(ch) {
    currentChallenge = ch;
    document.getElementById("modalTitle").textContent = ch.title;
    document.getElementById("modalDescription").textContent = ch.description ?? '';
    document.getElementById("proofUpload").value = "";
    document.getElementById("previewImage").style.display = "none";
    document.getElementById("challengeModal").style.display = "flex";

    const btn = document.getElementById("submitProof");
    btn.textContent = "✅ Submit Proof";

  }

  function closeModal() {
    document.getElementById("challengeModal").style.display = "none";
  }

  document.getElementById("proofUpload").addEventListener("change", function () {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
      const preview = document.getElementById("previewImage");
      preview.src = e.target.result;
      preview.style.display = "block";
    };
    reader.readAsDataURL(file);
  });

  document.getElementById("submitProof").addEventListener("click", function () {
    if (!currentChallenge) return;

    // Upload proof -> pending
    if (currentChallenge.status === 'not-started') {
      const fileInput = document.getElementById("proofUpload");
      if (!fileInput.files || !fileInput.files[0]) {
        alert("Please upload an image as proof.");
        return;
      }
      const form = new FormData();
      form.append('proof', fileInput.files[0]);

      fetch(`${BASE_URL}/api/challenges/${currentChallenge.assignment_id}/submit-proof`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: form
      })
      .then(async r => {
        if (!r.ok) throw new Error(`Upload failed: ${r.status} ${await r.text()}`);
        return r.json();
      })
      .then(() => { closeModal(); fetchToday(); })
      .catch(err => alert(err.message || 'Upload failed.'));
      return;
    }

    // Pending -> completed
    if (currentChallenge.status === 'pending') {
      fetch(`${BASE_URL}/api/challenges/${currentChallenge.assignment_id}/mark-completed`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
      })
      .then(async r => {
        if (!r.ok) throw new Error(`Complete failed: ${r.status} ${await r.text()}`);
        return r.json();
      })
      .then(() => { closeModal(); fetchToday(); })
      .catch(err => alert(err.message || 'Complete failed.'));
    }
  });

  // Countdown (absolute, handles skew)
  function startCountdownFromAbsolute(refreshAtIso, serverNowIso) {
    const el = document.getElementById("challenge-timer");
    const refreshAtMs = new Date(refreshAtIso).getTime();
    const serverNowMs = new Date(serverNowIso).getTime();
    const skewMs = Date.now() - serverNowMs;

    function tick() {
      const nowMs = Date.now() - skewMs;
      let s = Math.max(0, Math.floor((refreshAtMs - nowMs) / 1000));
      const h = String(Math.floor(s / 3600)).padStart(2, '0');
      const m = String(Math.floor((s % 3600) / 60)).padStart(2, '0');
      const sec = String(s % 60).padStart(2, '0');
      el.textContent = `Refreshes in: ${h}:${m}:${sec}`;
    }

    tick();
    clearInterval(window.__countdown);
    window.__countdown = setInterval(tick, 1000);
  }

  // Simple countdown fallback
  function startCountdown(secondsLeft) {
    const el = document.getElementById("challenge-timer");
    let s = Math.max(0, parseInt(secondsLeft, 10) || 0);
    function tick() {
      const h = String(Math.floor(s / 3600)).padStart(2, '0');
      const m = String(Math.floor((s % 3600) / 60)).padStart(2, '0');
      const sec = String(s % 60).padStart(2, '0');
      el.textContent = `Refreshes in: ${h}:${m}:${sec}`;
      if (s > 0) s -= 1;
    }
    tick();
    clearInterval(window.__countdown);
    window.__countdown = setInterval(tick, 1000);
  }

  // init
  fetchToday();
   document.addEventListener('DOMContentLoaded', () => {
    if (localStorage.getItem('darkMode') === 'enabled') {
        document.body.classList.add('dark-mode');
    }
});
</script>



</body>
</html>
