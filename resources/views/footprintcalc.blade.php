<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SUSTENA - Footprint Tracker</title>
<link rel="stylesheet" href="{{ asset('css/footprintcalc.css') }}">

</head>
<body>
<div class="sidebar" id="sidebar">
    <div class="sidebar-toggle" onclick="toggleSidebar()">☰</div>
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

    <div class="calculator-container">
        <div class="calculator-header">
            <h1 class="calculator-title">CARBON FOOTPRINT CALCULATOR</h1>
            <p class="calculator-subtitle">" MEASURE YOUR IMPACT ON THE PLANET! "</p>
            <div class="progress-bar" style="position: relative;">
    <div class="progress-fill" id="progressFill"></div>
</div>


        <div id="quizContainer" class="form-section"></div>

        <!-- Result Display -->
        <div class="result-display" id="resultDisplay">
            <div class="result-text">Your estimated weekly carbon footprint:</div>
            <div class="carbon-value" id="carbonValue">0.0</div>
            <div class="carbon-unit">kg CO₂</div>
            <form id="saveScoreForm" method="POST" action="{{ url('/save-footprint-score') }}">
                @csrf
                <input type="hidden" name="score" id="scoreInput">
                <button type="submit" class="submit-btn">Save Score to Profile</button>
            </form>
            <button type="button" class="submit-btn" onclick="restartQuiz()" style="background-color:#777; margin-top:10px;">
                Restart
            </button>
        </div>
    </div>
</div>

