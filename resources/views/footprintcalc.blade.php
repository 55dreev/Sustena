<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>SUSTENA - Footprint Tracker</title>
<link rel="stylesheet" href="{{ asset('css/footprintcalc.css') }}">
<link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">


</head>

<body class="footprint-calc-page">

  <div class="mobile-hamburger" onclick="toggleMobileSidebar()">☰</div>

<div class="sidebar" id="sidebar">
   

    <div class="logo">
        <div class="logo-icon">🌱</div>
        <div class="logo-text">SUSTENA</div>
    </div>
    <a href="{{ url('/landing-page') }}" class="nav-item">
        <div class="nav-icon">🏠</div>
        <span>Home</span>
    </a>
    <a href="{{ url('/footprint-calculator') }}" class="nav-item active">
        <div class="nav-icon">👣</div>
        <span>Footprint Tracker</span>
    </a>
    <a href="{{ url('/learning-modules') }}" class="nav-item">
        <div class="nav-icon">📚</div>
        <span>Learn</span>
    </a>
    <a href="{{ url('/challenge') }}" class="nav-item">
        <div class="nav-icon">🏆</div>
        <span>Challenges</span>
    </a>
    <a href="{{ url('/forum') }}" class="nav-item">
        <div class="nav-icon">💬</div>
        <span>MicroForum</span>
    </a>
    <a href="{{ url('/profile') }}" class="nav-item">
        <div class="nav-icon">👤</div>
        <span>Profile</span>
    </a>
</div>

<div class="main-content" id="mainContent">
    <div class="floating-icons">
        <a href="{{ route('analytics') }}" class="floating-icon" title="Analytics">🔥</a>
        <a href="{{ route('learning-modules') }}" class="floating-icon" title="Learning Modules">🌱</a>
        <a href="{{ route('leaderboard') }}" class="floating-icon" title="Leaderboard">🏆</a>
        <a href="{{ route('badges') }}" class="floating-icon" title="Badges">🥇</a>
        <a href="{{ route('settings') }}" class="floating-icon" title="Settings">⚙️</a>
    </div>

    <!-- Toast -->
    <div id="xpToast" style="display:none;position:fixed;right:20px;bottom:20px;background:#111;color:#fff;padding:12px 14px;border-radius:10px;box-shadow:0 8px 24px rgba(0,0,0,.2);z-index:9999;transition:opacity .25s ease;">
      <div id="xpToastText">—</div>
    </div>

    <div class="calculator-container">
        <div class="calculator-header">
            <h1 class="calculator-title">CARBON FOOTPRINT CALCULATOR</h1>
            <p class="calculator-subtitle">" MEASURE YOUR IMPACT ON THE PLANET! "</p>
            <div class="progress-bar" style="position: relative;">
                <div class="progress-fill" id="progressFill"></div>
            </div>

            <div id="quizContainer" class="form-section"></div>

         <div class="farm-row">
    <div class="farm-decor cow">🐄</div>
    <div class="farm-decor chicken">🐓</div>
    <div class="farm-decor tree">🌳</div>
    <div class="farm-decor barn">🏠</div>
</div>

            <div class="result-display" id="resultDisplay">
                <div class="result-text">Your estimated weekly carbon footprint:</div>
                <div class="carbon-value" id="carbonValue">0.0</div>
                <div class="carbon-unit">kg CO₂</div>

                <form id="saveScoreForm" method="POST" action="{{ url('/save-footprint-score') }}">
                  @csrf
                  <input type="hidden" name="score" id="scoreInput">
                  <input type="hidden" name="attempt_id" id="attemptInput">
                  <button type="submit" class="submit-btn">Save Score to Profile</button>
                </form>

                <button type="button" class="submit-btn" onclick="restartQuiz()" style="background-color:#777; margin-top:10px;">
                    Restart
                </button>
            </div>
        </div>
    </div>
</div>

<script>
/* ------- small helpers ------- */
function toggleSidebar(){
  const s = document.getElementById('sidebar');
  const m = document.getElementById('mainContent');
  if (!s || !m) return;
  s.classList.toggle('collapsed');
  m.classList.toggle('expanded');
}

