<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUSTENA - Planet Stages Test</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0a0e27 0%, #1a1f3a 100%);
            color: #fff;
            padding: 40px;
            min-height: 100vh;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
            font-size: 42px;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #4ade80 0%, #22c55e 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .subtitle {
            text-align: center;
            font-size: 18px;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 40px;
        }

        .stages-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .stage-card {
            background: rgba(26, 31, 58, 0.8);
            border: 2px solid rgba(74, 222, 128, 0.3);
            border-radius: 16px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .stage-card:hover {
            transform: translateY(-8px);
            border-color: rgba(74, 222, 128, 0.6);
            box-shadow: 0 12px 32px rgba(74, 222, 128, 0.2);
        }

        .stage-number {
            font-size: 48px;
            font-weight: 700;
            color: #4ade80;
            margin-bottom: 10px;
        }

        .stage-name {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .stage-score {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 20px;
        }

        .stage-preview {
            width: 150px;
            height: 150px;
            margin: 0 auto 20px;
            border-radius: 50%;
            position: relative;
            overflow: hidden;
        }

        .stage-preview.stage-0 {
            background: radial-gradient(circle, #4a2c1f 0%, #2d1810 100%);
            box-shadow: inset -10px -10px 30px rgba(0, 0, 0, 0.5), 0 0 30px rgba(100, 60, 40, 0.3);
        }

        .stage-preview.stage-1 {
            background: radial-gradient(circle, #5a3c2f 0%, #3d2820 100%);
            box-shadow: inset -10px -10px 30px rgba(0, 0, 0, 0.5), 0 0 30px rgba(120, 80, 60, 0.3);
        }

        .stage-preview.stage-2 {
            background: radial-gradient(circle, #4a5c3f 0%, #2d3820 100%);
            box-shadow: inset -10px -10px 30px rgba(0, 0, 0, 0.4), 0 0 30px rgba(100, 120, 80, 0.3);
        }

        .stage-preview.stage-3 {
            background: radial-gradient(circle, #3a6c4f 0%, #2d4830 100%);
            box-shadow: inset -10px -10px 30px rgba(0, 0, 0, 0.3), 0 0 30px rgba(80, 140, 100, 0.4);
        }

        .stage-preview.stage-4 {
            background: radial-gradient(circle, #2a7c5f 0%, #1d5840 100%);
            box-shadow: inset -10px -10px 30px rgba(0, 0, 0, 0.3), 0 0 30px rgba(74, 222, 128, 0.4);
        }

        .stage-preview.stage-5 {
            background: radial-gradient(circle, #1a8c6f 0%, #0d6850 100%);
            box-shadow: inset -10px -10px 30px rgba(0, 0, 0, 0.2), 0 0 40px rgba(74, 222, 128, 0.5);
            animation: pulse-preview 3s infinite;
        }

        .stage-preview.stage-6 {
            background: radial-gradient(circle, #0a9c7f 0%, #067860 100%);
            box-shadow: inset -10px -10px 30px rgba(0, 0, 0, 0.2), 0 0 50px rgba(74, 222, 128, 0.6);
            animation: pulse-preview 2s infinite;
        }

        @keyframes pulse-preview {
            0%, 100% { box-shadow: inset -10px -10px 30px rgba(0, 0, 0, 0.2), 0 0 40px rgba(74, 222, 128, 0.5); }
            50% { box-shadow: inset -10px -10px 30px rgba(0, 0, 0, 0.2), 0 0 60px rgba(74, 222, 128, 0.8); }
        }

        .btn-primary {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #4ade80 0%, #22c55e 100%);
            color: #0a0e27;
            font-weight: 600;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-primary:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 24px rgba(74, 222, 128, 0.4);
        }

        .info-box {
            background: rgba(26, 31, 58, 0.6);
            border: 1px solid rgba(74, 222, 128, 0.2);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 40px;
            text-align: center;
        }

        .info-box p {
            font-size: 16px;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.8);
        }

        .back-link {
            display: inline-block;
            margin-top: 30px;
            padding: 10px 20px;
            background: rgba(74, 222, 128, 0.1);
            border: 1px solid rgba(74, 222, 128, 0.3);
            border-radius: 8px;
            color: #4ade80;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            background: rgba(74, 222, 128, 0.2);
            border-color: rgba(74, 222, 128, 0.5);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🌍 Planet Evolution Stages Test</h1>
        <p class="subtitle">Click on any stage to see the full interactive planet view</p>

        <div class="info-box">
            <p>
                <strong>Testing Instructions:</strong><br>
                Click on any stage below to preview how the planet looks at that level of progress.
                Each stage represents different milestones in your sustainability journey.
            </p>
        </div>

        <div class="stages-grid">
            @foreach($stages as $stage)
                <a href="{{ url('/planet-test/' . $stage['stage']) }}" class="stage-card">
                    <div class="stage-preview stage-{{ $stage['stage'] }}"></div>
                    <div class="stage-number">Stage {{ $stage['stage'] + 1 }}</div>
                    <div class="stage-name">{{ $stage['name'] }}</div>
                    <div class="stage-score">{{ $stage['score'] }} Progress</div>
                    <span class="btn-primary">View Stage</span>
                </a>
            @endforeach
        </div>

        <div style="text-align: center;">
            <a href="{{ url('/visual-progress') }}" class="back-link">← View Your Actual Progress</a>
            <a href="{{ url('/landing-page') }}" class="back-link">← Back to Home</a>
        </div>
    </div>
</body>
</html>
