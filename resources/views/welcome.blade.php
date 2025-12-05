<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <link rel="stylesheet" href="{{ asset('css/welcomeresponsive.css') }}">
<link rel="stylesheet" href="{{ asset('css/style.css') }}">



    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Loader -->
    <div class="loader">
        <p>loading</p>
        <div class="words">
            <span class="word">Earth</span>
            <span class="word">Environment</span>
            <span class="word">Games</span>
            <span class="word">Streaks</span>
            <span class="word">Sustena</span>
        </div>
    </div>

    <!-- Circle Transition -->
    <div class="circle-transition"></div>

    <!-- Login Form -->
    <div class="login-container" id="login">
        <h1>Sustena</h1>
        <h2>Login</h2>

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <input type="text" name="username" placeholder="Username" required>
            <div class="password-input-container">
    <input type="password" id="login_password" name="password" placeholder="Password" required>

    <span class="toggle-password eye-closed"
          data-target="login_password">
    </span>
</div>

            <div class="eula-container" style="margin: 8px 0 12px 0;">
                <label style="display: flex; align-items: center; gap: 6px; font-size: 10px; cursor: pointer; color: rgba(255,255,255,0.8);">
                    <input type="checkbox" name="accept_eula" id="accept_eula" required
                           style="cursor: pointer; width: 14px; height: 14px; flex-shrink: 0;">
                    <span style="line-height: 1.3;">
                        I agree to the <a href="#" id="view-eula" style="color: rgba(255,255,255,0.9); text-decoration: underline;">EULA</a>
                    </span>
                </label>
                @error('accept_eula')
                    <span style="color: #ff4444; font-size: 9px; display: block; margin-top: 3px; margin-left: 20px;">{{ $message }}</span>
                @enderror
            </div>

           <div class="button-group">
    <button type="submit" class="login-btn">Login</button>
    <button type="button" id="showSignup" class="signup-btn">Sign Up</button>
</div>

{{-- Google login --}}
<button type="button"
        class="gmail-btn"
        onclick="window.location.href='{{ route('auth.google') }}'">
          <img src="{{ asset('assets/google.png') }}" class="google-icon">
    Sign in with Gmail
  