(function attachToast(){
  // Simple toast with auto-hide. Falls back to alert if container missing.
  window.toast = function(message, type='info'){
    const el  = document.getElementById('xpToast');
    const txt = document.getElementById('xpToastText');
    if(!el || !txt){ alert(message); return; }
    txt.textContent = message;
    el.style.background = (type==='error') ? '#e74c3c' :
                          (type==='success') ? '#2ecc71' : '#111';
    el.style.display = 'block';
    el.style.opacity = '1';
    clearTimeout(window.__xpToastTimer);
    window.__xpToastTimer = setTimeout(()=>{
      el.style.opacity = '0';
      setTimeout(()=>{ el.style.display='none'; el.style.opacity='1'; }, 250);
    }, 3000);
  };
})();

function showBadgesPopup(badges){
  // create container if missing
  let el = document.getElementById('badgePopup');
  if(!el){
    el = document.createElement('div');
    el.id = 'badgePopup';
    el.style.cssText = `
      position:fixed; right:20px; bottom:80px; z-index:10000;
      background:#fff; color:#2d2d2d; border-radius:12px; box-shadow:0 12px 30px rgba(0,0,0,.2);
      padding:14px 16px; max-width:320px; font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu;
    `;
    document.body.appendChild(el);
  }
  const items = badges.map(b => `
    <div style="display:flex;align-items:center;gap:10px;margin:6px 0;">
      <div style="width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:#4CAF50;color:#fff;">🏅</div>
      <div style="flex:1;">
        <div style="font-weight:700;">${b.name || b.slug}</div>
        ${b.points_reward ? `<div style="font-size:12px;opacity:.75;">+${b.points_reward} pts</div>` : ''}
      </div>
    </div>
  `).join('');
  el.innerHTML = `
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;">
      <div style="font-weight:800;">New Badge${badges.length>1?'s':''} Unlocked!</div>
      <button onclick="this.closest('#badgePopup').remove()" style="border:none;background:transparent;font-size:18px;cursor:pointer;">×</button>
    </div>
    ${items}
  `;

  // auto-hide after 4s
  clearTimeout(window.__badgePopupTimer);
  window.__badgePopupTimer = setTimeout(() => {
    el.style.transition = 'opacity .25s ease, transform .25s ease';
    el.style.opacity = '0';
    el.style.transform = 'translateY(8px)';
    setTimeout(()=> el.remove(), 250);
  }, 4000);
}

/** =========================================================
 *  NORMALIZATION + EVIDENCE-BASED EMISSION FACTORS (kgCO2e)
 *  ====================================================== */
const ANALYTICS_BASIS = 'weekly';

const eventsPerWeek = {
  daily: 7,
  'few-times-week': 3.5,
  weekly: 1,
  monthly: 1 / 4.345,
  rarely: 0.25,
  never: 0
};
function toBasis(eventsPerWeekValue, basis = ANALYTICS_BASIS) {
  switch (basis) {
    case 'daily':   return eventsPerWeekValue / 7;
    case 'weekly':  return eventsPerWeekValue;
    case 'monthly': return eventsPerWeekValue * 4.345;
    case 'yearly':  return eventsPerWeekValue * 52.1429;
    default:        return eventsPerWeekValue;
  }
}

/** Factors & assumptions (unchanged) */
const FACTORS = {
  food_kg: {
    beef: 60, chicken: 6, pork: 7, fish: 5, eggs: 4.8, milk_l: 3.15, cheese: 21,
    tofu: 3, legumes: 0.9, processed_generic: 6, organic_bonus: -0.2, local_bonus: -0.1
  },
  electricity: { grid_PH_kg_per_kWh: 0.67 },
  transport: { car_avg_kg_per_km: 0.171, rail_kg_per_pkm: 0.035, bus_kg_per_pkm: 0.103 },
  flights: { one_way_shorthaul_kg: 250, one_way_longhaul_kg: 750 },
  water_waste: { shower_kg_per_min: 0.05, laundry_kg_per_load: 0.6, dishwasher_kg_per_run: 0.5 }
   ,waste: {
    kg_per_trash_bag: 3.0,                 // kg CO2e per full bag to landfill
    single_use_plastic_kg_per_event: 0.2,  // per single-use “event”

    recycle_saving_always:   -1.5,         // weekly savings
    recycle_saving_sometimes:-0.5,

    compost_saving_always:   -1.5,
    compost_saving_sometimes:-0.5,

    donate_saving_always:    -0.5,  
    donate_saving_sometimes: -0.2,

    minimal_pack_saving_always: -0.3,
    minimal_pack_saving_sometimes: -0.1,

    reusable_bag_saving_always: -0.2,
    reusable_bag_saving_sometimes: -0.05,

    battery_bulb_recycle_saving_always: -0.3,
    battery_bulb_recycle_sometimes:     -0.1,

    second_hand_saving_always: -0.5,
    second_hand_saving_sometimes: -0.2,

    e_waste_kg_per_event: 5.0             // per electronics disposal event
  }
};
const ASSUMPTIONS = {
  portions: { meat_g:150, cheese_g:30, milk_ml:250, eggs_per_serving:2, tofu_g:150, fish_g:150, legumes_g:150 },
  avg_drive_speed_kmh: 25,
  commute_km_midpoints: { '0 - 5 km':2.5,'5 - 10 km':7.5,'10 - 20 km':15,'20 - 50 km':35,'50+ km':60 },
};

