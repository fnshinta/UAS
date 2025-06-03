<?php
require_once __DIR__ . '/../../includes/init.php';
require_once __DIR__ . '/../../classes/Risk.php';


$username = $_SESSION['username'];

// Potong username hanya sampai sebelum tanda @
if (strpos($username, '@') !== false) {
    $username = explode('@', $username)[0];
}
$risk = new Risk($db);

$id = $_GET['id'];
$currentRisk = $risk->getRiskById($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'objective' => $_POST['objective'],
        'process_business' => $_POST['process_business'],
        'risk_category' => $_POST['risk_category'],
        'risk_code' => $currentRisk['risk_code'],
        'risk_event' => $_POST['risk_event'],
        'risk_cause' => $_POST['risk_cause'],
        'risk_source' => $_POST['risk_source'],
        'qualitative' => $_POST['qualitative'],
        'quantitative' => str_replace(['Rp', '.', ' '], '', $_POST['quantitative']),
        'risk_owner' => $_POST['risk_owner'],
        'department' => $_POST['department'],
    ];

    $risk->updateRisk($id, $data);
    header("Location: ../../pages/identification.php");
    exit;
}


?>

<!-- Bagian HTML Edit Risiko -->

<!DOCTYPE html>
<html lang="id">
<head>
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

    <main class="container mx-auto px-4 py-6 flex-grow">
        <section class="bg-white rounded shadow p-6 mb-8 border border-purple-300"> <h2 class="text-2xl font-semibold mb-5 text-purple-800">Edit Risiko</h2> <form action="edit_risk.php?id=<?php echo $id; ?>" method="POST" class="space-y-4">
                <input type="hidden" id="risk_code" name="risk_code" value="<?php echo $currentRisk['risk_code']; ?>" required>
            <div>
                <label for="objective" class="block text-gray-700 font-medium">Objective/Tujuan</label>
                <input type="text" id="objective" name="objective" value="<?php echo htmlspecialchars($currentRisk['objective']); ?>" required class="w-full mt-1 px-4 py-2 border border-purple-300 rounded focus:ring focus:ring-purple-500"> </div>

            <div>
                <label for="process_business" class="block text-gray-700 font-medium">Proses Bisnis</label>
                <select id="process_business" name="process_business"class="w-full mt-1 px-4 py-2 border border-purple-300 rounded focus:ring focus:ring-purple-500"> <option value="akademik" <?php if ($currentRisk['process_business'] === 'akademik') echo 'selected'; ?>>Akademik</option>
                    <option value="finansial" <?php if ($currentRisk['process_business'] === 'finansial') echo 'selected'; ?>>Finansial</option>
                    <option value="kepegawaian" <?php if ($currentRisk['process_business'] === 'kepegawaian') echo 'selected'; ?>>Kepegawaian</option>
                </select>
            </div>

            <div>
            <label for="risk_category"  class="block text-gray-700 font-medium">Risk Category</label>
            <select id="risk_category" name="risk_category"class="w-full mt-1 px-4 py-2 border border-purple-300 rounded focus:ring focus:ring-purple-500"> <option value="strategic" <?php if ($currentRisk['risk_category'] === 'strategic') echo 'selected'; ?>>Strategic</option>
                <option value="financial" <?php if ($currentRisk['risk_category'] === 'financial') echo 'selected'; ?>>Financial</option>
                <option value="operational" <?php if ($currentRisk['risk_category'] === 'operational') echo 'selected'; ?>>Operational</option>
            </select>
            </div>

            <div>
                <label for="risk_event"class="block text-gray-700 font-medium">Risk Event</label>
                <input type="text" id="risk_event" name="risk_event" value="<?php echo htmlspecialchars($currentRisk['risk_event']); ?>" required class="w-full mt-1 px-4 py-2 border border-purple-300 rounded focus:ring focus:ring-purple-500"> </div>

            <div>
                <label for="risk_cause" class="block text-gray-700 font-medium">Risk Cause</label>
                <input type="text" id="risk_cause" name="risk_cause" value="<?php echo htmlspecialchars($currentRisk['risk_cause']); ?>" required class="w-full mt-1 px-4 py-2 border border-purple-300 rounded focus:ring focus:ring-purple-500"> </div>

            <div>
                <label for="risk_source" class="block text-gray-700 font-medium">Risk Source</label>
                <select id="risk_source" name="risk_source" class="w-full mt-1 px-4 py-2 border border-purple-300 rounded focus:ring focus:ring-purple-500"> <option value="internal" <?php if ($currentRisk['risk_source'] === 'internal') echo 'selected'; ?>>Internal</option>
                    <option value="external" <?php if ($currentRisk['risk_source'] === 'external') echo 'selected'; ?>>External</option>
                </select>
            </div>

            <div>
                <label for="qualitative" class="block text-gray-700 font-medium">Severity (Qualitative)</label>
                <input type="text" id="qualitative" name="qualitative" value="<?php echo htmlspecialchars($currentRisk['qualitative']); ?>" required class="w-full mt-1 px-4 py-2 border border-purple-300 rounded focus:ring focus:ring-purple-500"> </div>

            <div>
                <label for="quantitative" class="block text-gray-700 font-medium">Severity (Quantitative)</label>
                <input type="text" id="quantitative" name="quantitative" value="<?php echo htmlspecialchars($currentRisk['quantitative']); ?>" required class="w-full mt-1 px-4 py-2 border border-purple-300 rounded focus:ring focus:ring-purple-500"> </div>

            <div>
                <label for="risk_owner" class="block text-gray-700 font-medium">Risk Owner</label>
                <input type="text" id="risk_owner" name="risk_owner" value="<?php echo htmlspecialchars($currentRisk['risk_owner']); ?>" required class="w-full mt-1 px-4 py-2 border border-purple-300 rounded focus:ring focus:ring-purple-500"> </div>

            <div>
                <label for="department" class="block text-gray-700 font-medium">Nama Dept./Unit Terkait</label>
                <input type="text" id="department" name="department" value="<?php echo htmlspecialchars($currentRisk['department']); ?>" required class="w-full mt-1 px-4 py-2 border border-purple-300 rounded focus:ring focus:ring-purple-500"> </div>

            <button type="submit" class="bg-purple-700 text-white px-4 py-2 rounded hover:bg-purple-800 transition">Update</button> </form>
        </section>
    </main>

</body>
</html>