<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>Sustena - Reset Password</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Loader -->
    <div class="loader">

    </div>

    <!-- Circle Transition -->
    <div class="circle-transition"></div>

    <!-- Reset Password Form -->
    <div class="login-container" id="reset" style="display: none;">
        <h1>Sustena</h1>
        <h2>Reset Your Password</h2>
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="email" name="email" value="{{ old('email', $email) }}" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="New Password" required><br>
            <input type="password" name="password_confirmation" placeholder="Confirm New Password" required><br>
            <button type="submit">Reset Password</button> 
        </form>
    </div>

    <script>
        setTimeout(() => {
            const loader = document.querySelector('.loader');
            const reset = document.getElementById('reset');

            loader.classList.add('fade-out');

            setTimeout(() => {
                loader.style.display = 'none';
                document.body.classList.add('fade-in-bg');
                reset.style.display = 'block';
                reset.style.opacity = 0;
                reset.style.transition = 'opacity 1s ease';
                setTimeout(() => {
                    reset.style.opacity = 1;
                }, 10);
            }, 100);
        }, 400);
    </script>
</body>
</html>