/** Attempt tracking */
const categoryTotals = {};
const categoryCounts = {};
let attemptId;
function newAttemptId() {
  attemptId = (crypto && crypto.randomUUID) ? crypto.randomUUID() : String(Date.now());
}
newAttemptId();

/** BG helpers (unchanged) */
function getDayCycleBackground(progress) {
  if (progress < 0.25) return "linear-gradient(to top, #fff1c1 0%, #a8edea 100%)";
  if (progress < 0.5)  return "linear-gradient(to top, #89f7fe 0%, #66a6ff 100%)";
  if (progress < 0.75) return "linear-gradient(to top, #f6d365 0%, #fda085 100%)";
  return "linear-gradient(to top, #2c3e50 0%, #4ca1af 100%)";
}
function updateDayCycle(progress) {
  const container = document.querySelector('.calculator-container');
  const newLayer = document.createElement('div');
  newLayer.classList.add('gradient-layer', 'inactive');
  newLayer.style.background = getDayCycleBackground(progress);
  container.appendChild(newLayer);
  requestAnimationFrame(() => newLayer.classList.replace('inactive', 'active'));
  const oldLayers = container.querySelectorAll('.gradient-layer:not(:last-child)');
  setTimeout(() => oldLayers.forEach(l => l.remove()), 4000);
}

