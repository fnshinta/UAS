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

            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
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
                <div class="mt-4">
                    <span class="text-red-500 text-sm font-medium"><i class="fas fa-caret-up"></i> 8.7% from last month</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-md p-6 w-full">
                <h3 class="text-lg font-semibold text-purple-900 mb-4">Risk Category Distribution</h3>
                <canvas id="riskCategoryChart" class="w-full"></canvas>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6 w-full">
                <h3 class="text-lg font-semibold text-purple-900 mb-4">Resource Distribution</h3>
                <canvas id="resourceChart" class="w-full"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold text-purple-900 mb-4">Risk Matrix Visualization</h3>
           <div class="risk-matrix">
            <div class="matrix-cell bg-green-100 text-green-800">Rare</div>
                        <div class="matrix-cell bg-green-100 text-green-800">Unlikely</div>
                        <div class="matrix-cell bg-yellow-100 text-yellow-800">Possible</div>
                        <div class="matrix-cell bg-yellow-100 text-yellow-800">Likely</div>
                        <div class="matrix-cell bg-red-100 text-red-800">Almost Certain</div>
                        
                        <div class="matrix-cell bg-green-100 text-green-800">Insignificant</div>
                        <div class="matrix-cell bg-green-200 text-green-900">Low</div>
                        <div class="matrix-cell bg-green-200 text-green-900">Low</div>
                        <div class="matrix-cell bg-yellow-200 text-yellow-900">Medium</div>
                        <div class="matrix-cell bg-yellow-200 text-yellow-900">Medium</div>
                        
                        <div class="matrix-cell bg-green-100 text-green-800">Minor</div>
                        <div class="matrix-cell bg-green-200 text-green-900">Low</div>
                        <div class="matrix-cell bg-yellow-200 text-yellow-900">Medium</div>
                        <div class="matrix-cell bg-yellow-200 text-yellow-900">Medium</div>
                        <div class="matrix-cell bg-red-200 text-red-900">High</div>
                        
                        <div class="matrix-cell bg-yellow-100 text-yellow-800">Moderate</div>
                        <div class="matrix-cell bg-yellow-200 text-yellow-900">Medium</div>
                        <div class="matrix-cell bg-yellow-200 text-yellow-900">Medium</div>
                        <div class="matrix-cell bg-red-200 text-red-900">High</div>
                        <div class="matrix-cell bg-red-200 text-red-900">High</div>
                        
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

        <div class="bg-white rounded-xl shadow-md p-6 mb-6 w-full">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-purple-900">Risk Data Summary</h3>
                <button class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm">
                    <i class="fas fa-download mr-2"></i> Export
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-purple-50 text-purple-800 uppercase text-xs font-medium">
                        <tr>
                            <th class="px-6 py-3 text-left">Risk ID</th>
                            <th class="px-6 py-3 text-left">Category</th>
                            <th class="px-6 py-3 text-left">Description</th>
                            <th class="px-6 py-3 text-left">Impact</th>
                            <th class="px-6 py-3 text-left">Likelihood</th>
                            <th class="px-6 py-3 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($risks as $risk): ?>
                        <tr>
                           <td class="px-6 py-4 whitespace-nowrap"><?php echo isset($risk['risk_id']) ? $risk['risk_id'] : ''; ?></td>
                           <td class="px-6 py-4"><?php echo $risk['risk_category'] ?? ''; ?></td>
                           <td class="px-6 py-4"><?php echo $risk['risk_description'] ?? ''; ?></td>
                           <td class="px-6 py-4"><?php echo $risk['impact_level'] ?? ''; ?></td>
                           <td class="px-6 py-4"><?php echo $risk['likelihood'] ?? ''; ?></td>
                           <td class="px-6 py-4">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            <?php
                            $status = $risk['status'] ?? 'Unknown';
                            switch($status) {
                            case 'Pending': echo 'bg-yellow-100 text-yellow-800'; break;
                            case 'Critical': echo 'bg-red-100 text-red-800'; break;
                            case 'Mitigated': echo 'bg-green-100 text-green-800'; break;
                            case 'Monitoring': echo 'bg-blue-100 text-blue-800'; break;
                            default: echo 'bg-gray-100 text-gray-800'; break;
                        }
                        ?>">
                          <?php echo $status; ?>
                        </span>
                        </td>

                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
<footer class="bg-purple-900 text-white text-center py-4 mt-auto">
    <div class="text-sm">
        © 2025 Risk Management Dashboard. All rights reserved.
    </div>
</footer>
</div>