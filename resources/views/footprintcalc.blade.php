<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUSTENA - Footprint Tracker</title>
     <link rel="stylesheet" href="{{ asset('css/footprintcalc.css') }}">
</head>
<body>
<div class="sidebar">
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

<div class="main-content">
  <div class="header-icons">
    <div class="header-icon">🔥</div>
    <div class="header-icon">🌱</div>
    <div class="header-icon">🏆</div>
    <div class="header-icon">💰</div>
    <div class="header-icon">⚙️</div>
  </div>

  <div class="calculator-container">
    <div class="calculator-header">
      <h1 class="calculator-title">CARBON FOOTPRINT CALCULATOR</h1>
      <p class="calculator-subtitle">" MEASURE YOUR IMPACT ON THE PLANET! "</p>
      <div class="progress-bar">
        <div class="progress-fill" id="progressFill"></div>
      </div>
    </div>



<!-- FOOD  -->
<!-- Q1: Dairy - Milk -->
<div class="form-section" id="question1">
  <div class="section-header">
    <div class="section-title">FOOD - Dairy</div>
  </div>
  <div class="question-text">HOW OFTEN DO YOU DRINK MILK?</div>
  <div class="input-container">
    <select class="input-field" id="dairyMilk">
      <option value="">Select frequency...</option>
      <option value="daily">Daily</option>
      <option value="few-times-week">Few times a week</option>
      <option value="weekly">Weekly</option>
      <option value="monthly">Monthly</option>
      <option value="rarely">Rarely</option>
      <option value="never">Never</option>
    </select>
  </div>
  <button class="submit-btn" onclick="nextQuestion(1)">Next</button>
</div>

<!-- Q2: Dairy - Cheese/Yogurt -->
<div class="form-section" id="question2" style="display:none;">
  <div class="section-header">
    <div class="section-title">FOOD - Dairy</div>
  </div>
  <div class="question-text">HOW OFTEN DO YOU EAT CHEESE OR YOGURT?</div>
  <div class="input-container">
    <select class="input-field" id="dairyCheese">
      <option value="">Select frequency...</option>
      <option value="daily">Daily</option>
      <option value="few-times-week">Few times a week</option>
      <option value="weekly">Weekly</option>
      <option value="monthly">Monthly</option>
      <option value="rarely">Rarely</option>
      <option value="never">Never</option>
    </select>
  </div>
  <button class="submit-btn" onclick="nextQuestion(2)">Next</button>
</div>

<!-- Q3: Meat - Beef -->
<div class="form-section" id="question3" style="display:none;">
  <div class="section-header">
    <div class="section-title">FOOD - Meat</div>
  </div>
  <div class="question-text">HOW OFTEN DO YOU EAT BEEF?</div>
  <div class="input-container">
    <select class="input-field" id="meatBeef">
      <option value="">Select frequency...</option>
      <option value="daily">Daily</option>
      <option value="few-times-week">Few times a week</option>
      <option value="weekly">Weekly</option>
      <option value="monthly">Monthly</option>
      <option value="rarely">Rarely</option>
      <option value="never">Never</option>
    </select>
  </div>
  <button class="submit-btn" onclick="nextQuestion(3)">Next</button>
</div>

<!-- Q4: Meat - Chicken/Pork -->
<div class="form-section" id="question4" style="display:none;">
  <div class="section-header">
    <div class="section-title">FOOD - Meat</div>
  </div>
  <div class="question-text">HOW OFTEN DO YOU EAT CHICKEN OR PORK?</div>
  <div class="input-container">
    <select class="input-field" id="meatChickenPork">
      <option value="">Select frequency...</option>
      <option value="daily">Daily</option>
      <option value="few-times-week">Few times a week</option>
      <option value="weekly">Weekly</option>
      <option value="monthly">Monthly</option>
      <option value="rarely">Rarely</option>
      <option value="never">Never</option>
    </select>
  </div>
  <button class="submit-btn" onclick="nextQuestion(4)">Next</button>
</div>