/** Questions (unchanged) */
const frequencyOptions = ['daily','few-times-week','weekly','monthly','rarely','never'];
const questions = {
  'Food': [
    {id:'dairyMilk',text:'How often do you drink milk?',options:frequencyOptions},
    {id:'dairyCheese',text:'How often do you eat cheese or yogurt?',options:frequencyOptions},
    {id:'meatBeef',text:'How often do you eat beef?',options:frequencyOptions},
    {id:'meatChickenPork',text:'How often do you eat chicken or pork?',options:frequencyOptions},
    {id:'fish',text:'How often do you eat fish or seafood?',options:frequencyOptions},
    {id:'eggs',text:'How often do you eat eggs?',options:frequencyOptions},
    {id:'plantProtein',text:'How often do you eat plant-based protein?',options:frequencyOptions},
    {id:'processedFoods',text:'How often do you eat processed foods?',options:frequencyOptions},
    {id:'organicFoods',text:'How often do you eat organic foods?',options:frequencyOptions},
    {id:'localFoods',text:'How often do you eat locally-sourced foods?',options:frequencyOptions},
  ],
  'Transportation': [
    {id:'transport',text:'How often do you use a car or motorcycle?',options:frequencyOptions},
    {id:'gasSpend',text:'How much money do you spend on gas per week?',options:['₱0 - ₱500','₱500 - ₱1000','₱1000 - ₱2000','₱2000 - ₱5000','₱5000+']},
    {id:'driveTime',text:'How many hours do you drive per week?',options:['0 - 2 hours','3 - 5 hours','6 - 8 hours','9 - 12 hours','12+ hours']},
    {id:'publicTransport',text:'How often do you use public transportation?',options:frequencyOptions},
    {id:'rideSharing',text:'How often do you use ride sharing services?',options:frequencyOptions},
    {id:'bikeWalk',text:'How often do you bike or walk instead of driving?',options:frequencyOptions},
    {id:'flights',text:'How many flights do you take per year?',options:['0','1 - 2','3 - 5','6 - 10','10+']},
    {id:'commuteDistance',text:'Average distance of daily commute',options:['0 - 5 km','5 - 10 km','10 - 20 km','20 - 50 km','50+ km']},
    {id:'electricVehicle',text:'Do you use an electric/hybrid vehicle?',options:['Yes','No']},
    {id:'carpool',text:'How often do you carpool?',options:frequencyOptions},
  ],
  'Energy': [
    {id:'energySource',text:'What is your main source of electricity?',options:['Coal','Mixed','Renewable']},
    {id:'electricBill',text:'How much is your average monthly electric bill?',options:['₱0 - ₱1000','₱1000 - ₱2000','₱2000 - ₱3000','₱3000 - ₱4000','₱4000+']},
    {id:'applianceUsage',text:'How many hours do you use air conditioning or fans per day?',options:['0 - 2 hours','3 - 4 hours','5 - 6 hours','7 - 8 hours','8+ hours']},
    {id:'solarPanels',text:'Do you have solar panels at home?',options:['Yes','No']},
    {id:'energySaving',text:'Do you use energy-saving lightbulbs?',options:['Always','Sometimes','Never']},
    {id:'unplugDevices',text:'Do you unplug unused devices?',options:['Always','Sometimes','Never']},
    {id:'efficientAppliances',text:'Do you use energy-efficient appliances?',options:['Yes','No']},
    {id:'smartThermostat',text:'Do you use smart thermostats or timers?',options:['Yes','No']},
    {id:'coldWash',text:'How often do you wash clothes with cold water?',options:frequencyOptions},
    {id:'checkLeaks',text:'Do you regularly check for energy leaks?',options:['Always','Sometimes','Never']},
  ],
  'Water Usage': [
    {id:'showerLength',text:'How long are your showers on average?',options:['0 - 3 mins','4 - 6 mins','7 - 9 mins','10 - 12 mins','12+ mins']},
    {id:'laundry',text:'How many times do you do laundry per week?',options:['0 - 1 times','2 - 3 times','4 times','5 - 6 times','7+ times']},
    {id:'dishwashing',text:'How many times do you run the dishwasher per week?',options:['0 - 1 times','2 times','3 - 4 times','5 - 6 times','7+ times']},
    {id:'leaks',text:'Do you regularly check for water leaks?',options:['Always','Sometimes','Never']},
    {id:'gardenWatering',text:'How often do you water your garden?',options:frequencyOptions},
    {id:'lowFlowShower',text:'Do you use a low-flow showerhead?',options:['Yes','No']},
    {id:'rainwater',text:'Do you collect rainwater for garden use?',options:['Yes','No']},
    {id:'reuseGreywater',text:'Do you reuse greywater for plants?',options:['Yes','No']},
    {id:'bathInsteadShower',text:'How often do you take baths instead of showers?',options:frequencyOptions},
    {id:'turnOffTap',text:'Do you turn off the tap while brushing teeth?',options:['Always','Sometimes','Never']},
    {id:'waterEfficientAppliances',text:'Do you use water-efficient appliances?',options:['Yes','No']},
  ],
  'Waste Management': [
    {id:'trashBags',text:'How many bags of trash do you produce per week?',options:['0 to 1 bags','2 to 3 bags','4 to 5 bags','6 to 7 bags','8+ bags']},
    {id:'recycle',text:'How often do you recycle plastics, paper, or metals?',options:['Always','Sometimes','Never']},
    {id:'plasticUsage',text:'How often do you use single-use plastic items?',options:frequencyOptions},
    {id:'compost',text:'Do you compost organic waste?',options:['Always','Sometimes','Never']},
    {id:'electronicsWaste',text:'How often do you dispose electronic waste?',options:['Monthly','Quarterly','Yearly','Every few years','Never']},
    {id:'donateItems',text:'Do you donate or sell unused items instead of throwing them away?',options:['Always','Sometimes','Never']},
    {id:'minimalPackaging',text:'Do you purchase products with minimal packaging?',options:['Always','Sometimes','Never']},
    {id:'reusableBags',text:'Do you use reusable shopping bags?',options:['Always','Sometimes','Never']},
    {id:'recycleBatteries',text:'Do you recycle batteries and lightbulbs?',options:['Always','Sometimes','Never']},
    {id:'secondHand',text:'Do you buy second-hand or refurbished products?',options:['Always','Sometimes','Never']},
  ]
};

