<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recycling & Waste - SUSTENA</title>
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

    .module-section ul {
      margin-left: 20px;
      color: #444;
      line-height: 1.8;
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
      <h1>Recycling & Waste</h1>
      <p>Understand the importance of recycling and how to properly manage waste to protect our planet.</p>
    </div>

    <!-- Section 1: Introduction -->
    <div class="module-section">
      <h2>Why Recycling Matters</h2>
      <p>
        Recycling is the process of collecting, processing, and repurposing materials that would otherwise be discarded as waste.
        It helps conserve natural resources, reduce landfill waste, and minimize pollution caused by raw material extraction and manufacturing.
      </p>
    </div>

    <!-- Section 2: History of Recycling -->
    <div class="module-section">
      <h2>A Brief History of Recycling</h2>
      <p>
        Recycling has been around for centuries. Ancient civilizations reused bronze, glass, and other materials to conserve resources.
        In modern times, recycling gained global importance during the 1970s environmental movement, with the iconic recycling symbol being created in 1970.
      </p>
    </div>

    <!-- Section 3: Steps to Effective Recycling -->
    <div class="module-section">
      <h2>How to Recycle Properly</h2>
      <p>Follow these steps to ensure proper recycling:</p>
      <ul>
        <li>Separate recyclable items from general waste.</li>
        <li>Clean and dry containers like bottles and cans before recycling.</li>
        <li>Know which materials are accepted in your local recycling program.</li>
        <li>Reuse items like jars, boxes, and bags whenever possible.</li>
        <li>Compost organic waste such as food scraps and garden trimmings.</li>
      </ul>
    </div>

    <!-- Section 4: Embedded Video -->
    <div class="module-section">
      <h2>Watch: Recycling Explained</h2>
      <div class="video-container">
        <iframe 
          src="https://www.youtube.com/embed/_6xlNyWPpB8" 
          title="Recycling and Waste Management"
          allowfullscreen>
        </iframe>
      </div>
    </div>

    <!-- Section 5: Tips for Waste Reduction -->
    <div class="module-section">
      <h2>Simple Ways to Reduce Waste</h2>
      <ul>
        <li>Carry reusable shopping bags and water bottles.</li>
        <li>Avoid single-use plastics whenever possible.</li>
        <li>Buy in bulk to reduce packaging waste.</li>
        <li>Support companies that use eco-friendly packaging.</li>
        <li>Donate old clothes and electronics instead of throwing them away.</li>
      </ul>
    </div>

    <!-- Back button -->
    <div class="module-footer" style="text-align:center;">
      <a href="{{ url('/learning-modules') }}" class="back-button">← Back to Learning Modules</a>
    </div>
  </div>

</body>
</html>
