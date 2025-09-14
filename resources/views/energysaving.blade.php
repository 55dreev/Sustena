<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Energy Saving - SUSTENA</title>
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

    /* List styling */
    ul {
      padding-left: 20px;
    }

    ul li {
      margin-bottom: 8px;
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
      <h1>Energy Saving and Conservation</h1>
      <p>Learn how to save energy, lower costs, and protect the environment through smart energy practices.</p>
    </div>

    <!-- Section 1: Introduction -->
    <div class="module-section">
      <h2>Why Energy Saving Matters</h2>
      <p>
        Energy saving is the practice of using less energy by eliminating unnecessary usage and improving efficiency.
        By conserving energy, we reduce greenhouse gas emissions, slow climate change, and save money on electricity bills.
        Simple habits, like turning off unused devices and using renewable energy, can make a big difference.
      </p>
    </div>

    <!-- Section 2: Sources of Energy -->
    <div class="module-section">
      <h2>Sources of Energy</h2>
      <p>
        Our energy comes from different sources, and some are more sustainable than others:
      </p>
      <ul>
        <li><strong>Renewable Energy</strong> – Solar, wind, hydro, and geothermal are clean and sustainable.</li>
        <li><strong>Non-Renewable Energy</strong> – Fossil fuels like coal, oil, and natural gas are limited and harmful to the environment.</li>
      </ul>
      <p>Shifting to renewable sources is key to reducing pollution and ensuring a sustainable future.</p>
    </div>

    <!-- Section 3: Energy Saving Tips -->
    <div class="module-section">
      <h2>Practical Energy Saving Tips</h2>
      <ul>
        <li>Switch to LED bulbs instead of incandescent lights.</li>
        <li>Unplug chargers and electronics when not in use.</li>
        <li>Use natural light during the day to reduce electricity use.</li>
        <li>Invest in energy-efficient appliances with a high energy rating.</li>
        <li>Limit air conditioning and set thermostats to optimal temperatures.</li>
        <li>Consider installing solar panels for sustainable energy.</li>
      </ul>
    </div>

    <!-- Section 4: Embedded Video -->
    <div class="module-section">
      <h2>Watch: Easy Energy Saving Hacks</h2>
      <div class="video-container">
        <iframe 
           src="https://www.youtube.com/embed/D11iFUw_ImU" 
          title="Energy Saving Tips"
          allowfullscreen>
        </iframe>
      </div>
    </div>

    <!-- Section 5: Summary -->
    <div class="module-section">
      <h2>Summary</h2>
      <p>
        Saving energy not only lowers your electricity bill but also helps combat climate change.
        Through simple steps like reducing waste, switching to renewables, and making energy-smart decisions,
        we can create a cleaner, more sustainable future for everyone.
      </p>
    </div>

    <!-- Back button -->
    <div class="module-footer" style="text-align:center;">
      <a href="{{ url('/learning-modules') }}" class="back-button">← Back to Learning Modules</a>
    </div>
  </div>
          
</body>
</html>