/** Compute emissions (unchanged logic) */
function computeEmission(qid, option) {
  const evPerWeek = eventsPerWeek[option] ?? null;

  // FOOD
  if (qid === 'meatBeef') {
    const kgPerServing = ASSUMPTIONS.portions.meat_g / 1000;
    return toBasis((evPerWeek ?? 0) * kgPerServing * FACTORS.food_kg.beef);
  }
  if (qid === 'meatChickenPork') {
    const kgPerServing = ASSUMPTIONS.portions.meat_g / 1000;
    const perKg = (FACTORS.food_kg.chicken + FACTORS.food_kg.pork) / 2;
    return toBasis((evPerWeek ?? 0) * kgPerServing * perKg);
  }
  if (qid === 'fish') {
    const kgPerServing = ASSUMPTIONS.portions.fish_g / 1000;
    return toBasis((evPerWeek ?? 0) * kgPerServing * FACTORS.food_kg.fish);
  }
  if (qid === 'dairyMilk') {
    const litersPerGlass = ASSUMPTIONS.portions.milk_ml / 1000;
    return toBasis((evPerWeek ?? 0) * litersPerGlass * FACTORS.food_kg.milk_l);
  }
  if (qid === 'dairyCheese') {
    const kg = ASSUMPTIONS.portions.cheese_g / 1000;
    return toBasis((evPerWeek ?? 0) * kg * FACTORS.food_kg.cheese);
  }
  if (qid === 'eggs') {
    const kg = (ASSUMPTIONS.portions.eggs_per_serving * 60) / 1000;
    return toBasis((evPerWeek ?? 0) * kg * FACTORS.food_kg.eggs);
  }
  if (qid === 'plantProtein') {
    const kg = ASSUMPTIONS.portions.tofu_g / 1000;
    return toBasis((evPerWeek ?? 0) * kg * FACTORS.food_kg.tofu);
  }
  if (qid === 'processedFoods') {
    const kg = 0.15;
    return toBasis((evPerWeek ?? 0) * kg * FACTORS.food_kg.processed_generic);
  }
  if (qid === 'organicFoods') {
    const kg = 0.5;
    return toBasis((evPerWeek ?? 0) * kg * FACTORS.food_kg.organic_bonus);
  }
  if (qid === 'localFoods') {
    const kg = 0.5;
    return toBasis((evPerWeek ?? 0) * kg * FACTORS.food_kg.local_bonus);
  }

  // TRANSPORT
  if (qid === 'driveTime') {
    const hoursMap = {'0 - 2 hours':1,'3 - 5 hours':4,'6 - 8 hours':7,'9 - 12 hours':10.5,'12+ hours':14};
    const hours = hoursMap[option] ?? 0;
    const km = hours * ASSUMPTIONS.avg_drive_speed_kmh;
    return toBasis(km) * FACTORS.transport.car_avg_kg_per_km;
  }
  if (qid === 'commuteDistance') {
    const kmPerDay = ASSUMPTIONS.commute_km_midpoints[option] ?? 0;
    return toBasis(kmPerDay * 5) * FACTORS.transport.car_avg_kg_per_km;
  }
  if (qid === 'publicTransport') {
    const kmPerEvent = 10;
    const ev = evPerWeek ?? 0;
    return toBasis(ev * kmPerEvent) * FACTORS.transport.bus_kg_per_pkm;
  }
  if (qid === 'rideSharing') {
    const kmPerEvent = 8;
    const ev = evPerWeek ?? 0;
    return toBasis(ev * kmPerEvent) * FACTORS.transport.car_avg_kg_per_km;
  }
  if (qid === 'bikeWalk') {
    const avoidedKmPerEvent = 3;
    const savings = toBasis((evPerWeek ?? 0) * avoidedKmPerEvent) * FACTORS.transport.car_avg_kg_per_km;
    return -savings;
  }
  if (qid === 'flights') {
    const flightsMap = {'0':0,'1 - 2':1.5,'3 - 5':4,'6 - 10':8,'10+':12};
    const oneWayCountPerYear = (flightsMap[option] ?? 0) * 2;
    const yearlyKg = oneWayCountPerYear * FACTORS.flights.one_way_shorthaul_kg;
    return toBasis(yearlyKg / 52.1429);
  }
  if (qid === 'electricVehicle') { return 0; }
  if (qid === 'carpool') {
    const kmPerEvent = 10;
    const saving = 0.5 * kmPerEvent * FACTORS.transport.car_avg_kg_per_km;
    return toBasis((evPerWeek ?? 0) * (-saving));
  }

  // ENERGY
  if (qid === 'applianceUsage') {
    const hoursMap = {'0 - 2 hours':1,'3 - 4 hours':3.5,'5 - 6 hours':5.5,'7 - 8 hours':7.5,'8+ hours':9};
    const hoursPerDay = hoursMap[option] ?? 0;
    const kWhPerWeek = 0.4 * hoursPerDay * 7;
    return toBasis(kWhPerWeek) * FACTORS.electricity.grid_PH_kg_per_kWh;
  }
  if (qid === 'electricBill') { return 0; }
  if (qid === 'energySaving' || qid === 'unplugDevices' || qid === 'efficientAppliances' ||
      qid === 'smartThermostat' || qid === 'checkLeaks' || qid === 'coldWash') {
    return 0;
  }

  // WATER
  if (qid === 'showerLength') {
    const minsMap = {'0 - 3 mins':2,'4 - 6 mins':5,'7 - 9 mins':8,'10 - 12 mins':11,'12+ mins':14};
    return toBasis((minsMap[option] ?? 0) * FACTORS.water_waste.shower_kg_per_min * 7);
  }
  if (qid === 'laundry') {
    const loadsMap = {'0 - 1 times':0.5,'2 - 3 times':2.5,'4 times':4,'5 - 6 times':5.5,'7+ times':7.5};
    return toBasis((loadsMap[option] ?? 0) * FACTORS.water_waste.laundry_kg_per_load);
  }
  if (qid === 'dishwashing') {
    const runsMap = {'0 - 1 times':0.5,'2 times':2,'3 - 4 times':3.5,'5 - 6 times':5.5,'7+ times':7.5};
    return toBasis((runsMap[option] ?? 0) * FACTORS.water_waste.dishwasher_kg_per_run);
  }

  // WASTE – neutral
    // WASTE
  if (qid === 'trashBags') {
    // '0 to 1 bags','2 to 3 bags','4 to 5 bags','6 to 7 bags','8+ bags'
    const bagsMap = {'0 to 1 bags':0.5,'2 to 3 bags':2.5,'4 to 5 bags':4.5,'6 to 7 bags':6.5,'8+ bags':8.5};
    const bagsPerWeek = bagsMap[option] ?? 0;
    return toBasis(bagsPerWeek) * FACTORS.waste.kg_per_trash_bag;
  }

  if (qid === 'recycle') {
    if (option === 'Always')    return FACTORS.waste.recycle_saving_always;
    if (option === 'Sometimes') return FACTORS.waste.recycle_saving_sometimes;
    return 0;
  }

  if (qid === 'plasticUsage') {
    const ev = eventsPerWeek[option] ?? 0;
    return toBasis(ev) * FACTORS.waste.single_use_plastic_kg_per_event;
  }

  if (qid === 'compost') {
    if (option === 'Always')    return FACTORS.waste.compost_saving_always;
    if (option === 'Sometimes') return FACTORS.waste.compost_saving_sometimes;
    return 0;
  }

  if (qid === 'electronicsWaste') {
    // 'Monthly','Quarterly','Yearly','Every few years','Never'
    const perYearMap = { 'Monthly':12, 'Quarterly':4, 'Yearly':1, 'Every few years':0.25, 'Never':0 };
    const eventsPerYear = perYearMap[option] ?? 0;
    return (eventsPerYear * FACTORS.waste.e_waste_kg_per_event) / 52.1429; // weekly
  }

  if (qid === 'donateItems') {
    if (option === 'Always')    return FACTORS.waste.donate_saving_always;
    if (option === 'Sometimes') return FACTORS.waste.donate_saving_sometimes;
    return 0;
  }

  if (qid === 'minimalPackaging') {
    if (option === 'Always')    return FACTORS.waste.minimal_pack_saving_always;
    if (option === 'Sometimes') return FACTORS.waste.minimal_pack_saving_sometimes;
    return 0;
  }

  if (qid === 'reusableBags') {
    if (option === 'Always')    return FACTORS.waste.reusable_bag_saving_always;
    if (option === 'Sometimes') return FACTORS.waste.reusable_bag_saving_sometimes;
    return 0;
  }

  if (qid === 'recycleBatteries') {
    if (option === 'Always')    return FACTORS.waste.battery_bulb_recycle_saving_always;
    if (option === 'Sometimes') return FACTORS.waste.battery_bulb_recycle_sometimes;
    return 0;
  }

  if (qid === 'secondHand') {
    if (option === 'Always')    return FACTORS.waste.second_hand_saving_always;
    if (option === 'Sometimes') return FACTORS.waste.second_hand_saving_sometimes;
    return 0;
  }

  return 0;

}

