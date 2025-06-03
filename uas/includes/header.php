<?php 
$username = $_SESSION['username'];
$role = ucfirst($_SESSION['role'] ?? 'unknown');

// Potong username hanya sampai sebelum tanda @
if (strpos($username, '@') !== false) {
    $username = explode('@', $username)[0];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Risk Management Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        .risk-card {
            transition: all 0.3s ease;
        }
        .risk-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1),
                        0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body class="bg-gray-50">

    <!-- Horizontal Navbar -->
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
                    <a href="dashboard.php" class="hover:text-purple-300 flex items-center space-x-1"><i class="fas fa-home"></i><span>Dashboard</span></a>
                    <a href="identification.php" class="hover:text-purple-300 flex items-center space-x-1"><i class="fas fa-fingerprint"></i><span>Identification</span></a>
                    <a href="mitigation.php" class="hover:text-purple-300 flex items-center space-x-1"><i class="fas fa-tasks"></i><span>Mitigations</span></a>
                    <a href="monitoring.php" class="hover:text-purple-300 flex items-center space-x-1"><i class="fas fa-chart-line"></i><span>Monitoring</span></a>
                    <a href="riskmap.php" class="hover:text-purple-300 flex items-center space-x-1"><i class="fas fa-map-marked-alt"></i><span>Risk Map</span></a>
                    <a href="reports.php" class="hover:text-purple-300 flex items-center space-x-1"><i class="fas fa-file-alt"></i><span>Reports</span></a>
                    <a href="manage_users.php" class="hover:text-purple-300 flex items-center space-x-1"><i class="fas fa-users-cog"></i><span>Manage Users</span></a>
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



<script>
function toggleProfileDropdown() {
    const dropdown = document.getElementById("profileDropdown");
    dropdown.classList.toggle("hidden");
}

// Tutup dropdown jika klik di luar
window.addEventListener("click", function(e) {
    const wrapper = document.getElementById("profileDropdownWrapper");
    const dropdown = document.getElementById("profileDropdown");

    if (!wrapper.contains(e.target)) {
        dropdown.classList.add("hidden");
    }
});
</script>

</body>
</html>