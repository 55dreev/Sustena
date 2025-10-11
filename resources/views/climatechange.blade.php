<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Climate Change - SUSTENA</title>
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
    .quiz-instructions {
    font-size: 1rem;
    color: #555;
    margin-bottom: 20px;
  }

  .quiz-instructions ul {
    padding-left: 20px;
    list-style-type: disc;
  }

  .start-btn {
    background-color: #388e3c;
    color: white;
    border: none;
    padding: 10px 20px;
    margin-top: 15px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 1rem;
    transition: background 0.3s;
  }

  .start-btn:hover {
    background-color: #2e7d32;
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
    }

    .back-button:hover {
      background-color: #256528;
    }

    /* Quiz Button */
    .quiz-launch-btn {
      display: block;
      width: 200px;
      margin: 30px auto 0;
      text-align: center;
      background-color: #388e3c;
      color: white;
      padding: 12px 20px;
      border-radius: 8px;
      text-decoration: none;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.3s;
    }

    .quiz-launch-btn:hover {
      background-color: #2e7d32;
    }

    /* Modal Background */
    .quiz-modal {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.5);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 9999;
    }

    /* Modal Content Box */
    .quiz-box {
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      width: 90%;
      max-width: 500px;
      text-align: center;
      position: relative;
      transform: translateY(-50px);
      opacity: 0;
      transition: all 0.4s ease;
    }

    /* Animation when active */
    .quiz-modal.active .quiz-box {
      transform: translateY(0);
      opacity: 1;
    }

    /* Close Button */
    .close-btn {
      position: absolute;
      top: 10px;
      right: 15px;
      font-size: 1.5rem;
      color: #666;
      cursor: pointer;
    }

    .close-btn:hover {
      color: #000;
    }

    /* Quiz Content */
    .quiz-question {
      font-size: 1.2rem;
      margin-bottom: 20px;
    }

    .quiz-buttons button {
      background-color: #2e7d32;
      color: white;
      border: none;
      padding: 10px 20px;
      margin: 0 10px;
      border-radius: 8px;
      cursor: pointer;
      font-size: 1rem;
      transition: background 0.3s;
    }

    .quiz-buttons button:hover {
      background-color: #256528;
    }

    .quiz-timer {
      font-size: 1rem;
      color: #555;
      margin-top: 10px;
    }

    .quiz-result {
      font-size: 1.4rem;
      font-weight: bold;
      color: #2e7d32;
      margin-top: 20px;
    }

    @media (max-width: 768px) {
      .quiz-box {
        width: 95%;
        padding: 20px;
      }
    }
  </style>
</head>
<body>

  <div class="module-container">
    <div class="module-header">
      <h1>Climate Change</h1>
      <p>Learn about the causes, effects, and solutions to one of the most pressing global issues today.</p>
    </div>

    <!-- Section 1: Introduction -->
    <div class="module-section">
      <h2>What is Climate Change?</h2>
      <p>
        Climate change refers to long-term shifts in temperatures and weather patterns, mainly caused by human activities such as burning fossil fuels, deforestation, and industrial processes.
        These activities increase greenhouse gas concentrations in the atmosphere, trapping heat and causing global warming.
      </p>
    </div>

    <!-- Section 2: History -->
    <div class="module-section">
      <h2>History of Climate Change</h2>
      <p>
        Awareness of climate change dates back to the 19th century, when scientists like Svante Arrhenius first studied how carbon dioxide affects the Earth's temperature.  
        By the late 20th century, evidence of human-induced warming became clearer, leading to major international agreements such as the <strong>Kyoto Protocol (1997)</strong> and the <strong>Paris Agreement (2015)</strong>.
      </p>
    </div>

    <!-- Section 3: Embedded Video -->
    <div class="module-section">
      <h2>Watch: Understanding Climate Change</h2>
      <div class="video-container">
        <iframe 
          src="https://www.youtube.com/embed/G4H1N_yXBiA" 
          title="Climate Change Explained"
          allowfullscreen>
        </iframe>
      </div>
    </div>

    <!-- Section 4: Solutions -->
    <div class="module-section">
      <h2>What Can We Do?</h2>
      <p>
        Here are some steps individuals and communities can take to fight climate change:
      </p>
      <ul>
        <li>Reduce energy consumption by using energy-efficient appliances and turning off unused electronics.</li>
        <li>Use renewable energy sources like solar and wind power when possible.</li>
        <li>Plant trees and support reforestation efforts.</li>
        <li>Use public transportation, carpool, or cycle to reduce carbon emissions.</li>
        <li>Advocate for sustainable policies and environmental protection laws.</li>
      </ul>
    </div>

    <!-- Take a Quiz Button -->
    <button class="quiz-launch-btn" onclick="openQuiz()">Take a Quiz</button> <br><br><br>

    <!-- Back button -->
    <div class="module-footer" style="text-align:center;">
      <a href="{{ url('/learning-modules') }}" class="back-button">← Back to Learning Modules</a>
    </div>
  </div>

  <!-- Quiz Modal -->