/** Quiz engine (unchanged core) */
let flatQuestions = [];
for (const category in questions) {
  questions[category].forEach(q => flatQuestions.push({...q, category}));
}
let currentIndex = 0;
let totalScore = 0;

const quizContainer = document.getElementById('quizContainer');
const progressFill = document.getElementById('progressFill');
const resultDisplay = document.getElementById('resultDisplay');
const carbonValue = document.getElementById('carbonValue');
const scoreInput = document.getElementById('scoreInput');

function addToCategory(category, scoreKg) {
  categoryTotals[category] = (categoryTotals[category] || 0) + scoreKg;
  categoryCounts[category] = (categoryCounts[category] || 0) + 1;
}

function showQuestion() {
  quizContainer.innerHTML = '';
  if (currentIndex >= flatQuestions.length) { showResult(); return; }

  const q = flatQuestions[currentIndex];
  const progress = currentIndex / flatQuestions.length;
  updateDayCycle(progress);

  const questionDiv = document.createElement('div');
  questionDiv.classList.add('input-container');

  const categoryLabel = document.createElement('div');
  categoryLabel.classList.add('category-label');
  categoryLabel.textContent = q.category;
  categoryLabel.style.fontWeight = 'bold';
  categoryLabel.style.marginBottom = '5px';
  questionDiv.appendChild(categoryLabel);

  const label = document.createElement('div');
  label.classList.add('question-text');
  label.textContent = q.text;
  questionDiv.appendChild(label);

  const frequencyValues = ['daily','few-times-week','weekly','monthly','rarely','never'];
  let val;

  if (q.options.every(opt => frequencyValues.includes(opt))) {
    const sliderContainer = document.createElement('div');
    sliderContainer.classList.add('slider-container');

    const slider = document.createElement('input');
    slider.type = 'range';
    slider.min = 0;
    slider.max = q.options.length - 1;
    slider.value = Math.floor((q.options.length - 1)/2);
    slider.step = 1;
    slider.id = q.id;

    const sliderLabel = document.createElement('div');
    sliderLabel.classList.add('slider-label');
    sliderLabel.textContent = q.options[slider.value];

    slider.addEventListener('input', () => {
      sliderLabel.textContent = q.options[slider.value];
      const percent = slider.value / (slider.max - slider.min);
      sliderLabel.style.left = `calc(${percent * 100}% )`;
      sliderLabel.style.transform = 'translateX(-50%)';
    });

    sliderContainer.appendChild(slider);
    sliderContainer.appendChild(sliderLabel);
    questionDiv.appendChild(sliderContainer);
  } else {
    const optionsContainer = document.createElement('div');
    optionsContainer.classList.add('options-container');

    q.options.forEach(opt => {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.textContent = opt;
      btn.classList.add('option-btn');
      btn.style.margin = '5px 5px 0 0';
      btn.addEventListener('click', () => {
        val = opt;
        const score = computeEmission(q.id, val) || 0;
        totalScore += score;
        addToCategory(q.category, score);
        currentIndex++;
        updateProgress();
        showQuestion();
      });
      optionsContainer.appendChild(btn);
    });
    questionDiv.appendChild(optionsContainer);
  }

  const nextButton = document.createElement('button');
  nextButton.textContent = currentIndex === flatQuestions.length - 1 ? 'Finish' : 'Next';
  nextButton.classList.add('submit-btn');
  nextButton.style.marginTop = '15px';
  nextButton.addEventListener('click', function() {
    if (q.options.every(opt => frequencyValues.includes(opt))) {
      const slider = document.getElementById(q.id);
      val = q.options[slider.value];
    } else {
      const select = document.getElementById(q.id);
      if (select) { val = select.value; if (!val) { alert('Please select an option!'); return; } }
      else { return; }
    }
    const score = computeEmission(q.id, val) || 0;
    totalScore += score;
    addToCategory(q.category, score);
    currentIndex++;
    updateProgress();
    showQuestion();
  });

  questionDiv.appendChild(nextButton);
  quizContainer.appendChild(questionDiv);
}

