<?php
require_once '../includes/init.php';
require_once '../classes/RiskManager.php';
require_once '../classes/MitigationManager.php';
require_once '../includes/header.php'; // Header dipanggil di sini

// Inisialisasi objek
$riskManager = new RiskManager($conn);
$mitigationManager = new MitigationManager($conn);

// Ambil data
$role = $_SESSION['role'];
$staff_id = $_SESSION['staff_id'] ?? null;

$risks = $riskManager->getRecentRisks($role, $staff_id);
$riskSummary = $riskManager->getRiskSummary($role, $staff_id);
$totalRisks = $riskManager->getTotalRisks($role, $staff_id);

$mitigations = $mitigationManager->getRecentMitigations($role, $staff_id);

$totalLoss = $mitigationManager->getTotalLoss($role, $staff_id);
$activeRisks = $mitigationManager->getActiveRisks($role, $staff_id);
  
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Risk Management Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .risk-matrix {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            grid-template-rows: repeat(5, 1fr);
            gap: 4px;
            height: 300px;
        }
        .matrix-cell {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: bold;
            border-radius: 4px;
            transition: all 0.2s ease;
        }
        .matrix-cell:hover {
            transform: scale(1.05);
            z-index: 1;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <div class="main-content flex-1 overflow-auto w-full">
    <header class="bg-white shadow-sm p-6">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <h1 class="text-2xl font-bold text-purple-800 mb-4 md:mb-0">Dashboard Overview</h1>
            <div class="flex items-center space-x-4">
                </div>
        </div>
    </header>

    <main class="w-full px-6 py-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 font-medium">Total Risk</p>
                        <h3 class="text-3xl font-bold text-purple-800 mt-2"><?= $totalRisks; ?></h3>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <i class="fas fa-exclamation-triangle text-purple-600 text-xl"></i>
                    </div>
                </div>
                
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 font-medium">Active Risk</p>
                        <h3 class="text-3xl font-bold text-blue-800 mt-2"><?= $activeRisks; ?></h3>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-bolt text-blue-600 text-xl"></i>
                    </div>
                </div>
                
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 font-medium">Severity (Quantitative)</p>
                        <h3 class="text-3xl font-bold text-red-800 mt-2">Rp <?php echo number_format($totalLoss, 0, ',', '.'); ?></h3>
                    </div>
                    <div class="bg-red-100 p-3 rounded-full">
                        <i class="fas fa-money-bill-wave text-red-600 text-xl"></i>
                    </div>
                </div>
                            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Risk Category (Bar Chart) -->
    <div class="bg-white rounded-xl shadow-md p-6 w-full">
        <h3 class="text-lg font-semibold text-purple-900 mb-4">Risk Category Distribution</h3>
        <div class="flex justify-center">
            <canvas id="riskCategoryChart" class="w-full max-w-[600px] h-[600px]"></canvas>
        </div>
    </div>

    <!-- Resource Distribution (Pie Chart) -->
    <div class="bg-white rounded-xl shadow-md p-6 w-full">
        <h3 class="text-lg font-semibold text-purple-900 mb-4">Resource Distribution</h3>
        <div class="flex justify-center">
            <div class="w-full max-w-xs aspect-square mx-auto">
                <canvas id="riskSourceChart" class="w-full max-w-[300px] h-[300px]"></canvas>
            </div>
        </div>
    </div>
</div>


        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold text-purple-900 mb-4">Recent Risks</h3>
           <div class="overflow-auto">
                <table class="bg-white rounded-x1 shadow-md p-6 w-full">
                    <tr>
                                <th class="px-6 py-3 border border-gray-400">Risk Event</th>
                                <th class="px-6 py-3 border border-gray-400">Risk Cause</th>
                                <th class="px-6 py-3 border border-gray-400">Mitigation Plan</th>
                                <th class="px-6 py-3 border border-gray-400">Status</th>
                                <?php if ($role === 'admin'): ?>
                                    <th class="px-6 py-3 border border-gray-400">Staff</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($i = 0; $i < count($risks); $i++): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 border border-gray-400"><?= htmlspecialchars($risks[$i]['risk_event'] ?? 'N/A'); ?></td>
                                    <td class="px-6 py-3 border border-gray-400"><?= htmlspecialchars($risks[$i]['risk_cause'] ?? 'N/A'); ?></td>
                                    <td class="px-6 py-3 border border-gray-400"><?= htmlspecialchars($mitigations[$i]['mitigation_plan'] ?? 'N/A'); ?></td>
                                    <td class="px-6 py-3 border text-center border-gray-400">
                                        <input type="checkbox"
                                        <?= !empty($mitigations[$i]['is_completed']) && $mitigations[$i]['is_completed'] ? 'checked' : ''; ?>
                                        >

                                    </td>
                                    <?php if ($role === 'admin'): ?>
                                        <td class="px-6 py-3 border border-gray-400"><?= htmlspecialchars($risks[$i]['staff_name'] ?? 'N/A'); ?></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endfor; ?>
                        </tbody>
                </table>
                <div class="mt-4 text-right">
                        <a href="identification.php#data-risiko" class="text-blue-500 hover:underline font-semibold">
                            View All ->
                        </a>
                </div>
            </div>    
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 mb-6 w-full">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-purple-900">Risk Data Summary</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
    <thead class="bg-purple-50 text-purple-800 uppercase text-xs font-medium">
        <tr>
            <th class="px-6 py-3 text-left">Risk Category</th>
            <th class="px-6 py-3 text-center">Risk Total</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        <?php foreach ($riskSummary as $summary): ?>
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-3 border border-gray-400"><?= htmlspecialchars($summary['risk_category'] ?? 'N/A'); ?></td>
            <td class="px-6 py-3 border border-gray-400 text-center"><?= htmlspecialchars($summary['total'] ?? 0); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

            </div>
        </div>
    </main>
    
<?php require_once '../includes/footer.php'; ?>

<!-- Data Grafik -->
    <?php
    // Daftar kategori risiko statis
    $defaultCategories = ['Financial', 'Operational', 'Strategic'];
    $categories = $defaultCategories;
    $totals = array_fill(0, count($defaultCategories), 0); // Isi dengan 0 terlebih dahulu

    // Ambil staff_id dari session
    $staff_id = $_SESSION['staff_id'] ?? null;

    // Query data kategori risiko dengan filter staff_id
    if ($_SESSION['role'] === 'admin') {
        // Admin melihat semua data risiko tanpa filter
        $query = $conn->query("SELECT risk_category, COUNT(*) AS total FROM risks GROUP BY risk_category");
    } else {
        // Sub-Admin melihat data risiko sesuai staff_id
        $query = $conn->prepare("SELECT risk_category, COUNT(*) AS total 
                                FROM risks 
                                WHERE staff_id = :staff_id 
                                GROUP BY risk_category");
        $query->execute(['staff_id' => $staff_id]);
    }

    while ($row = $query->fetch()) {
        $index = array_search($row['risk_category'], $categories);
        if ($index !== false) {
            $totals[$index] = (int)$row['total'];
        }
    }

    // Data sumber risiko (Internal & External)
    $riskSources = ['Internal', 'External'];
    $sourceCounts = [];

    foreach ($riskSources as $source) {
        if ($_SESSION['role'] === 'admin') {
            // Admin melihat semua data sumber risiko
            $query = $conn->prepare("SELECT COUNT(*) AS total FROM risks WHERE risk_source = :source");
            $query->execute(['source' => $source]);
        } else {
            // Sub-Admin melihat sumber risiko sesuai staff_id
            $query = $conn->prepare("SELECT COUNT(*) AS total 
                                    FROM risks 
                                    WHERE risk_source = :source AND staff_id = :staff_id");
            $query->execute(['source' => $source, 'staff_id' => $staff_id]);
        }
        $result = $query->fetch();
        $sourceCounts[] = $result['total'];
    }
    ?>

    <script>
        const categories = <?php echo json_encode($categories); ?>;
        const totals = <?php echo json_encode($totals); ?>;
        const riskSources = <?php echo json_encode($riskSources); ?>;
        const sourceCounts = <?php echo json_encode($sourceCounts); ?>;

        const ctxCategory = document.getElementById('riskCategoryChart').getContext('2d');
        new Chart(ctxCategory, {
            type: 'bar',
            data: {
                labels: categories,
                datasets: [{
                    label: 'Jumlah Risiko',
                    data: totals,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const ctxSource = document.getElementById('riskSourceChart').getContext('2d');
        new Chart(ctxSource, {
            type: 'pie',
            data: { labels: riskSources, datasets: [{ data: sourceCounts, backgroundColor: ['#36A2EB', '#FF6384'] }] }
        });
    </script>

</body>
</html>