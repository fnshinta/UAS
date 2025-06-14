<?php
require_once '../includes/init.php';
require_once '../classes/Monitoring.php';
require_once '../classes/Risk.php'; // Pastikan class Risk di-include
require_once '../classes/Mitigation.php';

$mitigationClass = new Mitigation($conn);
$mitigations = $mitigationClass->getAllMitigations(); // Ambil data mitigations

if (!$mitigations) {
    $mitigations = []; // Jika tidak ada data, gunakan array kosong
}

$riskClass = new Risk($conn); // Inisialisasi class Risk
$risks = $riskClass->getAllRiskCodes(); // Ambil risk codes

if (!$risks) {
    $risks = []; // Default ke array kosong jika query gagal
}

$monitoring = new Monitoring($conn);

$months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];


$is_admin = $_SESSION['role'] === 'admin' || $_SESSION['role'] === 'sub-admin';
$staff_id = $_SESSION['staff_id'] ?? null;

$role = $_SESSION['role'] ?? 'guest';

// Ambil semua data monitoring
$monitoringData = $monitoring->getAllMonitoring($staff_id, $is_admin);
// Ambil semua risk_code
$riskCodes = $monitoring->getAllRiskCodes($role, $staff_id);

// Tambah data monitoring
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($is_admin) {
        $data = [
            'risk_code' => $_POST['risk_code'],
            'risk_event' => $_POST['risk_event'],
            'mitigation_plan' => $_POST['mitigation_plan'],
            'month_status' => $_POST['month_status'],
            'evidence' => $_POST['evidence'],
            'pic' => $_POST['pic'],
            'staff_id' => $staff_id
        ];
        $monitoring->addMonitoring($data);
        header("Location: monitoring.php");
        exit;
    }
}
require_once '../includes/header.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Monitoring Data</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        /* Ungu utama dan senada */
        .purple {
            background-color:rgb(131, 52, 143); /* Indigo/Purple */
            color: white;
        }
        .purple-light {
            background-color:rgb(201, 178, 233); /* Ungu muda */
            color:rgb(121, 70, 193); /* Ungu gelap */
        }
        .purple-dark {
            background-color:rgb(140, 54, 157); /* Ungu lebih gelap */
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
        <!-- Form Tambah Monitoring -->
         <?php if ($is_admin): ?>
        <section class="bg-white p-6 rounded shadow mb-6">
            <h2 class="text-xl font-bold mb-4 text-purple-dark">Monitoring Data</h2>
            <form action="monitoring.php" method="POST" class="space-y-4">
                <input type="hidden" id="id" name="id" value="<?php echo $editData['id'] ?? ''; ?>">
                <div>
                    <label for="risk_code" class="block text-purple-dark font-medium">Risk Code</label>
                    <select id="risk_code" name="risk_code" required onchange="updateRiskDetails(this.value)" class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-purple-500">
                        <option value="" disabled selected>Select Risk Code</option>
                        <?php foreach ($riskCodes as $risk): ?>
                             <option value="<?= htmlspecialchars($risk['risk_code']); ?>">
                                <?= htmlspecialchars($risk['risk_code']); ?>
                             </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <input type="hidden" id="risk_event" name="risk_event" value="<?php echo $editData['risk_event'] ?? ''; ?>" >
                    <input type="hidden" id="mitigation_plan" name="mitigation_plan" value="<?php echo $editData['mitigation_plan'] ?? ''; ?>" >
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

                            foreach ($months as $month){
                                $rencana = $monthStatus[$month]['rencana'] ?? 'none';
                                $pelaksanaan = $monthStatus[$month]['pelaksanaan'] ?? 'none';

                                echo "<tr class='hover:bg-purple-100'>";
                                //Kolom Bulan
                                echo "<td class='border border-purple-500 px-4 py-2 text-center text-purple-dark font-medium'>$month</td>";

                                // Kolom Rencana Mitigasi
                                echo "<td
                                    class='month-cell editable border border-purple-500 px-4 py-2 text-center font-medium " . 
                                    ($rencana === 'rencana' ? 'purple' : 'purple-light') . "' 
                                    data-month='$month' 
                                    data-type='rencana' 
                                    data-status='$rencana'>
                                &nbsp;
                                </td>";

                                // Kolom Pelaksanaan Mitigasi
                                echo "<td 
                                    class='month-cell editable border border-purple-500 px-4 py-2 text-center font-medium " . 
                                    ($pelaksanaan === 'pelaksanaan' ? 'purple-dark' : 'purple-light') . "' 
                                    data-month='$month' 
                                    data-type='pelaksanaan' 
                                    data-status='$pelaksanaan'>
                                &nbsp;
                                </td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                <input type="hidden" id="month_status" name="month_status">
                </div>

                <div>
                    <label for="evidence" class="block text-purple-dark font-medium">Evidence</label>
                    <textarea id="evidence" name="evidence" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-purple-500"><?php echo $editData['evidence'] ?? ''; ?></textarea>
                </div>

                <div>
                    <label for="pic" class="block text-purple-dark font-medium">PIC</label>
                    <input type="text" id="pic" name="pic" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-purple-500" value="<?php echo $editData['pic'] ?? ''; ?>" />
                </div>

                <button type="submit" class="bg-purple-700 text-white px-4 py-2 rounded hover:bg-purple-800 transition">Save</button>
            </form>
        </section>
        <?php endif;?>
        
        <!-- Tabel Data Monitoring -->
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
                            <th class="border border-purple-400 px-4 py-2">Jan</th>
                            <th class="border border-purple-400 px-4 py-2">Feb</th>
                            <th class="border border-purple-400 px-4 py-2">Mar</th>
                            <th class="border border-purple-400 px-4 py-2">Apr</th>
                            <th class="border border-purple-400 px-4 py-2">May</th>
                            <th class="border border-purple-400 px-4 py-2">Jun</th>
                            <th class="border border-purple-400 px-4 py-2">Jul</th>
                            <th class="border border-purple-400 px-4 py-2">Aug</th>
                            <th class="border border-purple-400 px-4 py-2">Sep</th>
                            <th class="border border-purple-400 px-4 py-2">Oct</th>
                            <th class="border border-purple-400 px-4 py-2">Nov</th>
                            <th class="border border-purple-400 px-4 py-2">Dec</th>
                        </tr>
                    </thead>
                    <tbody class="text-purple-dark">
                        <?php
                        // Ambil role dan staff_id dari session
                        $role = $_SESSION['role'] ?? '';
                        $staff_id = $_SESSION['staff_id'] ?? null;

                        // Query data monitoring sesuai role
                        if ($role === 'admin') {
                            // Admin utama: melihat semua data, termasuk staff_name
                            $query = $conn->query("SELECT monitoring.*, 
                                                         risks.risk_event, 
                                                         mitigations.mitigation_plan, 
                                                         staffs.staff_name 
                                                  FROM monitoring
                                                  LEFT JOIN risks ON monitoring.risk_code = risks.risk_code
                                                  LEFT JOIN mitigations ON monitoring.risk_code = mitigations.risk_code
                                                  LEFT JOIN staffs ON risks.staff_id = staffs.id");
                        } else {
                            // Sub-admin: melihat data sesuai staff_id
                            $query = $conn->prepare("SELECT monitoring.*, 
                                                           risks.risk_event, 
                                                           mitigations.mitigation_plan, 
                                                           staffs.staff_name 
                                                    FROM monitoring
                                                    LEFT JOIN risks ON monitoring.risk_code = risks.risk_code
                                                    LEFT JOIN mitigations ON monitoring.risk_code = mitigations.risk_code
                                                    LEFT JOIN staffs ON risks.staff_id = staffs.id
                                                    WHERE risks.staff_id = :staff_id");
                            $query->execute(['staff_id' => $staff_id]);
                        }

                        while ($row = $query->fetch()) {
                            $month_status = json_decode($row['month_status'], true); // Decode JSON status bulanan
                            $risk_code = htmlspecialchars($row['risk_code']);
                            $risk_event = htmlspecialchars($row['risk_event'] ?? 'N/A'); // Ambil risk_event dari query
                            $mitigation_plan = htmlspecialchars($row['mitigation_plan'] ?? 'N/A'); // Ambil mitigation_plan dari query

                            // Mulai baris tabel untuk risiko
                            echo "<tr>
                                <td class='border border-purple-400 px-4 py-2 text-center' rowspan='2'>$risk_code</td>
                                    <td class='border border-purple-400 px-4 py-2 text-center' rowspan='2'>$risk_event</td>
                                    <td class='border border-purple-400 px-4 py-2 text-center' rowspan='2'>$mitigation_plan</td>";

                             // Baris pertama: Rencana Mitigasi
                            foreach ($months as $month) {
                                $rencana = $month_status[$month]['rencana'] ?? 'none'; // Ambil status rencana, default 'none'
                                $color = $rencana === 'rencana' ? 'purple text-white' : ''; // Tambahkan warna jika status rencana
                                echo "<td class='border border-gray-400 px-2 py-1 text-center $color'>&nbsp;</td>"; // Border eksplisit di setiap sel
                            }

                            echo "<td class='border border-purple-400 px-4 py-2 text-center' rowspan='2'>{$row['evidence']}</td>
                                <td class='border border-purple-400 px-4 py-2 text-center' rowspan='2'>{$row['pic']}</td>";
                            if ($role === 'admin') {
                                echo "<td class='border border-purple-400 px-4 py-2 text-center' rowspan='2'>{$row['staff_name']}</td>";
                            }
                             if ($is_admin) {
                                echo "<td class='border border-purple-400 px-4 py-2 text-center' rowspan='2'>
                                        <a href='../modules/monitoring/edit_monitoring.php?edit={$row['id']}' class='text-blue-500 hover:underline'>Edit</a>
                                        <a href='../modules/monitoring/delete_monitoring.php?delete={$row['id']}' onclick='return confirm(\"Hapus data monitoring ini?\")' class='text-red-500 hover:underline'>Hapus</a>
                                    </td>";
                            }
                            "</tr>";

                            // Baris kedua: Pelaksanaan Mitigasi
                            echo "<tr class='hover:bg-gray-50'>";
                            foreach ($months as $month) {
                                $pelaksanaan = $month_status[$month]['pelaksanaan'] ?? 'none'; // Ambil status pelaksanaan, default 'none'
                                $color = $pelaksanaan === 'pelaksanaan' ? 'purple-dark text-white' : ''; // Tambahkan warna jika status pelaksanaan
                                echo "<td class='border border-purple-400 px-2 py-1 text-center $color'>&nbsp;</td>"; // Border eksplisit di setiap sel
                            }
                            echo "</tr>";

                        }
                            
                        ?>
                    </tbody>
                </table>
            </div>
         </section>
    </main>

    <?php
    require_once '../includes/footer.php';
    ?>

    <!-- js -->
    <script>
        // Data risks dan mitigations diambil dari PHP dan dikirim ke JavaScript
        const risks = <?php echo json_encode($risks, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>;
        console.log(risks); // Debug: pastikan data muncul di konsol
        const mitigations = <?php echo json_encode($mitigations, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>;
    
        console.log("Risks:", risks); // Debugging: pastikan data risks muncul di konsol
        console.log("Mitigations:", mitigations); // Debugging: pastikan data mitigations muncul di konsol

        // Fungsi untuk mengisi Risk Event dan Mitigation Plan berdasarkan Risk Code
        function updateRiskDetails(selectedCode) {
        console.log("Risk code selected:", selectedCode);

        // Contoh manipulasi input berdasarkan risk_code
        const riskDetails = risks.find(risk => risk.risk_code === selectedCode);

        if (riskDetails) {
            document.getElementById('risk_event').value = riskDetails.risk_event || '';
            document.getElementById('mitigation_plan').value = riskDetails.mitigation_plan || '';
        } else {
            document.getElementById('risk_event').value = '';
            document.getElementById('mitigation_plan').value = '';
        }

         // Fungsi untuk toggle status (rencana atau pelaksanaan)
        function toggleStatus(cell) {
        console.log("Cell diklik:", cell); // Debug untuk memeriksa elemen

        const currentStatus = cell.getAttribute('data-status');
        const type = cell.getAttribute('data-type'); // Jenis status: rencana atau pelaksanaan
        let newStatus;

        // Tentukan status baru berdasarkan status saat ini
        if (currentStatus === 'none') {
            newStatus = type; // Ubah menjadi status aktif
        } else if (currentStatus === type) {
            newStatus = 'none'; // Ubah kembali ke status nonaktif
        } else {
            return; // Jika status tidak valid, keluar dari fungsi
        }
    }
}

        // Perbarui data-status elemen
        cell.setAttribute('data-status', newStatus);

        // Tambahkan kelas sesuai status, tanpa menghapus properti lainnya
        cell.classList.remove('bg-magenta-500', 'bg-purple-500', 'text-white', 'bg-gray-100', 'text-gray-700');
        if (newStatus === 'rencana') {
            cell.classList.add('bg-magenta-500', 'text-white');
        } else if (newStatus === 'pelaksanaan') {
            cell.classList.add('bg-purple-500', 'text-white');
        } else {
            cell.classList.add('bg-gray-100', 'text-gray-700');
        }

        // Event delegation untuk semua elemen dengan kelas 'editable'
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('editable') && e.target.classList.contains('month-cell')) {
                console.log("Kelas 'editable' dan 'month-cell' terdeteksi!"); // Debug
                console.log("Mengklik elemen dengan kelas editable");
                toggleStatus(e.target);
            }
        });

        // Simpan data status per bulan ke input hidden sebelum form disubmit
        document.querySelector('form').addEventListener('submit', function (e) {
            const monthCells = document.querySelectorAll('.month-cell.editable');
            const monthStatus = {};

            // Loop melalui semua bulan dan simpan status
            monthCells.forEach(cell => {
            const month = cell.getAttribute('data-month'); // Nama bulan (Jan, Feb, dll.)
            const type = cell.getAttribute('data-type'); // Jenis status (rencana/pelaksanaan)
            const status = cell.getAttribute('data-status'); // Status saat ini

            if (!monthStatus[month]) {
                monthStatus[month] = {}; // Buat objek bulan jika belum ada
            }
            monthStatus[month][type] = status; // Simpan status rencana/pelaksanaan
        });

        // Simpan JSON ke input hidden
        document.getElementById('month_status').value = JSON.stringify(monthStatus);
    });

    // Tutup dropdown jika klik di luar menu
    window.addEventListener("click", () => {
    if (!userMenu.classList.contains("hidden")) {
        userMenu.classList.add("hidden");
    }
    });

    </script>
    <script>
    // Toggle status dan update warna cell
    function toggleStatus(cell) {
        const currentStatus = cell.getAttribute('data-status');
        const type = cell.getAttribute('data-type');
        let newStatus;

        if (currentStatus === 'none') {
            newStatus = type;
        } else if (currentStatus === type) {
            newStatus = 'none';
        } else {
            return;
        }

        // Perbarui status
        cell.setAttribute('data-status', newStatus);

        // Perbarui kelas warna
        if (type === 'rencana') {
            cell.className = `month-cell editable border border-purple-500 px-4 py-2 text-center font-medium ${newStatus === 'rencana' ? 'purple' : 'purple-light'}`;
        } else if (type === 'pelaksanaan') {
            cell.className = `month-cell editable border border-purple-500 px-4 py-2 text-center font-medium ${newStatus === 'pelaksanaan' ? 'purple-dark' : 'purple-light'}`;
        }

        updateMonthStatusInput();
    }

    // Update input hidden berdasarkan semua cell bulan
    function updateMonthStatusInput() {
        const cells = document.querySelectorAll('.month-cell');
        const data = {};

        cells.forEach(cell => {
            const month = cell.getAttribute('data-month');
            const type = cell.getAttribute('data-type');
            const status = cell.getAttribute('data-status');

            if (!data[month]) {
                data[month] = {};
            }

            data[month][type] = status;
        });

        document.getElementById('month_status').value = JSON.stringify(data);
    }

    // Tambahkan event listener ke semua cell bulan
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.month-cell.editable').forEach(cell => {
            cell.addEventListener('click', () => toggleStatus(cell));
        });
    });
</script>

</body>
</html>