function updateProgress() {
  const percent = (currentIndex / flatQuestions.length) * 100;
  progressFill.style.width = percent + '%';
}

/** Finalize and POST category totals */
function showResult() {
  quizContainer.style.display = 'none';
  resultDisplay.classList.add('show');
  carbonValue.textContent = totalScore.toFixed(1);
  if (scoreInput) scoreInput.value = totalScore.toFixed(1);

  const attemptInput = document.getElementById('attemptInput');
  if (attemptInput) attemptInput.value = attemptId;

  const timeframe = (document.getElementById('timeframeSelect')?.value ?? 'weekly');
  const period_start = document.getElementById('periodStart')?.value || null;
  const period_end   = document.getElementById('periodEnd')?.value || null;

  fetch('/save-footprint-category-totals', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? ''
    },
    credentials: 'same-origin',
    body: JSON.stringify({
      attempt_id: attemptId,
      totals: categoryTotals,
      counts: categoryCounts,
      overall: totalScore,
      basis: ANALYTICS_BASIS,
      timeframe,
      period_start,
      period_end
    })
  })
  .then(async (r) => {
  const raw = await r.text();
  let data; try { data = raw ? JSON.parse(raw) : null; }
  catch { throw new Error('Bad JSON from server'); }

  if (!r.ok || !data || data.status !== 'ok') {
    const msg = (data && (data.message || data.reason)) || `HTTP ${r.status}`;
    throw new Error(msg);
  }

  // XP + Points
  const xp  = (data.xp && (data.xp.awarded ?? data.xp.awarded_xp)) ?? 0;
  const pts = (data.points && data.points.awarded) ?? 0;

  // NEW: badges from backend (array of {slug,name,points_reward?...})
const badges = Array.isArray(data.badges)
  ? data.badges
  : Array.isArray(data.badges_awarded)
  ? data.badges_awarded
  : [];

  // Optional: write into the DOM if the element exists
  const ptsEl = document.getElementById('pointsEarned');
  if (ptsEl) ptsEl.textContent = `+${pts} pts`;

  // Build toast text
  const parts = ['Saved!', `+${xp} XP`, `+${pts} pts`];
  if (badges.length) {
    const names = badges.map(b => b.name || b.slug).join(', ');
    parts.push(`${badges.length} badge${badges.length>1?'s':''}: ${names}`);
  }
  toast(parts.join(' • '), 'success');

  // Optional: show a quick popup card with the badges
  if (badges.length) showBadgesPopup(badges);

  console.log('Saved category totals', data);
  return data;
})
.catch((err) => {
  console.error('Save error', err);
  toast(`Save failed: ${err.message}`, 'error');
});

}

function restartQuiz() {
  currentIndex = 0;
  totalScore = 0;
  for (const k in categoryTotals) delete categoryTotals[k];
  for (const k in categoryCounts) delete categoryCounts[k];
  newAttemptId();
  resultDisplay.classList.remove('show');
  quizContainer.style.display = 'block';
  updateProgress();
  showQuestion();
}

showQuestion();
updateProgress();
 document.addEventListener('DOMContentLoaded', () => {
    if (localStorage.getItem('darkMode') === 'enabled') {
        document.body.classList.add('dark-mode');
    }
});
function toggleMobileSidebar() {
    document.querySelector('.sidebar').classList.toggle('open');
}
</script>
</body>
</html>
