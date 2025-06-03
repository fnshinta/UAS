<?php
require_once '../../includes/init.php';
require_once '../../classes/Monitoring.php';

$username = $_SESSION['username'];
if (strpos($username, '@') !== false) {
    $username = explode('@', $username)[0];
}

$monitoring = new Monitoring($conn);

// Ambil data monitoring berdasarkan ID
$id = $_GET['edit'] ?? null;
if (!$id) {
    header("Location: ../../pages/monitoring.php");
    exit;
}
$editData = $monitoring->getMonitoringById($id);

// Update data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'risk_code' => $_POST['risk_code'],
        'risk_event' => $_POST['risk_event'],
        'mitigation_plan' => $_POST['mitigation_plan'],
        'month_status' => $_POST['month_status'],
        'evidence' => $_POST['evidence'],
        'pic' => $_POST['pic']
    ];
    $monitoring->updateMonitoring($id, $data);
    header("Location: ../../pages/monitoring.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Risiko</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.4/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Definisi warna sesuai dengan identification.php */
        .bg-purple-900 { background-color: #553c9a; } /* Warna ungu gelap */
        .bg-purple-800 { background-color: #6b46c1; } /* Warna ungu sedang */
        .bg-purple-700 { background-color: #805ad5; } /* Warna ungu terang */
        .bg-purple-300 { background-color: #d6bcfa; } /* Warna ungu sangat terang */
        .bg-purple-200 { background-color: #f3e8ff; } /* Warna ungu paling muda */
        .text-purple-900 { color: #553c9a; }
        .text-purple-800 { color: #6b46c1; }
        .text-purple-700 { color: #805ad5; }
        .border-purple-300 { border-color: #d6bcfa; }
        .border-purple-400 { border-color: #b794f4; } /* Tambahan, jika diperlukan */

        /* Warna untuk elemen header/navigasi agar serasi dengan skema ungu */
        .bg-custom-header {
            background-color: #6B46C1; /* Menggunakan warna ungu utama dari identification.php */
        }
        .text-custom-header {
            color: white; /* Teks putih untuk kontras dengan header ungu */
        }
        .hover\:bg-custom-nav-hover:hover {
            background-color: #805ad5; /* Ungu sedikit lebih terang saat hover */
        }
        .text-custom-nav {
            color: #d6bcfa; /* Ungu muda untuk navigasi */
        }
        .text-custom-profile {
            color: #d6bcfa; /* Ungu muda untuk teks profile */
        }
        .bg-custom-profile-circle {
            background-color: #553C9A; /* Ungu gelap untuk lingkaran profil */
        }

    </style>
</head>
<body class="bg-light-purple text-gray-800">
    <main class="container mx-auto px-4 py-6 flex-grow ">
        <section class="bg-white p-6 rounded shadow mb-6">
        <form action="edit_monitoring.php?edit=<?php echo htmlspecialchars($id); ?>" method="POST" class="space-y-4" >
            <div>
                <input type="hidden" id="id" name="id" value="<?php echo htmlspecialchars($editData['id'] ?? ''); ?>">
                
                <label for="risk_code" class="block text-gray-700 font-medium">Risk Code</label>
                <select id="risk_code" name="risk_code" required onchange="updateRiskDetails(this.value)" class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-purple-500">
                    <option value="" disabled selected>Pilih Risk Code</option>
                    <?php
                    $query = $conn->query("SELECT risks.risk_code, risks.risk_event, mitigations.mitigation_plan 
                    FROM risks 
                    LEFT JOIN mitigations ON risks.risk_code = mitigations.risk_code");
                    $risks = [];
                    while ($row = $query->fetch()) {
                        $risks[$row['risk_code']] = [
                            'risk_event' => $row['risk_event'],
                            'mitigation_plan' => $row['mitigation_plan']
                        ];
                        $selected = ($editData['risk_code'] ?? '') === $row['risk_code'] ? 'selected' : '';
                        echo "<option value='{$row['risk_code']}' $selected>{$row['risk_code']}</option>";
                    }
                    ?>
                </select>
            </div>

            <input type="hidden" id="risk_event" name="risk_event" value="<?php echo htmlspecialchars($editData['risk_event'] ?? ''); ?>">
            <input type="hidden" id="mitigation_plan" name="mitigation_plan" value="<?php echo htmlspecialchars($editData['mitigation_plan'] ?? ''); ?>">

            <h3 class="text-lg font-bold mb-2">Status Per Bulan</h3>
            <table class="table-auto w-full border border-gray-500">
                <thead class="bg-beige-purple">
                    <tr>
                        <th class="border border-gray-500 px-4 py-2 text-center text-gray-800 font-bold">Bulan</th>
                        <th class="border border-gray-500 px-4 py-2 text-center text-gray-800 font-bold">Rencana Mitigasi</th>
                        <th class="border border-gray-500 px-4 py-2 text-center text-gray-800 font-bold">Pelaksanaan Mitigasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    $monthStatus = json_decode($editData['month_status'] ?? '{}', true);

                    foreach ($months as $month) {
                        $rencana = $monthStatus[$month]['rencana'] ?? 'none';
                        $pelaksanaan = $monthStatus[$month]['pelaksanaan'] ?? 'none';

                        echo "<tr class='hover:bg-gray-50'>";
                            // Kolom Bulan
                            echo "<td class='border border-gray-500 px-4 py-2 text-center text-gray-700 font-medium'>$month</td>";

                            // Kolom Rencana Mitigasi
                            echo "<td
                                    class='month-cell editable border border-gray-500 px-4 py-2 text-center font-medium " . 
                                    ($rencana === 'rencana' ? 'bg-purple-500 text-white' : 'bg-purple-100 text-gray-700') . "' 
                                    data-month='$month' 
                                    data-type='rencana' 
                                    data-status='$rencana'>
                                &nbsp;
                                </td>";

                            // Kolom Pelaksanaan Mitigasi
                            echo "<td 
                                    class='month-cell editable border border-gray-500 px-4 py-2 text-center font-medium " . 
                                    ($pelaksanaan === 'pelaksanaan' ? 'bg-purple-500 text-white' : 'bg-green-100 text-gray-700') . "' 
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

            <label for="evidence" class="block text-gray-700 font-medium">Evidence</label>
            <textarea id="evidence" name="evidence" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-purple-500"><?php echo htmlspecialchars($editData['evidence'] ?? ''); ?></textarea>

            <label for="pic" class="block text-gray-700 font-medium">PIC</label>
            <input type="text" id="pic" name="pic" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-purple-500" value="<?php echo htmlspecialchars($editData['pic'] ?? ''); ?>">

            <button type="submit" class="bg-purple-700 text-white px-4 py-2 rounded hover:bg-purple-800 transition">Update</button>
        </form>
        </section>
    </main>
    <script>
    const risks = <?php echo json_encode($risks, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>;

    function updateRiskDetails(selectedCode) {
        const riskEventInput = document.getElementById('risk_event');
        const mitigationPlanInput = document.getElementById('mitigation_plan');
        riskEventInput.value = risks[selectedCode]?.risk_event || '';
        mitigationPlanInput.value = risks[selectedCode]?.mitigation_plan || '';
    }

    document.querySelectorAll('.month-cell.editable').forEach(cell => {
        cell.addEventListener('click', function () {
            toggleStatus(cell);
        });
    });


    document.querySelector('form').addEventListener('submit', function () {
        const monthCells = document.querySelectorAll('.month-cell.editable');
        const monthStatus = {};
        monthCells.forEach(cell => {
            const month = cell.getAttribute('data-month');
            const type = cell.getAttribute('data-type');
            const status = cell.getAttribute('data-status');
            if (!monthStatus[month]) {
                monthStatus[month] = {};
            }
            monthStatus[month][type] = status;
        });
        document.getElementById('month_status').value = JSON.stringify(monthStatus);
    });


    // Fungsi untuk toggle status (rencana atau pelaksanaan)
    function toggleStatus(cell) {
    const currentStatus = cell.getAttribute('data-status'); // Status saat ini
    const type = cell.getAttribute('data-type');           // Jenis: rencana/pelaksanaan
    let newStatus;

    // Logika untuk toggle status
    if (currentStatus === 'none') {
        newStatus = type; // Aktifkan status
    } else {
        newStatus = 'none'; // Matikan status
    }

    // Perbarui data-status
    cell.setAttribute('data-status', newStatus);

    // Hapus semua kelas warna dan tambahkan sesuai status baru
    cell.classList.remove('bg-purple-500', 'bg-purple-500', 'text-white', 'bg-gray-100', 'text-gray-700');
    if (newStatus === 'rencana') {
        cell.classList.add('bg-purple-500', 'text-white');
    } else if (newStatus === 'pelaksanaan') {
        cell.classList.add('bg-purple-500', 'text-white');
    } else {
        cell.classList.add('bg-gray-100', 'text-gray-700');
    }
}
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
    // Hamburger Menu Logic
    const hamburgerButton = document.getElementById('hamburgerButton');
    const navMenu = document.getElementById('navMenu');

    if (hamburgerButton && navMenu) {
        hamburgerButton.addEventListener('click', () => {
            navMenu.classList.toggle('hidden');
        });
    }

    // User Dropdown Logic
    const userMenuButton = document.getElementById('userMenuButton');
    const userMenu = document.getElementById('userMenu');

    if (userMenuButton && userMenu) {
        userMenuButton.addEventListener('click', (e) => {
            e.stopPropagation();
            userMenu.classList.toggle('hidden');
            userMenu.classList.toggle('scale-100'); // Add transition effect
        });

        window.addEventListener('click', () => {
            if (!userMenu.classList.contains('hidden')) {
                userMenu.classList.add('hidden');
                userMenu.classList.remove('scale-100');
            }
        });

        userMenu.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    }
});
    </script>
</body>
</html>