<!-- Q5: Fish -->
<div class="form-section" id="question5" style="display:none;">
  <div class="section-header">
    <div class="section-title">FOOD - Fish</div>
  </div>
  <div class="question-text">HOW OFTEN DO YOU EAT FISH OR SEAFOOD?</div>
  <div class="input-container">
    <select class="input-field" id="fish">
      <option value="">Select frequency...</option>
      <option value="daily">Daily</option>
      <option value="few-times-week">Few times a week</option>
      <option value="weekly">Weekly</option>
      <option value="monthly">Monthly</option>
      <option value="rarely">Rarely</option>
      <option value="never">Never</option>
    </select>
  </div>
  <button class="submit-btn" onclick="nextQuestion(5)">Next</button>
</div>

<!--  TRANSPORTATION  -->
<!-- Q6: Vehicle Usage -->
<div class="form-section" id="question6" style="display:none;">
  <div class="section-header">
    <div class="section-title">TRANSPORTATION</div>
  </div>
  <div class="question-text">HOW OFTEN DO YOU USE A CAR OR MOTORCYCLE?</div>
  <div class="input-container">
    <select class="input-field" id="transport">
      <option value="">Select frequency...</option>
      <option value="daily">Daily</option>
      <option value="few-times-week">Few times a week</option>
      <option value="weekly">Weekly</option>
      <option value="rarely">Rarely</option>
      <option value="never">Never</option>
    </select>
  </div>
  <button class="submit-btn" onclick="nextQuestion(6)">Next</button>
</div>

<!-- Q7: Gas Spend -->
<div class="form-section" id="question7" style="display:none;">
  <div class="section-header">
    <div class="section-title">TRANSPORTATION</div>
  </div>
  <div class="question-text">HOW MUCH MONEY DO YOU SPEND ON GAS PER WEEK?</div>
  <div class="input-container">
    <select class="input-field" id="gasSpend">
      <option value="">Select...</option>
      <option value="low">₱0–₱500</option>
      <option value="medium">₱500–₱1500</option>
      <option value="high">₱1500+</option>
    </select>
  </div>
  <button class="submit-btn" onclick="nextQuestion(7)">Next</button>
</div>

<!-- Q8: Driving Time -->
<div class="form-section" id="question8" style="display:none;">
  <div class="section-header">
    <div class="section-title">TRANSPORTATION</div>
  </div>
  <div class="question-text">HOW MANY HOURS DO YOU DRIVE PER WEEK?</div>
  <div class="input-container">
    <select class="input-field" id="driveTime">
      <option value="">Select...</option>
      <option value="short">0–3 hours</option>
      <option value="medium">3–10 hours</option>
      <option value="long">10+ hours</option>
    </select>
  </div>
  <button class="submit-btn" onclick="nextQuestion(8)">Next</button>
</div>

<!-- Q9: Public Transport -->
<div class="form-section" id="question9" style="display:none;">
  <div class="section-header">
    <div class="section-title">TRANSPORTATION</div>
  </div>
  <div class="question-text">HOW OFTEN DO YOU USE PUBLIC TRANSPORTATION?</div>
  <div class="input-container">
    <select class="input-field" id="publicTransport">
      <option value="">Select frequency...</option>
      <option value="daily">Daily</option>
      <option value="few-times-week">Few times a week</option>
      <option value="weekly">Weekly</option>
      <option value="rarely">Rarely</option>
      <option value="never">Never</option>
    </select>
  </div>
  <button class="submit-btn" onclick="nextQuestion(9)">Next</button>
</div>

<!--  ENERGY  -->
<!-- Q10: Main Source -->
<div class="form-section" id="question10" style="display:none;">
  <div class="section-header">
    <div class="section-title">ENERGY</div>
  </div>
  <div class="question-text">WHAT'S YOUR MAIN SOURCE OF ELECTRICITY?</div>
  <div class="input-container">
    <select class="input-field" id="energySource">
      <option value="">Select...</option>
      <option value="coal">Mostly Coal</option>
      <option value="mixed">Mixed Sources</option>
      <option value="renewable">Mostly Renewable</option>
    </select>
  </div>
  <button class="submit-btn" onclick="nextQuestion(10)">Next</button>
</div>