</button>

        </form>

        <p style="margin-top: 15px;">
            <a href="#" class="forgot-password">Forgot password?</a>
        </p>
    </div>

    <!-- Signup Form -->
    <div class="login-container" id="signup" style="display: none;">
        <h1>Sustena</h1>
        <h2>Sign Up</h2>

        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div style="position: relative; width: 100%;">
                <input type="text" name="username" placeholder="Username" value="{{ old('username') }}" required>
                @error('username')
                    <span style="color: #ff4444; font-size: 10px; display: block; margin-top: 3px;">{{ $message }}</span>
                @enderror
            </div>

            <div style="position: relative; width: 100%;">
                <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                @error('email')
                    <span style="color: #ff4444; font-size: 10px; display: block; margin-top: 3px;">{{ $message }}</span>
                @enderror
            </div>

            <div class="password-input-container">
                <input type="password" id="signup_password" name="password" placeholder="Password (min. 6 characters)" required>
                <span class="toggle-password eye-closed" data-target="signup_password"></span>
            </div>
            @error('password')
                <span style="color: #ff4444; font-size: 10px; display: block; margin-top: 3px;">{{ $message }}</span>
            @enderror

            <div class="password-input-container">
                <input type="password" id="signup_confirm" name="password_confirmation" placeholder="Confirm Password" required>
                <span class="toggle-password eye-closed" data-target="signup_confirm"></span>
            </div>

            <button type="button" id="showLoginFromSignup">Back to Login</button>
            <button type="submit" name="register">Register</button>
        </form>
    </div>

    <!-- Forgot Password Form -->
    <div class="login-container" id="forgot" style="display: none;">
        <h1>Sustena</h1>
        <h2>Forgot Password</h2>

        {{-- Uses POST /forgot-password with name("password.email") --}}
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <input type="email" name="email" placeholder="Enter your email" required><br>
            <button type="submit">Send Reset Link</button>
            <button type="button" id="showLoginFromForgot">Back to Login</button>
        </form>
    </div>

    <!-- EULA Modal -->
    <div id="eulaModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 9999; overflow: auto;">
        <div style="background: white; margin: 50px auto; padding: 30px; max-width: 700px; border-radius: 10px; position: relative;">
            <button id="closeEula" style="position: absolute; top: 15px; right: 15px; background: #ff4444; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; font-weight: bold;">✕</button>
            <h2 style="color: #333; margin-bottom: 20px;">End User License Agreement (EULA)</h2>
            <div style="max-height: 400px; overflow-y: auto; padding: 15px; background: #f9f9f9; border-radius: 5px; color: #333; line-height: 1.6;">
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
            <button id="acceptEulaModal" style="margin-top: 20px; width: 100%; background: #4CAF50; color: white; border: none; padding: 12px; border-radius: 5px; cursor: pointer; font-weight: bold;">I Accept</button>
        </div>
    </div>

    <script>
        // Check if user has already seen the loading screen in this session
        const hasSeenLoader = sessionStorage.getItem('sustena_loader_shown');

        if (hasSeenLoader) {
            // Skip loader, show login immediately
            const loader = document.querySelector('.loader');
            const login = document.getElementById('login');
            const circle = document.querySelector('.circle-transition');

            loader.style.display = 'none';
            circle.style.display = 'none';
            document.body.classList.add('fade-in-bg');
            login.style.display = 'block';
            login.style.opacity = 1;
        } else {
            // Show loader animation for first visit
            sessionStorage.setItem('sustena_loader_shown', 'true');

            setTimeout(() => {
                const loader = document.querySelector('.loader');
                const login = document.getElementById('login');
                const circle = document.querySelector('.circle-transition');

                loader.classList.add('fade-out');

                setTimeout(() => {
                    loader.style.display = 'none';

                    // Start circle transition
                    circle.classList.add('active');

                    setTimeout(() => {
                        // Fade out circle at the same time as login fades in
                        circle.classList.add('fade-out');

                        document.body.classList.add('fade-in-bg');
                        login.style.display = 'block';
                        login.style.opacity = 0;
                        login.style.transition = 'opacity 1s ease';

                        setTimeout(() => {
                            login.style.opacity = 1;
                        }, 50);

                        // Remove circle from DOM after fade
                        setTimeout(() => {
                            circle.style.display = 'none';
                        }, 500); // matches fade-out duration
                    }, 800); // slightly before full circle expansion
                }, 1000);
            }, 4000);
        }

        // Toggle logic
        document.getElementById('showSignup').addEventListener('click', () => {
            document.getElementById('login').style.display = 'none';
            document.getElementById('signup').style.display = 'block';
        });

        document.getElementById('showLoginFromSignup').addEventListener('click', () => {
            document.getElementById('signup').style.display = 'none';
            document.getElementById('login').style.display = 'block';
        });

        document.querySelector('.forgot-password').addEventListener('click', (e) => {
            e.preventDefault();
            document.getElementById('login').style.display = 'none';
            document.getElementById('forgot').style.display = 'block';
        });

        document.getElementById('showLoginFromForgot').addEventListener('click', () => {
            document.getElementById('forgot').style.display = 'none';
            document.getElementById('login').style.display = 'block';
        });

        const hasSuccessMessage = @json(session('success') !== null);
        const hasErrors = @json($errors->any());

        if (hasSuccessMessage) {
            document.getElementById('login').style.display = 'none';
            document.getElementById('signup').style.display = 'block';

            // Show success alert
            alert("Registration successful! ✅");

            // Optional: Auto switch back to login after 2 seconds
            setTimeout(() => {
                document.getElementById('signup').style.display = 'none';
                document.getElementById('login').style.display = 'block';
            }, 2000);
        } else if (hasErrors) {
            // If there are validation errors, show the signup form
            document.getElementById('login').style.display = 'none';
            document.getElementById('signup').style.display = 'block';
        }
        

document.querySelectorAll(".toggle-password").forEach(icon => {
    icon.addEventListener("click", () => {
        const input = document.getElementById(icon.dataset.target);

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("eye-closed");
            icon.classList.add("eye-open");
        } else {
            input.type = "password";
            icon.classList.remove("eye-open");
            icon.classList.add("eye-closed");
        }
    });
});

// EULA Modal functionality
document.getElementById('view-eula').addEventListener('click', (e) => {
    e.preventDefault();
    document.getElementById('eulaModal').style.display = 'block';
});

document.getElementById('closeEula').addEventListener('click', () => {
    document.getElementById('eulaModal').style.display = 'none';
});

document.getElementById('acceptEulaModal').addEventListener('click', () => {
    document.getElementById('accept_eula').checked = true;
    document.getElementById('eulaModal').style.display = 'none';
});

// Close modal when clicking outside
document.getElementById('eulaModal').addEventListener('click', (e) => {
    if (e.target.id === 'eulaModal') {
        document.getElementById('eulaModal').style.display = 'none';
    }
});

    </script>
</body>
</html>
