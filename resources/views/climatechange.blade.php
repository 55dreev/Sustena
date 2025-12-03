<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Climate Change - SUSTENA</title>
  <link rel="stylesheet" href="{{ asset('css/learningmod.css') }}">
  <link rel="stylesheet" href="{{ asset('css/climatechange.css') }}">
  <link rel="stylesheet" href="{{ asset('css/climatechangeresponsive.css') }}">
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background: linear-gradient(135deg, #a8e6cf 0%, #88d8c0 100%);
      min-height: 100vh;
      margin: 0;
      padding: 20px;
    }

    .climate-container {
      max-width: 1000px;
      margin: 0 auto;
      padding: 20px;
    }

    /* Animated Header */
    .climate-header {
      background: linear-gradient(135deg, #4CAF50 0%, #388E3C 100%);
      border-radius: 25px;
      padding: 50px 40px;
      text-align: center;
      margin-bottom: 40px;
      position: relative;
      overflow: hidden;
      box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    }

    .climate-header::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="3" fill="white" opacity="0.3"/><circle cx="80" cy="40" r="2" fill="white" opacity="0.2"/><circle cx="40" cy="70" r="2.5" fill="white" opacity="0.25"/></svg>');
      animation: float 20s infinite linear;
    }

    @keyframes float {
      0% { transform: translateX(-100%) translateY(-100%) rotate(0deg); }
      100% { transform: translateX(0%) translateY(0%) rotate(360deg); }
    }

    .earth-icons {
      position: absolute;
      font-size: 40px;
      opacity: 0.6;
      animation: pulse 3s infinite ease-in-out;
    }

    .earth-1 { top: 20px; left: 10%; animation-delay: 0s; }
    .earth-2 { top: 30px; right: 10%; animation-delay: 1s; }
    .earth-3 { bottom: 30px; left: 15%; animation-delay: 2s; }
    .earth-4 { bottom: 40px; right: 15%; animation-delay: 1.5s; }

    @keyframes pulse {
      0%, 100% { transform: scale(1); opacity: 0.6; }
      50% { transform: scale(1.2); opacity: 0.9; }
    }

    .header-title {
      font-size: 52px;
      font-weight: bold;
      color: white;
      text-shadow: 3px 3px 6px rgba(0,0,0,0.3);
      z-index: 1;
      position: relative;
      margin-bottom: 15px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 15px;
    }

    .header-subtitle {
      background: rgba(255,255,255,0.95);
      color: #2E7D32;
      padding: 18px 35px;
      border-radius: 30px;
      font-size: 18px;
      font-weight: 600;
      display: inline-block;
      box-shadow: 0 6px 20px rgba(0,0,0,0.15);
      z-index: 1;
      position: relative;
    }

    .xp-badge {
      position: absolute;
      top: 25px;
      right: 25px;
      background: linear-gradient(135deg, #ffd54f, #ffb300);
      color: #333;
      padding: 12px 20px;
      border-radius: 25px;
      font-size: 16px;
      font-weight: bold;
      box-shadow: 0 4px 15px rgba(255,213,79,0.4);
      z-index: 2;
    }

    /* Info Cards Grid */
    .info-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 25px;
      margin-bottom: 40px;
    }

    .info-card {
      background: rgba(255,255,255,0.95);
      border-radius: 20px;
      padding: 30px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.12);
      transition: all 0.3s ease;
      border: 3px solid transparent;
      position: relative;
      overflow: hidden;
    }

    .info-card::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: linear-gradient(45deg, transparent, rgba(76,175,80,0.1), transparent);
      transform: rotate(45deg);
      transition: all 0.5s ease;
      opacity: 0;
    }

    .info-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 15px 40px rgba(0,0,0,0.2);
      border-color: #4CAF50;
    }

    .info-card:hover::before {
      opacity: 1;
      transform: rotate(45deg) translate(50%, 50%);
    }

    .card-icon {
      width: 70px;
      height: 70px;
      background: linear-gradient(135deg, #4CAF50, #388E3C);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 35px;
      margin-bottom: 20px;
      box-shadow: 0 4px 15px rgba(76,175,80,0.3);
    }

    .info-card h2 {
      font-size: 24px;
      color: #2E7D32;
      margin-bottom: 15px;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .info-card p {
      color: #555;
      line-height: 1.7;
      font-size: 15px;
    }

    .info-card ul {
      list-style: none;
      padding: 0;
      margin: 15px 0;
    }

    .info-card ul li {
      color: #555;
      padding: 10px 0;
      padding-left: 30px;
      position: relative;
      line-height: 1.6;
    }

    .info-card ul li::before {
      content: '🌱';
      position: absolute;
      left: 0;
      font-size: 18px;
    }

    /* Video Section */
    .video-section {
      background: rgba(255,255,255,0.95);
      border-radius: 20px;
      padding: 35px;
      margin-bottom: 30px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.12);
      text-align: center;
    }

    .video-section h2 {
      font-size: 28px;
      color: #2E7D32;
      margin-bottom: 25px;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .video-container {
      position: relative;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }

    .video-container iframe {
      width: 100%;
      max-width: 800px;
      height: 450px;
      border: none;
      border-radius: 15px;
    }

    /* Quiz Challenge Card */
    .quiz-challenge {
      background: linear-gradient(135deg, #66BB6A 0%, #4CAF50 100%);
      border-radius: 25px;
      padding: 40px;
      text-align: center;
      margin-bottom: 30px;
      position: relative;
      overflow: hidden;
      box-shadow: 0 10px 35px rgba(0,0,0,0.2);
      border: 4px solid rgba(255,255,255,0.3);
    }

    .quiz-challenge h2 {
      font-size: 32px;
      color: white;
      margin-bottom: 20px;
      font-weight: bold;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }

    .quiz-launch-btn {
      background: linear-gradient(135deg, #FF9800, #F57C00);
      color: white;
      border: none;
      padding: 18px 45px;
      border-radius: 30px;
      font-size: 20px;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.3s ease;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      box-shadow: 0 6px 20px rgba(255,152,0,0.4);
      display: inline-flex;
      align-items: center;
      gap: 10px;
    }

    .quiz-launch-btn:hover {
      transform: translateY(-4px) scale(1.05);
      box-shadow: 0 10px 30px rgba(255,152,0,0.5);
    }

    .quiz-launch-btn:active {
      transform: translateY(-2px) scale(1.02);
    }

    .back-section {
      text-align: center;
      margin-top: 30px;
    }

    .back-button {
      background: linear-gradient(135deg, #388E3C, #2E7D32);
      color: white;
      padding: 15px 35px;
      border-radius: 25px;
      text-decoration: none;
      font-weight: 600;
      font-size: 16px;
      transition: all 0.3s ease;
      display: inline-block;
      box-shadow: 0 4px 15px rgba(56,142,60,0.3);
      text-transform: uppercase;
      letter-spacing: 1px;
      border: none;
      cursor: pointer;
    }

    .back-button:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 20px rgba(56,142,60,0.4);
    }

    .progress-dots {
      display: flex;
      justify-content: center;
      gap: 8px;
      margin-top: 20px;
    }

    .dot {
      width: 12px;
      height: 12px;
      border-radius: 50%;
      background: rgba(255,255,255,0.4);
      transition: all 0.3s ease;
    }

    .dot.active {
      background: #FF9800;
      box-shadow: 0 0 10px rgba(255,152,0,0.6);
    }

    @media (max-width: 768px) {
      .header-title {
        font-size: 36px;
      }

      .header-subtitle {
        font-size: 16px;
        padding: 14px 25px;
      }

      .xp-badge {
        font-size: 14px;
        padding: 10px 16px;
        top: 15px;
        right: 15px;
      }

      .info-grid {
        grid-template-columns: 1fr;
        gap: 20px;
      }

      .video-container iframe {
        height: 250px;
      }

      .quiz-challenge h2 {
        font-size: 24px;
      }

      .quiz-launch-btn {
        font-size: 16px;
        padding: 14px 30px;
      }
    }
  </style>
</head>
<body>
  <div class="climate-container">
    <div class="climate-header">
      <div class="earth-icons earth-1">🌍</div>
      <div class="earth-icons earth-2">🌱</div>
      <div class="earth-icons earth-3">🌿</div>
      <div class="earth-icons earth-4">🌍</div>

      <div class="xp-badge">+70 XP</div>

      <h1 class="header-title">🌍 Climate Change</h1>
      <p class="header-subtitle">Learn about the causes, effects, and solutions to one of the most pressing global issues today.</p>

      <div class="progress-dots">
        <div class="dot active"></div>
        <div class="dot active"></div>
        <div class="dot active"></div>
        <div class="dot"></div>
      </div>
    </div>

    <div class="info-grid">
      <div class="info-card">
        <div class="card-icon">🌡️</div>
        <h2>What is Climate Change?</h2>
        <p>
          Climate change refers to long-term shifts in temperatures and weather patterns, mainly caused by human activities such as burning fossil fuels, deforestation, and industrial processes.
          These activities increase greenhouse gas concentrations in the atmosphere, trapping heat and causing global warming.
        </p>
      </div>

      <div class="info-card">
        <div class="card-icon">📚</div>
        <h2>History of Climate Change</h2>
        <p>
          Awareness of climate change dates back to the 19th century, when scientists like Svante Arrhenius first studied how carbon dioxide affects the Earth's temperature.
          By the late 20th century, evidence of human-induced warming became clearer, leading to major international agreements such as the <strong>Kyoto Protocol (1997)</strong> and the <strong>Paris Agreement (2015)</strong>.
        </p>
      </div>

      <div class="info-card">
        <div class="card-icon">✨</div>
        <h2>What Can We Do?</h2>
        <p>Here are some steps individuals and communities can take to fight climate change:</p>
        <ul>
          <li>Reduce energy consumption by using energy-efficient appliances and turning off unused electronics.</li>
          <li>Use renewable energy sources like solar and wind power when possible.</li>
          <li>Plant trees and support reforestation efforts.</li>
          <li>Use public transportation, carpool, or cycle to reduce carbon emissions.</li>
          <li>Advocate for sustainable policies and environmental protection laws.</li>
        </ul>
      </div>
    </div>

    <div class="video-section">
      <h2>🎥 Watch: Understanding Climate Change</h2>
      <div class="video-container">
        <iframe
          src="https://www.youtube.com/embed/G4H1N_yXBiA"
          title="Climate Change Explained"
          allowfullscreen>
        </iframe>
      </div>
    </div>

    <div class="quiz-challenge">
      <h2>🧠 Test Your Knowledge!</h2>
      <button class="quiz-launch-btn" onclick="openQuiz()">
        📝 Quiz
      </button>
      <div class="progress-dots" style="margin-top: 25px;">
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot active"></div>
      </div>
    </div>

    <div class="back-section">
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
      <div style="display: flex; justify-content: center; gap: 15px; flex-wrap: wrap; margin-top: 20px;">
        <button class="start-btn" onclick="startQuiz()">Start Quiz</button>
        <button class="quiz-exit-btn" onclick="closeQuiz()">❌ Cancel</button>
      </div>
    </div>

    <!-- Quiz Content -->
    <div id="quiz" style="display:none;">
      <div class="quiz-question" id="question">Loading question...</div>
      <div class="quiz-buttons">
        <button onclick="answer(true)">✅ True</button>
        <button onclick="answer(false)">❌ False</button>
      </div>
      <div class="quiz-timer" id="timer">Time left: 60s</div>
      <div class="quiz-result" id="result"></div>
      <div class="quiz-controls">
        <button class="quiz-exit-btn" onclick="closeQuiz()">🚪 Exit Quiz</button>
      </div>
    </div>

    <!-- Final Result Screen -->
    <div id="finalResult" style="display:none;">
      <h3 style="color: #2E7D32; font-size: 32px; margin-bottom: 20px;">🎉 Quiz Complete!</h3>
      <div id="finalResultText" class="final-result-text"></div>
      <div class="quiz-final-buttons">
        <button class="quiz-restart-btn" onclick="restartQuiz()">🔄 Try Again</button>
        <button class="quiz-exit-btn" onclick="closeQuiz()">❌ Exit</button>
      </div>
    </div>
  </div>
</div>

<style>
  .quiz-modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.8);
    justify-content: center;
    align-items: center;
    backdrop-filter: blur(5px);
  }

  .quiz-modal.active {
    display: flex;
  }

  .quiz-box {
    background: linear-gradient(135deg, #ffffff 0%, #f0fff0 100%);
    padding: 40px;
    border-radius: 25px;
    text-align: center;
    position: relative;
    box-shadow: 0 15px 50px rgba(0,0,0,0.3);
    border: 4px solid #4CAF50;
    max-width: 600px;
    width: 90%;
  }

  .quiz-box h2 {
    color: #2E7D32;
    margin-bottom: 20px;
    font-size: 32px;
  }

  .close-btn {
    position: absolute;
    top: 15px;
    right: 25px;
    font-size: 32px;
    font-weight: bold;
    color: #2E7D32;
    cursor: pointer;
    transition: all 0.3s ease;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,0.9);
    border-radius: 50%;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
  }

  .close-btn:hover {
    transform: rotate(90deg) scale(1.1);
    background: #ff5252;
    color: white;
  }

  .quiz-instructions p {
    font-size: 16px;
    color: #555;
    line-height: 1.8;
  }

  .start-btn {
    background: linear-gradient(135deg, #66BB6A, #4CAF50);
    color: white;
    border: none;
    padding: 15px 40px;
    border-radius: 25px;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 20px;
    text-transform: uppercase;
    box-shadow: 0 4px 15px rgba(76,175,80,0.3);
  }

  .start-btn:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 6px 20px rgba(76,175,80,0.4);
  }

  .quiz-question {
    font-size: 20px;
    color: #2E7D32;
    font-weight: bold;
    margin-bottom: 30px;
    min-height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .quiz-buttons {
    display: flex;
    gap: 20px;
    justify-content: center;
    margin-bottom: 20px;
  }

  .quiz-buttons button {
    background: linear-gradient(135deg, #4CAF50, #388E3C);
    color: white;
    border: none;
    padding: 15px 40px;
    border-radius: 25px;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(76,175,80,0.3);
  }

  .quiz-buttons button:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 6px 20px rgba(76,175,80,0.4);
  }

  .quiz-timer {
    font-size: 18px;
    color: #FF9800;
    font-weight: bold;
    margin: 20px 0;
  }

  .quiz-result {
    font-size: 24px;
    color: #2E7D32;
    font-weight: bold;
    margin-top: 20px;
  }

  .quiz-controls {
    margin-top: 20px;
    display: flex;
    justify-content: center;
    gap: 10px;
  }

  .quiz-exit-btn {
    background: linear-gradient(135deg, #ff5252, #d32f2f);
    color: white;
    border: none;
    padding: 15px 40px;
    border-radius: 25px;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    box-shadow: 0 4px 15px rgba(255,82,82,0.3);
  }

  .quiz-exit-btn:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 6px 20px rgba(255,82,82,0.4);
  }

  .quiz-restart-btn {
    background: linear-gradient(135deg, #66BB6A, #4CAF50);
    color: white;
    border: none;
    padding: 15px 40px;
    border-radius: 25px;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    box-shadow: 0 4px 15px rgba(76,175,80,0.3);
  }

  .quiz-restart-btn:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 6px 20px rgba(76,175,80,0.4);
  }

  .final-result-text {
    font-size: 24px;
    color: #555;
    margin: 25px 0;
    line-height: 1.8;
  }

  .quiz-final-buttons {
    display: flex;
    justify-content: center;
    gap: 15px;
    flex-wrap: wrap;
    margin-top: 25px;
  }

  #finalResult {
    padding: 20px;
  }

  @media (max-width: 768px) {
    .quiz-box {
      padding: 20px;
      width: 95%;
      max-width: 450px;
    }

    .quiz-box h2 {
      font-size: 24px;
    }

    .quiz-question {
      font-size: 16px;
      min-height: 60px;
    }

    .quiz-buttons {
      flex-direction: column;
      gap: 12px;
    }

    .quiz-buttons button {
      padding: 12px 25px;
      font-size: 15px;
      width: 100%;
    }

    .quiz-exit-btn,
    .quiz-restart-btn {
      padding: 15px 40px;
      font-size: 18px;
    }

    .start-btn {
      padding: 15px 40px;
      font-size: 18px;
    }

    .final-result-text {
      font-size: 18px;
    }
  }

  @media (max-width: 480px) {
    .quiz-box {
      padding: 15px;
    }

    .quiz-question {
      font-size: 14px;
    }

    .quiz-buttons button {
      padding: 10px 20px;
      font-size: 14px;
    }
  }
</style>

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
    clearInterval(timer);
    document.getElementById('quiz').style.display = 'none';
    document.getElementById('finalResult').style.display = 'block';
    document.getElementById('finalResultText').innerHTML = `
      <p style="font-size: 28px; font-weight: bold; color: #4CAF50; margin: 15px 0;">
        Score: ${score}/${shuffledQuestions.length}
      </p>
      <p style="font-size: 18px; color: #666;">
        ${score >= shuffledQuestions.length * 0.8 ? '🌟 Excellent!' : score >= shuffledQuestions.length * 0.6 ? '👍 Good job!' : '💪 Keep learning!'}
      </p>
    `;
  }

  function restartQuiz() {
    document.getElementById('finalResult').style.display = 'none';
    document.getElementById('instructions').style.display = 'block';
    score = 0;
    currentQuestion = 0;
    timeLeft = 60;
  }
</script>


</body>
</html>
