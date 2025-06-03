<?php
require_once '../includes/init.php';
require_once '../classes/ReportManager.php';

$username = $_SESSION['username'];

// Potong username hanya sampai sebelum tanda @
if (strpos($username, '@') !== false) {
    $username = explode('@', $username)[0];
}

// Pastikan hanya admin yang bisa mengakses halaman ini
if ($_SESSION['role'] !== 'admin') {
    header("HTTP/1.1 403 Forbidden");
    exit("Anda tidak memiliki izin untuk mengakses halaman ini.");
}

// Inisialisasi ReportManager
$reportManager = new ReportManager($conn);

// Update status mitigasi jika ada POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $risk_code = $_POST['risk_code'];
    $is_completed = isset($_POST['is_completed']) ? 1 : 0;
    $reportManager->updateMitigationStatus($risk_code, $is_completed);

    header("Location: reports.php");
    exit;
}

// Ambil data laporan
$staffs = $reportManager->getRisksPerStaff();
$mitigations = $reportManager->getMitigations();


?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Laporan Resiko</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">

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

    <!-- Main Content -->

    <!-- Total Resiko per Staff-->
    <main class="flex-grow container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6 text-purple-900">Total Risk by Staff</h1>
        <div class="overflow-x-auto bg-white rounded shadow p-4">
            <table class="min-w-full border border-gray-200">
                <thead class="bg-purple-800 text-white">
                    <tr>
                        <th class="px-4 py-2 border">Staff</th>
                        <th class="px-4 py-2 border">Total Risk</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($staffs as $staff): ?>
                        <tr>
                            <td class="border border-gray-400 px-4 py-2"><?= htmlspecialchars($staff['nama_staff']); ?></td>
                            <td class="border border-gray-400 px-4 py-2 text-center">
                                <?= htmlspecialchars($staff['total_risks']); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Rencana mitigasi dan Checklist-->
    <main class="flex-grow container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6 text-purple-900">Mitigation Plan and Checklist status</h1>
        <div class="overflow-x-auto bg-white rounded shadow p-4">
            <table class="min-w-full border border-gray-200">
                <thead class="bg-purple-800 text-white">
                    <tr>
                        <th class="px-4 py-2 border">Risk Code</th>
                        <th class="px-4 py-2 border">Risk Event</th>
                        <th class="px-4 py-2 border">Mitigation Plan</th>
                        <th class="px-4 py-2 border">Existing Control</th>
                        <th class="px-4 py-2 border text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($mitigations as $mitigation): ?>
                        <tr class="hover:bg-purple-100">
                            <td class="px-4 py-2 border"><?= htmlspecialchars($mitigation['risk_code']) ?></td>
                            <td class="px-4 py-2 border"><?= htmlspecialchars($mitigation['risk_event']) ?></td>
                            <td class="px-4 py-2 border"><?= htmlspecialchars($mitigation['mitigation_plan']) ?></td>
                            <td class="px-4 py-2 border"><?= htmlspecialchars($mitigation['existing_control']) ?></td>
                            <td class="px-4 py-2 border text-center">
                                <form method="POST" action="reports.php">
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

    <!-- Footer -->
<?php require_once '../includes/footer.php'; ?>

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