<script>
    // Sidebar toggle
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const main = document.getElementById('mainContent');
        sidebar.classList.toggle('collapsed');
        main.classList.toggle('expanded');
    }

    const categoryBackgrounds = {
    'Food': '#f0f9f0',           // soft green
    'Transportation': '#fff7e6', // soft orange
    'Energy': '#fffdf0',         // pale yellow
    'Water Usage': '#e6f7fd',    // light blue
    'Waste Management': '#fdeff0'// pale pink
};

    // Quiz questions and values (same as before)...
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

 const footprintValues = {
        dairyMilk: {daily:12,"few-times-week":8,weekly:5,monthly:2,rarely:1,never:0.5},
        dairyCheese: {daily:10,"few-times-week":7,weekly:4,monthly:2,rarely:1,never:0.5},
        meatBeef: {daily:25,"few-times-week":18,weekly:10,monthly:5,rarely:2,never:1},
        meatChickenPork: {daily:20,"few-times-week":15,weekly:8,monthly:4,rarely:2,never:1},
        fish: {daily:10,"few-times-week":7,weekly:4,monthly:2,rarely:1,never:0.5},
        eggs: {daily:5,"few-times-week":4,weekly:2,monthly:1,rarely:0.5,never:0.2},
        plantProtein: {daily:1,"few-times-week":0.8,weekly:0.5,monthly:0.2,rarely:0.1,never:0.05},
        processedFoods: {daily:8,"few-times-week":5,weekly:3,monthly:1,rarely:0.5,never:0.2},
        organicFoods: {daily:2,"few-times-week":1.5,weekly:1,monthly:0.5,rarely:0.2,never:0.1},
        localFoods: {daily:1,"few-times-week":0.8,weekly:0.5,monthly:0.2,rarely:0.1,never:0.05},
        transport: {daily:50,"few-times-week":30,weekly:15,monthly:5,rarely:2,never:0},
        gasSpend: {"₱0 - ₱500":5,"₱500 - ₱1000":15,"₱1000 - ₱2000":25,"₱2000 - ₱5000":40,"₱5000+":60},
        driveTime: {"0 - 2 hours":5,"3 - 5 hours":15,"6 - 8 hours":25,"9 - 12 hours":40,"12+ hours":60},
        publicTransport: {daily:5,"few-times-week":3,weekly:2,monthly:1,rarely:0.5,never:0},
        rideSharing: {daily:2,"few-times-week":1,weekly:0.5,monthly:0.2,rarely:0.1,never:0},
        bikeWalk: {daily:0,"few-times-week":0.5,weekly:1,monthly:2,rarely:3,never:5},
        flights: {"0":0,"1 - 2":50,"3 - 5":120,"6 - 10":200,"10+":400},
        commuteDistance: {"0 - 5 km":2,"5 - 10 km":5,"10 - 20 km":10,"20 - 50 km":20,"50+ km":40},
        electricVehicle: {"Yes":5,"No":30},
        carpool: {daily:0,"few-times-week":2,weekly:3,monthly:5,rarely:8,never:10},
        energySource: {"Coal":40,"Mixed":20,"Renewable":5},
        electricBill: {"₱0 - ₱1000":5,"₱1000 - ₱2000":10,"₱2000 - ₱3000":15,"₱3000 - ₱4000":20,"₱4000+":30},
        applianceUsage: {"0 - 2 hours":5,"3 - 4 hours":10,"5 - 6 hours":15,"7 - 8 hours":20,"8+ hours":25},
        solarPanels: {"Yes":0,"No":10},
        energySaving: {"Always":0,"Sometimes":5,"Never":10},
        unplugDevices: {"Always":0,"Sometimes":5,"Never":10},
        efficientAppliances: {"Yes":0,"No":10},
        smartThermostat: {"Yes":0,"No":5},
        coldWash: {daily:0,"few-times-week":1,weekly:2,monthly:3,rarely:4,never:5},
        checkLeaks: {"Always":0,"Sometimes":5,"Never":10},
        showerLength: {"0 - 3 mins":2,"4 - 6 mins":5,"7 - 9 mins":10,"10 - 12 mins":15,"12+ mins":20},
        laundry: {"0 - 1 times":2,"2 - 3 times":5,"4 times":10,"5 - 6 times":15,"7+ times":20},
        dishwashing: {"0 - 1 times":1,"2 times":3,"3 - 4 times":5,"5 - 6 times":8,"7+ times":12},
        leaks: {"Always":0,"Sometimes":5,"Never":10},
        gardenWatering: {daily:5,"few-times-week":3,weekly:2,monthly:1,rarely:0,never:0},
        lowFlowShower: {"Yes":0,"No":5},
        rainwater: {"Yes":0,"No":5},
        reuseGreywater: {"Yes":0,"No":5},
        bathInsteadShower: {daily:5,"few-times-week":3,weekly:2,monthly:1,rarely:0,never:0},
        turnOffTap: {"Always":0,"Sometimes":2,"Never":5},
        waterEfficientAppliances: {"Yes":0,"No":5},
        trashBags: {"0 to 1 bags":2,"2 to 3 bags":5,"4 to 5 bags":10,"6 to 7 bags":15,"8+ bags":20},
        recycle: {"Always":0,"Sometimes":2,"Never":5},
        plasticUsage: {"Always":10,"Sometimes":5,"Never":0},
        compost: {"Always":0,"Sometimes":2,"Never":5},
        electronicsWaste: {"Monthly":2,"Quarterly":5,"Yearly":10,"Every few years":12,"Never":15},
        donateItems: {"Always":0,"Sometimes":2,"Never":5},
        minimalPackaging: {"Always":0,"Sometimes":2,"Never":5},
        reusableBags: {"Always":0,"Sometimes":1,"Never":2},
        recycleBatteries: {"Always":0,"Sometimes":2,"Never":5},
        secondHand: {"Always":0,"Sometimes":1,"Never":3},
    };


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

    function showQuestion() {
        quizContainer.innerHTML = '';

        if (currentIndex >= flatQuestions.length) {
            showResult();
            return;
        }

        const q = flatQuestions[currentIndex];

        const quizContainerDiv = document.querySelector('.calculator-container');
        quizContainerDiv.style.background = categoryBackgrounds[q.category] || '#ffffff';

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
            slider.value = 0;
            slider.step = 1;
            slider.id = q.id;

            const sliderLabel = document.createElement('div');
            sliderLabel.classList.add('slider-label');
            sliderLabel.textContent = q.options[0];

            slider.addEventListener('input', () => {
            sliderLabel.textContent = q.options[slider.value];

          
              const percent = slider.value / (slider.max - slider.min);
              const sliderWidth = slider.offsetWidth;
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

        totalScore += footprintValues[q.id][val] || 0;
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
                val = select.value;
                if (!val) { alert('Please select an option!'); return; }
            }

            totalScore += footprintValues[q.id][val] || 0;
            currentIndex++;
            updateProgress();
            showQuestion();
        });

        questionDiv.appendChild(nextButton);
        quizContainer.appendChild(questionDiv);
    }

    function updateProgress() {
    const percent = ((currentIndex) / flatQuestions.length) * 100;
    progressFill.style.width = percent + '%';
}


    function showResult() {
        quizContainer.style.display = 'none';
        resultDisplay.classList.add('show');
        carbonValue.textContent = totalScore.toFixed(1);
        scoreInput.value = totalScore.toFixed(1);
    }

    function restartQuiz() {
        currentIndex = 0;
        totalScore = 0;
        resultDisplay.classList.remove('show');
        quizContainer.style.display = 'block';
        updateProgress();
        showQuestion();
    }

    showQuestion();
    updateProgress();
</script>
</body>
</html>
