<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Risk Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      background-color: #E6E6FA; /* ungu muda */
      color: #333;
      height: 100vh;
      overflow: hidden;
    }

    #particles-js {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -1;
      background-color: #E6E6FA;
    }

    .navbar {
      background-color: #650076; /* ungu tua */
    }

    .navbar .navbar-brand,
    .navbar .btn {
      color: #fff;
    }

    .navbar .btn:hover {
      background-color: #fff;
      color: #650076;
      border-color: #fff;
    }

    .login-box {
      background: #fff;
      padding: 2rem;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .btn-login {
      background-color: #BE58CF;
      border: none;
    }

    .btn-login:hover {
      background-color: #650076;
    }

    .error-message {
      color: red;
      font-size: 0.9rem;
      margin-bottom: 1rem;
    }

    footer {
      background-color: #650076;
      color: white;
    }
  </style>
</head>
<body class="d-flex flex-column min-vh-100 position-relative">
  <div id="particles-js"></div>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg shadow-sm">
    <div class="container">
      <a class="navbar-brand fw-bold" href="#">Risk Management</a>
      <a href="index.html" class="btn btn-outline-light ms-auto">Home</a>
    </div>
  </nav>

  <!-- Login Form -->
  <main class="flex-grow-1 d-flex align-items-center justify-content-center">
    <div class="login-box w-100" style="max-width: 400px;">
      <h2 class="text-center mb-4 fw-bold " style="color: #650076;">Login</h2>

      <?php
      session_start(); // Mulai sesi
      if (isset($_SESSION['error_message'])) {
          echo "<div class='error-message text-center'>{$_SESSION['error_message']}</div>";
          unset($_SESSION['error_message']);
      }
      ?>

      <form action="users/login_process.php" method="POST">
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input 
            type="text" 
            id="username" 
            name="username" 
            class="form-control" 
            placeholder="Username"
            required>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input 
            type="password" 
            id="password" 
            name="password" 
            class="form-control" 
            placeholder="Password"
            required>
        </div>
        <button type="submit" class="btn btn-login w-100 text-white fw-bold">Masuk</button>
        <div class="text-center mt-3">
          <a href="#" onclick="alert('Hubungi admin melalui email: admin@uin-suka.ac.id')" style="color: #650076; text-decoration: none hover:underline;">Forgot Password?</a>
        </div>
      </form>
    </div>
  </main>

  <!-- Footer -->
  <footer class="text-center py-3 mt-auto">
    <p class="mb-0">© 2025 Risk Management System — All Rights Reserved</p>
  </footer>

  <!-- Particles.js -->
  <script src="https://cdn.jsdelivr.net/npm/particles.js"></script>
  <script>
    particlesJS("particles-js", {
      "particles": {
        "number": {
          "value": 50,
          "density": { "enable": true, "value_area": 800 }
        },
        "color": { "value": "#650076" },
        "shape": { "type": "circle" },
        "opacity": {
          "value": 0.5,
          "random": false
        },
        "size": {
          "value": 3,
          "random": true
        },
        "line_linked": {
          "enable": true,
          "distance": 150,
          "color": "#650076",
          "opacity": 0.4,
          "width": 1
        },
        "move": {
          "enable": true,
          "speed": 2
        }
      },
      "interactivity": {
        "detect_on": "canvas",
        "events": {
          "onhover": { "enable": true, "mode": "repulse" },
          "onclick": { "enable": true, "mode": "push" }
        },
        "modes": {
          "repulse": { "distance": 100 },
          "push": { "particles_nb": 4 }
        }
      },
      "retina_detect": true
    });
  </script>
</body>
</html>
