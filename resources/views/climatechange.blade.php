<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    <!-- Back button -->
    <div class="module-footer" style="text-align:center;">
      <a href="{{ url('/learning-modules') }}" class="back-button">← Back to Learning Modules</a>

    </div>
  </div>

</body>
</html>