<!-- Q11: Electric Bill -->
<div class="form-section" id="question11" style="display:none;">
  <div class="section-header">
    <div class="section-title">ENERGY</div>
  </div>
  <div class="question-text">HOW MUCH IS YOUR AVERAGE MONTHLY ELECTRIC BILL?</div>
  <div class="input-container">
    <select class="input-field" id="electricBill">
      <option value="">Select...</option>
      <option value="low">₱0–₱2000</option>
      <option value="medium">₱2000–₱5000</option>
      <option value="high">₱5000+</option>
    </select>
  </div>
  <button class="submit-btn" onclick="nextQuestion(11)">Next</button>
</div>

<!-- Q12: Appliance Usage -->
<div class="form-section" id="question12" style="display:none;">
  <div class="section-header">
    <div class="section-title">ENERGY</div>
  </div>
  <div class="question-text">HOW MANY HOURS DO YOU USE AIR CONDITIONING OR ELECTRIC FANS PER DAY?</div>
  <div class="input-container">
    <select class="input-field" id="applianceUsage">
      <option value="">Select...</option>
      <option value="low">0–2 hours</option>
      <option value="medium">2–6 hours</option>
      <option value="high">6+ hours</option>
    </select>
  </div>
  <button class="submit-btn" onclick="nextQuestion(12)">Finish</button>
</div>

<!-- WATER USAGE -->
<!-- Q13: Shower Length -->
<div class="form-section" id="question13" style="display:none;">
  <div class="section-header">
    <div class="section-title">WATER USAGE</div>
  </div>
  <div class="question-text">HOW LONG ARE YOUR SHOWERS ON AVERAGE?</div>
  <div class="input-container">
    <select class="input-field" id="showerLength">
      <option value="">Select...</option>
      <option value="short">0–5 minutes</option>
      <option value="medium">5–10 minutes</option>
      <option value="long">10+ minutes</option>
    </select>
  </div>
  <button class="submit-btn" onclick="nextQuestion(13)">Next</button>
</div>

<!-- Q14: Laundry Frequency -->
<div class="form-section" id="question14" style="display:none;">
  <div class="section-header">
    <div class="section-title">WATER USAGE</div>
  </div>
  <div class="question-text">HOW MANY TIMES DO YOU DO LAUNDRY PER WEEK?</div>
  <div class="input-container">
    <select class="input-field" id="laundry">
      <option value="">Select...</option>
      <option value="low">1–2 times</option>
      <option value="medium">3–4 times</option>
      <option value="high">5+ times</option>
    </select>
  </div>
  <button class="submit-btn" onclick="nextQuestion(14)">Next</button>
</div>

<!-- WASTE MANAGEMENT -->
<!-- Q15: Trash Produced -->
<div class="form-section" id="question15" style="display:none;">
  <div class="section-header">
    <div class="section-title">WASTE MANAGEMENT</div>
  </div>
  <div class="question-text">HOW MANY BAGS OF TRASH DO YOU PRODUCE PER WEEK?</div>
  <div class="input-container">
    <select class="input-field" id="trashBags">
      <option value="">Select...</option>
      <option value="low">1–2 bags</option>
      <option value="medium">3–4 bags</option>
      <option value="high">5+ bags</option>
    </select>
  </div>
  <button class="submit-btn" onclick="nextQuestion(15)">Next</button>
</div>

<!-- Q16: Recycling Habit -->
<div class="form-section" id="question16" style="display:none;">
  <div class="section-header">
    <div class="section-title">WASTE MANAGEMENT</div>
  </div>
  <div class="question-text">DO YOU REGULARLY RECYCLE PLASTICS, PAPER, OR METALS?</div>
  <div class="input-container">
    <select class="input-field" id="recycle">
      <option value="">Select...</option>
      <option value="always">Always</option>
      <option value="sometimes">Sometimes</option>
      <option value="never">Never</option>
    </select>
  </div>
  <button class="submit-btn" onclick="nextQuestion(16)">Next</button>
</div>

