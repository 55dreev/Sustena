<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accept EULA - Sustena</title>
    <link rel="stylesheet" href="{{ asset('css/welcomeresponsive.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .eula-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            max-width: 800px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .eula-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .eula-header h1 {
            color: #4CAF50;
            margin-bottom: 10px;
        }
        .eula-content {
            max-height: 400px;
            overflow-y: auto;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 10px;
            margin-bottom: 25px;
            color: #333;
            line-height: 1.6;
        }
        .eula-content h3 {
            color: #333;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        .eula-content h4 {
            color: #555;
            margin-top: 15px;
            margin-bottom: 8px;
        }
        .checkbox-container {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            padding: 15px;
            background: #f0f0f0;
            border-radius: 8px;
        }
        .checkbox-container input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
        .checkbox-container label {
            font-size: 14px;
            color: #333;
            cursor: pointer;
        }
        .button-group {
            display: flex;
            gap: 15px;
        }
        .accept-btn, .decline-btn {
            flex: 1;
            padding: 15px;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
        }
        .accept-btn {
            background: #4CAF50;
            color: white;
        }
        .accept-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        .accept-btn:not(:disabled):hover {
            background: #45a049;
        }
        .decline-btn {
            background: #f44336;
            color: white;
        }
        .decline-btn:hover {
            background: #da190b;
        }
        .error-message {
            color: #ff4444;
            text-align: center;
            margin-bottom: 15px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="eula-container">
        <div class="eula-header">
            <h1>Welcome to Sustena!</h1>
            <p style="color: #666;">Please review and accept our Terms of Service to continue</p>
        </div>

        <div class="eula-content">
            <h3>Terms of Service - Sustena Platform</h3>
            <p><strong>Last Updated:</strong> December 2025</p>

            <h4>1. Acceptance of Terms</h4>
            <p>By accessing and using Sustena, you accept and agree to be bound by the terms and provision of this agreement.</p>

            <h4>2. Use License</h4>
            <p>Permission is granted to temporarily access and use Sustena for personal, non-commercial purposes. This is the grant of a license, not a transfer of title.</p>

            <h4>3. User Account</h4>
            <p>You are responsible for maintaining the confidentiality of your account and password. You agree to accept responsibility for all activities that occur under your account.</p>

            <h4>4. Data Collection</h4>
            <p>Sustena collects data about your carbon footprint, activity streaks, and gamification progress to provide personalized sustainability insights. We are committed to protecting your privacy.</p>

            <h4>5. User Conduct</h4>
            <p>You agree not to use Sustena to:</p>
            <ul>
                <li>Violate any laws or regulations</li>
                <li>Post harmful, offensive, or inappropriate content</li>
                <li>Attempt to gain unauthorized access to the system</li>
                <li>Manipulate XP, streaks, or other gamification features</li>
            </ul>

            <h4>6. Intellectual Property</h4>
            <p>All content, features, and functionality are owned by Sustena and are protected by international copyright, trademark, and other intellectual property laws.</p>

            <h4>7. Termination</h4>
            <p>We reserve the right to terminate or suspend your account at any time for violation of these terms.</p>

            <h4>8. Disclaimer</h4>
            <p>Sustena is provided "as is" without any warranties, expressed or implied. We do not guarantee that the service will be uninterrupted or error-free.</p>

            <h4>9. Limitation of Liability</h4>
            <p>Sustena shall not be liable for any indirect, incidental, special, consequential, or punitive damages resulting from your use of the platform.</p>

            <h4>10. Changes to Terms</h4>
            <p>We reserve the right to modify these terms at any time. Continued use of Sustena constitutes acceptance of modified terms.</p>

            <h4>Contact</h4>
            <p>For questions about these terms, please contact the Sustena team through the platform.</p>
        </div>

        @if($errors->any())
            <div class="error-message">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('auth.google.accept-eula') }}" id="eulaForm">
            @csrf
            <div class="checkbox-container">
                <input type="checkbox" name="accept_eula" id="accept_eula" required>
                <label for="accept_eula">I have read and agree to the Terms of Service and EULA</label>
            </div>

            <div class="button-group">
                <button type="submit" class="accept-btn" id="acceptBtn" disabled>Accept & Continue</button>
                <a href="{{ route('welcome') }}" class="decline-btn" style="display: flex; align-items: center; justify-content: center; text-decoration: none;">Decline</a>
            </div>
        </form>
    </div>

    <script>
        const checkbox = document.getElementById('accept_eula');
        const acceptBtn = document.getElementById('acceptBtn');

        checkbox.addEventListener('change', function() {
            acceptBtn.disabled = !this.checked;
        });
    </script>
</body>
</html>
