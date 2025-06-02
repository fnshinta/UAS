<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Reports</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        /* Ungu utama dan senada */
        .purple {
            background-color: #6B46C1; /* Indigo/Purple */
            color: white;
        }
        .purple-light {
            background-color: #D6BCFA; /* Ungu muda */
            color: #4C1D95; /* Ungu gelap */
        }
        .purple-dark {
            background-color: #553C9A; /* Ungu lebih gelap */
            color: white;
        }
        /* Warna dan border default */
        .month-cell {
            border: 1px solid #4C1D95; /* Ungu gelap */
            text-align: center;
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <main class="container mx-auto px-4 py-6 flex-grow">
        <?php if ($is_admin): ?>
        <section class="bg-white p-6 rounded shadow mb-6">
            <h2 class="text-xl font-bold mb-4 text-purple-dark">Monitoring Data</h2>
            <form action="monitoring.php" method="POST" class="space-y-4">
                <input type="hidden" id="id" name="id" value="<?php echo $editData['id'] ?? ''; ?>" />
                <div>
                    <label for="risk_code" class="block text-purple-dark font-medium">Risk Code</label>
                    <select id="risk_code" name="risk_code" required onchange="updateRiskDetails(this.value)"
                        class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-purple-500">
                        <option value="" disabled selected>Select Risk Code</option>
                        <?php foreach ($riskCodes as $risk): ?>
                        <option value="<?= htmlspecialchars($risk['risk_code']); ?>">
                            <?= htmlspecialchars($risk['risk_code']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>

                </div>
                <div>
                    <input type="hidden" id="risk_event" name="risk_event" value="<?php echo $editData['risk_event'] ?? ''; ?>" />
                    <input type="hidden" id="mitigation_plan" name="mitigation_plan" value="<?php echo $editData['mitigation_plan'] ?? ''; ?>" />
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-2 text-purple-dark">Monthly Status</h3>
                    <table class="table-auto w-full border border-purple-500">
                        <thead class="bg-purple-light">
                            <tr>
                                <th class="border border-purple-500 px-4 py-2 text-center text-purple-dark font-bold">Month</th>
                                <th class="border border-purple-500 px-4 py-2 text-center text-purple-dark font-bold">Mitigation Plan</th>
                                <th class="border border-purple-500 px-4 py-2 text-center text-purple-dark font-bold">Mitigation Execution</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'];
                            $monthStatus = json_decode($editData['month_status'] ?? '{}', true);
                            foreach ($months as $month) {
                                $rencana = $monthStatus[$month]['rencana'] ?? 'none';
                                $pelaksanaan = $monthStatus[$month]['pelaksanaan'] ?? 'none';

                                echo "<tr class='hover:bg-purple-100'>";
                                //Kolom Bulan
                                echo "<td class='border border-purple-500 px-4 py-2 text-center text-purple-dark font-medium'>$month</td>";
                                echo "<td class='month-cell editable border border-purple-500 px-4 py-2 text-center font-medium " . 
                                     ($rencana === 'rencana' ? 'purple' : 'purple-light') . "' data-month='$month' data-type='rencana' data-status='$rencana'>&nbsp;</td>";
                                //Kolom pelaksanaan mitigasi
                                echo "<td class='month-cell editable border border-purple-500 px-4 py-2 text-center font-medium " . 
                                     ($pelaksanaan === 'pelaksanaan' ? 'purple-dark' : 'purple-light') . "' data-month='$month' data-type='pelaksanaan' data-status='$pelaksanaan'>&nbsp;</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>

                    <input type="hidden" id="month_status" name="month_status" />
                </div>

                <div>
                    <label for="evidence" class="block text-purple-dark font-medium">Evidence</label>
                    <textarea id="evidence" name="evidence" required
                        class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-purple-500"><?php echo $editData['evidence'] ?? ''; ?></textarea>
                </div>
                <div>
                    <label for="pic" class="block text-purple-dark font-medium">PIC</label>
                    <input type="text" id="pic" name="pic" required
                        class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-purple-500"
                        value="<?php echo $editData['pic'] ?? ''; ?>" />
                </div>
               <button type="submit" class="bg-purple-700 text-white px-4 py-2 rounded hover:bg-purple-800 transition">Save</button>
            </form>
        </section>
        <?php endif; ?>

        <section class="table-section px-4 py-6 bg-white rounded shadow">
            <h2 class="text-xl font-bold mb-4 text-purple-dark">Data Monitoring</h2>
            <div class="overflow-x-auto">
                <table class="table-auto w-full border border-purple-300">
                    <thead class="bg-purple-light">
                        <tr>
                            <th class="border border-purple-400 px-4 py-2" rowspan="2">Risk Code</th>
                            <th class="border border-purple-400 px-4 py-2" rowspan="2">Risk Event</th>
                            <th class="border border-purple-400 px-4 py-2" rowspan="2">Mitigation Plan</th>
                            <th class="border border-purple-400 px-4 py-2" colspan="12">Timing of Mitigation Implementation & Mitigation Realization</th>
                            <th class="border border-purple-400 px-4 py-2" rowspan="2">Evidence</th>
                            <th class="border border-purple-400 px-4 py-2" rowspan="2">PIC</th>
                            <?php if (strtolower($role) === 'admin'): ?>
                            <th class="border border-purple-400 px-4 py-2" rowspan="2">Staff</th>
                            <?php endif; ?>
                            <?php if ($is_admin): ?>
                            <th class="border border-purple-400 px-4 py-2" rowspan="2">Option</th>
                            <?php endif; ?>
                        </tr>
                        <tr>
                            <?php foreach ($months as $m): ?>
                            <th class="border border-purple-400 px-4 py-2"><?= $m ?></th>
                            <?php endforeach;?>
                        </tr>
                    </thead>
                    <tbody class="text-purple-dark">
                        <?php
                        $role = $_SESSION['role'] ?? '';
                        $staff_id = $_SESSION['staff_id'] ?? null;

                       if ($role === 'admin') {
                        $query = $conn->query("SELECT monitoring.*, risks.risk_event, mitigations.mitigation_plan, staff.staff_name 
                                             FROM monitoring
                                             LEFT JOIN risks ON monitoring.risk_code = risks.risk_code
                                             LEFT JOIN mitigations ON monitoring.risk_code = mitigations.risk_code
                                             LEFT JOIN staff ON risks.staff_id = staff.id");
                        } else {
                            $query = $conn->prepare("SELECT monitoring.*, 
                                                            risks.risk_event, mitigations.mitigation_plan, staff.staff_name 
                                                    FROM monitoring
                                                    LEFT JOIN risks ON monitoring.risk_code = risks.risk_code
                                                    LEFT JOIN mitigations ON monitoring.risk_code = mitigations.risk_code
                                                    LEFT JOIN staff ON risks.staff_id = staff.id
                                                    WHERE monitoring.staff_id = ?");
    $query->execute([$staff_id]);
    }

                        
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                            $monthStatus = json_decode($row['month_status'] ?? '{}', true);
                            $risk_code = htmlspecialchars($row['risk_code']);
                            $risk_event = htmlspecialchars($row['risk_event'] ?? 'N/A'); // Ambil risk_event dari query
                            $mitigation_plan = htmlspecialchars($row['mitigation_plan'] ?? 'N/A'); // Ambil mitigation_plan dari query
                            
                            echo "<tr>";
                            echo "<td class='border border-purple-400 px-4 py-2'>{$row['risk_code']}</td>";
                            echo "<td class='border border-purple-400 px-4 py-2'>{$row['risk_event']}</td>";
                            echo "<td class='border border-purple-400 px-4 py-2'>{$row['mitigation_plan']}</td>";

                            // Tampilkan kolom bulan, tandai rencana dan pelaksanaan
                            foreach ($months as $m) {
                                $rencana = $monthStatus[$m]['rencana'] ?? 'none';                    
                                $pelaksanaan = $monthStatus[$m]['pelaksanaan'] ?? 'none';

                                $rencanaClass = $rencana === 'rencana' ? 'bg-purple text-white font-bold' : '';
                                $pelaksanaanClass = $pelaksanaan === 'pelaksanaan' ? 'bg-purple-dark text-white font-bold' : '';

                                echo "<td class='border border-purple-400 px-2 py-1 text-center'>";
                                echo "<div class='mb-1 {$rencanaClass}' style='font-size:0.75rem;'>Rencana</div>";
                                echo "<div class='{$pelaksanaanClass}' style='font-size:0.75rem;'>Pelaksanaan</div>";
                                echo "</td>";
                            }
                            echo "<td class='border border-purple-400 px-4 py-2'>{$row['evidence']}</td>";
                            echo "<td class='border border-purple-400 px-4 py-2'>{$row['pic']}</td>";
                            if (strtolower($role) === 'admin') {
                                echo "<td class='border border-purple-400 px-4 py-2'>{$row['staff_name']}</td>";
                            }
                            if ($is_admin) {
                                echo "<td class='border border-purple-400 px-4 py-2'>";
                                echo "<a href='../modules/monitoring/edit_monitoring.php?id={$row['id']}' class='text-blue-500 hover:underline mr-2'>Edit</a>";
                                echo "<a href='../modules/monitoring/delete_monitoring.php?id={$row['id']}' class='text-red-600 hover:underline' onclick=\"return confirm('Are you sure?')\">Delete</a>";
                                echo "</td>";
                            }
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
    <footer class="bg-purple-900 text-white text-center py-4 mt-auto">
    <div class="text-sm">
        © 2025 Risk Management Dashboard. All rights reserved.
    </div>
</footer>

    <script>
        // Toggle warna sel rencana dan pelaksanaan di form input
        document.querySelectorAll('.editable').forEach(cell => {
            cell.addEventListener('click', () => {
                const type = cell.dataset.type;  // 'rencana' atau 'pelaksanaan'
                let status = cell.dataset.status;

                if (status === 'none') {
                    status = type; // set jadi rencana/pelaksanaan
                } else if (status === type) {
                    status = 'none'; // toggle off
                }

                cell.dataset.status = status;

                // Set class ungu sesuai status
                if (type === 'rencana') {
                    cell.classList.toggle('purple', status === 'rencana');
                    cell.classList.toggle('purple-light', status !== 'rencana');
                } else if (type === 'pelaksanaan') {
                    cell.classList.toggle('purple-dark', status === 'pelaksanaan');
                    cell.classList.toggle('purple-light', status !== 'pelaksanaan');
                }

                updateHiddenMonthStatus();
            });
        });

        function updateHiddenMonthStatus() {
            const data = {};
            document.querySelectorAll('.month-cell').forEach(cell => {
                const month = cell.dataset.month;
                const type = cell.dataset.type;
                const status = cell.dataset.status;

                if (!data[month]) {
                    data[month] = {rencana: 'none', pelaksanaan: 'none'};
                }
                data[month][type] = status;
            });

            document.getElementById('month_status').value = JSON.stringify(data);
        }

        // Update risk_event dan mitigation_plan otomatis saat risk_code dipilih
        const risks = <?php echo json_encode($riskCodes); ?>;
        const mitigations = <?php echo json_encode($mitigations); ?>;

        function updateRiskDetails(selectedRiskCode) {
            const risk = risks.find(r => r.risk_code === selectedRiskCode);
            const mitigation = mitigations.find(m => m.risk_code === selectedRiskCode);

            document.getElementById('risk_event').value = risk ? risk.risk_event : '';
            document.getElementById('mitigation_plan').value = mitigation ? mitigation.mitigation_plan : '';
        }
    </script>
</body>
</html>
