<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/adminlogin.css') }}">
  <title>Sustena Admin Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">

</head>

<body>
  <!-- Loader -->
  <div class="loader">
    <span>Loading</span>
    <div class="words">
      <span class="word">System</span>
      <span class="word">Admin</span>
      <span class="word">Sustena</span>
      <span class="word">Access</span>
    </div>
  </div>

  <!-- Green Reveal -->
  <div class="reveal" id="reveal"></div>

  <!-- Admin Login -->
  <div class="admin-login" id="adminLogin">
    <h1>Sustena Admin</h1>
    <form action="{{ route('admin.login') }}" method="POST">
      @csrf
      <input type="text" name="username" placeholder="Admin Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
      <button type="button" onclick="window.location.href='{{ route('login') }}'">Back to User</button>
    </form>
  </div>

  <script>
    setTimeout(() => {
      const loader = document.querySelector('.loader');
      const reveal = document.getElementById('reveal');
      const login = document.getElementById('adminLogin');

      loader.classList.add('fade-out');

      setTimeout(() => {
        reveal.classList.add('expand'); // green reveal

        setTimeout(() => {
          loader.style.display = 'none';
          reveal.style.opacity = 0; // fade out reveal
          reveal.style.transition = 'opacity 1s ease';
          setTimeout(() => {
            login.style.display = 'block';
            login.style.opacity = 0;
            login.style.transition = 'opacity 1.2s ease';
            setTimeout(() => {
              login.style.opacity = 1;
            }, 100);
          }, 500);
        }, 1200);
      }, 800);
    }, 3500);
  </script>
</body>
</html>