<div class="quiz-modal" id="quizModal" onclick="outsideClick(event)">
  <div class="quiz-box">
    <span class="close-btn" onclick="closeQuiz()">&times;</span>
    <h2>SpeedQuiz</h2>

    <!-- Instructions -->
    <div class="quiz-instructions" id="instructions">
      <p>
        Test your knowledge about climate change!  
        <br><br>
        <strong>How it works:</strong>
        <ul style="text-align:left; margin-left:15px;">
          <li>You have <strong>1 minute</strong> to answer as many questions as you can.</li>
          <li>Each question has <strong>True</strong> or <strong>False</strong> answers.</li>
          <li>Your final score will be shown when the timer ends or you finish all questions.</li>
        </ul>
      </p>
      <button class="start-btn" onclick="startQuiz()">Start Quiz</button>
    </div>

    <!-- Quiz Content -->
    <div id="quiz" style="display:none;">
      <div class="quiz-question" id="question">Loading question...</div>
      <div class="quiz-buttons">
        <button onclick="answer(true)">True</button>
        <button onclick="answer(false)">False</button>
      </div>
      <div class="quiz-timer" id="timer">Time left: 60s</div>
      <div class="quiz-result" id="result"></div>
    </div>
  </div>
</div>



<script>
  const questions = [
    { text: "Climate change is caused only by natural factors like volcanoes and the sun.", correct: false },
    { text: "Deforestation increases greenhouse gases in the atmosphere.", correct: true },
    { text: "The Paris Agreement was signed in 2015.", correct: true },
    { text: "Using renewable energy can help fight climate change.", correct: true },
    { text: "Global warming has no effect on sea levels.", correct: false },
    { text: "Burning fossil fuels releases carbon dioxide, a greenhouse gas.", correct: true },
    { text: "Recycling has no impact on reducing greenhouse gas emissions.", correct: false },
    { text: "Melting glaciers are a direct result of global warming.", correct: true },
    { text: "Switching to LED lights can help reduce energy consumption.", correct: true },
    { text: "Greenhouse gases trap heat in the Earth’s atmosphere.", correct: true },
    { text: "Sea levels are decreasing due to climate change.", correct: false },
    { text: "Deforestation can lead to loss of biodiversity.", correct: true },
    { text: "Electric cars produce zero direct carbon emissions.", correct: true },
    { text: "Plastic pollution has no relation to climate change.", correct: false },
    { text: "The industrial revolution increased greenhouse gas emissions drastically.", correct: true },
    { text: "Planting trees can help absorb carbon dioxide from the atmosphere.", correct: true },
    { text: "Climate change only affects polar regions.", correct: false },
    { text: "Global temperatures have been rising steadily over the past century.", correct: true },
    { text: "Oceans absorb a significant amount of the Earth's heat.", correct: true },
    { text: "Renewable energy sources include solar and wind power.", correct: true },
    { text: "Air pollution and climate change are completely unrelated.", correct: false },
    { text: "Coral reefs are vulnerable to rising ocean temperatures.", correct: true },
    { text: "Energy-efficient appliances can reduce carbon footprints.", correct: true },
    { text: "Climate change is a myth and has no scientific basis.", correct: false },
    { text: "Public transportation can help reduce greenhouse gas emissions.", correct: true },
    { text: "Global warming leads to more extreme weather patterns.", correct: true },
    { text: "The Kyoto Protocol was an agreement to reduce greenhouse gases.", correct: true },
    { text: "Polar bears are unaffected by climate change.", correct: false },
    { text: "Methane is a greenhouse gas that comes from livestock like cows.", correct: true },
    { text: "Climate change is only a future problem, not affecting us now.", correct: false }
  ];

  let currentQuestion = 0;
  let score = 0;
  let timeLeft = 60; // 1 minute
  let timer;
  let shuffledQuestions = [];

  const modal = document.getElementById('quizModal');
  const questionElement = document.getElementById('question');
  const timerElement = document.getElementById('timer');
  const resultElement = document.getElementById('result');
  const instructionsElement = document.getElementById('instructions');
  const quizElement = document.getElementById('quiz');

  function openQuiz() {
    modal.style.display = 'flex';
    modal.classList.add('active');
    instructionsElement.style.display = 'block'; // Show instructions
    quizElement.style.display = 'none';          // Hide quiz
  }

  function closeQuiz() {
    modal.style.display = 'none';
    clearInterval(timer);
  }

  function outsideClick(e) {
    if (e.target === modal) {
      closeQuiz();
    }
  }

  function startQuiz() {
    instructionsElement.style.display = 'none'; // Hide instructions
    quizElement.style.display = 'block';        // Show quiz
    score = 0;
    currentQuestion = 0;

    // Shuffle questions
    shuffledQuestions = [...questions].sort(() => Math.random() - 0.5);

    // Start global 1-minute timer
    timeLeft = 60;
    timerElement.innerText = `Time left: ${timeLeft}s`;
    clearInterval(timer);
    timer = setInterval(() => {
      timeLeft--;
      timerElement.innerText = `Time left: ${timeLeft}s`;
      if (timeLeft <= 0) {
        clearInterval(timer);
        endQuiz();
      }
    }, 1000);

    showQuestion();
  }

  function showQuestion() {
    if (currentQuestion >= shuffledQuestions.length) {
      endQuiz();
      return;
    }
    questionElement.innerText = shuffledQuestions[currentQuestion].text;
    resultElement.innerText = "";
  }

  function answer(choice) {
    if (choice === shuffledQuestions[currentQuestion].correct) {
      score++;
    }
    currentQuestion++;
    showQuestion();
  }

  function endQuiz() {
    questionElement.innerText = "Time's up or quiz completed!";
    resultElement.innerText = `Your Final Score: ${score}/${shuffledQuestions.length}`;
    document.querySelector('.quiz-buttons').style.display = 'none';
    timerElement.innerText = "";
  }
</script>


</body>
</html>
