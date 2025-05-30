<?php
require_once __DIR__ .  '/../includes/init.php';
require_once __DIR__ .  '/../classes/ReportManager.php';

session_start();

// Cek role admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("HTTP/1.1 403 Forbidden");
    exit("Anda tidak memiliki izin untuk mengakses halaman ini.");
}

$username = $_SESSION['username'] ?? 'guest@example.com';
$role = ucfirst($_SESSION['role'] ?? 'Guest');
if (strpos($username, '@') !== false) {
    $username = explode('@', $username)[0];
}

$reportManager = new ReportManager($conn);
$mitigations = $reportManager->getMitigations();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Monitoring Risiko - RiskGuard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        /* Reuse your sidebar styles */
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
        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead tr {
            background-color: #6b21a8; /* purple-800 */
            color: white;
        }
        th, td {
            padding: 0.75rem 1rem;
            border: 1px solid #ddd;
        }
        tbody tr:hover {
            background-color: #ddd6fe; /* purple-200 */
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
                <div class="w-10 h-10 rounded-full bg-purple-600 flex items-center justify-center">
                    <i class="fas fa-user text-lg"></i>
                </div>
                <div class="menu-text">
                    <div class="font-medium"><?= htmlspecialchars($username) ?></div>
                    <div class="text-xs text-purple-300"><?= htmlspecialchars($role) ?></div>
                </div>
            </div>
            <nav class="p-4 space-y-2">
                <a href="dashboard.php" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-purple-700 text-purple-100 hover:text-white">
                    <i class="fas fa-home text-lg"></i>
                    <span class="menu-text">Dashboard</span>
                </a>
                <a href="identification.php" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-purple-700 text-purple-100 hover:text-white">
                    <i class="fas fa-fingerprint text-lg"></i>
                    <span class="menu-text">Identification</span>
                </a>
                <a href="mitigation.php" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-purple-700 text-purple-100 hover:text-white">
                    <i class="fas fa-tasks text-lg"></i>
                    <span class="menu-text">Mitigations</span>
                </a>
                <a href="monitoring.php" class="flex items-center space-x-3 p-2 rounded-lg bg-purple-700 text-white">
                    <i class="fas fa-chart-line text-lg"></i>
                    <span class="menu-text">Monitoring</span>
                </a>
                <a href="riskmap.php" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-purple-700 text-purple-100 hover:text-white">
                    <i class="fas fa-map-marked-alt text-lg"></i>
                    <span class="menu-text">Risk Map</span>
                </a>
                <a href="reports.php" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-purple-700 text-purple-100 hover:text-white">
                    <i class="fas fa-file-alt text-lg"></i>
                    <span class="menu-text">Reports</span>
                </a>
                <a href="manage_users.php" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-purple-700 text-purple-100 hover:text-white">
                    <i class="fas fa-users-cog text-lg"></i>
                    <span class="menu-text">Manage Users</span>
                </a>
            </nav>
        </div>

        <!-- Main content wrapper -->
        <main class="main-content ml-64 p-6 flex-grow overflow-auto">
            <h1 class="text-3xl font-bold mb-6 text-purple-900">Monitoring Mitigation Status</h1>
            <div class="overflow-x-auto bg-white rounded shadow p-4">
                <table>
                    <thead>
                        <tr>
                            <th>Risk Code</th>
                            <th>Risk Event</th>
                            <th>Mitigation Plan</th>
                            <th>Existing Control</th>
                            <th>Status Completed</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($mitigations as $mitigation): ?>
                            <tr>
                                <td><?= htmlspecialchars($mitigation['risk_code']) ?></td>
                                <td><?= htmlspecialchars($mitigation['risk_event']) ?></td>
                                <td><?= htmlspecialchars($mitigation['mitigation_plan']) ?></td>
                                <td><?= htmlspecialchars($mitigation['existing_control']) ?></td>
                                <td class="text-center">
                                    <form method="POST" action="monitoring.php">
                                        <input type="hidden" name="risk_code" value="<?= htmlspecialchars($mitigation['risk_code']) ?>">
                                        <input type="checkbox" name="is_completed" onchange="this.form.submit()"
                                            <?= $mitigation['is_completed'] ? 'checked' : '' ?>>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <footer class="bg-purple-900 text-white text-center py-4 mt-auto">
        © 2024 RiskGuard Dashboard. All rights reserved.
    </footer>

    <script>
        // Toggle sidebar collapse
        const toggleSidebarBtn = document.getElementById('toggleSidebar');
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('.main-content');

        toggleSidebarBtn.addEventListener('click', () => {
            sidebar.classList.toggle('sidebar-collapsed');
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