<!-- Q17: Plastic Usage -->
<div class="form-section" id="question17" style="display:none;">
  <div class="section-header">
    <div class="section-title">WASTE MANAGEMENT</div>
  </div>
  <div class="question-text">HOW OFTEN DO YOU USE SINGLE-USE PLASTIC ITEMS (e.g., plastic bottles, straws)?</div>
  <div class="input-container">
    <select class="input-field" id="plasticUsage">
      <option value="">Select frequency...</option>
      <option value="daily">Daily</option>
      <option value="few-times-week">Few times a week</option>
      <option value="weekly">Weekly</option>
      <option value="rarely">Rarely</option>
      <option value="never">Never</option>
    </select>
  </div>
  <button class="submit-btn" onclick="calculateFootprint()">Finish</button>
</div>



<!--  RESULTS  -->
<div class="result-display" id="resultDisplay" style="display:none;">
  <div class="result-text">Your estimated weekly carbon footprint:</div>
  <div class="carbon-value" id="carbonValue">0.0</div>
  <div class="carbon-unit">kg CO₂</div>
</div>

<!--  SCRIPT  -->
<script>
const footprintValues = {
  // FOOD
  dairyMilk: { daily: 12, "few-times-week": 8, weekly: 5, monthly: 2, rarely: 1, never: 0.5 },
  dairyCheese: { daily: 10, "few-times-week": 7, weekly: 4, monthly: 2, rarely: 1, never: 0.5 },
  meatBeef: { daily: 25, "few-times-week": 18, weekly: 10, monthly: 5, rarely: 2, never: 1 },
  meatChickenPork: { daily: 20, "few-times-week": 15, weekly: 8, monthly: 4, rarely: 2, never: 1 },
  fish: { daily: 10, "few-times-week": 7, weekly: 4, monthly: 2, rarely: 1, never: 0.5 },

  // TRANSPORTATION
  transport: { daily: 50, "few-times-week": 30, weekly: 15, rarely: 5, never: 0 },
  gasSpend: { low: 5, medium: 15, high: 30 },
  driveTime: { short: 5, medium: 15, long: 30 },
  publicTransport: { daily: 5, "few-times-week": 3, weekly: 2, rarely: 1, never: 0 },

  // ENERGY
  energySource: { coal: 40, mixed: 20, renewable: 5 },
  electricBill: { low: 5, medium: 15, high: 30 },
  applianceUsage: { low: 5, medium: 15, high: 30 },

  // WATER USAGE
  showerLength: { short: 5, medium: 10, long: 20 },
  laundry: { low: 5, medium: 10, high: 20 },

  // WASTE MANAGEMENT
  trashBags: { low: 5, medium: 10, high: 20 },
  recycle: { always: 0, sometimes: 5, never: 10 },
  plasticUsage: { daily: 10, "few-times-week": 7, weekly: 4, rarely: 2, never: 0.5 }
};

let answers = {};

// Update list of IDs to 17
const ids = [
  "dairyMilk", "dairyCheese", "meatBeef", "meatChickenPork", "fish",
  "transport", "gasSpend", "driveTime", "publicTransport",
  "energySource", "electricBill", "applianceUsage",
  "showerLength", "laundry",
  "trashBags", "recycle", "plasticUsage"
];

function nextQuestion(step) {
  let inputId = ids[step - 1];
  let value = document.getElementById(inputId).value;

  if (!value) {
    alert("Please select an option before proceeding.");
    return;
  }

  answers[inputId] = value;

  document.getElementById(`question${step}`).style.display = "none";
  document.getElementById(`question${step + 1}`).style.display = "block";

  let progress = (step / ids.length) * 100;
  document.getElementById("progressFill").style.width = progress + "%";
}

function calculateFootprint() {
  let value = document.getElementById("plasticUsage").value;
  if (!value) {
    alert("Please select an option before finishing.");
    return;
  }
  answers["plasticUsage"] = value;

  let total = 0;
  for (let key in answers) {
    total += footprintValues[key][answers[key]];
  }

  document.getElementById("question17").style.display = "none";
  document.getElementById("carbonValue").textContent = total.toFixed(1);
  document.getElementById("resultDisplay").style.display = "block";

  document.getElementById("progressFill").style.width = "100%";
}

// Progress bar reset on page load
window.addEventListener('load', () => {
  document.getElementById("progressFill").style.width = '0%';
});
</script>
</body>
</html>