<?php  
session_start(); // Pastikan session aktif
$username = $_SESSION['username'] ?? 'guest@example.com';
$role = ucfirst($_SESSION['role'] ?? 'Guest');

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
        .sidebar {
            transition: all 0.3s ease;
        }
        .sidebar-collapsed {
            width: 70px;
        }
        .sidebar-collapsed .menu-text {
            display: none;
        }
        .sidebar-collapsed .logo-text {
            display: none;
        }
        .main-content {
            transition: all 0.3s ease;
        }
        .sidebar-collapsed + .main-content {
            margin-left: 70px;
        }
        .chart-container {
            position: relative;
            height: 250px;
        }
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
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="sidebar bg-purple-800 text-white w-64 fixed h-full">
            <div class="p-4 flex items-center justify-between border-b border-purple-700">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-shield-alt text-2xl text-purple-300"></i>
                    <span class="logo-text text-xl font-bold">RiskGuard</span>
                </div>
                <button id="toggleSidebar" class="text-purple-300 hover:text-white">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            <div class="p-4 flex items-center space-x-3 border-b border-purple-700">
                <div
                    class="w-10 h-10 rounded-full bg-purple-600 flex items-center justify-center"
                >
                    <i class="fas fa-user text-lg"></i>
                </div>
                <div class="menu-text">
                    <div class="font-medium"><?= htmlspecialchars($username) ?></div>
                    <div class="text-xs text-purple-300"><?= htmlspecialchars($role) ?></div>
                </div>
            </div>
            <nav class="p-4 space-y-2">
                <a
                    href="dashboard.php"
                    class="flex items-center space-x-3 p-2 rounded-lg bg-purple-700 text-white"
                >
                    <i class="fas fa-home text-lg"></i>
                    <span class="menu-text">Dashboard</span>
                </a>
                <a
                    href="identification.php"
                    class="flex items-center space-x-3 p-2 rounded-lg hover:bg-purple-700 text-purple-100 hover:text-white"
                >
                    <i class="fas fa-fingerprint text-lg"></i>
                    <span class="menu-text">Identification</span>
                </a>
                <a
                    href="mitigation.php"
                    class="flex items-center space-x-3 p-2 rounded-lg hover:bg-purple-700 text-purple-100 hover:text-white"
                >
                    <i class="fas fa-tasks text-lg"></i>
                    <span class="menu-text">Mitigations</span>
                </a>
                <a
                    href="monitoring.php"
                    class="flex items-center space-x-3 p-2 rounded-lg hover:bg-purple-700 text-purple-100 hover:text-white"
                >
                    <i class="fas fa-chart-line text-lg"></i>
                    <span class="menu-text">Monitoring</span>
                </a>
                <a
                    href="riskmap.php"
                    class="flex items-center space-x-3 p-2 rounded-lg hover:bg-purple-700 text-purple-100 hover:text-white"
                >
                    <i class="fas fa-map-marked-alt text-lg"></i>
                    <span class="menu-text">Risk Map</span>
                </a>
                <a
                    href="reports.php"
                    class="flex items-center space-x-3 p-2 rounded-lg hover:bg-purple-700 text-purple-100 hover:text-white"
                >
                    <i class="fas fa-file-alt text-lg"></i>
                    <span class="menu-text">Reports</span>
                </a>
                <a
                    href="manage_users.php"
                    class="flex items-center space-x-3 p-2 rounded-lg hover:bg-purple-700 text-purple-100 hover:text-white"
                >
                    <i class="fas fa-users-cog text-lg"></i>
                    <span class="menu-text">Manage Users</span>
                </a>
            </nav>
        </div>

        <!-- Main content wrapper -->
        <main class="main-content ml-64 p-6 flex-grow">
                    </main> <!-- Tutup main content -->

    </div> <!-- Tutup flex h-screen overflow-hidden -->

    <footer class="bg-dark-green text-white text-center">
        <div class="bg-medium-green py-2"></div> <!-- Warna hijau muda di atas -->
        <div class="py-4">© 2024 Risk Management Dashboard. All rights reserved.</div>
    </footer>

    <script>
        // Toggle sidebar collapse
        const toggleSidebarBtn = document.getElementById('toggleSidebar');
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('.main-content');

        toggleSidebarBtn.addEventListener('click', () => {
            sidebar.classList.toggle('sidebar-collapsed');
            // Optionally adjust main content margin
            if (sidebar.classList.contains('sidebar-collapsed')) {
                mainContent.classList.add('ml-20');
                mainContent.classList.remove('ml-64');
            } else {
                mainContent.classList.remove('ml-20');
                mainContent.classList.add('ml-64');
            }
        });
    </script>
</body>
</html>

