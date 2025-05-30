<?php

require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ .  '/../classes/RiskManager.php';
require_once __DIR__ . '/../classes/MitigationManager.php';
require_once __DIR__ . '/../includes/header.php'; // Header dipanggil di sini

// Inisialisasi objek
$riskManager = new RiskManager($conn);
$mitigationManager = new MitigationManager($conn);

// Ambil data
$role = $_SESSION['role'];
$faculty_id = $_SESSION['staff_id'] ?? null;

$risks = $riskManager->getRecentRisks($role, $staff_id);
$riskSummary = $riskManager->getRiskSummary($role, $staff_id);
$totalRisks = $riskManager->getTotalRisks($role, $staff_id);

$mitigations = $mitigationManager->getRecentMitigations($role, $staff_id);

$totalLoss = $mitigationManager->getTotalLoss($role, $staff_id);
$activeRisks = $mitigationManager->getActiveRisks($role, $staff_id);
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
        <!-- Main Content -->
        <div class="main-content flex-1 overflow-auto ml-64">
            <header class="bg-white shadow-sm p-4">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-purple-900">Dashboard Overview</h1>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input type="text" placeholder="Search..." class="pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                        <button class="text-gray-600 hover:text-purple-700">
                            <i class="fas fa-bell text-xl"></i>
                        </button>
                    </div>
                </div>
            </header>

            <main class="p-4">
                <!-- Risk Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="risk-card bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500 font-medium">Total Risk</p>
                                <h3 class="text-3xl font-bold text-purple-800 mt-2"><?php echo $totalRisks; ?></h3>
                            </div>
                            <div class="bg-purple-100 p-3 rounded-full">
                                <i class="fas fa-exclamation-triangle text-purple-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <span class="text-green-500 text-sm font-medium"><i class="fas fa-caret-up"></i> 2.5% from last month</span>
                        </div>
                    </div>
                                        
                    <div class="risk-card bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500 font-medium">Active Risk</p>
                                <h3 class="text-3xl font-bold text-blue-800 mt-2"><?php echo $activeRisks; ?></h3>
                            </div>
                            <div class="bg-blue-100 p-3 rounded-full">
                                <i class="fas fa-bolt text-blue-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <span class="text-red-500 text-sm font-medium"><i class="fas fa-caret-down"></i> 1.2% from last month</span>
                        </div>
                    </div>
                                        
                    <div class="risk-card bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500 font-medium">Severity (Quantitative)</p>
                                <h3 class="text-3xl font-bold text-red-800 mt-2">Rp <?php echo number_format($totalLoss, 0, ',', '.'); ?></h3>
                            </div>
                            <div class="bg-red-100 p-3 rounded-full">
                                <i class="fas fa-money-bill-wave text-red-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <span class="text-red-500 text-sm font-medium"><i class="fas fa-caret-up"></i> 8.7% from last month</span>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Risk Category Chart -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-lg font-semibold text-purple-900 mb-4">Risk Category Distribution</h3>
                        <div class="chart-container">
                            <canvas id="riskCategoryChart"></canvas>
                        </div>
                    </div>
                                        
                    <!-- Resource Distribution Chart -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-lg font-semibold text-purple-900 mb-4">Resource Distribution</h3>
                        <div class="chart-container">
                            <canvas id="resourceChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Risk Matrix Visualization -->
                <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                    <h3 class="text-lg font-semibold text-purple-900 mb-4">Risk Matrix Visualization</h3>
                    <div class="risk-matrix">
                        <!-- Row 1 -->
                        <div class="matrix-cell bg-green-100 text-green-800">Rare</div>
                        <div class="matrix-cell bg-green-100 text-green-800">Unlikely</div>
                        <div class="matrix-cell bg-yellow-100 text-yellow-800">Possible</div>
                        <div class="matrix-cell bg-yellow-100 text-yellow-800">Likely</div>
                        <div class="matrix-cell bg-red-100 text-red-800">Almost Certain</div>
                        
                        <!-- Row 2 -->
                        <div class="matrix-cell bg-green-100 text-green-800">Insignificant</div>
                        <div class="matrix-cell bg-green-200 text-green-900">Low</div>
                        <div class="matrix-cell bg-green-200 text-green-900">Low</div>
                        <div class="matrix-cell bg-yellow-200 text-yellow-900">Medium</div>
                        <div class="matrix-cell bg-yellow-200 text-yellow-900">Medium</div>
                        
                        <!-- Row 3 -->
                        <div class="matrix-cell bg-green-100 text-green-800">Minor</div>
                        <div class="matrix-cell bg-green-200 text-green-900">Low</div>
                        <div class="matrix-cell bg-yellow-200 text-yellow-900">Medium</div>
                        <div class="matrix-cell bg-yellow-200 text-yellow-900">Medium</div>
                        <div class="matrix-cell bg-red-200 text-red-900">High</div>
                        
                        <!-- Row 4 -->
                        <div class="matrix-cell bg-yellow-100 text-yellow-800">Moderate</div>
                        <div class="matrix-cell bg-yellow-200 text-yellow-900">Medium</div>
                        <div class="matrix-cell bg-yellow-200 text-yellow-900">Medium</div>
                        <div class="matrix-cell bg-red-200 text-red-900">High</div>
                        <div class="matrix-cell bg-red-200 text-red-900">High</div>
                        
                        <!-- Row 5 -->
                        <div class="matrix-cell bg-red-100 text-red-800">Major</div>
                        <div class="matrix-cell bg-yellow-200 text-yellow-900">Medium</div>
                        <div class="matrix-cell bg-red-200 text-red-900">High</div>
                        <div class="matrix-cell bg-red-200 text-red-900">High</div>
                        <div class="matrix-cell bg-red-300 text-red-900">Extreme</div>
                    </div>
                    <div class="mt-4 flex justify-between text-sm text-gray-600">
                        <div>Likelihood →</div>
                        <div>Impact ↓</div>
                    </div>
                </div>

                <!-- Risk Data Summary Table -->
                <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-purple-900">Risk Data Summary</h3>
                        <button class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm">
                            <i class="fas fa-download mr-2"></i> Export
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-purple-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-purple-800 uppercase tracking-wider">Risk ID</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-purple-800 uppercase tracking-wider">Category</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-purple-800 uppercase tracking-wider">Description</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-purple-800 uppercase tracking-wider">Impact</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-purple-800 uppercase tracking-wider">Likelihood</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-purple-800 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($risk as $risk): ?>
                                <tr>
                           <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-purple-900"><?php echo $risk['risk_id']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $risk['risk_category']; ?></td>
                            <td class="px-6 py-4 text-sm text-gray-500"><?php echo $risk['risk_description']; ?></td>
                             <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $risk['impact_level']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $risk['likelihood']; ?></td>
    
                                <?php 
                                   $statusClass = '';
                                 switch($risk['status']) {
                               case 'Pending': $statusClass = 'bg-yellow-100 text-yellow-800'; break;
                               case 'Critical': $statusClass = 'bg-red-100 text-red-800'; break;
                               case 'Mitigated': $statusClass = 'bg-green-100 text-green-800'; break;
                               case 'Monitoring': $statusClass = 'bg-blue-100 text-blue-800'; break;
                               default: $statusClass = 'bg-gray-100 text-gray-800'; break;
        }
    ?>
    <td class="px-6 py-4 whitespace-nowrap text-sm <?php echo $statusClass; ?>">
        <?php echo htmlspecialchars($risk['status']); ?>
    </td>
</tr>
<?php endforeach; ?>
