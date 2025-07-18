<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
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
    <input type="text" name="username" placeholder="Username" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Login</button>
    <button type="button" id="showSignup">Sign Up</button>
    <button type="button">Sign in with Gmail</button>
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
    <input type="text" name="username" placeholder="Username" required>
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password" required>
<input type="password" name="password_confirmation" placeholder="Confirm Password" required>
<!-- Fix button id -->
<button type="button" id="showLoginFromSignup">Back to Login</button>

    <button type="submit" name="register">Register</button>

</form>



    </div>  


    <!-- Forgot Password Form -->
    <div class="login-container" id="forgot" style="display: none;">
        <h1>Sustena</h1>
        <h2>Forgot Password</h2>
        <form method="POST" action="/forgot-password">
            @csrf
            <input type="email" name="email" placeholder="Enter your email" required><br>
            <button type="submit">Send Reset Link</button>
            <button type="button" id="showLoginFromForgot">Back to Login</button>
        </form>
    </div>

    <script>
    // Loader transition
    setTimeout(() => {
        const loader = document.querySelector('.loader');
        const login = document.getElementById('login');

        loader.classList.add('fade-out');

        setTimeout(() => {
            loader.style.display = 'none';
            document.body.classList.add('fade-in-bg');
            login.style.display = 'block';
            login.style.opacity = 0;
            login.style.transition = 'opacity 1s ease';
            setTimeout(() => {
                login.style.opacity = 1;
            }, 100);
        }, 1000);
    }, 4000);

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

    if (hasSuccessMessage) {
        document.getElementById('login').style.display = 'none';
        document.getElementById('signup').style.display = 'block';

        // ✅ Show success alert
        alert("Registration successful! ✅");

        // ⏳ Optional: Auto switch back to login after 2 seconds
        setTimeout(() => {
            document.getElementById('signup').style.display = 'none';
            document.getElementById('login').style.display = 'block';
        }, 2000);
    }
</script>


</body>
</html>
