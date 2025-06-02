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

   <!-- Top Navbar -->
<header class="bg-purple-800 text-white shadow">
    <div class="container mx-auto px-4 py-4 flex justify-between">
        <div class="flex items-center space-x-3">
            <i class="fas fa-shield-alt text-2xl text-purple-300"></i>
            <span class="text-xl font-bold">Risk Management</span>
        </div>
        <nav class="space-x-6 flex items-center">
            <a href="dashboard.php" class="hover:underline flex items-center space-x-1">
                <i class="fas fa-home"></i><span>Dashboard</span>
            </a>
            <a href="identification.php" class="hover:underline flex items-center space-x-1">
                <i class="fas fa-fingerprint"></i><span>Identification</span>
            </a>
            <a href="mitigation.php" class="hover:underline flex items-center space-x-1 font-semibold underline">
                <i class="fas fa-list-check"></i><span>Mitigations</span>
            </a>
            <a href="monitoring.php" class="hover:underline flex items-center space-x-1">
                <i class="fas fa-chart-line"></i><span>Monitoring</span>
            </a>
            <a href="riskmap.php" class="hover:underline flex items-center space-x-1">
                <i class="fas fa-map-marked-alt"></i><span>Risk Map</span>
            </a>
            <a href="reports.php" class="hover:underline flex items-center space-x-1">
                <i class="fas fa-file-alt"></i><span>Reports</span>
            </a>
            <a href="manage_users.php" class="hover:underline flex items-center space-x-1">
                <i class="fas fa-users-cog"></i><span>Manage Users</span>
            </a>
        </nav>
        <div class="text-sm text-purple-200">
        </div>
        <!-- Kanan: Icon + Nama -->
        <div class="flex items-center space-x-3">
            <div class="bg-purple-700 w-8 h-8 rounded-full flex justify-center items-center text-white font-bold text-sm">
                <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
            </div>
            <div class="text-sm text-purple-200">
                <?= htmlspecialchars($username) ?> (<?= htmlspecialchars($role) ?>)
            </div>
        </div>
    </div>
</header>

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

    <!-- Rencaan mitigasi dan Checklist-->
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
                                <form method="POST" action="monitoring.php">
                                    <input type="hidden" name="risk_code" value="<?= htmlspecialchars($mitigation['risk_code']) ?>">
                                    <input type="checkbox" name="is_completed" onchange="this.form.submit()" <?= $mitigation['is_completed'] ? 'checked' : '' ?>>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-purple-900 text-white text-center py-4">
        © 2025 Risk Management Dashboard. All rights reserved.
    </footer>

</body>
</html>