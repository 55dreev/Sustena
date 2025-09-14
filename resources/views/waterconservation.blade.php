<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Water Conservation - SUSTENA</title>
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
      <h1>Water Conservation</h1>
      <p>Learn about the importance of saving water and how you can make a difference in protecting this precious resource.</p>
    </div>

    <!-- Section 1: Introduction -->
    <div class="module-section">
      <h2>What is Water Conservation?</h2>
      <p>
        Water conservation involves the sustainable management of freshwater resources to ensure a balance between human consumption and natural ecosystems. 
        With climate change and population growth, the need to conserve water has become more critical than ever.
      </p>
    </div>

    <!-- Section 2: Importance -->
    <div class="module-section">
      <h2>Why Water Conservation Matters</h2>
      <p>
        Clean water is essential for life. Conserving water helps:
      </p>
      <ul>
        <li>Ensure safe drinking water for future generations.</li>
        <li>Protect natural ecosystems and wildlife.</li>
        <li>Reduce energy costs linked to water treatment and delivery.</li>
        <li>Mitigate the effects of droughts and water shortages.</li>
      </ul>
    </div>

    <!-- Section 3: Embedded Video -->
    <div class="module-section">
      <h2>Watch: Simple Water Saving Tips</h2>
      <div class="video-container">
        <iframe 
  src="https://www.youtube.com/embed/gJmY3dzg3Gk" 
  title="Water Conservation Video" 
  allowfullscreen>
</iframe>


      </div>
    </div>

    <!-- Section 4: Practical Steps -->
    <div class="module-section">
      <h2>How You Can Help</h2>
      <p>
        Small changes in your daily life can make a huge impact. Here are a few steps to conserve water:
      </p>
      <ul>
        <li>Turn off the tap while brushing your teeth or washing dishes.</li>
        <li>Fix leaks in sinks, toilets, and pipes promptly.</li>
        <li>Collect rainwater for gardening and outdoor use.</li>
        <li>Use water-efficient appliances and fixtures.</li>
        <li>Choose drought-resistant plants for landscaping.</li>
      </ul>
    </div>

    <!-- Back button -->
    <div class="module-footer" style="text-align:center;">
      <a href="{{ url('/learning-modules') }}" class="back-button">← Back to Learning Modules</a>
    </div>
  </div>

</body>
</html>
