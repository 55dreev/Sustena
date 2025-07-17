<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUSTENA - Footprint Tracker</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #a8e6cf 0%, #7fcdcd 100%);
            min-height: 100vh;
            display: flex;
        }

        .sidebar {
            width: 200px;
            background: linear-gradient(180deg, #4a7c59 0%, #2d5a3d 100%);
            padding: 20px;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .logo {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: #66bb6a;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            font-size: 20px;
        }

        .logo-text {
            color: white;
            font-weight: bold;
            font-size: 16px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            margin-bottom: 8px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .nav-item:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            transform: translateX(5px);
        }

        .nav-item.active {
            background: #66bb6a;
            color: white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .nav-icon {
            width: 24px;
            height: 24px;
            margin-right: 12px;
            border-radius: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            image-rendering: pixelated;
            image-rendering: -moz-crisp-edges;
            image-rendering: crisp-edges;
            filter: contrast(1.2) brightness(1.1);
            box-shadow: 
                inset 1px 1px 0px rgba(255,255,255,0.3),
                inset -1px -1px 0px rgba(0,0,0,0.2);
        }

        .main-content {
            margin-left: 200px;
            flex: 1;
            padding: 20px;
        }

        .header-icons {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            z-index: 1000;
        }

        .header-icon {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.9);
            border-radius: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
            image-rendering: pixelated;
            image-rendering: -moz-crisp-edges;
            image-rendering: crisp-edges;
            filter: contrast(1.2) brightness(1.1);
            box-shadow: 
                inset 2px 2px 0px rgba(255,255,255,0.8),
                inset -2px -2px 0px rgba(0,0,0,0.3),
                0px 4px 12px rgba(0,0,0,0.15);
        }

        .header-icon:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        }

        .calculator-container {
    background: linear-gradient(135deg, #87ceeb 0%, #b0e0e6 100%);
    border-radius: 20px;
    padding: 40px;
    padding-bottom: 180px; /* make room for the icons */
    margin-top: 60px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    position: relative;
    overflow: visible; /* 🔧 FIX: allow icons to appear outside */
}


        .calculator-container::before {
            content: "";
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            pointer-events: none;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
            animation: float 20s infinite linear;
            
        }

        @keyframes float {
            0% { transform: translateX(0) translateY(0); }
            100% { transform: translateX(-50px) translateY(-50px); }
        }

        .calculator-header {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
            z-index: 1;
        }

        .calculator-title {
            font-size: 32px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }

        .calculator-subtitle {
            font-size: 16px;
            color: #7f8c8d;
            font-style: italic;
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            background: rgba(255,255,255,0.3);
            border-radius: 4px;
            margin: 20px 0;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #4ecdc4, #44a08d);
            border-radius: 4px;
            width: 60%;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .form-section {
            position: relative;
            z-index: 1;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 28px;
            font-weight: bold;
            color: #27ae60;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }

        .question-text {
            font-size: 18px;
            color: #2c3e50;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .subtitle-text {
            font-size: 14px;
            color: #7f8c8d;
            margin-bottom: 30px;
        }

        .input-container {
            margin-bottom: 30px;
        }

        .input-field {
            width: 100%;
            padding: 15px 20px;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            background: rgba(255,255,255,0.9);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            outline: none;
        }

        .input-field:focus {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
            background: white;
        }

        .submit-btn {
            background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%);
            color: white;
            border: none;
            padding: 15px 40px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .submit-btn {
    display: block;
    margin: 30px auto 0 auto; /* Top margin optional */
    background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%);
    color: white;
    border: none;
    padding: 15px 40px;
    font-size: 18px;
    font-weight: bold;
    border-radius: 25px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    text-transform: uppercase;
    letter-spacing: 1px;
}


        .submit-btn:active {
            transform: translateY(-1px);
        }

        .farm-scene {
    position: absolute;
    bottom: 0; /* 🔧 FIXED: align to the bottom inside container */
    left: 0;
    right: 0;
    height: 200px;
    background: linear-gradient(180deg, transparent 0%, #8fbc8f 100%);
    pointer-events: none;
}


        .farm-element {
            position: absolute;
            bottom: 20px;
        }

        .barn {
            left: 50px;
            font-size: 60px;
            animation: sway 3s ease-in-out infinite;
        }

        .cow {
            left: 200px;
            font-size: 40px;
            animation: sway 4s ease-in-out infinite reverse;
        }

        .chicken {
            left: 300px;
            font-size: 30px;
            animation: hop 2s ease-in-out infinite;
        }

        .crops {
            left: 400px;
            font-size: 50px;
            animation: grow 3s ease-in-out infinite;
        }

        @keyframes sway {
            0%, 100% { transform: rotate(-2deg); }
            50% { transform: rotate(2deg); }
        }

        @keyframes hop {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        @keyframes grow {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .result-display {
            background: rgba(255,255,255,0.9);
            border-radius: 15px;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            display: none;
        }

        .result-display.show {
            display: block;
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .result-text {
            font-size: 18px;
            color: #2c3e50;
            text-align: center;
            margin-bottom: 15px;
        }

        .carbon-value {
            font-size: 36px;
            font-weight: bold;
            color: #e74c3c;
            text-align: center;
            margin-bottom: 10px;
        }

        .carbon-unit {
            font-size: 16px;
            color: #7f8c8d;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="sidebar">
  <div class="logo">
    <div class="logo-icon">🌱</div>
    <div class="logo-text">SUSTENA</div>
  </div>
  <a href="{{ url('/landing') }}" class="nav-item">
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
                    <div class="progress-fill"></div>
                </div>
            </div>

            <div class="form-section">
                <div class="section-header">
                    <div class="section-title">FOOD</div>
                </div>
                
                <div class="question-text">HOW OFTEN DO YOU EAT ANIMAL-BASED PRODUCTS?</div>
                <div class="subtitle-text">(beef, pork, chicken, fish, eggs, dairy products)</div>
                
                <div class="input-container">
                    <select class="input-field" id="animalProducts">
                        <option value="">Select frequency...</option>
                        <option value="daily">Daily</option>
                        <option value="few-times-week">Few times a week</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                        <option value="rarely">Rarely</option>
                        <option value="never">Never</option>
                    </select>
                </div>
                
                <button class="submit-btn" onclick="calculateFootprint()">SUBMIT</button>
            </div>

            <div class="result-display" id="resultDisplay">
                <div class="result-text">Your estimated weekly carbon footprint from food:</div>
                <div class="carbon-value" id="carbonValue">0.0</div>
                <div class="carbon-unit">kg CO₂</div>
            </div>

            <div class="farm-scene">
                <div class="farm-element barn">🏠</div>
                <div class="farm-element cow">🐄</div>
                <div class="farm-element chicken">🐔</div>
                <div class="farm-element crops">🌾</div>
            </div>
        </div>
    </div>

    <script>
    function calculateFootprint() {
        const selection = document.getElementById('animalProducts').value;
        const resultDisplay = document.getElementById('resultDisplay');
        const carbonValue = document.getElementById('carbonValue');

        const footprintValues = {
            'daily': 45.2,
            'few-times-week': 28.7,
            'weekly': 15.3,
            'monthly': 8.1,
            'rarely': 4.2,
            'never': 2.1
        };

        if (selection && footprintValues[selection]) {
            carbonValue.textContent = footprintValues[selection];
            resultDisplay.classList.add('show');

            setTimeout(() => {
                carbonValue.style.animation = 'pulse 1s ease-in-out';
            }, 500);
        } else {
            alert('Please select a frequency option to calculate your footprint!');
        }
    }

    document.querySelectorAll('.nav-item').forEach(item => {
        item.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href === '#') {
                e.preventDefault();
            } else {
                window.location.href = href;
            }
            document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
            this.classList.add('active');
        });
    });

    window.addEventListener('load', function () {
        const progressBar = document.querySelector('.progress-fill');
        setTimeout(() => {
            progressBar.style.width = '60%';
        }, 1000);
    });

    document.querySelectorAll('.farm-element').forEach(element => {
        element.addEventListener('mouseenter', function () {
            this.style.transform = 'scale(1.2)';
            this.style.transition = 'transform 0.3s ease';
        });

        element.addEventListener('mouseleave', function () {
            this.style.transform = 'scale(1)';
        });
    });
</script>

</body>
</html>