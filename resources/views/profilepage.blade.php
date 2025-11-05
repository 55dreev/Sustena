<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SUSTENA - Profile</title>
  @php
    use Illuminate\Support\Facades\Auth;
    $user = Auth::user();
    $xpTotal  = $user->xp_total ?? null;
    $level    = $user->level ?? null;
    $username = session('username') ?? ($user->name ?? 'Guest');

    $diet          = $user->diet ?? null;
    $transport     = $user->transport ?? null;
    $homeType      = $user->home_type ?? null;
    $energySource  = $user->energy_source ?? null;
    $weeklyTarget  = $user->weekly_target_kg ?? null; // kg CO2 per week
  @endphp

  <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
  <style>
    .alert{margin:8px 0;padding:10px 12px;border-radius:8px;font-size:.9rem}
    .alert.ok{background:#eefdf3;border:1px solid #d6f5df}
    .alert.err{background:#fff0f0;border:1px solid #ffd6d6}

    .modal{display:none;position:fixed;z-index:1000;left:0;top:0;width:100%;height:100%;overflow:auto;background:rgba(0,0,0,.5);justify-content:center;align-items:center}
    .modal-content{background:#fff;padding:20px;width:420px;max-width:92%;border-radius:12px;animation:fadeIn .25s ease}
    @keyframes fadeIn{from{opacity:0;transform:scale(.98)}to{opacity:1;transform:scale(1)}}
    .modal-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px}
    .close-btn{background:none;border:0;font-size:1.5rem;cursor:pointer}
    .modal form{display:flex;flex-direction:column;gap:10px}
    .modal label{font-weight:600;font-size:.92rem}
    .modal input,.modal select{padding:8px;border:1px solid #d9d9d9;border-radius:8px}
    .modal .row{display:grid;grid-template-columns:1fr 1fr;gap:10px}
    .modal .actions{display:flex;gap:8px;margin-top:4px}
    .modal button{border:0;border-radius:8px;padding:10px 12px;cursor:pointer}
    .save-btn{background:#2ecc71;color:#fff}
    .ghost-btn{background:#eef2f6}
    .primary-btn{background:#3498db;color:#fff}

    .progress-bar{width:100%;height:8px;background:#f1f5d0;border-radius:999px;overflow:hidden;margin:10px 0}
    .progress-fill{height:100%;background:linear-gradient(90deg,#fffacd,#ffeb3b);width:0%}
    .xp-row{display:flex;gap:8px;align-items:center;flex-wrap:wrap}
    .xp-pill{background:#eef6ee;padding:4px 8px;border-radius:999px;font-size:.85rem}
    .muted{opacity:.7;font-size:.9rem}

    .quick-stats{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px;margin:14px 0}
    .qstat{background:#f6fff6;border:1px solid #e3f3e3;border-radius:12px;padding:10px 12px}
    .qtitle{font-size:.85rem;opacity:.8}
    .qvalue{font-weight:700;font-size:1.1rem}

    .status-icon{margin-left:6px}
    .chip{display:inline-block;background:#f0f3ff;border:1px solid #e3e7ff;border-radius:999px;padding:4px 8px;font-size:.82rem;margin:4px 6px 0 0}
    .chip.bad{background:#fff0f0;border-color:#ffd6d6}
    .chip.good{background:#eefdf3;border-color:#d6f5df}
  </style>
</head>
<body>

<div class="sidebar" id="sidebar">
  <div class="sidebar-toggle" onclick="toggleSidebar()">☰</div>
  <div class="logo"><div class="logo-icon">🌱</div><div class="logo-text">SUSTENA</div></div>
  <a href="{{ url('/landing-page') }}" class="nav-item"><div class="nav-icon">🏠</div><span>Home</span></a>
  <a href="{{ url('/footprint-calculator') }}" class="nav-item"><div class="nav-icon">👣</div><span>Footprint Tracker</span></a>
  <a href="{{ url('/learning-modules') }}" class="nav-item"><div class="nav-icon">📚</div><span>Learn</span></a>
  <a href="{{ url('/challenge') }}" class="nav-item"><div class="nav-icon">🏆</div><span>Challenges</span></a>
  <a href="{{ url('/forum') }}" class="nav-item"><div class="nav-icon">💬</div><span>MicroForum</span></a>
  <a href="{{ route('profile') }}" class="nav-item active"><div class="nav-icon">👤</div><span>Profile</span></a>
</div>

<div class="floating-icons">
  <a href="{{ route('analytics') }}" class="floating-icon" title="Analytics">🔥</a>
  <a href="{{ route('learning-modules') }}" class="floating-icon" title="Learning Modules">🌱</a>
  <a href="{{ route('leaderboard') }}" class="floating-icon" title="Leaderboard">🏆</a>
  <a href="{{ route('badges') }}" class="floating-icon" title="Badges">🥇</a>
  <a href="{{ route('settings') }}" class="floating-icon" title="Settings">⚙️</a>
</div>

<div class="main-content"
     data-xp="{{ $xpTotal !== null ? (int)$xpTotal : '' }}"
     data-level="{{ $level !== null ? (int)$level : '' }}"
     data-target="{{ $weeklyTarget !== null ? (int)$weeklyTarget : '' }}"
>
  {{-- flash messages --}}
  @if(session('ok'))   <div class="alert ok">{{ session('ok') }}</div> @endif
  @if(session('err'))  <div class="alert err">{{ session('err') }}</div> @endif

<div class="profile-header">
  <div class="profile-content">
    <div class="profile-avatar">👤</div>
    <div class="profile-info">
      <h1 class="profile-name">{{ $username }} [Ecosaver]</h1>

      <div class="xp-row" id="xpSummary">
        <span class="xp-pill">Level: <strong id="levelText">{{ $level ?? '—' }}</strong></span>
        <span class="xp-pill">XP: <strong id="xpText">{{ $xpTotal ?? '—' }}</strong></span>
        <span class="muted" id="xpNextLabel">—</span>
      </div>
      <div class="progress-bar" title="XP towards next level">
        <div class="progress-fill" id="xpProgress" style="width:0%"></div>
      </div>

      <div style="display:flex;gap:10px;align-items:center;margin-top:8px;">
        <button class="edit-profile-btn" id="openModalBtn">Edit Profile</button>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="edit-profile-btn" style="background:#e74c3c;">Logout</button>
        </form>
      </div>

      {{-- Energy (month + vs last) --}}
      @php
        $signedMonth = $energy_change_kwh_signed ?? 0.0; // + saved, - up
        $savedMonth  = $energy_saved_kwh ?? 0.0;
        $isUpMonth   = $signedMonth < 0;
        $dir         = $energy_delta_direction ?? 'flat'; // 'up' | 'down' | 'flat'
        $vsAbs       = isset($energy_delta_kwh_abs) ? (float)$energy_delta_kwh_abs : 0.0;
      @endphp

      <div class="quick-stats" id="quickStats">
        <div class="qstat">
          <div class="qtitle">Community Rank</div>
          <div class="qvalue" id="qsRank">—</div>
        </div>

        <div class="qstat">
          <div class="qtitle">Day Streak</div>
          <div class="qvalue" id="qsStreak">—</div>
        </div>

        <div class="qstat">
          <div class="qtitle">Energy (mo)</div>
          <div class="qvalue" id="qsEnergy">
            @if($isUpMonth)
              {{ number_format(abs($signedMonth), 1) }} kWh up
            @else
              {{ number_format($savedMonth, 1) }} kWh saved
            @endif
          </div>
          <div class="muted" style="font-size:.8rem;line-height:1.2;">
            @if($dir === 'up')
              ↑ {{ number_format($vsAbs, 1) }} kWh vs last
            @elseif($dir === 'down')
              ↓ {{ number_format($vsAbs, 1) }} kWh vs last
            @else
              — vs last
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


  <!-- Edit Profile Modal -->
  <div class="modal" id="editProfileModal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Edit Profile</h2>
        <button class="close-btn" id="closeModalBtn">&times;</button>
      </div>

      <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        <label for="username">Username</label>
        <input type="text" id="username" name="username" value="{{ session('username') ?? ($user->name ?? '') }}">

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ $user->email ?? '' }}" required>

        <div class="row">
          <div>
            <label for="diet">Diet</label>
            <select id="diet" name="diet">
              @foreach(['Vegan','Vegetarian','Omnivore'] as $opt)
                <option value="{{ $opt }}" {{ ($diet===$opt)?'selected':'' }}>{{ $opt }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label for="transport">Transport</label>
            <select id="transport" name="transport">
              @foreach(['Bike + Public Transport','Car','Walking'] as $opt)
                <option value="{{ $opt }}" {{ ($transport===$opt)?'selected':'' }}>{{ $opt }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="row">
          <div>
            <label for="home_type">Home</label>
            <select id="home_type" name="home_type">
              @foreach(['Apartment','Detached House','Shared Dorm'] as $opt)
                <option value="{{ $opt }}" {{ ($homeType===$opt)?'selected':'' }}>{{ $opt }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label for="energy_source">Energy</label>
            <select id="energy_source" name="energy_source">
              @foreach(['Renewable','Grid Mix','Mostly Fossil'] as $opt)
                <option value="{{ $opt }}" {{ ($energySource===$opt)?'selected':'' }}>{{ $opt }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <label for="weekly_target_kg">Weekly Target (kg CO₂)</label>
        <input type="number" id="weekly_target_kg" name="weekly_target" min="0" step="1"
               value="{{ $weeklyTarget !== null ? (int)$weeklyTarget : 100 }}">

        <div class="actions">
          <button type="submit" class="save-btn">Save Changes</button>

          {{-- plain POST auto-fill; page will refresh with values --}}
          <form method="POST" action="{{ route('profile.autofill') }}" style="display:inline">
            @csrf
            <button type="submit" class="ghost-btn" title="Use latest calculator answers">Use latest calculator answers</button>
          </form>
        </div>
      </form>
    </div>
  </div>

  <div class="content-layout">
    <div class="left-content">
      <div class="lifestyle-card">
        <div class="card-header"><div class="card-icon">🌿</div><div class="card-title">Lifestyle Info</div></div>

        <div class="lifestyle-item">
          <div class="lifestyle-icon">🥗</div>
          <div class="lifestyle-text"><div class="lifestyle-label">Diet</div><div class="lifestyle-value" id="dietValue">{{ $diet ?? '—' }}</div></div>
        </div>

        <div class="lifestyle-item">
          <div class="lifestyle-icon">🚲</div>
          <div class="lifestyle-text"><div class="lifestyle-label">Transport</div><div class="lifestyle-value" id="transportValue">{{ $transport ?? '—' }}</div></div>
        </div>

        <div class="lifestyle-item">
          <div class="lifestyle-icon">🏠</div>
          <div class="lifestyle-text"><div class="lifestyle-label">Home</div><div class="lifestyle-value" id="homeValue">{{ $homeType ?? '—' }}</div></div>
        </div>

        <div class="lifestyle-item">
          <div class="lifestyle-icon">⚡</div>
          <div class="lifestyle-text"><div class="lifestyle-label">Energy</div><div class="lifestyle-value" id="energyValue">{{ $energySource ?? '—' }}</div></div>
        </div>

        <div style="margin-top:8px;"><span class="muted">Tip: you can also auto-fill from your latest calculator run.</span></div>
      </div>

      <div class="carbon-card">
        <div class="card-header"><div class="card-icon">📊</div><div class="card-title">Carbon Stats</div></div>

        <div class="carbon-main">
          <div class="carbon-total" id="totalYearly">Total CO₂: —</div>
          <div class="carbon-subtitle" id="totalDelta">(—)</div>
        </div>

        <div class="carbon-item" id="bestCat">
          <div class="carbon-label">🏆 Best:<span class="status-icon">✅</span></div>
          <div class="carbon-value"></div>
        </div>

        <div class="carbon-item" id="improveCat">
          <div class="carbon-label">⚠️ Improve:<span class="status-icon">🔄</span></div>
          <div class="carbon-value"></div>
        </div>

        <div id="recChips" style="margin-top:6px;"></div>
      </div>
    </div>

    <div class="right-content">
      <div class="impact-card">
        <div class="card-header"><div class="card-icon">🌍</div><div class="card-title">Your Impact</div></div>

        <div class="impact-main">
          <div class="impact-title">Your Footprint Score</div>
          <div class="impact-value" id="impactValue">{{ session('footprint_score') ?? 'No score yet' }}</div>

          <div class="progress-bar">
            <div class="progress-fill" id="impactProgress" style="width: {{ session('footprint_score') ? min(session('footprint_score'),100).'%' : '0%' }};"></div>
          </div>

          <div class="progress-text" id="impactText">
            @if(session('footprint_score'))
              {{ session('footprint_score') }} kg CO₂ — your current impact level
            @else
              Start by taking the footprint calculator!
            @endif
          </div>

          <form method="GET" action="{{ url('/footprint-calculator') }}">
            <button type="submit" class="get-score-btn">Get a New Score</button>
          </form>
        </div>
      </div>

      <div class="achievements-card">
        <div class="card-header"><div class="card-icon">🎯</div><div class="card-title">Personal Target</div></div>
        <div class="achievement-badges" style="justify-content:flex-start;gap:10px;flex-wrap:wrap;">
          <span class="chip">Target: <strong id="targetLabel">{{ $weeklyTarget !== null ? (int)$weeklyTarget : 100 }}</strong> kg/wk</span>
          <span class="chip good" id="onTrackChip" style="display:none;">On track</span>
          <span class="chip bad" id="offTrackChip" style="display:none;">Above target</span>
        </div>

        <div style="margin-top:10px;">
          <div class="progress-bar" title="Progress vs target">
            <div class="progress-fill" id="targetProgress" style="width:0%"></div>
          </div>
          <div class="muted" id="targetText">—</div>
        </div>

        <div class="achievement-level" style="margin-top:12px;">
          <div class="level-text" id="levelLabel">Level — Eco Champion</div>
          <div class="challenges-joined">Joined: 3 Challenges</div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  const modal = document.getElementById('editProfileModal');
  const openModalBtn = document.getElementById('openModalBtn');
  const closeModalBtn = document.getElementById('closeModalBtn');
  if (openModalBtn) openModalBtn.addEventListener('click', () => modal.style.display = 'flex');
  if (closeModalBtn) closeModalBtn.addEventListener('click', () => modal.style.display = 'none');
  window.addEventListener('click', (e) => { if (e.target === modal) modal.style.display = 'none'; });

  function toggleSidebar(){ const s=document.querySelector('.sidebar'), m=document.querySelector('.main-content'); s.classList.toggle('collapsed'); m.classList.toggle('expanded'); }

  function levelThresholdTotal(level){ if (level<=1) return 0; let sum=0; for(let k=1;k<level;k++) sum+=Math.round(100*Math.pow(k,1.15)); return sum; }
  function renderXp(){
    const root=document.querySelector('.main-content');
    const xp=root.getAttribute('data-xp'), lvl=root.getAttribute('data-level');
    const xpText=document.getElementById('xpText'), levelText=document.getElementById('levelText');
    const xpNextLabel=document.getElementById('xpNextLabel'), xpProgress=document.getElementById('xpProgress');
    const levelLabel=document.getElementById('levelLabel');

    if(!xp || !lvl){ xpText.textContent='—'; levelText.textContent='—'; xpNextLabel.textContent='Log an official attempt to earn XP!'; xpProgress.style.width='0%'; levelLabel.textContent='Level — Eco Champion'; return; }
    const xpTotal=parseInt(xp,10), level=parseInt(lvl,10);
    xpText.textContent=xpTotal; levelText.textContent=level;
    const currMin=levelThresholdTotal(level), nextMin=levelThresholdTotal(level+1);
    const span=Math.max(1,nextMin-currMin), into=Math.max(0,xpTotal-currMin);
    xpProgress.style.width=Math.max(0,Math.min(100,(into/span)*100)).toFixed(0)+'%';
    xpNextLabel.textContent=`Next level in ${Math.max(0,nextMin-xpTotal)} XP`;
    levelLabel.textContent=`Level ${level} Eco Champion`;
  }

  function fmt(n,d=1){ if(n===null||n===undefined||isNaN(n)) return '—'; const a=Math.abs(n); if(a>=1000) return n.toFixed(0); return n.toFixed(d); }

  let latestWeekly = null;
  function loadFootprint(){
    fetch('/analytics/summary?limit=5',{headers:{'Accept':'application/json'}})
      .then(r=>r.ok?r.json():null)
      .then(d=>{
        const totalYearlyEl=document.getElementById('totalYearly');
        const totalDeltaEl=document.getElementById('totalDelta');
        const bestCatEl=document.getElementById('bestCat');
        const improveCatEl=document.getElementById('improveCat');
        const recChips=document.getElementById('recChips');

        if(!d || !d.has_data){ totalYearlyEl.textContent='Total CO₂: —'; totalDeltaEl.textContent='(—)'; return; }

        const weekly=d.headline.kg_per_week ?? d.headline.total ?? 0;
        latestWeekly = weekly;
        totalYearlyEl.textContent=`Total CO₂: ${fmt(weekly*52.1429,1)} kg/year`;
        const delta=d.headline.delta_pct; totalDeltaEl.textContent=`(${delta>0?'+':''}${delta ?? 0}%)`;

        if(Array.isArray(d.cards)&&d.cards.length){
          const withKg=d.cards.map(c=>({title:c.title,kg:c.kg_per_week ?? c.total ?? 0}));
          withKg.sort((a,b)=>a.kg-b.kg);
          const best=withKg[0], worst=withKg[withKg.length-1];
          bestCatEl.querySelector('.carbon-value').textContent=`(${fmt(best.kg)} kg/wk)`;
          bestCatEl.querySelector('.carbon-label').childNodes[0].nodeValue='🏆 Best: '+best.title+' ';
          improveCatEl.querySelector('.carbon-value').textContent=`(${fmt(worst.kg)} kg/wk)`;
          improveCatEl.querySelector('.carbon-label').childNodes[0].nodeValue='⚠️ Improve: '+worst.title+' ';
          recChips.innerHTML=''; withKg.slice(-3).forEach(x=>{ const el=document.createElement('span'); el.className='chip bad'; el.textContent='Focus: '+x.title; recChips.appendChild(el); });
        }

        const impactValue=document.getElementById('impactValue');
        const impactProgress=document.getElementById('impactProgress');
        const impactText=document.getElementById('impactText');
        impactValue.textContent=fmt(weekly,1)+' kg CO₂ / wk';
        impactProgress.style.width=Math.max(0,Math.min(100,(weekly/100)*100))+'%';
        impactText.textContent=`${fmt(weekly,1)} kg CO₂ per week — your current impact level`;

        renderTargetProgress();
      }).catch(()=>{});
  }

  function renderTargetProgress(){
    const root=document.querySelector('.main-content');
    const target = parseInt(root.getAttribute('data-target') || '0',10) || 100;
    const label=document.getElementById('targetLabel');
    const bar=document.getElementById('targetProgress');
    const txt=document.getElementById('targetText');
    const onChip=document.getElementById('onTrackChip');
    const offChip=document.getElementById('offTrackChip');

    label.textContent = target;
    if(latestWeekly===null){ bar.style.width='0%'; txt.textContent='—'; onChip.style.display='none'; offChip.style.display='none'; return; }

    const pct = Math.max(0, Math.min(100, (latestWeekly/Math.max(1,target))*100));
    bar.style.width = pct.toFixed(0)+'%';
    const diff = target - latestWeekly;
    txt.textContent = (diff>=0) ? `Nice! ${fmt(Math.abs(diff),1)} kg/wk below your target.` : `You’re ${fmt(Math.abs(diff),1)} kg/wk above target.`;
    onChip.style.display = diff>=0 ? '' : 'none';
    offChip.style.display = diff<0 ? '' : 'none';
  }

 function loadQuickStats(){
  fetch('/me/summary',{headers:{'Accept':'application/json'}})
    .then(r=>r.ok?r.json():null)
    .then(d=>{
      document.getElementById('qsRank').textContent   = (d && d.rank_text) ? d.rank_text : '—';
      document.getElementById('qsStreak').textContent = (d && d.streak_days!=null) ? (d.streak_days+' days') : '—';

      // Energy: only update if the API provides the signed + saved + direction fields
      if (d && (d.energy_change_kwh_signed !== undefined) && (d.energy_saved_kwh !== undefined)) {
        const signed = Number(d.energy_change_kwh_signed) || 0;
        const saved  = Number(d.energy_saved_kwh) || 0;
        const dir    = d.energy_delta_direction || 'flat';
        const vsAbs  = Number(d.energy_delta_kwh_abs || 0);

        const el = document.getElementById('qsEnergy');
        el.textContent = (signed < 0)
          ? `${Math.abs(signed).toFixed(1)} kWh up`
          : `${saved.toFixed(1)} kWh saved`;

        // Optional: if you want to also update the tiny "vs last" line rendered in Blade:
        const vsNode = el.parentElement.querySelector('.muted');
        if (vsNode) {
          vsNode.textContent =
            dir === 'up'   ? `↑ ${vsAbs.toFixed(1)} kWh vs last` :
            dir === 'down' ? `↓ ${vsAbs.toFixed(1)} kWh vs last` :
                             '— vs last';
        }
      }
    }).catch(()=>{});
}


  fetch('/me/xp').then(r=>r.ok?r.json():null).then(d=>{
    const root=document.querySelector('.main-content');
    if(d && (d.xp!==undefined) && (d.level!==undefined)){
      root.setAttribute('data-xp', d.xp);
      root.setAttribute('data-level', d.level);
    }
    renderXp();
  }).catch(()=>renderXp());

  loadFootprint();
  loadQuickStats();
</script>
</body>
</html>
