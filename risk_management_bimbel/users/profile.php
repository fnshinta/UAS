<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Risk Management Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        
    #userMenu {
        position: absolute; /* Agar dropdown muncul relatif terhadap parent */
        z-index: 50; /* Pastikan elemen berada di atas elemen lain */
        background-color: white; /* Warna background agar terlihat */
        border: 1px solid #ddd; /* Tambahkan border untuk visibilitas */
        padding: 10px; /* Tambahkan padding agar terlihat rapi */
        border-radius: 5px; /* Membuat sudut melengkung */
        display: none; /* Secara default, dropdown disembunyikan */
    }

    #userMenu.scale-100 {
        display: block; /* Ditampilkan saat class 'scale-100' ditambahkan */
    }

</style>
</head>
<body class="bg-light-purple text-gray-800 flex flex-col min-h-screen">
   <!-- Top Navbar -->
<header class="bg-purple-800 text-white shadow">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <div class="flex items-center space-x-3">
            <i class="fas fa-shield-alt text-2xl text-purple-300"></i>
            <span class="text-xl font-bold">Risk Management</span>
        </div>
       <nav class="flex justify-center items-center space-x-6">
    <a href="dashboard.php" class="hover:underline flex items-center space-x-1">
        <i class="fas fa-home"></i>
        <span class="text-center">Dashboard</span>
    </a>
    <a href="identification.php" class="hover:underline flex items-center space-x-1">
        <i class="fas fa-fingerprint"></i>
        <span class="text-center">Identification</span>
    </a>
    <a href="mitigation.php" class="hover:underline flex items-center space-x-1 font-semibold underline">
        <i class="fas fa-list-check"></i>
        <span class="text-center">Mitigations</span>
    </a>
    <a href="monitoring.php" class="hover:underline flex items-center space-x-1">
        <i class="fas fa-chart-line"></i>
        <span class="text-center">Monitoring</span>
    </a>
    <a href="riskmap.php" class="hover:underline flex items-center space-x-1">
        <i class="fas fa-map-marked-alt"></i>
        <span class="text-center">Risk Map</span>
    </a>
    <a href="reports.php" class="hover:underline flex items-center space-x-1">
        <i class="fas fa-file-alt"></i>
        <span class="text-center">Reports</span>
    </a>
    <a href="manage_users.php" class="hover:underline flex items-center space-x-1">
        <i class="fas fa-users-cog"></i>
        <span class="text-center">Manage Users</span>
    </a>
</nav>      
        <!-- Hamburger Button -->
        <button id="hamburgerButton" class="md:hidden focus:outline-none transition-transform duration-300 transform hover:scale-110">
            <svg class="w-6 h-6 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
        </button>

        <!-- User Profile -->
        <div class="relative">
            <button id="userMenuButton" class="flex items-center space-x-4 focus:outline-none transition-all duration-300 hover:scale-105">
                <!-- Gambar Profile -->
                <div class="bg-purple-500 w-8 h-8 rounded-full flex justify-center items-center text-white font-bold text-sm">
                    <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                </div>
                <!-- Tulisan Profil dan Role -->
                <div class="hidden md:flex flex-col text-left">
                    <span class="text-white-700 font-semibold text-base leading-tight">Welcome, <?php echo htmlspecialchars($username); ?></span>
                    <span class="text-white-500 text-sm leading-tight"><?php echo ucfirst($_SESSION['role']); ?></span>
                </div>
                <!-- Icon Dropdown -->
                <svg class="w-4 h-4 text-white-500 ml-2 transition-transform duration-300 transform group-hover:rotate-180" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Dropdown Menu -->
            <div id="userMenu" class="hidden absolute right-0 mt-2 bg-white border rounded-lg shadow-lg w-48 z-100 transform scale-0 origin-top-right transition-transform duration-300">
                <a href="..\users\profile.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded transition-all duration-300">Profile</a>
                <a href="..\users\logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded transition-all duration-300">Logout</a>
            </div>
        </div>
    </div>
</header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-6 flex-grow">
        <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-lg mx-auto">
            <h2 class="text-2xl font-bold mb-6 text-center">Profil Pengguna</h2>

            <!-- Informasi Profil -->
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="font-semibold text-gray-700">Username:</span>
                    <span class="text-gray-900"><?php echo htmlspecialchars($username); ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-semibold text-gray-700">Staff:</span>
                    <span class="text-gray-900"><?php echo htmlspecialchars($nama_lengkap); ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-semibold text-gray-700">Login Terakhir:</span>
                    <span class="text-gray-900"><?php echo htmlspecialchars($last_login); ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-semibold text-gray-700">Total Login:</span>
                    <span class="text-gray-900"><?php echo htmlspecialchars($total_logins); ?></span>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark-purple text-white text-center">
        <div class="bg-medium-purple py-2"></div> <!-- Warna ungu muda di atas -->
            <div class="py-4"> <!-- Padding top dan bottom pada bagian utama -->
                © 2025 Risk Management Dashboard. All rights reserved.
            </div>
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
    const userMenuButton = document.getElementById('userMenuButton');
    const userMenu = document.getElementById('userMenu');

    if (userMenuButton && userMenu) {
        userMenuButton.addEventListener('click', (e) => {
            console.log('User menu button clicked'); // Debug
            console.log('Toggling user menu'); // Debug
            e.stopPropagation();
            userMenu.classList.toggle('hidden');
            userMenu.classList.toggle('scale-100');
        });

        window.addEventListener('click', () => {
            if (!userMenu.classList.contains('hidden')) {
                console.log('Hiding user menu'); // Debug
                userMenu.classList.add('hidden');
                userMenu.classList.remove('scale-100');
            }
        });
    }
});
    </script>
    <script src ="../assets\js\script.js"></script>
</body>
</html>
