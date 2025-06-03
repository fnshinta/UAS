<?php
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../classes/UserManager.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit("Anda harus login terlebih dahulu.");
}

try {
    // Inisialisasi UserManager
    $userManager = new UserManager();

    // Ambil data pengguna dari session
    $user_id = $_SESSION['user_id'];
    
    // Gunakan setter untuk mengatur ID pengguna
    $userManager->setId($user_id);
    
    // Ambil profil pengguna dengan method getUserProfile()
    $profile = $userManager->getUserProfile();

    // Gunakan setter untuk mengatur data dari hasil query
    $username = $profile['username'] ?? 'N/A';
    $nama_lengkap = $profile['nama_lengkap'] ?? 'Tidak Diketahui';  // Ganti dari $nama_lengkap
    $last_login = $profile['last_login'] ?? 'Belum Pernah Login';
    $last_login = $profile['last_login'] ?? 'Belum Pernah Login';
    $total_logins = $profile['total_logins'] ?? 0;

} catch (Exception $e) {
    // Tangani kesalahan koneksi atau query
    $error_message = $e->getMessage();
    echo "Terjadi kesalahan: " . htmlspecialchars($error_message);
    exit();
}

$username = $_SESSION['username'];
$role = ucfirst($_SESSION['role'] ?? 'unknown');

// Potong username hanya sampai sebelum tanda @
if (strpos($username, '@') !== false) {
    $username = explode('@', $username)[0];
}

// Jika ada request logout via query param ?action=logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset();
    session_destroy();
    header("Location: index.php");  // redirect ke halaman login setelah logout
    exit;
}

?>
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
   <nav class="bg-purple-800 text-white shadow-md w-full">
        <div class="px-6 py-4 flex flex-col md:flex-row items-center justify-between w-full">
            <!-- Logo Kiri -->
            <div class="flex items-center space-x-2">
                <i class="fas fa-shield-alt text-2xl text-purple-300"></i>
                <span class="text-xl font-bold">Risk Management</span>
            </div>

            <!-- Menu Tengah -->
            <div class="flex-1 flex justify-center mt-4 md:mt-0">
                <div class="flex flex-wrap justify-center space-x-6 text-sm font-medium">
                    <a href="../pages/dashboard.php" class="hover:text-purple-300 flex items-center space-x-1"><i class="fas fa-home"></i><span>Dashboard</span></a>
                    <a href="../pages/identification.php" class="hover:text-purple-300 flex items-center space-x-1"><i class="fas fa-fingerprint"></i><span>Identification</span></a>
                    <a href="../pages/mitigation.php" class="hover:text-purple-300 flex items-center space-x-1"><i class="fas fa-tasks"></i><span>Mitigations</span></a>
                    <a href="../pages/monitoring.php" class="hover:text-purple-300 flex items-center space-x-1"><i class="fas fa-chart-line"></i><span>Monitoring</span></a>
                    <a href="../pages/riskmap.php" class="hover:text-purple-300 flex items-center space-x-1"><i class="fas fa-map-marked-alt"></i><span>Risk Map</span></a>
                    <a href="../pages/reports.php" class="hover:text-purple-300 flex items-center space-x-1"><i class="fas fa-file-alt"></i><span>Reports</span></a>
                    <a href="../pages/manage_users.php" class="hover:text-purple-300 flex items-center space-x-1"><i class="fas fa-users-cog"></i><span>Manage Users</span></a>
                </div>
            </div>

            <!-- Profil User Kanan -->
            <div class="relative" id="profileDropdownWrapper">
                <button onclick="toggleProfileDropdown()" class="flex items-center space-x-3 text-sm focus:outline-none">
                    <div class="w-10 h-10 rounded-full bg-purple-600 flex items-center justify-center">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div class="text-right hidden md:block">
                        <div class="font-medium"><?= htmlspecialchars($username) ?></div>
                        <div class="text-xs text-purple-300"><?= htmlspecialchars($role) ?></div>
                    </div>
                </button>
                <!-- Dropdown -->
                <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg z-50 text-gray-800">
                    <a href="../users/profile.php" class="block px-4 py-2 text-sm hover:bg-gray-100">Profile</a>
                    <a href="../users/logout.php" class="block px-4 py-2 text-sm hover:bg-gray-100">Logout</a>
                </div>
            </div>
        </div>
    </nav>

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

    <script src ="../assets/js/script.js"></script>
    
    <!-- Script -->
    <script>
        function toggleProfileDropdown() {
            const dropdown = document.getElementById("profileDropdown");
            dropdown.classList.toggle("hidden");
        }

        document.addEventListener("click", function(event) {
            const dropdown = document.getElementById("profileDropdown");
            const wrapper = document.getElementById("profileDropdownWrapper");

            if (!wrapper.contains(event.target)) {
                dropdown.classList.add("hidden");
            }
        });
    </script>
    
    
</body>
</html>
