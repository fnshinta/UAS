<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <link href="assets/css/palette.css" rel="stylesheet">
    <style>
        #particles-js {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
            background-color: rgb(245, 235, 255); /* Latar belakang ungu muda */
        }
        .login-box {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.06);
        }
        .error-message {
            color: #ff0000;
            font-size: 0.875rem;
            text-align: center;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body class="bg-white flex flex-col min-h-screen">

    <!-- Latar Partikel -->
    <div id="particles-js"></div>

    <!-- Header -->
    <header class="bg-purple-100 shadow w-full">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="text-xl font-bold text-purple-800">Risk Management</span>
            </div>
            <div>
                <a href="index.php" class="btn btn-sm font-bold text-purple-700 py-2 px-4 rounded transition duration-300 hover:bg-purple-700 hover:text-white">
                    Home
                </a>
            </div>
        </div>
    </header>

    <!-- Login Form -->
    <main class="flex-grow flex items-center justify-center">
        <div class="bg-white login-box rounded-lg p-8 w-full max-w-md">
            <h2 class="text-2xl font-bold text-center text-purple-700 mb-6">Login</h2>
            <?php
            if (isset($_SESSION['error_message'])) {
                echo "<div class='error-message'>{$_SESSION['error_message']}</div>";
                unset($_SESSION['error_message']);
            }
            ?>

            <form action="users/proses_login.php" method="POST" class="space-y-4">
                <div>
                    <label for="username" class="block text-gray-700 font-medium">Username</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none"
                        placeholder="Username"
                        required
                    >
                </div>
                <div>
                    <label for="password" class="block text-gray-700 font-medium">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none"
                        placeholder="Password"
                        required
                    >
                </div>
                <button 
                    type="submit" 
                    class="w-full bg-purple-700 text-white font-medium py-2 px-4 rounded-lg hover:bg-purple-800 focus:ring-2 focus:ring-purple-500 focus:outline-none transition-all"
                >
                    Masuk
                </button>
                <div class="text-center mt-4">
                    <a href="#" 
                    onclick="alert('Hubungi admin melalui email: admin@uin-suka.ac.id');" 
                    class="text-purple-600 hover:underline">
                        Forgot Password?
                    </a>
                </div>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-purple-800 text-white text-center">
        <div class="bg-purple-600 py-2"></div>
        <div class="py-4">
            © 2025 Risk Management Dashboard. All rights reserved.
        </div>
    </footer>

    <!-- Particles.js Konfigurasi -->
    <script>
    particlesJS("particles-js", {
      particles: {
        number: {
          value: 80,
          density: {
            enable: true,
            value_area: 800
          }
        },
        color: {
          value: "#a020f0"
        },
        shape: {
          type: "circle"
        },
        opacity: {
          value: 0.4
        },
        size: {
          value: 4
        },
        line_linked: {
          enable: true,
          distance: 150,
          color: "#a020f0",
          opacity: 0.2,
          width: 1
        },
        move: {
          enable: true,
          speed: 2
        }
      },
      interactivity: {
        detect_on: "canvas",
        events: {
          onhover: {
            enable: true,
            mode: "grab"
          }
        }
      },
      retina_detect: true
    });
    </script>
</body>
</html>
